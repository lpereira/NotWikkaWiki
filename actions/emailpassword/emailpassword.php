<?php
/** 
 * Send the user a reminder with the md5 checksum of his or her password via email.
 * 
 * @author	{@link http://comawiki.martignier.net Costal Martignier} initial action
 * @author	{@link http://wikkawiki.org/NilsLindenberg Nils Lindenberg} rewritten
 * @author	{@link http://wikkawiki.org/DarTar Dario Taraborelli} further cleanup, error styling and improved logical structure
 * @license http://comawiki.martignier.net/LizenzenUndBedingungen
 * @email 	actions@martignier.net
 * 
 */ 

// *** initialization ***
$input = $output = '';
$highlight = '';
$user = FALSE;
$mailsent = FALSE;

//print heading
$output .= $this->Format(PW_FORGOTTEN_HEADING);

if (isset($_POST['wikiname'])) // get posted values
{
	$input = $_POST['wikiname'];
	$user = $this->LoadUser($input);

	switch(TRUE)
	{
		case ($input == ''): // empty user
			$output .= '<em class="error">'.ERROR_EMPTY_USER.'</em><br />'."\n";
			$highlight = INPUT_ERROR_STYLE;
			break;
		case ($input != '' && !$user): // non-existing user
			$output .= '<em class="error">'.ERROR_UNKNOWN_USER.'</em><br />'."\n";
			$highlight = INPUT_ERROR_STYLE;
			break;
		case ($input != '' && $user): // user exists, proceed
			$header = "From: ".$this->config['wakka_name']." <".$this->config['admin_email'].">";
			$reference = sprintf(PW_FORGOTTEN_MAIL_REF, $user['name']);
			$mail = sprintf(PW_FORGOTTEN_MAIL, $user['name'], $this->config['wakka_name'], $user['password'], $this->config['base_url'].'UserSettings')."\n";
			if (mail($user['email'], $reference, $mail, $header))
			{
				$mailsent = TRUE;
				$output .= '<br /><em class="success">'.sprintf(PW_CHK_SENT, $user['name']).'</em><br />'."\n";
				$output .= $this->Format(USERSETTINGS_LINK);
			}
			else 
			{
				$output .= '<em class="error">'.ERROR_MAIL_NOT_SENT.'</em><br />'."\n";
			}
			break;
	}
}

// display input form
if (!$mailsent)
{
	$output .= '<p>'.PW_FORM_TEXT.'</p>'; 
//	$output .= '<form name="getwikiname" action="'.$this->href().'" method="post">';
	$output .= $this->FormOpen();
	$output .= '<input '.$highlight.' type="text" name="wikiname" value="" />';
	$output .= '<input type="submit" value="'.BUTTON_SEND_PW_LABEL.'" />';
	$output .= $this->FormClose();   
}

// *** output section ***
if ($output !== '') echo $output;
?>
