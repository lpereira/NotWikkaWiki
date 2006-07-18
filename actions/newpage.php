<?php
/**
 * Display a form to create a new page.
 * 
 * @package Actions
 * @version	$Id$
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @filesource
 * 
 * @author 	{@link http://www.comawiki.org/CoMa.php?CoMa=Costal_Martignier Costal Martignier} (initial version)
 * @author	{@link http://wikkawiki.org/JsnX JsnX} (modified 2005-1-17)
 * @author	{@link http://wikkawiki.org/JavaWoman JavaWoman} (modified 2005-1-17)
 * 
 * @uses	Wakka::redirect()
 * @uses	Wakka::FormOpen()
 * @uses	Wakka::FormClose()
 * @filesource
 */

$showform = TRUE;

if (isset($_POST['pagename']))
{
	$pagename = $_POST['pagename'];

	if (!(preg_match("/^[A-Z���]+[a-z����]+[A-Z0-9���][A-Za-z0-9�������]*$/s", $pagename))) 
	{
		echo '<em>The page name "'.$pagename.'" is invalid. Valid page names must start with a capital letter, contain only letters and numbers, and be in CamelCase format.</em>'; #i18n
	}
	else 
	{
		$url = $this->config['base_url'];
		$this->redirect($url.$pagename.'/edit');
		$showform = FALSE;
	}
}

if ($showform)
{ ?>
	<br />
	<?php echo $this->FormOpen(); ?>
		<input type="text" name="pagename" size="50" value="<?php echo $pagename; ?>" />  
		<input type="submit" value="Create and Edit" />
	<?php echo $this->FormClose(); 
} 
?>