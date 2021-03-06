<div id="content">
<?php
/**
 * Delete a page if the user is an admin.
 *
 * @package		Handlers
 * @subpackage	Page
 * @version		$Id: delete.php 1280 2009-01-11 04:17:12Z BrianKoontz $
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @uses	WIKKA_BASE_URL
 * @uses	Wakka::FormOpen()
 * @uses	Wakka::FormClose()
 * @uses	Wakka::IsAdmin()
 * @uses	Wakka::UserIsOwner()
 * @uses	Wakka::GetConfigValue()
 * @uses	Wakka::Link()
 * @uses	Wakka::Query()
 * @uses	Wakka::Redirect()
 * @uses	Wakka::Href()
 *
 * @todo	don't show cancel button when JavaScript is not active
 * @todo	avoid layout table (there are not even virtual columns!)
 * @todo	check if the "nonsense input" is really needed for rewrite mode;
 * 			if not (likely) remove!
 */

$tag = $this->GetPageTag();
// cancel operation and return to the page
if ($this->GetSafeVar('cancel', 'post') == T_("Cancel"))
{
	$this->Redirect($this->Href());
}

if ($this->IsAdmin() || ($this->UserIsOwner($tag) && (bool) $this->GetConfigValue('owner_delete_page')))
{
	if (isset($_POST['form_id']))
    {
	    $delete = FALSE;
    	if (FALSE != ($aKey = $this->getSessionKey($this->GetSafeVar('form_id', 'post'))))	# check if form key was stored in session
		{
			if (TRUE == ($rc = $this->hasValidSessionKey($aKey)))	# check if correct name,key pair was passed
			{
				$delete = TRUE;
			}
		}
		if (TRUE == $delete)
		{
			// delete the page, comments, related "from" links, acls and referrers
			$this->Query("DELETE FROM ".$this->GetConfigValue('table_prefix')."pages WHERE tag = '".mysql_real_escape_string($tag)."'");
			$this->Query("DELETE FROM ".$this->GetConfigValue('table_prefix')."comments WHERE page_tag = '".mysql_real_escape_string($tag)."'");
			$this->Query("DELETE FROM ".$this->GetConfigValue('table_prefix')."links WHERE from_tag = '".mysql_real_escape_string($tag)."'");
			$this->Query("DELETE FROM ".$this->GetConfigValue('table_prefix')."acls WHERE page_tag = '".mysql_real_escape_string($tag)."'");
			$this->Query("DELETE FROM ".$this->GetConfigValue('table_prefix')."referrers WHERE page_tag = '".mysql_real_escape_string($tag)."'");

			// redirect back to main page
			$this->Redirect(WIKKA_BASE_URL, T_("Page has been deleted!"));
		}
	}
	else
	{
		// show form
		?>
		<h3><?php printf(T_("Delete %s"),$this->Link($tag));?></h3>
		<br />

		<?php echo $this->FormOpen('delete') ?>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><?php echo T_("Completely delete this page, including all comments?") ?></td>
			</tr>
			<tr>
				<td>
				<!-- nonsense input so form submission works with rewrite mode -->
				<input type="hidden" value="" name="null">
				<input name="delete" type="submit" value="<?php echo T_("Delete Page") ?>"  style="width: 120px" />
				<input type="submit" value="<?php echo T_("Cancel") ?>" name="cancel" style="width: 120px" />
				</td>
			</tr>
		</table>
		<?php
		echo $this->FormClose();
	}
}
else
{
	echo '<em class="error">'.T_("You are not allowed to delete this page.").'</em>'."\n";
}
?>
</div>
