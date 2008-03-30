<?php
/**
 * Display a list of pages owned by the current user.
 *
 * If the current user is logged-in and owns at least one page, a list of pages owned by the current user
 * is displayed, ordered alphabetically or by date and time (last edit first).
 *
 * @package		Actions
 * @version		$Id:mypages.php 369 2007-03-01 14:38:59Z DarTar $
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @author	{@link http://web.archive.org/web/20040616194824/http://www.wakkawiki.com/CarloZottmann Carlo Zottmann}
 *
 * @uses	Wakka::reg_username
 * @uses	Wakka::existsUser()
 * @uses	Wakka::LoadPagesByOwner()
 * @uses	Wakka::GetUserName()
 * @uses	Wakka::Link()
 * @todo	fix RE (#104 etc.); also lose the comma in there!
 * @todo	actually add the (intended) timestanmp sorting; cf. mychanges action
 */

$username = '';
if(isset($_REQUEST['user']))
{
	$username = $this->htmlspecialchars_ent($_REQUEST['user']); 
}
if (($this->IsAdmin() && !empty($username)) || 
		($this->GetUser() && $username = $this->GetUserName())) 
{ 
	printf("<div class="floatl">".OWNED_PAGES_TXT."</div><div class="clear">&nbsp;</div>\n", $username);
	$curChar = '';


	#if ($pages = $this->LoadPagesByOwner($user['name']))
	if ($pages = $this->LoadPagesByOwner($this->reg_username))
	{
		$my_pages_count = 0;
		foreach ($pages as $page)
		{
			if($username == $page['owner'])
			{
				++$my_pages_count;
				$firstChar = strtoupper($page["tag"][0]);
				if (!preg_match("/[A-Z,a-z]/", $firstChar)) //TODO: (#104 #340, #34) Internationalization (allow other starting chars, make consistent with Formatter REs)
				{
					$firstChar = "#";
				}

				if ($firstChar != $curChar)
				{
					if ($curChar) print("<br />\n");
					echo '<strong>'.$firstChar."</strong><br />\n";
					$curChar = $firstChar;
				}

				echo $this->Link($page['tag'])."<br />\n";

			}
		}
		if($my_pages_count == 0)
		{
			print("<em class='error'>".MYPAGES_NONE_OWNED."</em>");
		}

	}
	else
	{
		echo '<em>'.OWNED_NO_PAGES.'</em>';
	}
}
else
{
	echo '<em>'.OWNED_NOT_LOGGED_IN.'</em>';
}
?>
