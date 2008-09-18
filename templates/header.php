<?php
// Init UniversalEditButton (http://universaleditbutton.org/Universal_Edit_Button) #779
$ueb = ''; 
if ($this->GetMethod() != 'edit' && $this->HasAccess("write", $this->page["tag"])) 
{ 
	$ueb .= '<link rel="alternate" type="application/x-wiki" title="'.sprintf('Click to edit %s', $this->page["tag"]).'" href="'.$this->Href('edit', $this->page["tag"]).'"/>'."\n"; 
} 

	$message = $this->GetRedirectMessage();
	$user = $this->GetUser();
      $site_base = $this->GetConfigValue("base_url");
      if ( substr_count($site_base, 'wikka.php?wakka=') > 0 ) $site_base = substr($site_base,0,-16);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $this->GetWakkaName().": ".$this->PageTitle(); ?></title>
	<base href="<?php echo $site_base ?>" />
	<?php if ($this->GetMethod() != 'show' || $this->page["latest"] == 'N' || $this->page["tag"] == 'SandBox') echo "<meta name=\"robots\" content=\"noindex, nofollow, noarchive\" />\n"; ?>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="keywords" content="<?php echo $this->htmlspecialchars_ent($this->GetConfigValue("meta_keywords")) ?>" />
	<meta name="description" content="<?php echo $this->htmlspecialchars_ent($this->GetConfigValue("meta_description")) ?>" />
	<link rel="stylesheet" type="text/css" href="css/<?php echo $this->GetConfigValue("stylesheet") ?>" />
	<link rel="stylesheet" type="text/css" href="css/print.css" media="print" />
	<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<?php
if ($this->GetMethod() != 'edit') {
	$rsslink  = '	<link rel="alternate" type="application/rss+xml" title="'.$this->GetWakkaName().': revisions for '.$this->tag.' (RSS)" href="'.$this->Href('revisions.xml', $this->tag).'" />'."\n";
	$rsslink .= '	<link rel="alternate" type="application/rss+xml" title="'.$this->GetWakkaName().': recently edited pages (RSS)" href="'.$this->Href('recentchanges.xml', $this->tag).'" />'."\n";
	echo $rsslink;	
}
if (isset($this->additional_headers) && is_array($this->additional_headers) && count($this->additional_headers)) 
{ 
		foreach ($this->additional_headers as $additional_headers) 
		{ 
				echo $additional_headers; 
		} 
} 

// UniversalEditButton
echo "\t".$ueb;	
?>
</head>
<body <?php echo $message ? "onLoad=\"alert('".$message."');\" " : "" ?> >
<div class="header">
	<h2><a id="homepage_link" href="<?php echo $this->href('', $this->config['root_page'], ''); ?>"><?php echo $this->config['wakka_name'];?></a> : <a href="<?php echo $this->href('backlinks', '', ''); ?>" title="Display a list of pages linking to <?php echo $this->tag ?>"><?php echo $this->GetPageTag(); ?></a></h2>
	<?php echo $this->Link($this->config["root_page"]); ?> ::
	<?php 
		if ($this->GetUser()) {
			echo $this->config["logged_in_navigation_links"] ? $this->Format($this->config["logged_in_navigation_links"])." :: " : ""; 
			echo "You are ".$this->Format($this->GetUserName());
		} else { 
			echo $this->config["navigation_links"] ? $this->Format($this->config["navigation_links"]) : ""; 
		} 
	?> 	
</div>
