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
 */
function test($text, $condition, $errorText = "", $stopOnError = 1) {
	print("$text ");
	if ($condition)
	{
		print('<span class="ok">OK</span><br />'."\n");
	}
	else
	{
		print('<span class="failed">FAILED</span>');
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
 * @param mixed $tag 
 * @param mixed $dblink 
 * @param mixed $config 
 * @access public
 * @return void
 */
function update_default_page($tag, $dblink, $config)
{
	if (is_array($tag))
	{
		foreach ($tag as $v)
		{
			update_default_page($v, $dblink, $config);
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
	$txt_filename = 'lang'.DIRECTORY_SEPARATOR.$config['default_lang'].DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.$filename.'.txt';
	if (!file_exists($txt_filename) || !is_readable($txt_filename))
	{
		$txt_filename = 'lang'.DIRECTORY_SEPARATOR.'en'.DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.$filename.'.txt';
	}
	if (file_exists($txt_filename) && is_readable($txt_filename))
	{
		$body = implode('', file($txt_filename));
		mysql_query('update '.$config['table_prefix'].'pages set latest = "N" where tag = \''.$tag.'\'', $dblink);
		test (sprintf(__('Adding/Updating default page %s'.'...'), $tag),
		@mysql_query('insert into '.$config['table_prefix'].'pages set tag=\''.$tag.'\', body = \''.mysql_real_escape_string($body).'\', user=\'WikkaInstaller\', owner = \''.$admin_main_user.'\', time=now(), latest =\'Y\'', $dblink),
  	'',
  	0);
	}
	else
	{
		test (sprintf(__('Adding/Updating default page %s'.'...'), $tag), false, sprintf(__('Default page not found or file not readable (%s, %s)'), $tag, $txt_filename), 0);
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
	echo '</td><td><select name="config['.$config_name.']">'."\n";
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
 * subdirectories of the lang/ folder: if a file called PageIndex.txt if found inside it, then, it's a valid
 * language pack subfolder. (To avoid treating some obscure system dependent special folders).
 * 
 * @access public
 * @return void
 */
function Language_selectbox($default_lang)
{
	echo '<select name="config[default_lang]">';
	/** @todo fill the array. */
	$human_lang = array (
		'en' => 'English',
		'fr' => 'Fran�ais',
		'de' => 'Deutsch');
	$hdl = opendir('lang');
	while ($f = readdir($hdl))
	{
		if ($f[0] == '.') continue;
		if (file_exists('lang'.DIRECTORY_SEPARATOR.$f.DIRECTORY_SEPARATOR.'defaults'.DIRECTORY_SEPARATOR.'PageIndex.txt'))
		{
			echo "\n ".'<option value="'.$f.'"';
			if ($f == $default_lang) echo ' selected="selected"';
			echo '>'.(isset($human_lang[$f]) ? $human_lang[$f] : $f).'</option>';
		}
	}
	echo '</select>';
}
?>
