<?php
/**
 * Codon PHP Framework
 *	www.nsslive.net/codon
 * Software License Agreement (BSD License)
 *
 * Copyright (c) 2008 Nabeel Shahzad, nsslive.net
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2.  Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. The name of the author may not be used to endorse or promote products
 *    derived from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Nabeel Shahzad 
 * @copyright Copyright (c) 2008, Nabeel Shahzad
 * @link http://www.nsslive.net/codon
 * @license BSD License
 * @package codon_core
 */
 
class Debug
{
	function Init()
	{
		
		echo "
<script type=\"text/javascript\" src=\"".SITE_URL."/lib/js/jquery.growl.js\"></script>
		
<script type=\"text/javascript\">
$(document).ready(function() {
	$.growl.settings.displayTimeout = 0;
	$.growl.settings.noticeTemplate = ''
		+ '<div class=\"%priority%\">'
		+ '<div style=\"float: right; background-image: url(my.growlTheme/normalTop.png); position: relative; width: 259px; height: 16px; margin: 0pt; overflow:scroll;\"></div>'
		+ '<div style=\"float: left; background-image: url(my.growlTheme/normalBackground.png); position: relative; font-family: Arial; font-size: 12px; line-height: 14px; width: 259px; margin: 0pt;\">' 
		//+ '  <img style=\"margin: 14px; margin-top: 0px; float: left;\" src=\"%image%\" />'
		+ '  <h3 style=\"margin: 0pt; margin-left: 2px; padding: 0px; padding-bottom: 10px; font-size: 13px;\">%title% (%priority%)</h3>'
		+ '  <p style=\"margin: 0pt 4px; margin-left: 2px; font-size: 12px; color: #FFFFFF; overflow:scroll;\">%message%</p>'
		+ '</div>'
		+ '<div style=\"float: right; background-image: url(my.growlTheme/normalBottom.png); position: relative; width: 259px; height: 16px; margin-bottom: 10px;\"></div>'
		+ '</div>';

	$.growl.settings.noticeCss = {
		position: 'relative'
	};
});

function AddItem(title, msg)
{
	$.growl(title, msg);
}

</script>";		

		
	}
			
	function Show($input)
	{
		if(is_array($input) || is_object($input))
		{
			$input = print_r($input, true);
		}
		
		$input = addslashes($input);
		$array = array("\r\n", "\n\r", "\n", "\r");
		$input = str_replace($array, "<br>", $input);
		$input = '<pre>'.$input.'</pre>';
		echo "<script type=\"text/javascript\">
				AddItem('Debug Message', \"$input\");
			  </script>";
	}
}