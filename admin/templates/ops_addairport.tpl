<h3>Add Airport</h3>
<form id="form" action="action.php?admin=airports" method="post">
<dl>
<dt>Airport ICAO Code</dt>
<dd><input id="airporticao" name="icao" type="text"> <input type="button" id="lookupicao" value="Lookup" /></dd>

<dt>Airport Name</dt>
<dd><input id="airportname" name="name" type="text" /></dd>

<dt>Latitude</dt>
<dd><input id="airportlat" name="lat" type="text" /></dd>

<dt>Longitude</dt>
<dd><input id="airportlong" name="long" type="text" /></dd>
</dl>
</form>