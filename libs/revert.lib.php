<?php

/**
 * Page reversion routines 
 *
 * Various routines to handle the reversion of a page to a previous
 * version. 
 *
 * @name	    revert.lib.php 
 *
 * @package	    Lib	
 * @version		$Id$
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @since		Wikka 1.1.6.4
 * @filesource
 *
 * @author		{@link http://wikkawiki.org/BrianKoontz Brian Koontz}
 *
 * Based upon the Delete handler written by DarTar, NilsLindenberg,
 * and MinusF
 *
 * @uses Wakka::Query()
 * @uses Wakka::LoadAll()
 * @uses Wakka::IsAdmin()
 * @uses Wakka::LoadPageById()
 *
 */

//i18n
if(!defined('DEFAULT_COMMENT')) define ('DEFAULT_COMMENT', 'Reverting last edit by %s [%d] to previous version [%d]');
if(!defined('MESSAGE_SUCCESS')) define ('MESSAGE_SUCCESS', 'Reverted to previous version');
if(!defined('MESSAGE_FAILED')) define ('MESSAGE_FAILED', 'Reversion to previous version FAILED!');

/**
 * RevertPageToPreviousByTag
 *
 * Reverts a page to the version immediately preceding the "latest"
 * version. New page is created with previous version's metadata.
 *
 * @param object $wakka Wakka class instantiation
 * @param string $tag Page tag
 * @param string $comment Page comment (defaults to DEFAULT_COMMENT)
 * @return string MESSAGE_SUCCESS or MESSAGE_FAILURE
 * 
 */
function RevertPageToPreviousByTag($wakka, $tag, $comment='')
{
	$message = MESSAGE_FAILURE;
	$tag = mysql_real_escape_string($tag);
	$comment = mysql_real_escape_string($comment);
	if(TRUE===$wakka->IsAdmin())
	{
		// Select current version of this page and version immediately preceding
		$res = $wakka->LoadAll("SELECT * FROM ".$wakka->config['table_prefix']."pages WHERE tag='".$tag."' ORDER BY time DESC LIMIT 2");
		if($res)
		{
			// $res[0] is current page, $res[1] is page we're reverting to

			// Set default comment
			if(TRUE===empty($comment))
			{
				$comment = sprintf(DEFAULT_COMMENT, $res[0]['user'], $res[0]['id'], $res[1]['id']);
			}

			$time = strftime("%F %H:%M:%S");
			$body = $res[1]['body'];
			$owner = $res[1]['owner'];
			$user = $res[1]['user'];
			$latest = 'Y';
			$handler = $res[1]['handler'];
			$wakka->Query("INSERT INTO ".$wakka->config['table_prefix']."pages (tag, time, body, owner, user, latest, note, handler) VALUES ('$tag', '$time', '$body', '$owner', '$user', '$latest', '$comment', '$handler')");
			// Reset 'latest' flag on older version to 'N'
			$wakka->Query("UPDATE ".$wakka->config['table_prefix']."pages SET latest='N' where id=".$res[0]['id']);
			$message = MESSAGE_SUCCESS;
		}
	}
	return $message;
}

/**
 * RevertPageToPreviousById
 *
 * Reverts a page to the version immediately preceding the "latest"
 * version. New page is created with previous version's metadata.
 *
 * @param object $wakka Wakka class instantiation
 * @param string $id Page id (converted to page tag) 
 * @param string $comment Page comment (defaults to DEFAULT_COMMENT)
 * @return string MESSAGE_SUCCESS or MESSAGE_FAILURE
 * 
 */
function RevertPageToPreviousById($wakka, $id, $comment='')
{
	$message = MESSAGE_FAILURE;
	$id = mysql_real_escape_string($id);
	if(TRUE===$wakka->IsAdmin())
	{
		$res = $wakka->LoadPageById($id);
		if(TRUE===isset($res))
		{
			$tag = $res['tag'];
			if(TRUE===isset($tag))
			{
				return RevertPageToPreviousByTag($wakka, $tag, $comment);
			}
		}
	}
	return $message;
}

?>
