<?php
/**
 * Ownership menulet
 */
if ($this->page)
{
	if ($owner = $this->GetPageOwner())
	{
		if ($owner == '(Public)')
		{
			echo OWNERLINK_PUBLIC_PAGE." ".($this->IsAdmin() ? '<a title="'.EDITACLLINK_TITLE.'" href="'.$this->Href('acls').'">'.EDITACLLINK_TEXT.'</a>'."\n" : "\n");
		}
		// if owner is current user
		elseif ($this->UserIsOwner())
		{
       		if ($this->IsAdmin())
       		{
				echo OWNERLINK_OWNER.' '.$this->Link($owner, '', '', 0).' <a title="'.EDITACLLINK_TITLE.'" href="'.$this->Href('acls').'">'.EDITACLLINK_TEXT.'</a>'."\n";
			} 
			else
 				{
				echo OWNERLINK_SELF.' <a title="'.EDITACLLINK_TITLE.'" href="'.$this->Href('acls').'">'.EDITACLLINK_TEXT.'</a>'."\n";
			}
		}
		else
		{
			echo OWNERLINK_OWNER.' '.$this->Link($owner, '', '', 0)."\n";
		}
	}
	else
	{
		echo OWNERLINK_NOBODY.($this->GetUser()? ' <a title="'.CLAIMLINK_TITLE.'" href="'.$this->Href('claim').'">'.CLAIMLINK_TEXT.'</a>'."\n" : "\n");
	}
}
?>
