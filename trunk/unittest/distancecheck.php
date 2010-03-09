<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include '../core/codon.config.php';
?>
<html>
<head>
</head>
<body>
<form method="get" action="">
	Enter airport: <input type ="text" name="depicao" value="<?php echo $_GET['depicao'];?>" /> 
	<input type="submit" name="submit" value="View Distances" />
</form>
<p>To verify, use <a href="http://www.gpsvisualizer.com/calculators" target="_new">gps visualizer</a>. press control+f to find a certain airport.</p>
<?php
if(!isset($_GET['submit']))
	exit;
	
$depicao = OperationsData::getAirportInfo($_GET['depicao']);
$all_airports = OperationsData::getAllAirports();

echo "Plotting distance between {$depicao->name} ({$depicao->icao}) and...<br><br>";
echo '<ul>';
foreach($all_airports as $airport)
{
	Config::Set('UNITS', 'mi');
	$mi_dist = OperationsData::getAirportDistance($depicao, $airport);
	
	Config::Set('UNITS', 'km');
	$km_dist = OperationsData::getAirportDistance($depicao, $airport);
	
	Config::Set('UNITS', 'nm');
	$nm_dist = OperationsData::getAirportDistance($depicao, $airport);
	
	echo "<li>{$airport->name} ({$airport->icao})
			<ul>
				<li>{$depicao->lat}, {$depicao->lng}  >  {$airport->lat}, {$airport->lng}</li>
				<li>{$mi_dist} m</li>
				<li>{$km_dist} km</li>
				<li>{$nm_dist} nm</li>
			</ul>
		  </li>";
}
echo '</ul>';