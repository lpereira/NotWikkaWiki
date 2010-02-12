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
 * @package		Handlers
 * @subpackage	Page
 * @version 	$Id:diff.php 407 2007-03-13 05:59:51Z DarTar $
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
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
 * @todo	- This is a really cheap way to do it. I think it may be more intelligent to write the two pages
 *			to temporary files and run /usr/bin/diff over them. Then again, maybe not.
 * 			JW: that may be nice but won't work on a Windows system ;)
 */

echo '<div id="page">'."\n"; //TODO: move to templating class //TODO move _after_ redirect

$output = '';
$info = '';

// If javascript is disabled, user may get here after pressing button Next... on the /revisions handler.
if ((isset($_GET['more_revisions'])) && (isset($_GET['a'])) && (isset($_GET['start'])))
{
	$this->Redirect($this->Href('revisions', '', 'a='.$_GET['a'].'&start='.$_GET['start']));
}

if ($this->HasAccess('read'))
{
	// looking for the diff-classes
	// @@@ TODO needed only if we're NOT doing a 'fastdiff'
	// instead of copping out we could use fastdiff as fallback if the library is missing:
	// first determine diff method based on params AND presense of library; then do it
	#if (file_exists('libs'.DIRECTORY_SEPARATOR.'diff.lib.php')) require_once('libs'.DIRECTORY_SEPARATOR.'diff.lib.php');	#89
	#else die(ERROR_DIFF_LIBRARY_MISSING);	// NO wrapper div will be produced here!
	$diff_library_path = WIKKA_LIBRARY_PATH.DIRECTORY_SEPARATOR.'diff.lib.php';
	if (file_exists($diff_library_path))
	{
		/**
		 * Diff library.
		 */
		require_once $diff_library_path;
	}
	else
	{
		die(ERROR_DIFF_LIBRARY_MISSING);	// @@@ ERROR: end div won't be produced here
	}

	// load pages
	$pageA = (isset($_GET['a'])) ? $this->LoadPageById($_GET['a']) : '';	# #312
	$pageB = (isset($_GET['b'])) ? $this->LoadPageById($_GET['b']) : '';	# #312
	if ('' == $pageA || '' == $pageB)
	{
		echo '<em class="error">'.ERROR_BAD_PARAMETERS.'</em><br />';
		echo '</div>'."\n";
		return;
	}

	$pageA_edited_by = $this->FormatUser($pageA['user']);
	if ($pageA['note']) $noteA='['.$this->htmlspecialchars_ent($pageA['note']).']'; else $noteA ='';

	$pageB_edited_by = $this->FormatUser($pageB['user']);
	if ($pageB['note']) $noteB='['.$this->htmlspecialchars_ent($pageB['note']).']'; else $noteB ='';
	
	// If asked, call original diff
	if (isset($_GET['fastdiff']) && $_GET['fastdiff'])	# #312
	{
		// prepare bodies
		$bodyA = explode("\n", $this->htmlspecialchars_ent($pageA['body']));
		$bodyB = explode("\n", $this->htmlspecialchars_ent($pageB['body']));

		$added   = array_diff($bodyA, $bodyB);
		$deleted = array_diff($bodyB, $bodyA);

		//infobox
		$info .= '<div class="revisioninfo">'."\n";
		$info .= '<h3>'.sprintf(DIFF_COMPARISON_HEADER, '<a title="'.sprintf(DIFF_REVISION_LINK_TITLE, $pageA['tag']).'" href="'.$this->Href('revisions').'">'.WIKKA_REVISIONS.'</a>', '<a title="'.DIFF_PAGE_LINK_TITLE.'" href="'.$this->Href().'">'.$pageA['tag'].'</a>').'</h3>'."\n";
		$info .= '<ul style="margin: 10px 0;">'."\n";
		$info .= '	<li><a href="'.$this->Href('show', '', 'time='.urlencode($pageA['time'])).'">['.$pageA['id'].']</a> '.sprintf(WIKKA_REV_WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageA["time"])).'">'.$pageA['time'].'</a>', $pageA_edited_by).' <span class="pagenote smaller">'.$noteA.'</span></li>'."\n";
		$info .= '	<li><a href="'.$this->Href('show', '', 'time='.urlencode($pageB['time'])).'">['.$pageB['id'].']</a> '.sprintf(WIKKA_REV_WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageB["time"])).'">'.$pageB['time'].'</a>', $pageB_edited_by).' <span class="pagenote smaller">'.$noteB.'</span></li>'."\n";
		$info .= '</ul>'."\n";
		$info .= $this->FormOpen('diff', '', 'GET');
		$info .= '<input type="hidden" name="fastdiff" value="0" />'."\n";
		$info .= '<input type="hidden" name="a" value="'.$_GET['a'].'" />'."\n";
		$info .= '<input type="hidden" name="b" value="'.$_GET['b'].'" />'."\n";
		$info .= '<input type="submit" value="'.DIFF_FULL_BUTTON.'" />';
		$info .= $this->FormClose();		
		$info .= '</div>'."\n";
		
		if ($added)
		{
			// remove blank lines
			$output .= "\n".'<h5 class="clear">'.WIKKA_DIFF_ADDITIONS_HEADER.'</h5>'."\n";
			$output .= '<div class="wikisource"><ins>'.nl2br(implode("\n", $added)).'</ins></div>'."\n";
		}

		if ($deleted)
		{
			$output .= "\n".'<h5 class="clear">'.WIKKA_DIFF_DELETIONS_HEADER.'</h5>'."\n";
			$output .= '<div class="wikisource"><del>'.nl2br(implode("\n", $deleted)).'</del></div>'."\n";
		}

		if (!$added && !$deleted)
		{
			$output .= "<br />\n".WIKKA_DIFF_NO_DIFFERENCES;
		}
	}
	else
	{
		// extract text from bodies
		$textA = $this->htmlspecialchars_ent($pageB['body']);
		$textB = $this->htmlspecialchars_ent($pageA['body']);

		$sideA = new Side($textA);
		$sideB = new Side($textB);

		$bodyA = '';
		$sideA->split_file_into_words($bodyA);

		$bodyB = '';
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

		$info .= '<div class="revisioninfo">'."\n";
		$info .= '<h3>'.sprintf(DIFF_COMPARISON_HEADER, '<a title="'.sprintf(DIFF_REVISION_LINK_TITLE, $pageA['tag']).'" href="'.$this->Href('revisions').'">'.WIKKA_REVISIONS.'</a>', '<a title="'.DIFF_PAGE_LINK_TITLE.'" href="'.$this->Href().'">'.$pageA['tag'].'</a>').'</h3>'."\n";
		$info .= '<ul style="margin: 10px 0">'."\n";
		$info .= '	<li><a href="'.$this->Href('show', '', 'time='.urlencode($pageA['time'])).'">['.$pageA['id'].']</a> '.sprintf(WIKKA_REV_WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageA["time"])).'">'.$pageA['time'].'</a>', $pageA_edited_by).' <span class="pagenote smaller">'.$noteA.'</span></li>'."\n";
		$info .= '	<li><a href="'.$this->Href('show', '', 'time='.urlencode($pageB['time'])).'">['.$pageB['id'].']</a> '.sprintf(WIKKA_REV_WHEN_BY_WHO, '<a class="datetime" href="'.$this->Href('show','','time='.urlencode($pageB["time"])).'">'.$pageB['time'].'</a>', $pageB_edited_by).' <span class="pagenote smaller">'.$noteB.'</span></li>'."\n";
		$info .= '</ul>'."\n";
		$info .= $this->FormOpen('diff', '', 'GET');
		$info .= '<input type="hidden" name="fastdiff" value="1" />'."\n";
		$info .= '<input type="hidden" name="a" value="'.$_GET['a'].'" />'."\n";
		$info .= '<input type="hidden" name="b" value="'.$_GET['b'].'" />'."\n";
		$info .= '<input type="submit" value="'.DIFF_SIMPLE_BUTTON.'" />';
		$info .= $this->FormClose();		
		$info .= '</div>'."\n";
		$info .= '<strong>'.HIGHLIGHTING_LEGEND.'</strong> <ins><tt>'.DIFF_SAMPLE_ADDITION.'</tt></ins> <del><tt>'.DIFF_SAMPLE_DELETION.'</tt></del></p>'."\n"; #i18n

		while (1)
		{
			$sideO->skip_line();
			if ($sideO->isend())
			{
				break;
			}
			if ($sideO->decode_directive_line())
			{
				$argument=$sideO->getargument();
				$letter=$sideO->getdirective();
				switch ($letter)
				{
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
				if (($letter=='d') || ($letter=='c'))
				{
					$sideA->copy_whitespace($output);
					$output .='<del>';
					$sideA->copy_word($output);
					$sideA->copy_until_ordinal($argument[1],$output);
					$output .='</del>';
				}

				// inserted word
				if ($letter == 'a' || $letter == 'c')
				{
					$sideB->copy_whitespace($output);
					$output .='<ins>';
					$sideB->copy_word($output);
					$sideB->copy_until_ordinal($argument[3],$output);
					$output .='</ins>';
				}
			}
		}
		$sideB->copy_until_ordinal($count_total_right,$output);
		$sideB->copy_whitespace($output);

		$output = '<div class="wikisource">'.nl2br($output).'</div>';
	}

	// show output
	echo $info.$output;
}
else
{
	echo '<em class="error">'.WIKKA_ERROR_ACL_READ.'</em>'."\n";
}
echo '<div style="clear: both"></div>'."\n";
echo '</div>'."\n";
?>
