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
 * @todo	fix RE (#104 etc.); also lose the comma in there!
 * @todo	actually add the (intended) timestanmp sorting; cf. mychanges action
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
				if (!preg_match("/[A-Z,a-z]/", $firstChar)) //TODO: (#104 #340, #34) Internationalization (allow other starting chars, make consistent with Formatter REs) 
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
		print '<em>'.OWNED_NO_PAGES.'</em>';
	}
}
else
{
	print '<em>'.OWNED_NOT_LOGGED_IN.'</em>';
}
?>