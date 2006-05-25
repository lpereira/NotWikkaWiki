<?php
/**
 * Display a form to register, login and change user settings.
 *
 * @package		Actions
 * @name			UserSettings
 *
 * @author		{@link http://wikkawiki.org/MinusF MinusF} (code cleanup and validation)
 * @author		{@link http://wikkawiki.org/DarTar Dario Taraborelli} (further cleanup, i18n, replaced JS dialogs with server-generated messages)
 * @since		Wikka 1.1.6.2
 *
 * @input		none
 * @todo			-different actions for registration / login / user settings
 					-add documentation links or short explanations for each option
 					-error handler for displaying messages and highlighting invalid fields
 					- give correct explanation of password retrieval procedure
 */

// defaults
if (!defined('PASSWORD_MIN_LENGTH')) define('PASSWORD_MIN_LENGTH', "5");
if (!defined('VALID_EMAIL_PATTERN')) define('VALID_EMAIL_PATTERN', "/^.+?\@.+?\..+$/"); //TODO: Use central regex library
if (!defined('REVISION_DISPLAY_LIMIT_MIN')) define('REVISION_DISPLAY_LIMIT_MIN', "0"); // 0 means no limit, 1 is the minimum number of revisions
if (!defined('REVISION_DISPLAY_LIMIT_MAX')) define('REVISION_DISPLAY_LIMIT_MAX', "20"); // keep this value within a reasonable limit to avoid an unnecessary long lists
if (!defined('RECENTCHANGES_DISPLAY_LIMIT_MIN')) define('RECENTCHANGES_DISPLAY_LIMIT_MIN', "0"); // 0 means no limit, 1 is the minimum number of changes
if (!defined('RECENTCHANGES_DISPLAY_LIMIT_MAX')) define('RECENTCHANGES_DISPLAY_LIMIT_MAX', "50"); // keep this value within a reasonable limit to avoid an unnecessary long list
if (!defined('INPUT_ERROR_STYLE')) define('INPUT_ERROR_STYLE', 'class="highlight"');

// i18n strings
if (!defined('USER_LOGGED_OUT')) define('USER_LOGGED_OUT', "You have successfully logged out.");
if (!defined('USER_SETTINGS_STORED')) define('USER_SETTINGS_STORED', "User settings stored!");
if (!defined('ERROR_NO_BLANK')) define('ERROR_NO_BLANK', "Sorry, blanks are not permitted in the password.");
if (!defined('ERROR_PASSWORD_TOO_SHORT')) define('ERROR_PASSWORD_TOO_SHORT', "Sorry, the password must contain at least %s characters.");
if (!defined('PASSWORD_CHANGED')) define('PASSWORD_CHANGED', "Password successfully changed!");
if (!defined('ERROR_OLD_PASSWORD_WRONG')) define('ERROR_OLD_PASSWORD_WRONG', "The old password you entered is wrong.");
if (!defined('USER_EMAIL_LABEL')) define('USER_EMAIL_LABEL', "Your email address:");
if (!defined('DOUBLECLICK_LABEL')) define('DOUBLECLICK_LABEL', "Doubleclick Editing:");
if (!defined('SHOW_COMMENTS_LABEL')) define('SHOW_COMMENTS_LABEL', "Show comments by default:");
if (!defined('RECENTCHANGES_DISPLAY_LIMIT_LABEL')) define('RECENTCHANGES_DISPLAY_LIMIT_LABEL', "RecentChanges display limit:");
if (!defined('PAGEREVISION_LIST_LIMIT_LABEL')) define('PAGEREVISION_LIST_LIMIT_LABEL', "Page revisions list limit:");
if (!defined('UPDATE_SETTINGS_INPUT')) define('UPDATE_SETTINGS_INPUT', "Update Settings");
if (!defined('CHANGE_PASSWORD_LABEL')) define('CHANGE_PASSWORD_LABEL', "Change your password:");
if (!defined('CURRENT_PASSWORD_LABEL')) define('CURRENT_PASSWORD_LABEL', "Your current password:");
if (!defined('NEW_PASSWORD_LABEL')) define('NEW_PASSWORD_LABEL', "Your new password:");
if (!defined('CHANGE_BUTTON_LABEL')) define('CHANGE_BUTTON_LABEL', "Change");
if (!defined('REGISTER_BUTTON_LABEL')) define('REGISTER_BUTTON_LABEL', "Register");
if (!defined('QUICK_LINKS')) define('QUICK_LINKS', "See a list of pages you own (MyPages) and pages you've edited (MyChanges).");
if (!defined('ERROR_WRONG_PASSWORD')) define('ERROR_WRONG_PASSWORD', "Sorry, you entered the wrong password.");
if (!defined('ERROR_EMPTY_USERNAME')) define('ERROR_EMPTY_USERNAME', "Please fill in your user name.");
if (!defined('ERROR_RESERVED_PAGENAME')) define('ERROR_RESERVED_PAGENAME', "Sorry, this name is reserved for a page. Please choose a different name.");
if (!defined('ERROR_WIKINAME')) define('ERROR_WIKINAME', "User name must be formatted as a ##\"\"WikiName\"\"##, e.g. ##\"\"JohnDoe\"\"##.");
if (!defined('ERROR_EMPTY_PASSWORD')) define('ERROR_EMPTY_PASSWORD', "Please fill in a password.");
if (!defined('ERROR_EMPTY_CONFIRMATION_PASSWORD')) define('ERROR_EMPTY_CONFIRMATION_PASSWORD', "Please confirm your password in order to register a new account.");
if (!defined('ERROR_EMPTY_NEW_PASSWORD')) define('ERROR_EMPTY_NEW_PASSWORD', "You must also fill in a new password.");
if (!defined('ERROR_PASSWORD_MATCH')) define('ERROR_PASSWORD_MATCH', "Passwords don't match.");
if (!defined('ERROR_EMAIL_ADDRESS_REQUIRED')) define('ERROR_EMAIL_ADDRESS_REQUIRED', "Please specify an email address.");
if (!defined('ERROR_INVALID_EMAIL_ADDRESS')) define('ERROR_INVALID_EMAIL_ADDRESS', "That doesn't quite look like an email address.");
if (!defined('ERROR_INVALID_REVISION_DISPLAY_LIMIT')) define('ERROR_INVALID_REVISION_DISPLAY_LIMIT', "The number of page revisions should not exceed %d.");
if (!defined('ERROR_INVALID_RECENTCHANGES_DISPLAY_LIMIT')) define('ERROR_INVALID_RECENTCHANGES_DISPLAY_LIMIT', "The number of recently changed pages should not exceed %d.");
if (!defined('REGISTRATION_SUCCEEDED')) define('REGISTRATION_SUCCEEDED', "You have successfully registered!");
if (!defined('REGISTERED_USER_LOGIN_LABEL')) define('REGISTERED_USER_LOGIN_LABEL', "If you're already a registered user, log in here!");
if (!defined('REGISTER_HEADING')) define('REGISTER_HEADING', "===Login/Register===");
if (!defined('WIKINAME_LABEL')) define('WIKINAME_LABEL', "Your <abbr title=\"A WikiName is formed by two or more capitalized words without space, e.g. JohnDoe\">WikiName</abbr>:");
if (!defined('PASSWORD_LABEL')) define('PASSWORD_LABEL', "Password (%s+ chars):");
if (!defined('LOGIN_BUTTON_LABEL')) define('LOGIN_BUTTON_LABEL', "Login");
if (!defined('LOGOUT_BUTTON_LABEL')) define('LOGOUT_BUTTON_LABEL', "Logout");
if (!defined('NEW_USER_REGISTER_LABEL')) define('NEW_USER_REGISTER_LABEL', "Stuff you only need to fill in when you're logging in for the first time (and thus signing up as a new user on this site).");
if (!defined('CONFIRM_PASSWORD_LABEL')) define('CONFIRM_PASSWORD_LABEL', "Confirm password:");
if (!defined('RETRIEVE_PASSWORD_HEADING')) define('RETRIEVE_PASSWORD_HEADING', "===Forgot your password?===");
if (!defined('RETRIEVE_PASSWORD_MESSAGE')) define('RETRIEVE_PASSWORD_MESSAGE', "Log in here with the temporary password. --- If you need a temporary password, click [[PasswordForgotten here]].");
if (!defined('TEMP_PASSWORD_LABEL')) define('TEMP_PASSWORD_LABEL', "Your temp password:");

//initialize variables
$email = '';
$doubleclickedit = '';
$show_comments = '';
$revisioncount = '';
$changescount = '';
$password = '';
$oldpass = '';
$username_highlight = '';
$username_temp_highlight = '';
$password_temp_highlight = '';
$email_highlight = '';
$password_highlight = '';
$password_confirm_highlight = '';
$revisioncount_highlight = '';
$changescount_highlight = '';

// is user trying to log out?
if (isset($_REQUEST['action']) && ($_REQUEST['action'] == 'logout'))
{
	$this->LogoutUser();
	$this->Redirect($this->href('','','out=true'));
}
// user is still logged in
else if ($user = $this->GetUser())
{
	// is user trying to update user settings?
	if (isset($_POST['action']) && ($_POST['action'] == 'update'))
	{
		// get POST parameters
		$email = $_POST['email'];
		$doubleclickedit = $_POST['doubleclickedit'];
		$show_comments = $_POST['show_comments'];
		$revisioncount = (int) $_POST['revisioncount'];
		$changescount = (int) $_POST['changescount'];
		
		switch(TRUE) // validate form input
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
					"changescount = '".mysql_real_escape_string($changescount)."' ".
					"WHERE name = '".$user['name']."' LIMIT 1");
				$this->SetUser($this->LoadUser($user["name"]));
			
				// forward
				$this->Redirect($this->href('','','stored=true'));
		}
	}
	else //user just logged in
	{
		// get stored settings
		$email = $user['email'];
		$doubleclickedit = $user['doubleclickedit'];
		$show_comments = $user['show_comments'];
		$revisioncount = $user['revisioncount'];
		$changescount = $user['changescount'];
		
	}

	// display user settings form
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
			<td>&nbsp;</td>
			<td><input type="submit" value="<?php echo UPDATE_SETTINGS_INPUT ?>" /> <input type="button" value="<?php echo LOGOUT_BUTTON_LABEL; ?>" onclick="document.location='<?php echo $this->href('', '', 'action=logout'); ?>'" /></td>
		</tr>
	</table>
<?php	
	echo $this->FormClose(); //close user settings form

	if (isset($_POST['action']) && ($_POST['action'] == 'changepass'))
	{
		// check password
		$oldpass = $_POST['oldpass'];
		$password = $_POST['password'];			
		
		switch (TRUE)
		{
			case (strlen($oldpass) == 0):
				$passerror = ERROR_EMPTY_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;
				break;
			case (md5($oldpass) != $user['password']):
				$passerror = ERROR_WRONG_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;			
				break;
			case (strlen($password) == 0):
				$passerror = ERROR_EMPTY_NEW_PASSWORD;
				$password_highlight = INPUT_ERROR_STYLE;			
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			case (preg_match("/ /", $password)):
				$passerror = ERROR_NO_BLANK;
				$password_highlight = INPUT_ERROR_STYLE;			
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;	
			case (strlen($password) < PASSWORD_MIN_LENGTH):
				$passerror = sprintf(ERROR_PASSWORD_TOO_SHORT, PASSWORD_MIN_LENGTH);
				$password_highlight = INPUT_ERROR_STYLE;			
				$password_confirm_highlight = INPUT_ERROR_STYLE;
				break;
			default:
				$this->Query('UPDATE '.$this->config['table_prefix'].'users set '."password = md5('".mysql_real_escape_string($password)."') "."WHERE name = '".$user['name']."'");
				$user['password'] = md5($password);
				$this->SetUser($user);
				$this->Redirect($this->href('','','newpassword=true'));
		}
	}

	//display password update form
	echo $this->FormOpen();
?>
	<input type="hidden" name="action" value="changepass" />
	<table class="usersettings">
		<tr>
			<td align="left"><b><?php echo CHANGE_PASSWORD_LABEL ?></b></td>
			<td><br /><br />&nbsp;</td>
		</tr>
<?php
		if (isset($passerror))
		{
			print('<tr><td></td><td><em class="error">'.$this->Format($passerror).'</em></td></tr>'."\n");
		}
?>
		<tr>
			<td align="left"><?php echo CURRENT_PASSWORD_LABEL ?></td>
			<td><input <?php echo $password_highlight; ?> type="password" name="oldpass" size="40" /></td>
		</tr>
		<tr>
			<td align="left"><?php echo NEW_PASSWORD_LABEL ?></td>
			<td><input  <?php echo $password_confirm_highlight; ?> type="password" name="password" size="40" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="<?php echo CHANGE_BUTTON_LABEL ?>" size="40" /></td>
		</tr>
	</table>
	<br />
<?php
	echo $this->Format(QUICK_LINKS);
	print($this->FormClose());
}
else // user is not logged in
{
	// print confirmation message on successful logout
	if (isset($_GET['out']) && ($_GET['out'] == 'true'))
	{
		$success = USER_LOGGED_OUT;
	}

	// is user trying to log in or register?
	if (isset($_POST['action']) && ($_POST['action'] == 'login'))
	{
		// if user name already exists, check password
		if (isset($_POST['name']) && $existingUser = $this->LoadUser($_POST['name']))
		{
			// check password
			switch(TRUE){
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
					$this->Redirect($this->href());
			}
		}
		else // otherwise, proceed to registration
		{
			$name = trim($_POST['name']);
			$email = trim($_POST['email']);
			$password = $_POST['password'];
			$confpassword = $_POST['confpassword'];

			// validate input
			switch(TRUE)
			{
				case (strlen($name) == 0):
					$error = ERROR_EMPTY_USERNAME;
					$username_highlight = INPUT_ERROR_STYLE;
					break;
				case (!$this->IsWikiName($name)):
					$error = ERROR_WIKINAME;
					$username_highlight = INPUT_ERROR_STYLE;
					break;
				case ($this->ExistsPage($name)):
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
					$this->Redirect($this->href('', '', 'registered=true'));
			}
		}
	}
	elseif  (isset($_POST['action']) && ($_POST['action'] == 'updatepass'))
	{
		// check if name is WikiName style
        $name = trim($_POST['yourname']);
		if (!$this->IsWikiName($name))
		{
			$newerror = ERROR_WIKINAME;
			$username_temp_highlight = INPUT_ERROR_STYLE;
		}
		// if user name already exists, check password
		elseif ($existingUser = $this->LoadUser($_POST['yourname']))   
		{
			// updatepassword
			if ($existingUser['password'] == $_POST['temppassword'])
			{
				$this->SetUser($existingUser, $_POST["remember"]);
				$this->Redirect($this->href());
			}
			else
			{
				$newerror = ERROR_WRONG_PASSWORD;
				$password_temp_highlight = INPUT_ERROR_STYLE;
			}
		}
	}

	print($this->FormOpen());
?>
	<input type="hidden" name="action" value="login" />
	<table class="usersettings">
   	<tr>
   		<td colspan="2"><?php echo $this->Format(REGISTER_HEADING) ?></td>
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
		<td><input <?php echo $username_highlight; ?> name="name" size="40" value="<?php if (isset($_POST['name'])){ echo $_POST['name']; }?>" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo sprintf(PASSWORD_LABEL, PASSWORD_MIN_LENGTH) ?></td>
		<td><input <?php echo $password_highlight; ?> type="password" name="password" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?php echo LOGIN_BUTTON_LABEL ?>" size="40" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td width="500"><?php echo $this->Format(NEW_USER_REGISTER_LABEL); ?></td>
	</tr>
	<tr>
		<td align="right"><?php echo CONFIRM_PASSWORD_LABEL ?></td>
		<td><input  <?php echo $password_confirm_highlight; ?> type="password" name="confpassword" size="40" /></td>
	</tr>
	<tr>
		<td align="right"><?php echo USER_EMAIL_LABEL ?></td>
		<td><input <?php echo $email_highlight; ?> name="email" size="40" value="<?php if (isset($email)){ echo $email; }?>" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="<?php echo REGISTER_BUTTON_LABEL ?>" size="40" /></td>
	</tr>
	</table>
<?php
	print($this->FormClose());
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
		<td><input <?php echo $username_temp_highlight; ?> name="yourname" value="<?php if (isset($_POST['yourname'])){ echo $_POST["yourname"]; }?>" size="40" /></td>
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
}
?>