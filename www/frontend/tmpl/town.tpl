<div>
<p><h3><?=$town?></h3></p>
<p><b>Apixu</b></p>
<table>
	<tr><td>last_update:</td><td><?=$WSA->getLastUpdate()?></td></tr>
	<tr><td>temperature:</td><td><?=$WSA->getTemperature()?></td></tr>
	<tr><td>sky:</td><td><?=$WSA->getSky()?></td></tr>
	<tr><td>wind_speed:</td><td><?=$WSA->getWindSpeed()?></td></tr>
	<tr><td>wind_degree:</td><td><?=$WSA->getWindDegree()?></td></tr>
	<tr><td>pressure_hpa:</td><td><?=$WSA->getPressureHpa()?></td></tr>
	<tr><td>humidity</td><td><?=$WSA->getHumidity()?></td></tr>
</table>
<p><b>OpenWeatherMap</b></p>
<table>
	<tr><td>last_update:</td><td><?=$WSOWM->getLastUpdate()?></td></tr>
	<tr><td>temperature:</td><td><?=$WSOWM->getTemperature()?></td></tr>
	<tr><td>sky:</td><td><?=$WSOWM->getSky()?></td></tr>
	<tr><td>wind_speed:</td><td><?=$WSOWM->getWindSpeed()?></td></tr>
	<tr><td>wind_degree:</td><td><?=$WSOWM->getWindDegree()?></td></tr>
	<tr><td>pressure_hpa:</td><td><?=$WSOWM->getPressureHpa()?></td></tr>
	<tr><td>humidity</td><td><?=$WSOWM->getHumidity()?></td></tr>
</table>
<p><b>Yahoo</b></p>
<table>
	<tr><td>last_update:</td><td><?=$WSY->getLastUpdate()?></td></tr>
	<tr><td>temperature:</td><td><?=$WSY->getTemperature()?></td></tr>
	<tr><td>sky:</td><td><?=$WSY->getSky()?></td></tr>
	<tr><td>wind_speed:</td><td><?=$WSY->getWindSpeed()?></td></tr>
	<tr><td>wind_degree:</td><td><?=$WSY->getWindDegree()?></td></tr>
	<tr><td>pressure_hpa:</td><td><?=$WSY->getPressureHpa()?></td></tr>
	<tr><td>humidity</td><td><?=$WSY->getHumidity()?></td></tr>
</table>
</div>
<div class="row rstyle">
	<div class="col-md-4 col-sm-6 box">
		<div class="cstyle">
			<p>Wheather: </p>
		</div>
	</div>
	<!-- <div class="col-sm-1"></div> -->
	<div class="col-md-4 col-sm-6 box">
		<div class="cstyle">
			Wheather
		</div>
	</div>
	<!-- <div class="col-sm-1"></div> -->
	<div class="col-md-4 col-sm-6 box">
		<div class="cstyle">
			Wheather
		</div>
	</div>
</div>
