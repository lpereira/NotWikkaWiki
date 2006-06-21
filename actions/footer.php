<div class="footer">
<?php 
	echo $this->FormOpen("", "TextSearch", "get"); 
	echo $this->HasAccess("write") ? "<a href=\"".$this->href("edit")."\" title=\"Click to edit this page\">Edit page</a> ::\n" : "";
	echo "<a href=\"".$this->href("history")."\" title=\"Click to view recent edits to this page\">Page History</a> ::\n";
	echo $this->GetPageTime() ? "<a href=\"".$this->href("revisions")."\" title=\"Click to view recent revisions list for this page\">".$this->GetPageTime()."</a> <a href=\"".$this->href("revisions.xml")."\" title=\"Click to view recent page revisions in XML format.\"><img src=\"images/xml.png\" width=\"36\" height=\"14\" align=\"middle\" style=\"border : 0px;\" alt=\"XML\" /></a> ::\n" : "";

	// if this page exists
	if ($this->page)
	{
		if ($owner = $this->GetPageOwner())
		{
			if ($owner == "(Public)")
			{
				print("Public page ".($this->IsAdmin() ? "<a href=\"".$this->href("acls")."\">(Edit ACLs)</a> ::\n" : "::\n"));
			}
			// if owner is current user
			elseif ($this->UserIsOwner())
			{
           			if ($this->IsAdmin())
           			{
					print("Owner: ".$this->Link($owner, "", "", 0)." :: <a href=\"".$this->href("acls")."\">Edit ACLs</a> ::\n");
            		} 
            		else 
            		{
					print("You own this page. :: <a href=\"".$this->href("acls")."\">Edit ACLs</a> ::\n");
				}
			}
			else
			{
				print("Owner: ".$this->Link($owner, "", "", 0)." ::\n");
			}
		}
		else
		{
			print("Nobody".($this->GetUser() ? " (<a href=\"".$this->href("claim")."\">Take Ownership</a>) ::\n" : " ::\n"));
		}
	}
?>
<?php echo ($this->GetUser() ? "<a href='".$this->href("referrers")."' title='Click to view a list of URLs referring to this page.'>Referrers</a> :: " : "") ?> 
Search: <input name="phrase" size="15" class="searchbox" />
<?php echo $this->FormClose(); ?>
</div>

<div class="smallprint">
<?php echo $this->Link("http://validator.w3.org/check/referer", "", "Valid XHTML 1.0 Transitional") ?> ::
<?php echo $this->Link("http://jigsaw.w3.org/css-validator/check/referer", "", "Valid CSS") ?> ::
Powered by <?php echo $this->Link("http://wikkawiki.org/", "", "Wikka Wakka Wiki ".$this->GetWakkaVersion()) ?>
</div>

<?php
	// display SQL debug information to admins
	if ($this->config['sql_debugging'] == 1 && $this->IsAdmin())
	{
		echo '<div class="smallprint"><strong>Query log:</strong><br />'."\n";
		foreach ($this->queryLog as $query)
		{
			echo $query['query'].' ('.$query['time'].')<br />'."\n";
		}
		echo '</div>';
	}
?>