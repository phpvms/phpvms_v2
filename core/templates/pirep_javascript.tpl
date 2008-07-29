<script type="text/javascript">
//<![CDATA[

var map = new GMap2(document.getElementById("map"));
map.addControl(new GLargeMapControl());
map.addControl(new GMapTypeControl());
map.addControl(new GScaleControl());
map.setCenter(new GLatLng(51.512161, -0.14110), 11, G_NORMAL_MAP);

// Creates a marker whose info window displays the given number
function createMarker(point, number)
{
	var marker = new GMarker(point);
	// Show this markers index in the info window when it is clicked
	var html = number;
	GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(html);});
	return marker;
};

<?php
echo $points;
?>

//]]>
</script>

</body>
</html>