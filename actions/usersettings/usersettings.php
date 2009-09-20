<?php
/**
 * Display a form to register, login and change user settings.
 *
 * @package		Actions
 * @version		$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @author		{@link http://wikkawiki.org/MinusF MinusF} (code cleanup and validation)
 * @author		{@link http://wikkawiki.org/DarTar Dario Taraborelli} (further cleanup, i18n, replaced JS dialogs with server-generated messages)
 *
 * @uses		Wakka::LogoutUser()
 * @uses		Wakka::Redirect()
 * @uses		Wakka::Getuser()
 * @uses		Wakka::GetSafeVar()
 * @uses		Wakka::Query()
 * @uses		Wakka::SetUser()
 * @uses		Wakka::LoadUser()
 * @uses		Wakka::FormOpen()
 * @uses		Wakka::FormClose()
 * @uses		Wakka::Link()
 * @uses		Wakka::Format()			to be avoided
 * @uses		Wakka::htmlspecialchars_ent()
 * @uses		Wakka::IsWikiName()
 * @uses		Wakka::existsPage()
 *
 * @todo		use different actions for registration / login / user settings;
 * @todo		add documentation links or short explanations for each option;
 * @todo		use error handler for displaying messages and highlighting
 * 				invalid input fields;
 * @todo		remove useless redirections;
 * @todo		avoid use of Format() (too costly for just headings and error strings)
 */

//initialize variables
$params = '';
$url = '';
$email = '';
$doubleclickedit = '';
$show_comments = '';
$revisioncount = '';
$changescount = '';
$password = '';
$oldpass = '';
$password_confirm = '';
$pw_selected = '';
$hash_selected = '';
$username_highlight = '';
$username_temp_highlight = '';
$password_temp_highlight = '';
$email_highlight = '';
$password_highlight = '';
$password_new_highlight = '';
$password_confirm_highlight = '';
$revisioncount_highlight = '';
$changescount_highlight = '';

// Create URAuth object
include_once('libs/userregistration.class.php');
$urobj = new URAuth($this);

//create URL
$url = $this->config['base_url'].$this->tag;

// append URL params depending on rewrite_mode
$params = ($this->config['rewrite_mode'] == 1) ? '?' : '&';

$regex_referrer = '@^'.preg_quote($this->config['base_url'], '@').'([^\/\?&]*)@i';
if (isset($_SERVER['HTTP_REFERER']) && preg_match($regex_referrer, $_SERVER['HTTP_REFERER'], $match))
{
	if (strcasecmp($this->tag, $match[1]))
	{
		$_SESSION['go_back'] = $_SERVER['HTTP_REFERER'];
		//We save the tag of the referring page, this tag is to be shown in label <Go back to ...>. We must use a session here because if the user 
		//Refresh the page by hitting <Enter> on the address bar, the value would be lost.
		$_SESSION['go_back_tag'] = $match[1];
	}
}

// BEGIN *** Logout ***
// is user trying to log out?
if (isset($_POST['logout']) && $_POST['logout'] == LOGOUT_BUTTON_LABEL)		// replaced with normal form button #353, #312
{
	$this->LogoutUser();
	$params .= 'out=true';
	$this->Redirect($url.$params);
}
// END *** Logout ***

// BEGIN *** Usersettings ***
// user is still logged in
else if ($user = $this->GetUser())
{
	// validate data source
	$keep_post_data = FALSE;
	if (isset($_POST['form_id']) && FALSE != ($aKey = $this->getSessionKey($_POST['form_id'])))	# check if page key was stored in session
	{
		if (TRUE == ($rc = $this->hasValidSessionKey($aKey)))	# check if correct name,key pair was passed
		{
			$keep_post_data  = TRUE;
		}
	}
	if(!$keep_post_data) unset($_POST);
	
	// is user trying to update user settings?
	if (isset($_POST['action']) && ($_POST['action'] == 'update'))
	{
		// get POST parameters
		$email = $this->GetSafeVar('email', 'post');
		$doubleclickedit = $this->GetSafeVar('doubleclickedit', 'post');
		$show_comments = $this->GetSafeVar('show_comments', 'post');
		$revisioncount = (int) $this->GetSafeVar('revisioncount', 'post');
		$changescount = (int) $this->GetSafeVar('changescount', 'post');
		$usertheme = $this->GetSafeVar('theme', 'post');

		// validate form input
		switch (TRUE)
		{
			case (strlen($email) == 0): //email is empty
				$error = ERROR_EMAIL_ADDRESS_REQUIRED;
				$email_highlight = INPUT_ERROR_STYLE;
				break;
			case (!preg_match(VALID_EMAIL_PATTERN, $email)): //invalid email
				$error = ERROR_INVALID_EMAIL_ADDRESS;
				$email_highlight = INPUT_ERROR_STYLE;
				break;
			case (($revisioncount < REVISION_DISPLAY_LIMIT_MIN) || ($revisioncount > REVISION_DISPLAY_LIMIT_MAX)): //invalid revision display limit
				$error = sprintf(ERROR_INVALID_REVISION_DISPLAY_LIMIT, REVISION_DISPLAY_LIMIT_MAX);
				$revisioncount_highlight = INPUT_ERROR_STYLE;
				break;
			case (($changescount < RECENTCHANGES_DISPLAY_LIMIT_MIN) || ($changescount > RECENTCHANGES_DISPLAY_LIMIT_MAX)): //invalid recentchanges display limit
				$error = sprintf(ERROR_INVALID_RECENTCHANGES_DISPLAY_LIMIT, RECENTCHANGES_DISPLAY_LIMIT_MAX);
				$changescount_highlight = INPUT_ERROR_STYLE;
				break;
			default: // input is valid
				$this->Query('UPDATE '.$this->config['table_prefix'].'users SET '.
					"email = '".mysql_real_escape_string($email)."', ".
					"doubleclickedit = '".mysql_real_escape_string($doubleclickedit)."', ".
					"show_comments = '".mysql_real_escape_string($show_comments)."', ".
					"revisioncount = '".mysql_real_escape_string($revisioncount)."', ".
					"changescount = '".mysql_real_escape_string($changescount)."', ".
					"theme = '".mysql_real_escape_string($usertheme)."' ".
					"WHERE name = '".$user['name']."' LIMIT 1");
				$this->SetUser($this->LoadUser($user["name"]));

				// forward
				$params .= 'stored=true';
				$this->Redirect($url.$params);
		}
	}
	//user just logged in
	else
	{
		// get stored settings
		$email = $user['email'];
		$doubleclickedit = $user['doubleclickedit'];
		$show_comments = $user['show_comments'];
		$revisioncount = $user['revisioncount'];
		$changescount = $user['changescount'];
		$usertheme = ($user['theme']!= '')? $user['theme'] : $this->GetConfigValue('theme');
	}

	// display user settings form
	echo '<h3>'.USER_SETTINGS_HEADING.'</h3>';
	echo $this->FormOpen();
?>
	<input type="hidden" name="action" value="update" />
	<table class="usersettings">
		<tr>
			<td>&nbsp;</td>
			<td>Hello, <?php echo $this->Link($user['name']) ?>!</td>
		</tr>
<?php

	// create confirmation message if needed
	switch(TRUE)
	{
		case (isset($_GET['registered']) && $_GET['registered'] == 'true'):
			$success = REGISTRATION_SUCCEEDED;
			break;
		case (isset($_GET['stored']) && $_GET['stored'] == 'true'):
			$success = USER_SETTINGS_STORED;
			break;
		case (isset($_GET['newpassword']) && $_GET['newpassword'] == 'true'):
			$success = PASSWORD_CHANGED;
	}

	// display error or confirmation message
	switch(TRUE)
	{
		case (isset($error)):
			echo '<tr><td></td><td><em class="error">'.$this->Format($error).'</em></td></tr>'."\n";
			break;
		case (isset($success)):
			echo '<tr><td></td><td><em class="success">'.$this->Format($success).'</em></td></tr>'."\n";
			break;
		default:
	}
?>
		<tr>
			<td align="right"><?php echo USER_EMAIL_LABEL ?></td>
			<td><input <?php echo $email_highlight; ?> name="email" value="<?php echo $this->htmlspecialchars_ent($email) ?>" size="40" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo DOUBLECLICK_LABEL ?></td>
			<td><input type="hidden" name="doubleclickedit" value="N" /><input type="checkbox" name="doubleclickedit" value="Y" <?php echo $doubleclickedit == 'Y' ? 'checked="checked"' : '' ?> /></td>
		</tr>
		<tr>
			<td align="right"><?php echo SHOW_COMMENTS_LABEL ?></td>
			<td><input type="hidden" name="show_comments" value="N" /><input type="checkbox" name="show_comments" value="Y" <?php echo $show_comments == 'Y' ? 'checked="checked"' : '' ?> /></td>
		</tr>
		<tr>
			<td align="right"><?php echo PAGEREVISION_LIST_LIMIT_LABEL ?></td>
			<td><input <?php echo $revisioncount_highlight; ?> name="revisioncount" value="<?php echo $this->htmlspecialchars_ent($revisioncount) ?>" size="40" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo RECENTCHANGES_DISPLAY_LIMIT_LABEL ?></td>
			<td><input <?php echo $changescount_highlight; ?> name="changescount" value="<?php echo $this->htmlspecialchars_ent($changescount) ?>" size="40" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo THEME_LABEL ?></td>
			<td><?php $this->SelectTheme($usertheme); ?></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="submit" value="<?php echo UPDATE_SETTINGS_INPUT ?>" /><!-- <input type="button" value="<?php echo LOGOUT_BUTTON_LABEL; ?>" onclick="document.location='<?php echo $this->href('', '', 'action=logout'); ?>'" /></td>-->
				<input id="logout" name="logout" type="submit" value="<?php echo LOGOUT_BUTTON_LABEL; ?>" />
			</td>
		</tr>
	</table>
<?php
	echo $this->FormClose(); //close user settings form

	if (isset($_POST['action']) && ($_POST['action'] == 'changepass'))
	{
		// check password
		$oldpass = $_POST['oldpass']; //can be current password or hash sent as password reminder
		$password = $_POST['password'];
		$password_confirm = $_POST['password_confirm'];
		$update_option = $this->GetSafeVar('update_option', 'post');

		switch (TRUE)
		{
			case (strlen($oldpass) == 0):
				$passerror = ERROR_EMPTY_PASSWORD_OR_HASH;
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (($update_option == 'pw') && md5($oldpass) != $user['password']): //wrong password
				$passerror = ERROR_WRONG_PASSWORD;
				$pw_selected = 'selected="selected"';
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (($update_option == 'hash') && $oldpass != $user['password']): //wrong hash
				$passerror = ERROR_WRONG_HASH;
				$hash_selected = 'selected="selected"';
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($password) == 0):
				$passerror = ERROR_EMPTY_NEW_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_new_highlight = INPUT_ERROR_STYLE;
				break;
			case (preg_match("/ /", $password)):
				$passerror = ERROR_NO_BLANK;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_new_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($password) < PASSWORD_MIN_LENGTH):
				$passerror = sprintf(ERROR_PASSWORD_TOO_SHORT, PASSWORD_MIN_LENGTH);
				$password_highlight = INPUT_ERROR_STYLE;
				$password_new_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($password_confirm) == 0):
				$passerror = ERROR_EMPTY_NEW_CONFIRMATION_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_new_highlight = INPUT_ERROR_STYLE;
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			case ($password_confirm != $password):
				$passerror = ERROR_PASSWORD_MATCH;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_new_highlight = INPUT_ERROR_STYLE;
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			default:
				$this->Query('UPDATE '.$this->config['table_prefix'].'users set '."password = md5('".mysql_real_escape_string($password)."') "."WHERE name = '".$user['name']."'");
				$user['password'] = md5($password);
				$this->SetUser($user);
				$params .= 'newpassword=true';
				$this->Redirect($url.$params);
		}
	}

	//display password update form
	echo '<hr />'."\n";
	echo $this->FormOpen();
?>
	<input type="hidden" name="action" value="changepass" />
	<h5><?php echo CHANGE_PASSWORD_HEADING ?></h5>
	<table class="usersettings">
<?php
		if (isset($passerror))
		{
			print('<tr><td></td><td><em class="error">'.$this->Format($passerror).'</em></td></tr>'."\n");
		}
?>
		<tr>
			<td align="right">
				<select name="update_option">
					<option value="pw" <?php echo $pw_selected; ?>><?php echo CURRENT_PASSWORD_LABEL; ?></option>
					<option value="hash" <?php echo $hash_selected; ?>><?php echo PASSWORD_REMINDER_LABEL; ?></option>
			</select></td>
			<td><input <?php echo $password_highlight; ?> type="password" name="oldpass" size="40" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo NEW_PASSWORD_LABEL ?></td>
			<td><input  <?php echo $password_new_highlight; ?> type="password" name="password" size="40" /></td>
		</tr>
		<tr>
			<td align="right"><?php echo NEW_PASSWORD_CONFIRM_LABEL ?></td>
			<td><input  <?php echo $password_confirm_highlight; ?> type="password" name="password_confirm" size="40" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="<?php echo CHANGE_BUTTON_LABEL ?>" size="40" /></td>
		</tr>
	</table>
<?php
	echo '<hr />'."\n";
	echo '<h5>'.QUICK_LINKS_HEADING.'</h5>'."\n";
	echo $this->Format(QUICK_LINKS);
	print($this->FormClose());
}
// user is not logged in
else
{
	// print confirmation message on successful logout
	if (isset($_GET['out']) && ($_GET['out'] == 'true'))
	{
		$success = USER_LOGGED_OUT;
	}

	$register = $this->GetConfigValue('allow_user_registration');
	// Login request
	if (isset($_POST['submit']) && ($_POST['submit'] == LOGIN_BUTTON_LABEL))
	{
		// if user name already exists, check password
		if (isset($_POST['name']) && $existingUser = $this->LoadUser($_POST['name']))
		{
			// check password
			$status = $existingUser['status'];
			switch(TRUE){
				case ($status=='deleted' ||
			          $status=='suspended' ||
					  $status=='banned'):
					$error = ERROR_USER_SUSPENDED;
					break;
				case (strlen($_POST['password']) == 0):
					$error = ERROR_EMPTY_PASSWORD;
					$password_highlight = INPUT_ERROR_STYLE;
					break;
				case (md5($_POST['password']) != $existingUser['password']):
					$error = ERROR_WRONG_PASSWORD;
					$password_highlight = INPUT_ERROR_STYLE;
					break;
				default:
					$this->SetUser($existingUser);
					if ((isset($_SESSION['go_back'])) && (isset($_POST['do_redirect'])))
					{
						$go_back = $_SESSION['go_back'];
						unset($_SESSION['go_back']);
						unset($_SESSION['go_back_tag']);
						$this->Redirect($go_back);
					}
					else
					{
						$this->Redirect($url, '');
					}
			}
		}
		else
		{
			$error = ERROR_NON_EXISTENT_USERNAME;
			$username_highlight = INPUT_ERROR_STYLE;
		}
	}

	// Registration request
	if (isset($_POST['submit']) && ($_POST['submit'] == REGISTER_BUTTON_LABEL) && $register == '1')
	{
		$name = trim($_POST['name']);
		$email = trim($this->GetSafeVar('email', 'post'));
		$password = $_POST['password'];
		$confpassword = $_POST['confpassword'];

		// validate input
		switch(TRUE)
		{
			case (FALSE===$urobj->URAuthVerify()):
				$error = ERROR_VALIDATION_FAILED;
				break;
			case (isset($_POST['name']) && $existingUser = $this->LoadUser($_POST['name'])):
				$error = ERROR_USERNAME_EXISTS;
				$username_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($name) == 0):
				$error = ERROR_EMPTY_USERNAME;
				$username_highlight = INPUT_ERROR_STYLE;
				break;
			case (!$this->IsWikiName($name)):
				$error = ERROR_WIKINAME;
				$username_highlight = INPUT_ERROR_STYLE;
				break;
			case ($this->existsPage($name,NULL,NULL,FALSE)):	// name change, new interface (check for non-active page, too)
				$error = ERROR_RESERVED_PAGENAME;
				$username_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($password) == 0):
				$error = ERROR_EMPTY_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (preg_match("/ /", $password)):
				$error = ERROR_NO_BLANK;
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($password) < PASSWORD_MIN_LENGTH):
				$error = sprintf(ERROR_PASSWORD_TOO_SHORT, PASSWORD_MIN_LENGTH);
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($confpassword) == 0):
				$error = ERROR_EMPTY_CONFIRMATION_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			case ($confpassword != $password):
				$error = ERROR_PASSWORD_MATCH;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			case (strlen($email) == 0):
				$error = ERROR_EMAIL_ADDRESS_REQUIRED;
				$email_highlight = INPUT_ERROR_STYLE;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			case (!preg_match(VALID_EMAIL_PATTERN, $email)):
				$error = ERROR_INVALID_EMAIL_ADDRESS;
				$email_highlight = INPUT_ERROR_STYLE;
				$password_highlight = INPUT_ERROR_STYLE;
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			default: //valid input, create user
				$this->Query("INSERT INTO ".$this->config['table_prefix']."users SET ".
					"signuptime = now(), ".
					"name = '".mysql_real_escape_string($name)."', ".
					"email = '".mysql_real_escape_string($email)."', ".
					"password = md5('".mysql_real_escape_string($_POST['password'])."')");

				// log in
				$this->SetUser($this->LoadUser($name));
				if ((isset($_SESSION['go_back'])) && (isset($_POST['do_redirect'])))
				{
					$go_back = $_SESSION['go_back'];
					unset($_SESSION['go_back']);
					unset($_SESSION['go_back_tag']);
					$this->Redirect($go_back);
				}
				else
				{
					$params .= 'registered=true';
					$this->Redirect($url.$params);
				}
		}
		// END *** Register ***
	}

	// BEGIN *** Usersettings ***
	elseif  (isset($_POST['action']) && ($_POST['action'] == 'updatepass'))
	{
		$name = trim($_POST['yourname']);
		if (strlen($name) == 0) // empty username
		{
			$newerror = ERROR_EMPTY_USERNAME;
			$username_temp_highlight = INPUT_ERROR_STYLE;
		}
		elseif (!$this->IsWikiName($name)) // check if name is WikiName style
		{
			$newerror = ERROR_WIKINAME;
			$username_temp_highlight = INPUT_ERROR_STYLE;
		}
		elseif (!($this->LoadUser($_POST['yourname']))) //check if user exists
		{
			$newerror = ERROR_NON_EXISTENT_USERNAME;
			$username_temp_highlight = INPUT_ERROR_STYLE;
		}
		elseif ($existingUser = $this->LoadUser($_POST['yourname']))  // if user name already exists, check password
		{
			// updatepassword
			if ($existingUser['password'] == $_POST['temppassword'])
			{
				$this->SetUser($existingUser, $_POST['remember']);
				$this->Redirect($url);
			}
			else
			{
				$newerror = ERROR_WRONG_PASSWORD;
				$password_temp_highlight = INPUT_ERROR_STYLE;
			}
		}
	}
	// END *** Usersettings ***

	// BEGIN ***  Login/Register ***
	print($this->FormOpen());
?>
	<input type="hidden" name="action" value="login" />
	<table class="usersettings">
	<tr>
		<td colspan="2"><?php echo ($register == '1') ?  $this->Format(LOGIN_REGISTER_HEADING) : $this->Format(LOGIN_HEADING) ?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $this->Format(REGISTERED_USER_LOGIN_LABEL); ?></td>
	</tr>
<?php
	switch (true)
	{
		case (isset($error)):
			echo '<tr><td></td><td><em class="error">'.$this->Format($error).'</em></td></tr>'."\n";
			break;
		case (isset($success)):
			echo '<tr><td></td><td><em class="success">'.$this->Format($success).'</em></td></tr>'."\n";
			break;
	}
?>
	<tr>
		<td align="right"><?php echo WIKINAME_LABEL ?></td>
		<td><input <?php echo $username_highlight; ?> name="name" size="40" value="<?php echo $this->GetSafeVar('name', 'post'); ?>" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo sprintf(PASSWORD_LABEL, PASSWORD_MIN_LENGTH) ?></td>
		<td><input <?php echo $password_highlight; ?> type="password" name="password" size="40" /></td>
	</tr>
	<?php
		if (isset($_SESSION['go_back']))
		{
		?>
	<tr>
		<td align="right"><?php printf(USERSETTINGS_REDIRECT_AFTER_LOGIN_LABEL, $_SESSION['go_back_tag']); ?></td>
		<td><input type='checkbox' name='do_redirect' id='do_redirect'<?php if(isset($_POST['do_redirect']) || empty($_POST)) echo 'checked="checked"'; ?> />
	</tr>
	<?php
		}
	?>
	<tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="<?php echo LOGIN_BUTTON_LABEL ?>" size="40" /></td>
	</tr>
<?php
	// END *** Login/Logout ***
	$register = $this->GetConfigValue('allow_user_registration');
	if($register == '1')
	{
?>
	<tr>
		<td>&nbsp;</td>
		<td width="500"><?php echo $this->Format(NEW_USER_REGISTER_LABEL); ?></td>
	</tr>
	<tr>
		<td colspan="2" align="left"><?php $urobj->URAuthDisplay(); ?></td>
	</tr>
	<tr>
		<td align="right"><?php echo CONFIRM_PASSWORD_LABEL ?></td>
		<td><input  <?php echo $password_confirm_highlight; ?> type="password" name="confpassword" size="40" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo USER_EMAIL_LABEL ?></td>
		<td><input <?php echo $email_highlight; ?> name="email" size="40" value="<?php echo $email; ?>" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="<?php echo REGISTER_BUTTON_LABEL ?>" size="40" /></td>
	</tr>
<?php
	}
?>
	</table>
<?php
	print($this->FormClose());
	// END *** Register ***

	// BEGIN *** Login Temp Password ***
	print($this->FormOpen());
?>
	<input type="hidden" name="action" value="updatepass" />
	<table class="usersettings">
	<tr>
		<td colspan="2"><br /><hr /><?php echo $this->Format(RETRIEVE_PASSWORD_HEADING) ?></td><td></td>
	</tr>
	<tr>
		<td align="left"></td>
		<td><?php echo $this->Format(RETRIEVE_PASSWORD_MESSAGE) ?></td>
	</tr>
<?php
	if (isset($newerror))
	{
		print('<tr><td></td><td><em class="error">'.$this->Format($newerror).'</em></td></tr>'."\n");
	}
?>
	<tr>
		<td align="right"><?php echo WIKINAME_LABEL ?></td>
		<td><input <?php echo $username_temp_highlight; ?> name="yourname" value="<?php echo $this->GetSafeVar('yourname', 'post'); ?>" size="40" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo TEMP_PASSWORD_LABEL ?></td>
		<td><input <?php echo $password_temp_highlight; ?> name="temppassword" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?php echo LOGIN_BUTTON_LABEL ?>" size="40" /></td>
	</tr>
   </table>
<?php
	print($this->FormClose());
	// END *** Login Temp Password ***
}
?>
