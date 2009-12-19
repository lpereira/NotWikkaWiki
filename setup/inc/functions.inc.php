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
	$txt_filepath = $lang_defaults_path.$filename.'.txt';
	if (!file_exists($txt_filepath) || !is_readable($txt_filepath))
	{
		$txt_filepath = $lang_defaults_fallback_path.$filename.'.txt';
	}
	if (file_exists($txt_filepath) && is_readable($txt_filepath))
	{
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
		test (sprintf(__('Adding/Updating default page %s'.'...'), $tag), false, sprintf(__('Default page not found or file not readable (%s, %s, %s)'), $tag, $txt_filepath, $lang_defaults_path), 0);
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

?>
