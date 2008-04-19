<?php



class GoogleMap
{

	public $polylines = array();
	public $points = array();
	
	public $mapcenter_lat = 42.55;
	public $mapcenter_long = -78.50;
	
	public $maptype = 'G_NORMAL_MAP';
	
	/**
	 * Set the enter point of the map
	 */
	function CenterMap($lat, $long)
	{
		$this->mapcenter_lat = $lat;
		$this->mapcenter_long = $long;		
	}
	
	function AddPoint($lat, $long, $descrip)
	{
		
		$bubble = 'var point = new GLatLng('.$lat. ','. $long . ');
var marker = createMarker(point, "'. addslashes($descrip) . '");
map.addOverlay(marker);
';

		array_push($this->points, $bubble);
	}
	
	/** 
	 * This forms one polyline, simple, with the coordinates of
	 * where it starts, and where it ends. Ultimately goes to
	 * AddPolyline, but an easier way of calling it
	 */
	function AddPolylineFromTo($deplat, $deplong, $arrlat, $arrlong)
	{		
		$this->AddPolyline(array(array($deplat, $deplong), array($arrlat, $arrlong)));	
	}
	
	/**
	 * Passed as array:
	 * array ([0] => array([0]=>lat, [1]=>long)
	 *		  [1] => array([0]=>lat, [1]=>long)
	 * 
	 * Have as many sets as you want, this will form one
	 * polyline
	 * 
	 * $set[0] = array(44.47, 117.50);
	 * $set[1] = array(46.48, 100.47);
	 * $map->AddPolyline($set);
	 */
	function AddPolyline ($points)
	{
		array_push($this->polylines, $points);
	}
	
	/**
	 * Show the map
	 *	If a div name is supplied, display it in that
	 *	If it's not, then just create one
	 */
	function ShowMap($width='800px', $height='600px', $divname='')
	{
		if($divname == '')
		{
			$divname = 'googlemap';
			echo '<div style="clear:both;" align="center">
					<div id="'.$divname.'" style="width: '.$width.'; height: '.$height.'"></div>
				</div>';
		}

echo '<script type="text/javascript">
//<![CDATA[

var map = new GMap2(document.getElementById("'.$divname.'"));
map.addControl(new GLargeMapControl());
map.addControl(new GMapTypeControl());
map.addControl(new GScaleControl());
map.setCenter(new GLatLng('.$this->mapcenter_lat.', '.$this->mapcenter_long.'), 4, '.$this->maptype.');

// Creates a marker whose info window displays the given number
function createMarker(point, number)
{
	var marker = new GMarker(point);
	// Show this markers index in the info window when it is clicked
	var html = number;
	GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
	return marker;
};';

		
		foreach($this->points as $point)
		{
			echo $point;	
		}


echo 'var polyOptions = {geodesic:true};
';
		$count=0;
		foreach($this->polylines as $polyline)
		{
			echo 'var polyline'.$count.' = new GPolyline([
';
	  
			foreach($polyline as $points)
			{
				echo '	new GLatLng('.$points[0].', '.$points[1].'),
';
				//print_r($points);
			}
			echo '], "#ff0000", 5, 1, polyOptions);
map.addOverlay(polyline'.$count.');
';
		$count++;			
		}
	echo '  
//]]>
</script>';
	}
}
?>