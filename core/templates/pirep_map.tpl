<div style="clear:both;" align="center">
	<h3>Route Map</h3>
	<div id="map" style="width: 800px; height: 600px"></div> 
</div>
<script type="text/javascript">
//<![CDATA[

var map = new GMap2(document.getElementById("map"));
map.addControl(new GLargeMapControl());
map.addControl(new GMapTypeControl());
map.addControl(new GScaleControl());
map.setCenter(new GLatLng(<?=$report->deplat?>, <?=$report->deplong?>), 4, G_NORMAL_MAP);

// Creates a marker whose info window displays the given number
function createMarker(point, number)
{
	var marker = new GMarker(point);
	// Show this markers index in the info window when it is clicked
	var html = number;
	GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
	return marker;
};

var polyOptions = {geodesic:true};
var polyline = new GPolyline([
  new GLatLng(<?=$report->deplat?>, <?=$report->deplong?>),
  new GLatLng(<?=$report->arrlat?>, <?=$report->arrlong?>)
  ], "#ff0000", 5, 1, polyOptions);
map.addOverlay(polyline);
  
//]]>
</script>