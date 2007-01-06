<?php
/**
 * Display a form to manage ACL for the current page.
 *
 * @package		Handlers
 * @subpackage 	Page
 * @version		$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * @uses Config::$default_comment_acl
 * @uses Config::$default_read_acl
 * @uses Config::$default_write_acl
 *
 * @author		{@link http://wikkawiki.org/MinusF MinusF} (preliminary code cleanup, css selectors)
 * @author		{@link http://wikkawiki.org/DarTar Dario Taraborelli} (further cleanup)
 * @author		{@link http://wikkawiki.org/NilsLindenberg Nils Lindenberg} (i18n)
 *
 * @todo		move main <div> to templating class
 */

/**
 * i18n
 */
if (!defined('ACLS_UPDATED')) define('ACLS_UPDATED', 'Access control lists updated.');
if (!defined('NO_PAGE_OWNER')) define('NO_PAGE_OWNER', '(Nobody)');
if (!defined('NOT_PAGE_OWNER')) define('NOT_PAGE_OWNER', 'You are not the owner of this page.');
if (!defined('PAGE_OWNERSHIP_CHANGED')) define('PAGE_OWNERSHIP_CHANGED', 'Ownership changed to %s'); // %s - name of new owner
if (!defined('ACL_LEGEND')) define('ACL_LEGEND', 'Access Control Lists for %s'); // %s - name of current page
if (!defined('READ_ACL_LABEL')) define('READ_ACL_LABEL', 'Read ACL:');
if (!defined('WRITE_ACL_LABEL')) define('WRITE_ACL_LABEL', 'Write ACL:');
if (!defined('COMMENT_ACL_LABEL')) define('COMMENT_ACL_LABEL', 'Comment ACL:');
if (!defined('SET_OWNER_LABEL')) define('SET_OWNER_LABEL', 'Set Page Owner:');
if (!defined('SET_OWNER_CURRENT_LABEL')) define('SET_OWNER_CURRENT_LABEL', '(Current Owner)');
if (!defined('SET_OWNER_PUBLIC_LABEL')) define('SET_OWNER_PUBLIC_LABEL','(Public)');
if (!defined('SET_NO_OWNER_LABEL')) define('SET_NO_OWNER_LABEL', '(Nobody - Set free)');
if (!defined('ACL_SYNTAX_HELP')) define('ACL_SYNTAX_HELP', '===Syntax:=== ---##*## = Everyone ---##+## = Registered users ---##""JohnDoe""## = the user called ""JohnDoe"", enter as many users as you want, one per line --- --- Any of these items can be negated with a ##!##: ---##!*## = No one (except admins) ---##!+## = Anonymous users only ---##""!JohnDoe""## = ""JohnDoe"" will be denied access --- --- //ACLs are tested in the order they are specified:// --- So be sure to specify ##*## on a separate line //after// negating any users, not before.');
if (!defined('BUTTON_STORE_ACLS')) define('BUTTON_STORE_ACLS', 'Store ACLs');
if (!defined('BUTTON_CANCEL')) define('BUTTON_CANCEL', 'Cancel');

echo '<div class="page">'."\n"; //TODO: move to templating class

if ($this->UserIsOwner())
{
	if ($_POST)
	{
		$default_read_acl = $this->GetConfigValue('default_read_acl');
		$default_write_acl = $this->GetConfigValue('default_write_acl');
		$default_comment_acl = $this->GetConfigValue('default_comment_acl');
		$posted_read_acl = $_POST['read_acl'];
		$posted_write_acl = $_POST['write_acl'];
		$posted_comment_acl = $_POST['comment_acl'];
		$message = '';

		// store lists only if ACLs have previously been defined,
		// or if the posted values are different than the defaults

		$page = $this->LoadSingle('SELECT * FROM '.$this->config['table_prefix'].
		    "acls WHERE page_tag = '".mysql_real_escape_string($this->GetPageTag()).
		    "' LIMIT 1");

		if ($page ||
		    ($posted_read_acl != $default_read_acl||
		     $posted_write_acl != $default_write_acl||
		     $posted_comment_acl != $default_comment_acl))
		{
			$this->SaveACL($this->GetPageTag(), 'read', $this->TrimACLs($posted_read_acl));
			$this->SaveACL($this->GetPageTag(), 'write', $this->TrimACLs($posted_write_acl));
			$this->SaveACL($this->GetPageTag(), 'comment', $this->TrimACLs($posted_comment_acl));
			$message = ACLS_UPDATED;
		}

		// change owner?
		$newowner = $_POST['newowner'];

		if (($newowner != 'same') &&
		    ($this->GetPageOwner($this->GetPageTag()) != $newowner))
		{
			if ($newowner == '')
			{
				$newowner = NO_PAGE_OWNER;
			}

			$this->SetPageOwner($this->GetPageTag(), $newowner);
			$message .= sprintf(PAGE_OWNERSHIP_CHANGED, $newowner);
		}

		// redirect back to page
		$this->Redirect($this->Href(), $message);
	}
	else // show form
	{
?>
<?php echo $this->FormOpen('acls') ?>
<fieldset><legend><?php echo $this->Format(sprintf(ACL_LEGEND, '[['.$this->tag.']]').' --- ');?></legend>
<table class="acls">
<tr>
	<td>
	<label for="read_acl"><strong><?php echo READ_ACL_LABEL; ?></strong></label><br />
	<textarea id="read_acl" name="read_acl" rows="4" cols="20"><?php echo preg_replace("/[\s,]+/", "\n", $this->ACLs['read_acl']) ?></textarea>
	</td>

	<td>
	<label for="write_acl"><strong><?php echo WRITE_ACL_LABEL; ?></strong></label><br />
	<textarea id="write_acl" name="write_acl" rows="4" cols="20"><?php echo preg_replace("/[\s,]+/", "\n", $this->ACLs['write_acl']) ?></textarea>
	</td>

	<td>
	<label for="comment_acl"><strong><?php echo COMMENT_ACL_LABEL; ?></strong></label><br />
	<textarea id="comment_acl" name="comment_acl" rows="4" cols="20"><?php echo preg_replace("/[\s,]+/", "\n", $this->ACLs['comment_acl']) ?></textarea>
	</td>
</tr>

<tr>
	<td colspan="2">
	<br />
	<input type="submit" value="<?php echo BUTTON_STORE_ACLS; ?>" />
	<input type="button" value="<?php echo BUTTON_CANCEL; ?>" onclick="history.back();" />
	</td>

	<td>
	<label for="newowner"><strong><?php echo SET_OWNER_LABEL; ?></strong></label><br />
	<select id="newowner" name="newowner">
	<option value="same"><?php echo $this->GetPageOwner().' '.SET_OWNER_CURRENT_LABEL ?></option>
	<option value="(Public)"><?php echo SET_OWNER_PUBLIC_LABEL; ?></option>
	<option value=""><?php echo SET_NO_OWNER_LABEL; ?></option>
<?php
		if ($users = $this->LoadUsers())
		{
			foreach($users as $user)
			{
				echo "\t".'<option value="'.$this->htmlspecialchars_ent($user['name']).'">'.$user['name'].'</option>'."\n";
			}
		}
?>
	</select>
	</td>
</tr>
</table>
</fieldset>
<br />
<?php echo $this->Format(ACL_SYNTAX_HELP); ?>
<?php
		print($this->FormClose());
	}
}
else
{
	echo '<em class="error">'.NOT_PAGE_OWNER.'</em>'."\n";
}
echo '</div>'."\n" //TODO: move to templating class
?>
