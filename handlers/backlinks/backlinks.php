<?php
/**
* Displays a list of internal pages linking to the current page.
*
* Usage: append /backlinks to the URL of the page
*
* This handler retrieves a list of internal pages linking to the current page.
* It first checks if they exist and prints them on the screen.
*
* @package		Handlers
* @subpackage
* @name			backlinks
*
* @author	{@link http://wakkawiki.de/MartinBurger Martin Burger} - original idea and code.
* @author	{@link http://wikkawiki.org/DarTar Dario Taraborelli} - code rewritten, existsPage check added, removed links array.
* @author	{@link http://wikkawiki.org/BrianKoontz Brian Koontz} - code ported from 1.1.6.6 
* @version	0.4
* @since	Wikka 1.1.6.2
*
* @uses		Wakka::LoadPagesLinkingTo()
* @uses		Wakka::ListPages()
* @uses		Wakka::existsPage()
*
* @todo		optional (GET) parameter to list links from non-active (deleted, renamed)
*			pages as well
*
*/

echo '<div class="page">'."\n";

// build backlinks list
echo '<h3>'.sprintf(PAGE_TITLE,$this->tag).'</h3><br />'."\n";

switch(TRUE) 
{
	case (!$this->existsPage($this->tag)):
	echo '<em class="error">'.sprintf(MESSAGE_MISSING_PAGE,$this->tag).'</em>'."\n";
	break;

	case (!$this->HasAccess('read')):
	echo '<em class="error">'.MESSAGE_PAGE_INACCESSIBLE.'</em>'."\n";
	break;
	
	default:	
	if ($pages = $this->LoadPagesLinkingTo($this->tag))
	{
		echo $this->ListPages($pages, '', '', 0, 1, TRUE);
	}
	else
	{
		echo '<em class="error">'.MESSAGE_NO_BACKLINKS.'</em>';
	}
}
echo '</div>'."\n";
?>
