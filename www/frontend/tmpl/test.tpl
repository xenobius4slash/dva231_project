<?php if(!$isTown) { ?>
	<p><h3>please type a town name in the URL<br/>=> usage: index.php?page=test&town=Stockholm</h3></p>
<?php } else { ?>
	<p><h1><?=$town?></h1></p>
	<div style="float: left; margin-right: 20%;">
	<p><b>Apixu</b></p>
	<table>
		<tr><td colspan="2"><?=($WSA->getCurlError())?('cURL Error: '.$WSA->getCurlErrorCode().' => '.$WSA->getCurlErrorMessage()):('')?></td></tr>
		<tr><td>last_update:</td><td><?=$WSA->getLastUpdate()?></td></tr>
		<tr><td>temperature:</td><td><?=$WSA->getTemperature()?></td></tr>
		<tr><td>sky:</td><td><?=$WSA->getSky()?></td></tr>
		<tr><td>wind_speed:</td><td><?=$WSA->getWindSpeed()?></td></tr>
		<tr><td>wind_degree:</td><td><?=$WSA->getWindDegree()?></td></tr>
		<tr><td>pressure_hpa:</td><td><?=$WSA->getPressureHpa()?></td></tr>
		<tr><td>humidity</td><td><?=$WSA->getHumidity()?></td></tr>
	</table>
	</div>

	<div style="float:left; margin-right: 20%;">
	<p><b>OpenWeatherMap</b></p>
	<table>
		<tr><td colspan="2"><?=($WSOWM->getCurlError())?('cURL Error: '.$WSOWM->getCurlErrorCode().' => '.$WSOWM->getCurlErrorMessage()):('')?></td></tr>
		<tr><td>last_update:</td><td><?=$WSOWM->getLastUpdate()?></td></tr>
		<tr><td>temperature:</td><td><?=$WSOWM->getTemperature()?></td></tr>
		<tr><td>sky:</td><td><?=$WSOWM->getSky()?></td></tr>
		<tr><td>wind_speed:</td><td><?=$WSOWM->getWindSpeed()?></td></tr>
		<tr><td>wind_degree:</td><td><?=$WSOWM->getWindDegree()?></td></tr>
		<tr><td>pressure_hpa:</td><td><?=$WSOWM->getPressureHpa()?></td></tr>
		<tr><td>humidity</td><td><?=$WSOWM->getHumidity()?></td></tr>
	</table>
	</div>

	<div>
	<p><b>Yahoo</b></p>
	<table>
		<tr><td colspan="2"><?=($WSY->getCurlError())?('cURL Error: '.$WSY->getCurlErrorCode().' => '.$WSY->getCurlErrorMessage()):('')?></td></tr>
		<tr><td>last_update:</td><td><?=$WSY->getLastUpdate()?></td></tr>
		<tr><td>temperature:</td><td><?=$WSY->getTemperature()?></td></tr>
		<tr><td>sky:</td><td><?=$WSY->getSky()?></td></tr>
		<tr><td>wind_speed:</td><td><?=$WSY->getWindSpeed()?></td></tr>
		<tr><td>wind_degree:</td><td><?=$WSY->getWindDegree()?></td></tr>
		<tr><td>pressure_hpa:</td><td><?=$WSY->getPressureHpa()?></td></tr>
		<tr><td>humidity</td><td><?=$WSY->getHumidity()?></td></tr>
	</table>
	</div>
<?php } ?>
