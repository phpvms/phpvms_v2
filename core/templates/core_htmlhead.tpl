<?php
if(GOOGLE_KEY!='') {
echo '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.GOOGLE_KEY.'" type="text/javascript"></script>';
}
?>
<script type="text/javascript">
var urlbase = "<?php echo SITE_URL?>";
</script>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Config::Get('PAGE_ENCODING');?>">

<link rel="stylesheet" media="all" type="text/css" href="<?php echo SITE_URL?>/lib/css/phpvms.css" />

<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/jquery.sparklines.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/jquery.listen-min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/ui.tabs.min.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/ui.tabs.ext.pack.js"></script>
<script type="text/javascript" src="<?php echo SITE_URL?>/lib/js/phpvms.js"></script>

<?php echo $MODULE_HEAD_INC;?>