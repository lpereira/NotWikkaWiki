<?php
/**
 * Show a list of blacklisted referrers.
 * 
 * Admins have the possibility to remove entries.
 * 
 * @package		Handlers
 * @subpackage	Referrers
 * @version		$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * 
 * @uses		Wakka::IsAdmin()
 * @uses		Wakka::Href()
 * @uses		Wakka::htmlspecialchars_ent()
 * @uses		Wakka::LoadAll()
 * @uses		Wakka::Query()
 * @uses		Wakka::redirect()
 * 
 * @todo		move main <div> to templating class
 * @todo		make valid XHTML (can't mix table with ul)
 */

$IsAdmin = $this->IsAdmin();

if ($IsAdmin && isset($_REQUEST["whitelist"]))
{
	$whitelist = $_REQUEST["whitelist"];
	$this->Query("DELETE FROM ".$this->config["table_prefix"]."referrer_blacklist WHERE spammer = '".mysql_real_escape_string($whitelist)."'");
	$this->Redirect($this->Href("review_blacklist"));
}

// set up output variables
$ref_domains_to_wiki_url = $this->Href('referrers_sites','','global=1');
$ref_urls_to_wiki_url = $this->Href('referrers','','global=1');
$ref_domains_to_wiki_link = '<a href="'.$ref_domains_to_wiki_url.'">'.REFERRERS_DOMAINS_TO_WIKI_LINK_DESC.'</a>';
$ref_urls_to_wiki_link = '<a href="'.$ref_urls_to_wiki_url.'">'.REFERRERS_URLS_TO_WIKI_LINK_DESC.'</a>';
$menu = '['.$ref_domains_to_wiki_link.' | '.$ref_urls_to_wiki_link.']';

// get data
$blacklist = $this->LoadAll("SELECT * FROM ".$this->config["table_prefix"]."referrer_blacklist");

echo '<div class="page">'."\n"; //TODO: move to templating class
echo '<strong>'.BLACKLIST_HEADING.'</strong><br /><br />'."\n";

// present data
if ($blacklist)
{
	print("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><ul>\n");
	foreach ($blacklist as $spammer)
	{
		print('<tr>');
		print('<td valign="top"><li>'.$spammer['spammer'].' '.($IsAdmin ? '[<a href="'.$this->Href('review_blacklist', '', 'whitelist=').$this->htmlspecialchars_ent($spammer['spammer']).'">'.BLACKLIST_REMOVE_LINK_DESC.'</a>]' : '').'</li></td>');
		print("</tr>\n");
	}
	print("</ul></table><br />\n");
}
else
{
	print('<em>'.STATUS_BLACKLIST_EMPTY."</em><br /><br />\n");
}

echo '<br />'.$menu;
echo '</div>'."\n" //TODO: move to templating class
?>
