<?php
/**
 * Functions used by the installer/upgrader.
 * 
 * @package	Setup
 * @version	$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 */

/**
 * Notify if a test failed or succeded.
 * 
 * @version	$Id: header.php 161 2006-07-18 10:00:41Z DarTar $
 * @param	string $text mandatory: text for the condition
 * @param	boolean $condition mandatory: test failed/passed?
 * @param	string	$errorText optional: text to print out on error; Default: <empty>
 * @param	int		$stopOnError optional: stops the installation on error if set to 1; Default: 1 
 * @todo	internationalization (#i18n markers)
 * @todo	should also report any database errors
 */
function test($text, $condition, $errorText = "", $stopOnError = 1)
{
	print("$text ");
	if ($condition)
	{
		print('<span class="ok">OK</span><br />'."\n");	#i18n
	}
	else
	{
		print('<span class="failed">FAILED</span>');	#i18n
		if ($errorText) print(": ".$errorText);
		print("<br />\n");
		if ($stopOnError)
		{
			echo "</div>\n</body>\n</html>";
			exit;
		}
	}
}

/**
 * Delete a file, or a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Id: header.php 161 2006-07-18 10:00:41Z DarTar $
 * @param       string   $dirname    Directory to delete
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function rmdirr($dirname)
{
    // Sanity check
    if (!file_exists($dirname)) {
        return false;
    }
 
    // Simple delete for a file
    if (is_file($dirname)) {
        return unlink($dirname);
    }
 
    // Loop through the folder
    $dir = dir($dirname);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }
 
        // Recurse
        rmdirr("$dirname/$entry");
    }
 
    // Clean up
    $dir->close();
    return rmdir($dirname);
}
/**
 * Update content of a default page.
 * 
 * If $tag parameter is an array, it just passes elements of this array one by one to itself.
 * The value 'HomePage' is a special one: it will be replaced by the configured value $config['root_page'].
 * The content of the page is read at a file named with $tag, located in setup/default_pages.
 * @param mixed $tag	string or array of strings
 * @param resource $dblink
 * @param mixed $config
 * @param string $lang_defaults_path	mandatory: validated directory for language-specific default pages
 * @param string $lang_defaults_fallback_path	mandatory: validated directory for default pages in system default language
 * @access public
 * @return void
 * @todo avoid recursion: make a single tag into an array of one and then just loop over the tags
 */
function update_default_page($tag, $dblink, $config, $lang_defaults_path, $lang_defaults_fallback_path)
{
	if (is_array($tag))
	{
		foreach ($tag as $v)
		{
			update_default_page($v, $dblink, $config, $lang_defaults_path, $lang_defaults_fallback_path);
		}
		return;
	}
	
	$filename = $tag;
	if ($tag == '_rootpage')
	{
		$tag = $config['root_page'];
		$filename = 'HomePage';
	}
	$admin_users = explode(',', $config['admin_users']);
	$admin_main_user = trim($admin_users[0]);
	#$txt_filename = 'lang'.DIRECTORY_SEPARATOR.$config['default_lang'].DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.$filename.'.txt';
	$txt_filepath = $lang_defaults_path.$filename.'.txt';
	#if (!file_exists($txt_filename) || !is_readable($txt_filename))
	if (!file_exists($txt_filepath) || !is_readable($txt_filepath))
	{
		#$txt_filename = 'lang'.DIRECTORY_SEPARATOR.'en'.DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.$filename.'.txt';
		$txt_filepath = $lang_defaults_fallback_path.$filename.'.txt';
	}
	#if (file_exists($txt_filename) && is_readable($txt_filename))
	if (file_exists($txt_filepath) && is_readable($txt_filepath))
	{
		#$body = implode('', file($txt_filename));
		$body = implode('', file($txt_filepath));
		mysql_query('update '.$config['table_prefix'].'pages set latest = "N" where tag = \''.$tag.'\'', $dblink);
		test (sprintf(__('Adding/Updating default page %s'.'...'), $tag),
			@mysql_query('insert into '.$config['table_prefix'].'pages set tag=\''.$tag.'\', body = \''.mysql_real_escape_string($body).'\', user=\'WikkaInstaller\', owner = \''.$admin_main_user.'\', time=now(), latest =\'Y\'', $dblink),
			'',
			0);
		// @@@ pick up any page-specific ACL here (look in both $lang_defaults_path and $lang_defaults_fallback_path)
	}
	else
	{
		#test (sprintf(__('Adding/Updating default page %s'.'...'), $tag), false, sprintf(__('Default page not found or file not readable (%s, %s)'), $tag, $txt_filename), 0);
		test (sprintf(__('Adding/Updating default page %s'.'...'), $tag), false, sprintf(__('Default page not found or file not readable (%s, %s)'), $tag, $txt_filepath), 0);
	}
}

/**
 * __ .
 * i18n purpose: __() function is actually used to mark certain parts of the installer as translatable strings. This function doesn't echo
 * the string $s, it just returns it. If the string $s contains characters ---<, __() removes it and all strings after it, as if the 
 * serie ---< was a comment marker. Useful if you want to translate very little phrase like 'Do' in 2 situations where its translations may
 * be different! For example: __('Search---<Verb,action'); and __('Search---<Noun').
 * 
 * @param mixed $s 
 * @access public
 * @return void
 */
function __($s)
{
	return (eregi_replace('---<.*$', '', $s));
}

/**
 * _p .
 * The same as __(), but it escape slashes and doublequote. Use _p() if the string $s is to be inserted in an attribute like title=""
 * 
 * @param mixed $s 
 * @access protected
 * @return void
 */
function _p($s)
{
	return (str_replace(array("\\", '"'), array("\\\\", '&quot;'), __($s)));
}
/**
 * ACL_show_selectbox .
 * Facility to echo a <select>...</select> for acl options available. Generate a valid XHTML <tr> part.
 * @param mixed $type 
 * @access public
 * @return void
 */
function ACL_show_selectbox($type)
{
	global $config;
	// @@@ use constants for ACL symbols
	$default_acl['read'] = '*';
	$default_acl['write'] = '+';
	$default_acl['comment_read'] = '*';
	$default_acl['comment_post'] = '+';
	$config_name = 'default_'.$type.'_acl';
	if (!isset($config[$config_name])) $config[$config_name] = $default_acl[$type];
	$predef_acl = array(
		'!*' => __('No one (admin only)'),
		'+' => __('Registered users only'),
		'*' => __('Anyone'));
	echo ' <tr><td align="right" nowrap="nowrap">';
	printf(__('Default %s access'), __($type.'---<Default X access'));
	echo '</td><td><select name="pconfig['.$config_name.']">'."\n";
	foreach ($predef_acl as $value => $text)
	{
		echo '<option value="'.$value.'"';
		if ($value == $config[$config_name]) echo ' selected="selected"';
		echo '>'.$text;
		if ($value == $default_acl[$type]) echo ' ('.__('recommended').')';
		echo '</option>'."\n";
	}
	echo '</select></td></tr>'."\n";
}
/**
 * Facility to echo a <select>...</select> for language packs availables. A simple check is performed on all 
 * subdirectories of the lang/ folder: if a file called PageIndex.txt is found inside it, then, it's a valid
 * language pack subfolder. (To avoid treating some obscure system dependent special folders).
 * 
 * @access public
 * @return void
 */
function Language_selectbox($default_lang)
{
	echo '<select name="pconfig[default_lang]">';
	/** @todo fill the array. */
	$human_lang = array (
		'en' => 'English',
		'fr' => 'Fran�ais',
		'de' => 'Deutsch');
	// use configured path
	#$hdl = opendir('lang');
	$hdl = opendir(WIKKA_LANG_PATH);
	while ($f = readdir($hdl))
	{
		if ($f[0] == '.') continue;
		// use configured path
		#if (file_exists('lang'.DIRECTORY_SEPARATOR.$f.DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.'PageIndex.txt'))
		if (file_exists(WIKKA_LANG_PATH.DIRECTORY_SEPARATOR.$f.DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.'PageIndex.txt'))
		{
			echo "\n ".'<option value="'.$f.'"';
			if ($f == $default_lang) echo ' selected="selected"';
			echo '>'.(isset($human_lang[$f]) ? $human_lang[$f] : $f).'</option>';
		}
	}
	echo '</select>';
}
/**
 * Test if mod_rewrite Apache module was found to be available.
 * The real checking occured after submitting the form on the first installation setup
 * screen, using the files {@link Setup/test/test-mod-rewrite.php} and 
 * {@link Setup/test/rewrite-ok.php}.
 */
function test_mod_rewrite()
{
	return (isset($_SESSION['mod_rewrite']) && ($_SESSION['mod_rewrite'] == 'ok'));
}
/**
 * Write the file {@link Setup/test/.htaccess}.
 *
 * .htaccess files must be written dynamically, because the RewriteBase
 * directive needs the installation path.
 *
 * @todo: case setup files in other directory than setup
 * @return boolean true if the file .htaccess could be written successfully, false otherwise
 */
function init_test_mod_rewrite()
{
	global $action_target, $url;
	$data = '';
	$rewrite_base = substr($action_target, 0, strlen($action_target) - 9);
	$fp = @fopen('setup/test/.htaccess', 'w');
	if ($fp)
	{
		$htaccess_content = <<<HTACCESSCONTENT
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$rewrite_base}setup/test/
RewriteRule ^test-mod-rewrite.php$ rewrite-ok.php
</IfModule>
HTACCESSCONTENT;
		fwrite($fp, $htaccess_content);
		fclose($fp);
		return true;
	}
	return false;
}
/**
 * Check if the installer will be able to write the specified file.
 *
 * @param string $file the filename and path to be checked
 * @return boolean true if the file could be written (created if absent), false otherwise
 */
function setupfile_is_writable($file)
{
	$buff = @fopen($file, 'a');
	if (!$buff)
	{
		return (false);
	}
	fclose($buff);
	return (true);
}
?>
