<?php
/**
 * Display a form with file attachments to the current page.
 * 
 * This actions displays a form allowing users to download files uploaded to wiki pages. By default only 
 * wiki admins can upload and delete files. If the intranet mode option is enabled, any user with write access
 * to the current page can upload or remove file attachments. If the optional download parameter is set, a simple 
 * download link is displayed for the specified file.
 *
 * Usage: {{files [download="filename"] [text="download link text"]}}
 *
 * @package		Actions
 * @version		$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * 
 * @author Victor Manuel Varela (original code)
 * @author {@link http://wikkawiki.org/CryDust CryDust} (code overhaul, stylesheet)
 * @author {@link http://wikkawiki.org/DarTar Dario Taraborelli} (code cleanup, defaults, i18n, added intranet mode)
 * @author {@link http://wikkawiki.org/NilsLindenberg Nils Lindenberg} (i18n)
 * 
 * @input 	string 	$download  	optional: prints a link to the file specified in the string
 * 			string 	$text		optional: a text for the link provided with the download parameter
 * @output a form for file uploading/downloading
 *
 * @uses	Wakka::Href()
 * @uses	Wakka::HasAccess()
 * @uses	Wakka::IsAdmin()
 * @uses	Wakka::MiniHref()
 *
 * @todo security: check file type, not only extension
 * @todo use buttons instead of links for file deletion; #72
 * @todo replace $_REQUEST in files handler with $_POST and $_GET; #72
 * @todo replace intranet mode with fine-grained file ownership/ACL;
 * @todo integrate with edit handler for easy insertion of file links;
 * @todo maybe move some internal utilities to Wakka class?
 * @todo make datetime format configurable;
 * @todo add support for file versioning;
 * @todo add (AJAX-powered?) confirmation check on file deletion;
 * @todo integrate file table in page template, � la Wacko;
 */

//---- Global action settings ----
/**
 * Toggle intranet mode.
 *
 * Setting this mode to 1 allows anyone with write-access to the page to upload files.
 * WARNING: enabling this option on a public server will allow any user with write access to upload files to your wiki.
 * We strongly recommend enabling intranet mode in an intranet environment only to avoid major security issues.
 */
if(!defined('INTRANET_MODE')) define('INTRANET_MODE', 0);

/** Size limit for file uploads (in bites) */
if(!defined('MAX_UPLOAD_SIZE')) define('MAX_UPLOAD_SIZE', 2097152);
/** Pipe-separated list of allowed file extensions */
if(!defined('ALLOWED_FILE_EXTENSIONS')) define('ALLOWED_FILE_EXTENSIONS', 'gif|jpeg|jpg|jpe|png|doc|xls|csv|ppt|ppz|pps|pot|pdf|asc|txt|zip|gtar|gz|bz2|tar|rar|vpp|mpp|vsd|mm|htm|html');
/** Displayed date format */
if(!defined('UPLOAD_DATE_FORMAT')) define('UPLOAD_DATE_FORMAT', 'Y-m-d H:i'); //TODO use general config settings for date format 

// ---- Errore code constants ----
if (!defined('UPLOAD_ERR_OK')) define('UPLOAD_ERR_OK', 0);
if (!defined('UPLOAD_ERR_INI_SIZE')) define('UPLOAD_ERR_INI_SIZE', 1);
if (!defined('UPLOAD_ERR_FORM_SIZE')) define('UPLOAD_ERR_FORM_SIZE', 2);
if (!defined('UPLOAD_ERR_PARTIAL')) define('UPLOAD_ERR_PARTIAL', 3);
if (!defined('UPLOAD_ERR_NO_FILE')) define('UPLOAD_ERR_NO_FILE', 4);
if (!defined('UPLOAD_ERR_NO_TMP_DIR')) define('UPLOAD_ERR_NO_TMP_DIR', 6);

// ---- i18n ----
if (!defined('ERROR_UPLOAD_DIRECTORY_NOT_WRITABLE')) define ('ERROR_UPLOAD_DIRECTORY_NOT_WRITABLE', 'Please make sure that the server has write access to a folder named <tt>./%s</tt>.');
if (!defined('ERROR_UPLOAD_DIRECTORY_NOT_READABLE')) define ('ERROR_UPLOAD_DIRECTORY_NOT_READABLE', 'Please make sure that the server has read access to a folder named <tt>./%s</tt>.');
if (!defined('ERROR_INEXISTENT_FILE')) define ('ERROR_INEXISTENT_FILE', 'Sorry, a file named <tt>%s</tt> does not exist.'); // %s - name of the file
if (!defined('ERROR_FILE_UPLOAD_INCOMPLETE')) define ('ERROR_FILE_UPLOAD_INCOMPLETE', 'File upload incomplete! Please try again.');
if (!defined('ERROR_UPLOADING_FILE')) define ('ERROR_UPLOADING_FILE', 'There was an error uploading your file');
if (!defined('FILE_UPLOAD_SUCCESSFUL')) define ('FILE_UPLOAD_SUCCESSFUL','File was successfully uploaded.');
if (!defined('ERROR_FILE_ALREADY_EXISTS')) define ('ERROR_FILE_ALREADY_EXISTS', 'Sorry, a file named <tt>%s</tt> already exists.'); // %s - name of the file
if (!defined('ERROR_EXTENSION_NOT_ALLOWED')) define('ERROR_EXTENSION_NOT_ALLOWED', 'Sorry, files with this extension are not allowed.');
if (!defined('ERROR_FILE_TOO_BIG')) define ('ERROR_FILE_TOO_BIG','Attempted file upload was too big. Maximum allowed size is %s.'); // %s - allowed filesize 
if (!defined('ERROR_NO_FILE_SELECTED')) define ('ERROR_NO_FILE_SELECTED','No file selected.'); 
if (!defined('ERROR_FILE_UPLOAD_IMPOSSIBLE')) define('ERROR_FILE_UPLOAD_IMPOSSIBLE', 'File upload impossible due to misconfigured server.');
if (!defined('FILE_TABLE_CAPTION')) define ('FILE_TABLE_CAPTION', 'Attachments');
if (!defined('FILE_TABLE_HEADER_NAME')) define ('FILE_TABLE_HEADER_NAME', 'File');
if (!defined('FILE_TABLE_HEADER_SIZE')) define ('FILE_TABLE_HEADER_SIZE', 'Size');
if (!defined('FILE_TABLE_HEADER_DATE')) define ('FILE_TABLE_HEADER_DATE', 'Last modified');
if (!defined('FILE_UPLOAD_FORM_LABEL')) define ('FILE_UPLOAD_FORM_LABEL', 'Add new attachment:');
if (!defined('DOWNLOAD_LINK_TITLE')) define('DOWNLOAD_LINK_TITLE', 'Download %s'); // %s - file name
if (!defined('DELETE_LINK_TITLE')) define('DELETE_LINK_TITLE', 'Remove %s'); // %s - file name
if (!defined('NO_ATTACHMENTS')) define('NO_ATTACHMENTS', 'This page contains no attachment.');

// ---- Initialize variables ----
$text = '';
$output = '';
$error_msg = '';
$is_writeable = FALSE;
$is_readable = FALSE;
$notification_msg = '';
$max_upload_size = MAX_UPLOAD_SIZE;
$allowed_extensions = ALLOWED_FILE_EXTENSIONS;

// ---- Utilities ----
/**
 * Check if the current user can upload files
 */
if (!function_exists('userCanUpload'))
{
	function userCanUpload()
	{
		global $wakka;
		switch(TRUE)
		{
			case ($wakka->IsAdmin()):
			case (INTRANET_MODE && $wakka->HasAccess('write')):
				return TRUE;
				break;

			default:
				return FALSE;
		}
	}
}

/**
 * Create upload folder if it does not exist
 */
if (!function_exists('mkdir_r'))
{
	function mkdir_r ($dir)
	{
		if (strlen($dir) == 0) {
			return 0;
		}
		if (is_dir($dir)) {
			return 1;
	 	}
	 	elseif (dirname($dir) == $dir)
	 	{
	 		return 1;
	 	}
	 	return (mkdir_r(dirname($dir)) and mkdir($dir,0755));
	 }
}

/**
 * Convert bytes to a human readable string
 *
 * @param int $bytes Number of bytes
 * @param int $precision Number of decimal places to include in return string
 * @param array $names Custom usage strings
 * @return string formatted string rounded to $precision
 */
if (!function_exists('bytesToHumanReadableUsage'))
{
	function bytesToHumanReadableUsage($bytes, $precision = 0, $names = '')
	{
		if (!is_numeric($bytes) || $bytes < 0)
		{
			$bytes = 0;
		}
		if (!is_numeric($precision) || $precision < 0)
		{
			$precision = 0;
		}
		if (!is_array($names))
		{
			$names = array('b','Kb','Mb','Gb','Tb','Pb','Eb');
		}
		$level = floor(log($bytes)/log(1024));
		$suffix = '';
		if ($level < count($names))
		{
			$suffix = $names[$level];
		}
		return round($bytes/pow(1024, $level), $precision) . $suffix;
	}
}

// ---- Run action ----

// 0. define upload path for the current page
if ($this->config['upload_path'] == '')
{
	$this->config['upload_path'] = 'files';
}
$upload_path = $this->config['upload_path'].DIRECTORY_SEPARATOR.$this->tag; #89

// 1. check if main upload path is writable
if (!is_writable($this->config['upload_path']))
{
	echo '<div class="alertbox">'.sprintf(ERROR_UPLOAD_DIRECTORY_NOT_WRITABLE, $this->config['upload_path']).'</div>';
}
else
{
	$is_writable = TRUE;
}

// 2. print a simple download link for the specified file, if it exists
if (isset($vars['download']))
{
	if (file_exists($upload_path.DIRECTORY_SEPARATOR.$vars['download']))
	{
		if (!isset($vars['text']))
		{
			$text = $vars['download'];
		} else
		{
			$text = $vars['text'];
		}
		//Although $output is passed to ReturnSafeHTML, it's better to sanitize $text here. At least it can avoid invalid XHTML.
		$text = $this->htmlspecialchars_ent($text);
		$output .=  '<a href="'
			. $this->Href('files.xml', $this->tag, 'action=download&amp;file='.rawurlencode($vars['download']))
			. '" title="'.sprintf(DOWNLOAD_LINK_TITLE, $text).'">'
			. $text
			. '</a>';
	}
	else
	{
		echo '<em class="error">'.sprintf(ERROR_INEXISTENT_FILE, $vars['download']).'</em>';
	}
}

// 3. user is trying to upload
elseif ($this->page && $this->HasAccess('read') && $this->method == 'show' && $is_writable)
{

	// create new folders if needed
	if ($is_writable && !is_dir($upload_path))
	{
		mkdir_r($upload_path);
	}

	// get upload results
	if ($is_writable && $_POST['action'] == 'upload' && userCanUpload())
	{
		switch ($_FILES['file']['error'])
		{
			case UPLOAD_ERR_OK:
				if ($_FILES['file']['size'] > MAX_UPLOAD_SIZE)
				{
					$error_msg = sprintf(ERROR_FILE_TOO_BIG, bytesToHumanReadableUsage($max_upload_size));
				 	unlink($_FILES['file']['tmp_name']);
			 	}
			 	elseif (preg_match('/.+\.('.$allowed_extensions.')$/i', $_FILES['file']['name']))
			 	{
					$strippedname = str_replace('\'', '', $_FILES['file']['name']);
					$strippedname = str_replace(" ","_",$strippedname); #46						
				 	$strippedname = stripslashes($strippedname);
					$destfile = $upload_path.DIRECTORY_SEPARATOR.$strippedname; #89

					if (!file_exists($destfile))
				 	{
						if (move_uploaded_file($_FILES['file']['tmp_name'], $destfile))
					 	{
							$notification_msg = FILE_UPLOAD_SUCCESSFUL;
					 	}
					 	else
					 	{
							$error_msg = ERROR_UPLOADING_FILE;
					 	}
				 	}
				 	else
				 	{
						$error_msg = sprintf(ERROR_FILE_ALREADY_EXISTS, $strippedname);
				 	}
				}
				else
				{
					$error_msg = ERROR_EXTENSION_NOT_ALLOWED;
				 	unlink($_FILES['file']['tmp_name']);
				}
				break;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				$error_msg = sprintf(ERROR_FILE_TOO_BIG, bytesToHumanReadableUsage($max_upload_size)); 
				break;
			case UPLOAD_ERR_PARTIAL:
				$error_msg = ERROR_FILE_UPLOAD_INCOMPLETE;
				break;
			case UPLOAD_ERR_NO_FILE:
				$error_msg = ERROR_NO_FILE_SELECTED;
				break;
		 	case UPLOAD_ERR_NO_TMP_DIR:
				$error_msg = ERROR_FILE_UPLOAD_IMPOSSIBLE;
				break;
	}
	if ($error_msg != '')
	{
		 $output .= '<em class="error">'.$error_msg.'</em>';
	} else if ($notification_msg !='')
	{
		 $output .= '<em class="success">'.$notification_msg.'</em>';
	}
}

// 4. display file list
if (is_readable($upload_path))
{ 
	$is_readable = TRUE;
	$dir = opendir($upload_path);
	$n = 0;
	// build file interface
	while ($file = readdir($dir))
	{
		if ($file{0} != '.')
		{
			$n++;
			$delete_link = '<!-- delete -->';
			if (userCanUpload())
			{
				$delete_link = '<a class="keys" href="'
				.$this->Href('files.xml',$this->tag,'action=delete&amp;file='.rawurlencode($file))
				.'" title="'.sprintf(DELETE_LINK_TITLE, $file).'">x</a>';
			}
			$download_link = '<a href="'
				.$this->Href('files.xml',$this->tag,'action=download&amp;file='.rawurlencode($file))
				.'" title="'.sprintf(DOWNLOAD_LINK_TITLE, $file).'">'.$file.'</a>';
			$size = bytesToHumanReadableUsage(filesize($upload_path.DIRECTORY_SEPARATOR.$file)); #89
			$date = date(UPLOAD_DATE_FORMAT, filemtime($upload_path.DIRECTORY_SEPARATOR.$file)); #89
			$output_files .= '<tr>'."\n";
			if (userCanUpload())
			{
				$output_files .=	'<td>'.$delete_link.'</td>'."\n";
			}
			$output_files .=	'<td>'.$download_link.'</td>'."\n"
				.'<td>'.$date.'</td>'."\n"
				.'<td align="right"><tt>'.$size.'</tt></td>'."\n"
				.'</tr>'."\n";
		}
	}
	closedir($dir);
	if ($n > 0)
	{
		$output .=	'<div class="files">'."\n";
		// display uploaded files
		$output .=	'<table class="files">'."\n"
			.'<caption>'.FILE_TABLE_CAPTION.'</caption>'."\n"
	 		.'<thead>'."\n"
			.'<tr>'."\n";
		if (userCanUpload())
		{
			$output .= '<th>&nbsp;</th>'."\n"; //For the delete link. Only needed when user has file upload privs.
		}
		$output .= '<th>'.FILE_TABLE_HEADER_NAME.'</th>'."\n"
			.'<th>'.FILE_TABLE_HEADER_DATE.'</th>'."\n"
			.'<th>'.FILE_TABLE_HEADER_SIZE.'</th>'."\n"
			.'</tr>'."\n"
			.'</thead>'."\n"
			.'<tbody>'."\n";
		$output .= $output_files;
		$output .=	'</tbody>'."\n"
			.'</table>'."\n";
	}
}
// cannot read the folder contents
else
{
	echo '<div class="alertbox">'.sprintf(ERROR_UPLOAD_DIRECTORY_NOT_READABLE, $upload_path).'</div>';

}

// print message if no files are available
if ($is_readable && $n < 1)
{
	$output .=	'<em>'.NO_ATTACHMENTS.'</em>'."\n"; //
}

// 5. display upload form
if ($is_writable && userCanUpload())
{
	// upload form
	$input_for_rewrite_mode = '<!-- rewrite mode disabled -->';
	if (!$this->config['rewrite_mode'])
	{
		$input_for_rewrite_mode = '<input type="hidden" name="wakka" value="'.$this->MiniHref().'" />';
	}
	$href = $this->Href();
	$output .=	'<form action="'.$href.'" method="post" enctype="multipart/form-data">'."\n"
				.$input_for_rewrite_mode."\n"
				.'<input type="hidden" name="action" value="upload" />'."\n"
				.'<fieldset><legend>'.FILE_UPLOAD_FORM_LABEL.'</legend>'."\n"
				.'<input type="file" name="file" /><br />'."\n"
				.'<input type="submit" value="Upload" />'."\n"
				.'</fieldset>'."\n"
				.'</form>'."\n";
	}
}
$output .= '</div>';

// 6. print output to screen
$output = $this->ReturnSafeHTML($output);
echo $output;
?>
