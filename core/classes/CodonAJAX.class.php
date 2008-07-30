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

class CodonAJAX
{
	
	/**
	 * Show a link, when clicked on, it'll show a modal box
	 *  going to the $url_params (action.php/$url_params
	 *
	 * @example ModalLink('Click to show options', '/Welcome/showoptions');
	 *
	 * @param string $linkname Name of the link to click
	 * @param string $get_params Parameters as ?key=value, or /Module/Page or whatever your rewrite is
	 * @param string $class Optional CSS class for the link
	 * @return none
	 */
	public static function ModalLink($linkname, $url_params, $class='')
	{
		if($linkname == '' || $get_params == '') return false;
		if(substr($url_params, 0, 1) != '/') $url_params = '/'.$url_params;
		
		echo '<a href="'.SITE_URL.'/action.php'.$get_params.'" '
				.'class="codonmodal '.$class.'">'.$linkname.'</a>';
	}
	
	/**
	 * Show a button that will display a modal box
	 *
	 * @example ModalButton('Click me', 'Welcome/options', 'button');
	 *
	 * @param string $linkname  Name of the link to click
	 * @param string $get_params Parameters as ?key=value, or /Module/Page or whatever your rewrite is
	 * @param string $class Optional CSS class for the button
	 * @return none
	 */
	public static function ModalButton($linkname, $get_params, $class='')
	{
		if($linkname == '' || $get_params == '') return false;
		if(substr($url_params, 0, 1) != '/') $url_params = '/'.$url_params;
		
		echo '<button href="'.SITE_URL.'/action.php'.$get_params.'" '
				.'class="codonmodal '.$class.'">'.$linkname.'</button>';
	}
	
	/**
	 * Submit something via POST to a link
	 *
	 * @param string $linkname Name of the link to click
	 * @param string $url_params Parameters as ?key=value, or /Module/Page or whatever your rewrite is
	 * @param string $postparams String or array of POST parameters
	 * @param string $divupdate Optional DIV to put response in
	 * @param string $jsfunc Optional Javascript function for callback with response as parameter
	 * @param string $class Optional CSS class
	 */
	public static function AJAXSubmitLink($linkname, $url_params,
				$postparams='', $divupdate='', $callback='', $class='')
	{
		if($linkname == '' || $url_params == '') return;
		
		if(is_array($postparams))
			$postparams = http_build_query($postparams);
			
		if(substr($url_params, 0, 1) != '/') $url_params = '/'.$url_params;

		echo "<a href=\"".SITE_URL."/action.php$url_params\" class=\"codonlinkajax $class\""
				."callback=\"$callback\" post=\"$postparams\" divupdate=\"$divupdate\">"
				."$linkname</a>";
	}
	
	/**
	 * Submit something via POST using a button
	 *
	 * @param unknown_type $linkname
	 * @param unknown_type $url_params
	 * @param unknown_type $postparams
	 * @param unknown_type $divupdate
	 * @param unknown_type $callback
	 * @param unknown_type $class
	 */
	public static function AJAXSubmitButton($linkname, $url_params,
				$postparams='', $divupdate='', $callback='', $class='')
	{
		if($linkname == '' || $url_params == '') return;
		
		if(is_array($postparams))
			$postparams = http_build_query($postparams);
			
		if(substr($url_params, 0, 1) != '/') $url_params = '/'.$url_params;

		echo "<button href=\"".SITE_URL."/action.php$url_params\" class=\"codonlinkajax $class\""
				."callback=\"$callback\" post=\"$postparams\" divupdate=\"$divupdate\">"
				."$linkname</button>";
	}
	
	public static function LoadContent($url_params, $div_update)
	{
		if(substr($url_params, 0, 1) != '/') $url_params = '/'.$url_params;
		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$.get("action.php<?=$url_params ?>", function(data){$("#<?=$div_update?>").html(data)});
			});
		</script>
		<?
	}
}
?>