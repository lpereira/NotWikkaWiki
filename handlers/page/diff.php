<?php
/**
 * Compare two versions of a page and output the differences.
 * 
 * Parameters to this handler are passed through $_GET. <ul>
 * <li>$_GET['a'] is the id of the base revision of the page</li>
 * <li>$_GET['b'] is the id of the revision to compare</li>
 * <li>$_GET['fastdiff'], if provided, enables the normal diff, and if absent, the simple diff is used.</li>
 * <li><em>If $_GET['more_revisions'] is also present, this means that JavaScript is disabled and the user
 * was on the {@link revisions.php revision handler}, so the page is redirected to that handler, with the
 * parameters $a and $start.</em></li></ul>
 *
 * @package     Handlers
 * @subpackage  Page
 * @version 	$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * 
 * @author	David Delon
 * 
 * @uses	Wakka::HasAccess()
 * @uses	Wakka::LoadPageById()
 * @uses	Wakka::Href()
 * @uses	Wakka::Format()
 * @uses	Wakka::Redirect()
 * @uses	Diff
 * 
 * @todo	move main <div> to templating class;
 * @todo 	This is a really cheap way to do it. I think it may be more intelligent to write the two pages to temporary files and run /usr/bin/diff over them. Then again, maybe not.
 * 			JW: that may be nice but won't work on a Windows system ;) 
 */

if (!defined('ERROR_DIV_LIBRARY_MISSING')) define ('ERROR_DIV_LIBRARY_MISSING', 'The necessary file "libs/diff.lib.php" could not be found. Please make sure the file exists and is placed in the right directory!');
if (!defined('ERROR_NO_PAGE_ACCESS')) define ('ERROR_NO_PAGE_ACCESS', 'You are not authorized to view this page.');
if (!defined('CONTENT_ADDITIONS_HEADER')) define ('CONTENT_ADDITIONS_HEADER', 'Additions:');
if (!defined('CONTENT_DELETIONS_HEADER')) define ('CONTENT_DELETIONS_HEADER', 'Deletions:');
if (!defined('CONTENT_NO_DIFFERENCES')) define ('CONTENT_NO_DIFFERENCES', 'No Differences');
if (!defined('WHEN_BY_WHO')) define('WHEN_BY_WHO', '%1$s by %2$s');
if (!defined('UNREGISTERED_USER')) define('UNREGISTERED_USER', 'unregistered user');

echo '<div class="page">'."\n"; //TODO: move to templating class //TODO move _after_ redirect

if ($this->HasAccess('read')) 
{
	// looking for the diff-classes
	// @@@ TODO needed only if we're NOT doing a 'fastdiff'
	// instead of copping out we could use fastdiff as fallback if the library is missing:
	// first determine diff method based on params AND presense of library; then do it
	if (file_exists('libs'.DIRECTORY_SEPARATOR.'diff.lib.php'))
	{
		require_once('libs'.DIRECTORY_SEPARATOR.'diff.lib.php');	#89
	}
	else
	{
		die(ERROR_DIFF_LIBRARY_MISSING);	// @@@ ERROR: end div won't be produced here
	}

	// load pages
	$pageA = $this->LoadPageById($_GET['a']);	#312
	$pageB = $this->LoadPageById($_GET['b']);	#312

	$pageA_edited_by = $pageA['user'];
	if (!$this->LoadUser($pageA_edited_by)) $pageA_edited_by .= ' ('.UNREGISTERED_USER.')';
	if ($pageA['note']) $noteA='['.$this->htmlspecialchars_ent($pageA['note']).']'; else $noteA ='';

	$pageB_edited_by = $pageB['user'];	
	if (!$this->LoadUser($pageB_edited_by)) $pageB_edited_by .= ' ('.UNREGISTERED_USER.')';
	if ($pageB['note']) $noteB='['.$this->htmlspecialchars_ent($pageB['note']).']'; else $noteB ='';
	
	
	// If asked, call original diff 
	if (isset($_GET['fastdiff']) && $_GET['fastdiff'])	#312
	{
		// prepare bodies
		$bodyA = explode("\n", $pageA['body']);
		$bodyB = explode("\n", $pageB['body']);

		$added   = array_diff($bodyA, $bodyB);
		$deleted = array_diff($bodyB, $bodyA);
		
		$output .= '<h3>Comparing <a title="Display the revision list for '.$pageA['tag'].'" href="'.$this->Href('revisions').'">revisions</a> for <a title="Return to the current revision of the page" href="'.$this->Href().'">'.$pageA['tag'].'</a></h3>'."\n";
		$output .= '<ul style="margin: 10px 0;">'."\n";
		$output .= '	<li><a href="'.$this->Href('', '', 'time'.urlencode($pageA['time'])).'">['.$pageA['id'].']</a> '.sprintf(WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageA["time"])).'">'.$pageA['time'].'</a>', $pageA_edited_by).' <span class="pagenote smaller">'.$noteA.'</span></li>'."\n";
		$output .= '	<li><a href="'.$this->Href('', '', 'time'.urlencode($pageB['time'])).'">['.$pageB['id'].']</a> '.sprintf(WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageB["time"])).'">'.$pageB['time'].'</a>', $pageB_edited_by).' <span class="pagenote smaller">'.$noteB.'</span></li>'."\n";
		$output .= '</ul>'."\n";
		$output .= '<hr />'."\n";
		if ($added)
		{
			// remove blank lines
			$output .= "\n".'<h4 class="clear">'.CONTENT_ADDITIONS_HEADER.'</h4>'."\n";
			$output .= '<span class="additions">'.$this->Format(implode("\n", $added)).'</span>'."\n";
		}
	
		if ($deleted)
		{
			$output .= "\n".'<h4 class="clear">'.CONTENT_DELETIONS_HEADER.'</h4>'."\n";
			$output .= '<span class="deletions">'.$this->Format(implode("\n", $deleted)).'</span>'."\n";
		}
	
		if (!$added && !$deleted)
		{
			$output .= '<br />'."\n".CONTENT_NO_DIFFERENCES;
		}
		echo $output;
	}
	else
	{
		// extract text from bodies
		$textA = $pageA['body'];
		$textB = $pageB['body'];
	
		$sideA = new Side($textA);
		$sideB = new Side($textB);
	
		$bodyA='';
		$sideA->split_file_into_words($bodyA);
	
		$bodyB='';
		$sideB->split_file_into_words($bodyB);
	
		// diff on these two file
		$diff = new Diff(split("\n",$bodyA),split("\n",$bodyB));
	
		// format output
		$fmt = new DiffFormatter();
	
		$sideO = new Side($fmt->format($diff));
	
		$resync_left = 0;
		$resync_right = 0;
	
		$count_total_right=$sideB->getposition() ;
	
		$sideA->init();
		$sideB->init();

		echo '<h3>Comparing <a title="Display the revision list for '.$pageA['tag'].'" href="'.$this->Href('revisions').'">revisions</a> for <a title="Return to the current revision of the page" href="'.$this->Href().'">'.$pageA['tag'].'</a></h3>'."\n";
		echo '<ul style="margin: 10px 0">'."\n";
		echo '	<li><a href="'.$this->Href('', '', 'time'.urlencode($pageA['time'])).'">['.$pageA['id'].']</a> '.sprintf(WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageA["time"])).'">'.$pageA['time'].'</a>', $pageA_edited_by).' <span class="pagenote smaller">'.$noteA.'</span></li>'."\n";
		echo '	<li><a href="'.$this->Href('', '', 'time'.urlencode($pageB['time'])).'">['.$pageB['id'].']</a> '.sprintf(WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageB["time"])).'">'.$pageB['time'].'</a>', $pageB_edited_by).' <span class="pagenote smaller">'.$noteB.'</span></li>'."\n";
		echo '</ul>'."\n";
		echo '<p>Highlighting Guide: <span class="additions">addition</span> <span class="deletions">deletion</span></p>'."\n"; #i18n
		echo '<hr />'."\n";
		$output='';

		  while (1) {
		       
		      $sideO->skip_line();
		      if ($sideO->isend()) {
			  break;
		      }
	
		      if ($sideO->decode_directive_line()) {
			$argument=$sideO->getargument();
			$letter=$sideO->getdirective();
		      switch ($letter) {
			    case 'a':
			      $resync_left = $argument[0];
			      $resync_right = $argument[2] - 1;
			      break;
	
			    case 'd':
			      $resync_left = $argument[0] - 1;
			      $resync_right = $argument[2];
			      break;
	
			    case 'c':
			      $resync_left = $argument[0] - 1;
			      $resync_right = $argument[2] - 1;
			      break;
	
			    }
	
			    $sideA->skip_until_ordinal($resync_left);
			    $sideB->copy_until_ordinal($resync_right,$output);
	  
	// deleted word
	
			if (($letter=='d') || ($letter=='c')) {
				$sideA->copy_whitespace($output);
				$output .="&yen;&yen;";
				$sideA->copy_word($output);
				$sideA->copy_until_ordinal($argument[1],$output);
				$output .=" &yen;&yen;";
			}
	
	// inserted word
			    if ($letter == 'a' || $letter == 'c') {
				$sideB->copy_whitespace($output);
				$output .="&pound;&pound;";
				$sideB->copy_word($output);
				$sideB->copy_until_ordinal($argument[3],$output);
				$output .=" &pound;&pound;";
			    }
	
		  }
	
		}
	
		  $sideB->copy_until_ordinal($count_total_right,$output);
		  $sideB->copy_whitespace($output);
		  $out=$this->Format($output);
		  echo $out;
	
	}

}
else
{
	echo '<em class="error">'.ERROR_NO_PAGE_ACCESS.'</em>'."\n";
}
?>
</div>