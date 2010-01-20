<?php
/**
 * Display a table of recently registered users.
 *
 * @package		Actions
 * @version		$Id: lastusers.php 1232 2008-09-17 20:46:30Z DarTar $
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @uses	Wakka::LoadAll()
 * @uses	Wakka::getCount()
 * @uses	Wakka::GetConfigValue()
 * @uses	Wakka::FormatUser()
 *
 * @input		integer  $max  optional: number of rows to be displayed;
 *				default: 10
 * @input		string  $style  optional (simple|complex): displays a simple table or a table with caption and headers and statistics on the number of pages owned;
 *				default: "complex"
 * @output		a table with the last registered users
 * 
 * @todo	document usage and parameters
 */
 
//defaults
define('LASTUSERS_DEFAULT_STYLE', 'complex'); # consistent parameter naming with HighScores action
define('LASTUSERS_MAX_USERS_DISPLAY', 10);

//initialize
$htmlout = '';
$style = '';
$max = '';

//validate action parameters
if (isset($vars['style']) && in_array($vars['style'], array('complex','simple')))
{
	$style = $vars['style'];
}
else
{
	$style = LASTUSERS_DEFAULT_STYLE;	
}
if (isset($vars['max']) && $vars['max'] > 0)
{
	$max = (int) $vars['max'];
}
else
{
	$max = LASTUSERS_MAX_USERS_DISPLAY;	
}

// @@@TODO reformat query
$last_users = $this->LoadAll("SELECT name, signuptime FROM ".$this->GetConfigValue('table_prefix')."users ORDER BY signuptime DESC LIMIT ".$max);

$htmlout .= '<table class="data lastusers">'."\n";
if ($style == 'complex')
{
	$htmlout .= '<caption>'.LASTUSERS_CAPTION.'</caption>'."\n";
	$htmlout .= '  <tr>'."\n";
	$htmlout .= '    <th>'.NAME_TH.'</th>'."\n";
	$htmlout .= '    <th>'.OWNED_PAGES_TH.'</th>'."\n";
	$htmlout .= '    <th>'.SIGNUP_DATE_TIME_TH.'</th>'."\n";
	$htmlout .= '  </tr>'."\n";
}
foreach($last_users as $user)
{
	$htmlout .= '  <tr>'."\n";
	if ($style == 'complex')
	{
		$where = "`owner` = '".mysql_real_escape_string($user['name'])."' AND `latest` = 'Y'";
		$htmlout .= '    <td>'.$this->FormatUser($user['name']).'</td>'."\n";
		$htmlout .= '    <td class="number">'.$this->getCount('pages', $where).'</td>'."\n";
		$htmlout .= '    <td class="datetime">('.$user['signuptime'].')</td>'."\n";
	}
	else
	{
		$htmlout .= '    <td>'.$this->FormatUser($user['name']).'</td>'."\n";
		$htmlout .= '    <td class="datetime">'.$user['signuptime'].'</td>'."\n";
	}
	$htmlout .= "  </tr>\n";
}

$htmlout .= '</table>'."\n";
echo $htmlout;
?>