<?php
/**
 * Print number of pages owned by the current user.
 * 
 * @package		Actions
 * @name		Countowned
 * @version		$Id$
 * 
 * @uses	wakka::GetUserName()
 * @uses	wakka::Query()
 * @uses	wakka::Link()
 */

$str = 'SELECT COUNT(*) FROM '.$this->config["table_prefix"].'pages WHERE `owner` ';
$str .= "= '" . $this->GetUserName() . "' AND `latest` = 'Y'";
$countquery = $this->Query($str);
$count  = mysql_result($countquery, 0);
echo $this->Link('MyPages', '', $count,'','','Display a list of the pages you currently own');#i18n

?>