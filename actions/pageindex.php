<?php
/**
 * Display an alphabetical list of pages of the wiki.
 *
 * This action checks user read privileges and displays an index of read-accessible pages.
 *
 * @package    Actions
 * @version	$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @author    {@link http://wikkawiki.org/GiorgosKontopoulos GiorgosKontopoulos} (added ACL check, first code cleanup)
 * @author    {@link http://wikkawiki.org/DarTar DarTar} (adding doc header, minor code and layout refinements, i18n)
 * 
 * @uses		Wakka::LoadPageTitles()
 * @uses		Wakka::GetUserName()
 * @uses		Wakka::HasAccess()
 * @uses		Wakka::Link()
 * @uses		Wakka::Format()
 *
 * @output   a list of pages accessible to the current user
 * @todo		add filtering options
 */

if ($pages = $this->LoadPageTitles())
{
	// filter by letter
	if (isset($_REQUEST['letter'])) $requested_letter = $_REQUEST['letter']; else $requested_letter = '';
	if (!$requested_letter && isset($letter)) $requested_letter = strtoupper($letter); 

	// get things started
	$cached_username = $this->GetUserName();
	$user_owns_pages = false;
	$link = $this->href('', '', 'letter=');
	$alpha_bar = '<a href="'.$link.'">'.ALL_PAGES.'</a>&nbsp;'."\n";
	$index_header = INDEX_CAPTION;
	$index_output = '';
	$current_character = '';
	$character_changed = false;

	// get page list
	foreach ($pages as $page)
	{
		// check user read privileges
		if (!$this->HasAccess('read', $page['tag'])) continue;

		$page_owner = $page['owner'];
		// $this->CachePage($page);

		$firstChar = strtoupper($page['tag'][0]);
		if (!preg_match('/[A-Za-z]/', $firstChar)) $firstChar = '#'; //TODO: Internationalization
		if ($firstChar != $current_character) 
		{
			$alpha_bar .= '<a href="'.$link.$firstChar.'">'.$firstChar.'</a>&nbsp;'."\n";
			$current_character = $firstChar;
			$character_changed = true;
		}
		if ($requested_letter == '' || $firstChar == $requested_letter) 
		{
			if ($character_changed) 
			{
				$index_output .= "<br />\n<strong>$firstChar</strong><br />\n";
				$character_changed = false;
			}
			$index_output .= $this->Link($page['tag']);

			if ($cached_username == $page_owner) 
			{                       
				$index_output .= '*';
				$user_owns_pages = true;
			} 
			elseif ($page_owner != '(Public)' && $page_owner != '') 
			{
				$index_output .= sprintf(PAGE_OWNER, $page_owner);
			}
		     	$index_output .= "<br />\n";    
		}
	}
	// generate page
	$index_header .= ($user_owns_pages) ? '---'.OWNED_PAGES_CAPTION : '';
	echo $this->Format('===='.PAGE_HEADING.'==== --- <<'.$index_header.'<< ::c:: ---'); 
	echo "\n<strong>".$alpha_bar."</strong><br />\n".$index_output;
} 
else 
{
	echo NO_PAGES_FOUND;
}
?>
