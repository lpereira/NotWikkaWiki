<?php
/**
 * Show a list of revisions for the page sorted after time.
 * 
 * @package		Handlers
 * @subpackage	Page
 * @version		$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * 
 * @uses		Wakka::HasAccess()
 * @uses		Wakka::LoadRevisions()
 * @uses		Wakka::Format()
 * @uses		Wakka::FormOpen()
 * @uses		Wakka::GetUser()
 * @uses		Wakka::FormClose()
 * @uses		Wakka::Href()
 * 
 * @todo		move main <div> to templating class
 */
/**
 * i18n
 */
define('BUTTON_RETURN_TO_NODE', 'Return To Node / Cancel');
define('BUTTON_SHOW_DIFFERENCES', 'Show Differences');
define('ERROR_ACL_READ', 'You aren\'t allowed to read this page.');
define('SIMPLE_DIFF', 'Simple Diff');
define('WHEN_BY_WHO', '%1$s by %2$s');

echo '<div class="page">'."\n"; //TODO: move to templating class

if ($this->HasAccess("read")) {
	// load revisions for this page
	if ($pages = $this->LoadRevisions($this->tag))
	{
		$output = $this->FormOpen("diff", "", "get");
		$output .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
		$output .= "<tr>\n";
		$output .= '<td><input type="submit" value="'.BUTTON_SHOW_DIFFERENCES.'" /></td>';
		$output .= '<td><input value="1" type="checkbox" checked="checked" name="fastdiff" id="fastdiff" />'."\n".'<label for="fastdiff">'.SIMPLE_DIFF.'</label></td>';
		$output .= "</tr>\n";
		$output .= "</table>\n";
		$output .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
		#$max is now computed inside LoadRevisions

		$c = 0;
		foreach ($pages as $page)
		{
			$c++;
				if ($page['note']) $note='['.$this->htmlspecialchars_ent($page['note']).']'; else $note ='';
				$output .= "<tr>";
				$output .= "<td><input type=\"radio\" name=\"a\" value=\"".$page["id"]."\" ".($c == 1 ? "checked=\"checked\"" : "")." /></td>";
				$output .= "<td><input type=\"radio\" name=\"b\" value=\"".$page["id"]."\" ".($c == 2 ? "checked=\"checked\"" : "")." /></td>";
				$output .= '<td>'.sprintf(WHEN_BY_WHO, '<a href="'.$this->Href('show','','time='.urlencode($page["time"])).'">'.$page['time'].'</a>', $this->Link($page["user"])).' <span class="pagenote smaller">'.$note.'</span></td>';
				$output .= "</tr>\n";
		}
		$output .= "</table><br />\n";
		$output .= '<input type="button" value="'.BUTTON_RETURN_TO_NODE.'" onclick="document.location=\''.$this->Href('').'\';" />'."\n";
		$output .= $this->FormClose()."\n";
	}
	print($output);
} else {
	print('<em class="error">'.ERROR_ACL_READ.'</em>');
}
echo '</div>'."\n" //TODO: move to templating class
?>
