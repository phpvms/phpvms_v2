<?php
$condenser = new CodonCondenser();
$condenser->SetOptions(SITE_ROOT.'/lib/js', SITE_URL.'/lib/js', 'js', '');

$files = array(	'jquery.min.js', 
				'jquery-ui.js', 
				'jqModal.js',
				'jquery.form.js', 
				'jquery.bigiframe.js',
				'jquery.sparklines.js', 
				//'jquery.autocomplete.js',
				'jquery.tablesorter.pack.js',
				'jquery.tablesorter.pager.js', 
				'jquery.metadata.js', 
				'jquery.impromptu.js',
				'jquery.listen-min.js', 
				'nicEdit.js');

$cache_url = $condenser->GetCondensedFile($files, 'jquery_essentials.js');

?>
<script type="text/javascript" src="<?php echo $cache_url?>"></script>
<script type="text/javascript" src="<?php echo SITE_URL ?>/lib/js/jquery.dimensions.pack.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/admin/lib/phpvmsadmin.js"></script>

<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo SITE_URL?>/lib/rss/latestpireps.rss">

<?php echo $MODULE_HEAD_INC;?>