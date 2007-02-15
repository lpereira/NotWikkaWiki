<?php
/**
 * Display a box with information on the last edit.
 *
 * @package		Actions
 * @version		$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @author		{@link http://wikkawiki.org/DarTar Dario Taraborelli} (first draft)
 * @author		{@link http://wikkawiki.org/MinusF MinusF} (code cleanup and validation)
 * @since		Wikka 1.1.6.0
 *
 * @input		integer  $show  optional: amount of details to be displayed;
 *				default: 3
 *				0: show user only
 *				1: show user and notes (if available)
 *				2: show user, notes (if available) and date
 *				3: show user, notes (if available), date and quickdiff link
 * @output		box with lastedit information
 *
 * @uses	Wakka::Link()
 * @uses	Wakka::LoadUser()
 * @uses	Wakka::GetPageTag()
 * @uses	Wakka::Href()
 * @uses	Wakka::htmlspecialchars_ent()
 * @todo		make date/time format system-configurable;
 * @todo 	use FormatUser() method to render author name;
 */
/**
 * defaults
 */
define('DEFAULT_SHOW', '3');
define('DATE_FORMAT', 'D, d M Y'); #TODO make this system-configurable
define('TIME_FORMAT', 'H:i T'); #TODO make this system-configurable
/**
 * style
 */
define('LASTEDIT_BOX', 'lastedit');
define('LASTEDIT_NOTES', 'lastedit_notes');

if (!isset($show)) 
{
	$show = DEFAULT_SHOW;
}

if ($this->method == 'show') 
{
	$page = $this->page;
	$pagetag = $page['tag'];
	$user = ($this->LoadUser($page['user'])) ? $this->Link($page['user']) : WIKKA_ANONYMOUS_USER; // @@@ or WIKKA_UNREGISTERED_USER

	switch($show)
	{
		case 3:
		$oldpage = $this->LoadSingle("SELECT * FROM ".$this->config['table_prefix']."pages WHERE tag='".$this->GetPageTag()."' AND latest = 'N' ORDER BY time desc LIMIT 1");
		$newid = $page['id'];
		$oldid = $oldpage['id'];
		$difflink = ' [<a title="'.LASTEDIT_DIFF_LINK_TITLE.'" href="'.$this->Href('diff', $pagetag, 'a='.$page['id'].'&amp;b='.$oldpage['id'].'&amp;fastdiff=1').'">diff</a>]';

		case 2:
		list($day, $time) = explode(' ', $page['time']);
		$dateformatted = date(DATE_FORMAT, strtotime($day));
		$timeformatted = date(TIME_FORMAT, strtotime($page['time']));

		case 1:
		$note = ($page['note']) ? ':<br/><span class="'.LASTEDIT_NOTES.'">'.
		$this->htmlspecialchars_ent($page['note']).'</span>' : '';

		default:	
	}
	echo '<div class="'.LASTEDIT_BOX.'">'.sprintf(LASTEDIT_DESC, $user).$note.'<br /> '.$dateformatted.' '.$timeformatted.$difflink.'</div>';
}
?>
