<?php
/**
 * Clone the current page and save a copy of it as a new page.
 *
 * Usage: append /clone to the URL of the page you want to clone
 * 
 * This handler checks the existence of the source page, the validity of the 
 * name of the target page to be created, the user's read-access to the source 
 * page and write-access to the target page.
 * If the "Edit after creation" option is selected, the user is redirected to the target page for
 * edition immediately after its creation.
 * If the "Clone ACL" option is selected, ACL settings are copied to the target page, 
 * otherwise default ACL are applied to the new page.
 *
 * @package         Handlers
 * @subpackage        Page
 * @version           $Id$
 * @since             Wikka 1.1.6.0
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @author		{@link http://wikkawiki.org/ChristianBarthelemy Christian Barthelemy} - original idea and code.
 * @author		{@link http://wikkawiki.org/DarTar Dario Taraborelli} - bugs fixed, code improved, removed popup alerts.
 * @author		{@link http://wikkawiki.org/BrianKoontz Brian Koontz} - clone ACL option
 * 
 * @input             string  $to  required: the page to be created
 *                            must be a non-existing page and current user must have privs to create it
 *                            default is source page name 				
 * 
 * @input             string  $note  optional: the note to be added to the page when created
 *                            default is "Cloned from " followed by the name of the source page
 * 
 * @input             boolean $editoption optional: if true, the new page will be opened for edition on creation
 *                            default is false (to allow multiple cloning of the same source)
 *
 * @input             boolean $cloneaclsoption optional: if true, ACLs are copied from the source page to the new page
 *                            default is false
 *
 * @uses			  Wakka::ExistsPage()
 * @uses			  Wakka::Format() 
 * @uses			  Wakka::FormClose() 
 * @uses			  Wakka::FormOpen() 
 * @uses			  Wakka::HasAccess()    
 * @uses			  Wakka::LoadPage() 
 * @uses			  Wakka::Href() 
 * @uses			  Wakka::Redirect() 
 * @uses			  Wakka::SavePage()
 * 
 * @todo           use central library for valid pagenames
 * @todo			  move main <div> to templating class
 * @todo			  use input highlight to mark invalid values
 * @todo			  add check for require_edit_note in the config file to enforce note
 * @todo			  make note max. length configurable in a constant
 * 
 * @filesource
 */
/**
 * Defaults
 */
if(!defined('VALID_PAGENAME_PATTERN')) define ('VALID_PAGENAME_PATTERN', '/^[A-Za-z�������]+[A-Za-z0-9�������]*$/s');

/**
 * i18n
 */
if (!defined('CLONE_LEGEND')) define('CLONE_LEGEND', 'Clone current page');
if (!defined('CLONE_SUCCESSFUL')) define('CLONE_SUCCESSFUL', '%s was succesfully created!');
if (!defined('CLONE_X_TO')) define('CLONE_X_TO', 'Clone %s to:');
if (!defined('CLONED_FROM')) define('CLONED_FROM', 'Cloned from %s');
if (!defined('EDIT_NOTE')) define('EDIT_NOTE', 'Edit note:');
if (!defined('ERROR_ACL_READ')) define('ERROR_ACL_READ', 'You are not allowed to read the source of this page.');
if (!defined('ERROR_ACL_WRITE')) define('ERROR_ACL_WRITE', 'Sorry! You don\'t have write-access to %s');
if (!defined('ERROR_INVALID_PAGENAME')) define('ERROR_INVALID_PAGENAME', 'This page name is invalid. Valid page names must start with a letter and contain only letters and numbers.');
if (!defined('ERROR_PAGE_ALREADY_EXIST')) define('ERROR_PAGE_ALREADY_EXIST', 'Sorry, the destination page already exists');
if (!defined('ERROR_PAGE_NOT_EXIST')) define('ERROR_PAGE_NOT_EXIST', ' Sorry, page %s does not exist.');
if (!defined('LABEL_CLONE')) define('LABEL_CLONE', 'Clone');
if (!defined('LABEL_EDIT_OPTION')) define('LABEL_EDIT_OPTION', ' Edit after creation');
if (!defined('LABEL_ACL_OPTION')) define('LABEL_ACL_OPTION', ' Clone ACL');
if (!defined('PLEASE_FILL_VALID_TARGET')) define('PLEASE_FILL_VALID_TARGET', 'Please fill in a valid target page name and an (optional) edit note.');

// initialization
$from = $this->tag;
$to = $this->tag;
$note = sprintf(CLONED_FROM, $from);
$editoption = ''; 
$cloneaclsoption = '';
$box = '<em>'.PLEASE_FILL_VALID_TARGET.'<em>';

echo '<div class="page">'."\n"; //TODO: move to templating class

// 1. check source page existence
if (!$this->ExistsPage($from))
{
	// source page does not exist!
	$box = sprintf(ERROR_PAGE_NOT_EXIST, $from);
} else 
{
	// 2. page exists - now check user's read-access to the source page
	if (!$this->HasAccess('read', $from))
	{
		// user can't read source page!
		$box = ERROR_ACL_READ;
	} else
	{
		// page exists and user has read-access to the source - proceed
		if (isset($_POST) && $_POST)
		{
			// get parameters
			$to = isset($_POST['to']) && $_POST['to'] ? $_POST['to'] : $to;
			$note = isset($_POST['note']) && $_POST['note'] ? $_POST['note'] : $note;
			$editoption = (isset($_POST['editoption']))? 'checked="checked"' : '';
			$cloneaclsoption = (isset($_POST['cloneaclsoption']))? 'checked="checked"' : '';
		
			// 3. check target pagename validity
			if (!preg_match(VALID_PAGENAME_PATTERN, $to))  //TODO use central regex library
			{
				// invalid pagename!
				$box = '<em class="error">'.ERROR_INVALID_PAGENAME.'</em>';
			} else
			{
				// 4. target page name is valid - now check user's write-access
				if (!$this->HasAccess('write', $to))  
				{
					$box = '<em class="error">'.sprintf(ERROR_ACL_WRITE, $to).'</em>';
				}
				else
				{
					// 5. check target page existence
					if ($this->ExistsPage($to))
					{ 
						// page already exists!
						$box = '<em class="error">'.ERROR_PAGE_ALREADY_EXIST.'</em>';
					}
					else
					{
						// 6. Valid request - proceed to page cloning
						$thepage=$this->LoadPage($from);
						if ($thepage)
						{
							$pagecontent = $thepage['body'];	
						}
						$this->SavePage($to, $pagecontent, $note);
						// Clone ACLs if requested
						if ($cloneaclsoption == 'checked="checked"')
						{
							$acl = $this->LoadAllACLs($from, 0);
							$this->SaveACL($to, 'read', $this->TrimACLs($acl['read_acl']));
							$this->SaveACL($to, 'write', $this->TrimACLs($acl['write_acl']));
							$this->SaveACL($to, 'comment', $this->TrimACLs($acl['comment_acl']));
						}
						// Open editor if requested
						if ($editoption == 'checked="checked"')
						{
							// quick edit
							$this->Redirect($this->href('edit', $to));
						} else
						{
							// show confirmation message
							$box = '<em class="success">'.sprintf(CLONE_SUCCESSFUL, $to).'</em>';
						}
					}
				}
			}
		} 
		// build form
		$form = $this->FormOpen('clone');
		$form .= '<fieldset><legend>'.CLONE_LEGEND.'</legend>';
		$form .= '<table class="clone">'."\n".
			'<tr><td colspan="2">%s</td></tr>'."\n".
			'<tr>'."\n".
			'<td><label for="to">'.sprintf(CLONE_X_TO, $this->Link($this->tag)).'</label></td>'."\n".
			'<td><input id="to" type="text" name="to" value="'.$to.'" size="37" maxlength="75" /></td>'."\n".
			'</tr>'."\n".
			'<tr>'."\n".
			'<td><label for="note">'.EDIT_NOTE.'</label></td>'.
			'<td><input id="note" name="note" type="text" value="'.$note.'" size="37" maxlength="75" /></td>'."\n".
			'</tr>'."\n".
			'<tr>'."\n".
			'<td></td>'."\n".
			'<td>'."\n".
			'<input type="checkbox" name="editoption" '.$editoption.' id="editoption" /><label for="editoption">'.LABEL_EDIT_OPTION.'</label>'."\n".
			'<input type="checkbox" name="cloneaclsoption" '.$cloneaclsoption.' id="cloneaclsoption" /><label for="cloneaclsoption">'.LABEL_ACL_OPTION.'</label>'."\n".
			'</tr>'."\n".
			'<tr>'."\n".
			'<td></td>'."\n".
			'<td>'."\n".
			'<input type="submit" name="create" value="'.LABEL_CLONE.'" />'."\n".
			'</td>'."\n".
			'</tr>'."\n".
			'</table>'."\n".
			'</fieldset>'."\n";
		$form .= $this->FormClose();
	}
}

// display messages
//if (isset($box)) echo $this->Format(' --- '.$box.' --- --- ');
// print form
//echo $form;
echo sprintf($form, $box);
echo '</div>'."\n" //TODO: move to templating class
?>