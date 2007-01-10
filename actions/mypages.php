<?php
/**
 * Display a list of pages owned by the current user.
 * 
 * If the current user is logged-in and owns at least one page, a list of pages owned by the current user
 * is displayed, ordered alphabetically or by date and time (last edit first).
 * 
 * @package Actions
 * @version	$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * 
 * @author Carlo Zottmann
 * 
 * @uses	Wakka::GetUser()
 * @uses	Wakka::LoadPagesByOwner()
 * @uses	Wakka::GetUserName() 
 * @uses	Wakka::Link()
 */

if ($user = $this->GetUser())
{
	print '<strong>'.OWNED_PAGES_TXT.'</strong><br /><br />'."\n";
	$curChar = '';


	if ($pages = $this->LoadPagesByOwner($this->GetUserName()))
	{
		foreach ($pages as $page)
		{
			//if ($this->GetUserName() == $page["owner"]) 
			//{
				$firstChar = strtoupper($page["tag"][0]);
				if (!preg_match("/[A-Z,a-z]/", $firstChar)) 
				{
					$firstChar = "#";
				}
	
				if ($firstChar != $curChar) 
				{
					if ($curChar) print("<br />\n");
					print("<strong>$firstChar</strong><br />\n");
					$curChar = $firstChar;
				}
	
				print($this->Link($page["tag"])."<br />\n");
				
			//}
		}
		
	}
	else
	{
		print '<em>'.NO_OWNED_PAGES.'</em>';
	}
}
else
{
	print '<em>'.USER_NOT_LOGGED_IN.'</em>';
}
?>
