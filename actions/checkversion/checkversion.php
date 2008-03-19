<?php
/**
 * Compare this instance version to the latest version and display
 * notice if out of date. 
 *
 * Restricted to admins. Expects to find
 * wikkawiki.org/downloads/latest_wikka_version.txt; if not found, exits
 * gracefully. Disable by setting config param 'enable_version_check' to
 * 0.
 *
 * Syntax: {{checkversion}}
 *
 * @package		Actions
 * @version		$Id:mychanges.php 369 2007-03-01 14:38:59Z DarTar $
 * @license		http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * @author		{@link http://wikkawiki.org/BrianKoontz Brian Koontz}
 * @author		{@link http://wikkawiki.org/DarTar Dario Taraborelli}
 *
 * @todo	use core method to generate notes and badges
 * @todo	move GUI strings to lang in 1.1.7
 */

if($this->IsAdmin() && TRUE == $this->config['enable_version_check'])
{
	//defaults
	define('DOWNLOAD_URL', 'http://docs.wikkawiki.org/WhatsNew');	
	$latest = '';
	//color scheme array (ported from {{since}})
	$c = array(
			'A' => array('#699', '#BFFFFF', '#303030', '#A0E0E0', '#90B0B0'),
			'B' => array('#996', '#FFFFBF', '#303030', '#E0E0A0', '#B0B090'),
			'C' => array('#969', '#FFBFFF', '#303030', '#E0A0E0', '#B090B0'),
			'D' => array('#966', '#FFBFBF', '#303030', '#E0A0A0', '#B09090'),
			'E' => array('#669', '#BFBFFF', '#303030', '#A0A0E0', '#9090B0'),
			'F' => array('#696', '#BFFFBF', '#303030', '#A0E0A0', '#90B090')
	);
	
	// Attempt to get latest_wikka_version.txt
	// Won't work on Windows PHP 4.3.0 or less
	$timeout = 10;
	if(FALSE === strpos(strtolower(PHP_OS), 'windows') ||
	(TRUE === version_compare(PHP_VERSION, '4.3.0', '>=')))
	{
		if(TRUE == ini_get('allow_url_fopen'))
		{
			$ip = gethostbyname('wikkawiki.org');
			$fp = @fsockopen($ip, 80, $errno, $errstr, $timeout);
			if(!$fp)
			{
				return;
			}
			else
			{
				fwrite($fp, "GET /downloads/latest_wikka_version.txt HTTP/1.0\r\n");
				fwrite($fp, "Host: wikkawiki.org\r\n");
				fwrite($fp, "Connection: Close\r\n\r\n");
				stream_set_timeout($fp, $timeout);
				$data = fread($fp, 4096);
				$latest = array_pop(explode("\r\n", $data));
				fclose($fp);
				if(TRUE === version_compare($this->config['wakka_version'], $latest, "<"))
				{
					$s = 'F'; //green badge
					echo '<div title="A new version of WikkaWiki is available. Please upgrade!" style="float: left; width: 300px; border: 1px solid '.$c[$s][0].'; background-color: '.$c[$s][1].'; color: '.$c[$s][2].'; margin: 10px 0">'."\n";
					echo '<div style="text-align: center; padding: 0 3px 0 3px; background-color: '.$c[$s][3].'; font-size: 85%; font-weight: bold">UPGRADE NOTE</div>'."\n";
					echo '<div style="padding: 0 3px 2px 3px; font-size: 85%; line-height: 150%; border-top: 1px solid '.$c[$s][4].';">'."\n";
					echo '<strong>WikkaWiki '.$latest.'</strong> is available for <a href="'.DOWNLOAD_URL.'">download</a>!'."\n";
					echo '</div>'."\n";
					echo '</div>'."\n";
					echo '<div class="clear"></div>'."\n";
				}
			}
		}
	}
}			
