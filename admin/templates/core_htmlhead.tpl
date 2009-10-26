<script type="text/javascript">
var baseurl="<?php echo SITE_URL;?>";
var geourl="<?php echo Config::Get('GEONAME_API_SERVER'); ?>";
var airport_lookup = "<?php echo Config::Get('AIRPORT_LOOKUP_SERVER'); ?>";
var phpvms_api_server = "<?php echo Config::Get('PHPVMS_API_SERVER'); ?>";
</script>
<?php
$files = array(	'jquery.min.js',
				'jqModal.js',
				'jquery.form.js', 
				'jquery.bigiframe.js',
				'jquery.tablesorter.pack.js',
				'jquery.tablesorter.pager.js', 
				'jquery.metadata.js', 
				'jquery.impromptu.js',
				'jquery-ui.js',
				'jquery.dimensions.pack.js',
				'nicEdit.js');
				
# Build a condensed version of the above files
#	Suck 'em all into one file, reduce the number of HTTP requests
#	May also be cached
$condenser = new CodonCondenser();
$condenser->SetOptions(SITE_ROOT.'/lib/js', SITE_URL.'/lib/js', 'js', '');
$cache_url = $condenser->GetCondensedFile($files, 'jquery-admin.js');

/*
<script type="text/javascript" src="<?php echo SITE_URL ?>/lib/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL ?>/lib/js/jquery.dimensions.pack.js"></script>
*/
?>
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo $cache_url?>"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/admin/lib/phpvmsadmin.js"></script>

<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo SITE_URL?>/lib/rss/latestpireps.rss">

<?php echo $MODULE_HEAD_INC;?>