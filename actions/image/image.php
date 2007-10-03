﻿<?php
/**
 * Display an image.
 *
 * @package		Actions
 * @version		$Id$
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 *
 * @uses	Wakka::Link()
 * @uses	Wakka::ReturnSafeHTML()
 * @uses	Wakka::htmlspecialchars_ent()
 * @uses	Wakka::cleanUrl()
 * @uses	Wakka::StaticHref()
 *
 * @input	string	$url	mandatory: URL of image to be embedded
 * @input	string	$link	optional: target link for image (optional). Supports URL, WikiName links, InterWiki links etc.
 * @input	string	$title	optional: title text displayed when mouse hovers above image
 * @input	string	$class	optional: a class for the image
 * @input	string	$alt	optional: an alt text
 * @output	string	img element or image link
 * @todo	make alt required (wasn't there a better version already?)
 */

$title = 'WikiImage';	// @@@ don't use title attribute if no title specified
$class = $link = '';
$alt = 'image';			// @@@ don't generate a meaningless alt text: invalid!

if (is_array($vars))
{
	foreach ($vars as $param => $value)
	{
		if ($param == 'src' and $vars['url'] == '') {$vars['url']=$value;}
		if ($param == 'title') {$title = $this->htmlspecialchars_ent($vars['title']);}
		if ($param == 'class') {$class = $this->htmlspecialchars_ent($vars['class']);}
		if ($param == 'alt') {$alt = $this->htmlspecialchars_ent($vars['alt']);}
		if ($param == 'link') {$link = $this->htmlspecialchars_ent($vars['link']);}
	}
}
$url = $this->StaticHref($this->cleanUrl(trim($vars['url'])));

$output = '<img class="'.$class.'" src="'.$url.'" alt="'.$alt.'" title="'.$title.'" />';


// link?
if ($link !== '')
{
	$output = $this->Link($link, "", $output, 1, 0, 0);
}

$output = $this->ReturnSafeHTML($output);
print($output);

?>