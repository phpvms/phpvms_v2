		</td>
	</tr>
</table>
<table width="970px" align="center" class="footer">
<tr>
	<td align="right">
	<div id="copyright">
		Copyright &copy; 2007 - <?php echo date('Y'); ?> 
		<a href="http://www.phpvms.net/" target="_new">phpVMS</a>, 
		<a href="http://www.nsslive.net" target="_new">nsslive.net</a>
		<br />
		<a href="http://www.phpvms.net/docs/license">License & About</a> | 
		Version <?php 
		
		if(defined('INSTALLER_VERSION'))
			echo INSTALLER_VERSION;
		else
			echo UPDATE_VERSION;
		?>
	</div>
	</td>
	</tr>
</table>
</body>
</html>