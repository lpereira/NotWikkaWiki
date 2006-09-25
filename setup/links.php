<?php
/**
 * Re-generate the links table
 * 
 * Since 1.1.7, we have made an enhacement (optimization) in the categorization process.
 * Instead of querying all latest page looking for the word CategoryName, we should now do
 * the search inside the links table, and consider all pages linking to CategoryName belong
 * to that category. We must then fix an issue from previous versions of Wikka where links
 * on pages created from the Installer are not reported in the links table. Another issue
 * which is reported is that older versions of delete handler didn't clean up links table.
 * 
 * @package	Setup
 * @version $Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * 
 * @author {@link http://wikkawiki.org/Mokoshi Vincent Fr�tin}
 * @author {@link http://wikkawiki.org/DotMG Mahefa Randimbisoa}
 * 
 * @todo use a central RegEx library #34;
 * @todo apply coding guidelines;
 * @todo document functions using phpdoc syntax;
 * @todo refactor main documentation header
 */
error_reporting(E_ALL);
set_time_limit(30);
// You use less RAM if you do the job step by step, but this increases page generation time
// This avoid the script from crashing if you have thousands of pages.
function LoadSomePages($start='', $limit=100)
{
	global $config;
	//Less RAM: select tag and body only
	// Note that LoadSomePages needs result to be sorted by tag.
	$result = mysql_query("SELECT tag, body FROM {$config['table_prefix']}pages 
	 WHERE tag > '".mysql_real_escape_string($start)."' AND latest = 'Y' 
		ORDER BY tag ASC  
		LIMIT $limit");
	$pages = array();
	if ($result)
	{
		while ($row = mysql_fetch_assoc($result)) $pages[] = $row;
		mysql_free_result($result);
	}
	return ($pages);
}

$start = '';
$GLOBALS['sql'] = '';
$GLOBALS['written'] = '';
// Delete from wikka_links once for all
mysql_query("TRUNCATE TABLE {$config['table_prefix']}links");
while ($pages = LoadSomePages($start))
{
	foreach ($pages as $page)
	{
		$GLOBALS['tag'] = $page['tag'];
		$GLOBALS['written'] = array();
		preg_replace_callback(
		"/".
		"%%.*?%%|".																				# code
		"\"\".*?\"\"|".                      # literal 		
		"\[\[[^\[]*?\]\]|".																		# forced link
		#$mind_map_pattern.																		# (safe to be ignored)
		"\[\[\S*[^\[]*?\]\]|".																		# forced link
		#"-{3,}|".																			# forced linebreak and hr (safe to be ignored)
		"\b[a-z]+:\/\/\S+|".																	# URL
		#"\*\*|\'\'|\#\#|\#\%|@@|::c::|\>\>|\<\<|&pound;&pound;|&yen;&yen;|\+\+|__|<|>|\/\/|".	# Wiki markup (safe to be ignored)
		#"======|=====|====|===|==|".															# headings (safe to be ignored)
		#"\n[\t~]+(-|&|[0-9a-zA-Z]+\))?|".														# indents and lists (safe to be ignored)
		"\|(?:[^\|])?\|(?:\(.*?\))?(?:\{[^\{\}]*?\})?(?:\n)?|".										# Simple Tables	
		"\{\{.*?\}\}|".																			# action
		"\b[A-Z���][A-Za-z�������]+[:](?![=_])\S*\b|".											# InterWiki link
		"\b([A-Z���]+[a-z����]+[A-Z0-9���][A-Za-z0-9�������]*)\b|".								# CamelWords
		"\n".																					# new line
		"/ms", "relinkcallback", $page['body']);
	}
	$start = $page['tag'];
	// insert into wikka_links a batch of records at a time.
	relinkcallback('cleanup');
}

// It's a mini wakka2callback, only the necessary to relink.
function relinkcallback($thing)
{
	global $tag, $written, $config;
	static $sql = '';
	if ($thing === 'cleanup') 
	{
		// On cleanup, send command "INSERT INTO wikka_links VALUES ('a', 'b'), ('c', 'd'), ('e', 'f')"
		// Then reinitialize $sql and return
		if ($sql)
		{
			mysql_query("INSERT INTO {$config['table_prefix']}links VALUES $sql");
		}
		$sql = '';
		return;
	}
	// If not on cleanup, try to construct the part ('a', 'b'), ('c', 'd'), ...
	$thing = $thing[0];
	// This regexp treats CamelCases and [[forcedlink]] (or [[http://external.link of any type]])
	// In case of a Forced link, it treats only a word before the first space or closing bracket THAT satisfies the regexp [A-Z���a-z����]+
	// So, http links like above are not taken into account, because they contain : and /
	if (preg_match("/^(\[\[)?([A-Z���a-z����0-9]+(?(1)(?=[ \]])|[A-Z0-9���][A-Za-z0-9�������]*\\b$))/s", $thing, $matches))          # recognize forced links across lines
	{
		$url = $matches[2];
		if (($url) && (!isset($written[strtolower($url)])) && (strtolower($url) != strtolower($tag)))
		{
			if ($sql) $sql .= ', ';
			$sql .= "('".mysql_real_escape_string($tag)."', '".mysql_real_escape_string($url)."')";
			$written[strtolower($url)] = $url;
		}
	}
}


?>
