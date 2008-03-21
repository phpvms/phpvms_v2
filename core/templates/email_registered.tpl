Dear <?php echo $firstname .' '. $lastname; ?>,

Your account have been made at <?=SITE_NAME?>, but you must confirm it by clicking on this link:

<?php echo SITE_URL; ?>/index.php?page=confirm&confirmid=<?=$confid?>

Or if you have HTML enabled email:
<a href="<?php echo SITE_URL; ?>/index.php?page=confirm&confirmid=<?=$confid?>"><?php echo SITE_URL; ?>/index.php?page=confirm&confirmid=<?=$confid?></a>

Thanks!
<?=SITE_NAME?> Staff