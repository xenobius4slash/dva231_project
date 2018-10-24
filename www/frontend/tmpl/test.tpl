<?php if(!$isTown) { ?>
	<p><h3>please type a town name in the URL<br/>=> usage: index.php?page=test&town=Stockholm</h3></p>
<?php } else { ?>
	<div class="background"></div>
	<p><h1><?=$town?></h1></p>
	
	
	
	
	
	<div id="weatherBox" style="float: left; margin-right: 5%; width:1014px; height:320px; margin-bottom:5%">
		<table>
			<tr><td rowspan="2" height="120px" style="padding-top:80px;"><h1 id="printAvgTemp" style="font-size:100px;"></h1></td><td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" width="200"></td>	<td><p2 id="printAvgWind"></p2></td><tr>
			<tr><td></td>																			<td style="text-align: center;">				<img src="frontend/img/humidity.png" alt="Wind Speed" height="75"></td>		<td><p2 id="printAvgHum"></p2></td><tr>
		</table>
	</div>
	
	<script type="text/javascript" language="javascript">
	/*
	Calculates the average values.
	
	TODO: change all "numTwo" to get values from WSOWM and all "numThree" to get values from WSY.
	*/
	function avgTemp(){
		var numOne = <?=$WSA->getTemperature()?>;
		var numTwo = <?=$WSA->getTemperature()?>;
		var numThree = <?=$WSA->getTemperature()?>;
		var total = numOne + numTwo + numThree;
		var avg = total / 3;
		return avg.toFixed(0);
	}
	function avgWind(){
		var numOne = <?=$WSA->getWindSpeed()?>;
		var numTwo = <?=$WSA->getWindSpeed()?>;
		var numThree = <?=$WSA->getWindSpeed()?>;
		var total = numOne + numTwo + numThree;
		var avg = total / 3;
		return avg.toFixed(1);
	}
	function avgHum(){
		var numOne = <?=$WSA->getHumidity()?>;
		var numTwo = <?=$WSA->getHumidity()?>;
		var numThree = <?=$WSA->getHumidity()?>;
		var total = numOne + numTwo + numThree;
		var avg = total / 3;
		return avg.toFixed(0);
	}
	document.getElementById("printAvgTemp").innerHTML = avgTemp() + "°";
	document.getElementById("printAvgWind").innerHTML = avgWind() + " m/s";
	document.getElementById("printAvgHum").innerHTML = avgHum() + " %";
	</script>
	
	
	
	
	<div id="weatherBox" style="float: left; margin-right: 5%;">
	<p><b>Apixu</b></p>
	<table width="250px">
		<tr>				<td colspan="2">					<?=($WSA->getCurlError())?('cURL Error: '.$WSA->getCurlErrorCode().' => '.$WSA->getCurlErrorMessage()):('')?></td></tr>
		<tr>				<td colspan="2">					<h1><?=$WSA->getTemperature()?>°<h1>								</td>													</tr>
		<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" width="100"></td>	<td><p1><?=$WSA->getWindSpeed()?> m/s</p1></td>	</tr>
		<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/humidity.png" alt="Wind Speed" height="38">	</td>	<td><p1><?=$WSA->getHumidity()?> %</p1></td>	</tr>
		<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage1"/></td><td><p1 id="weatherText1"><?=$WSA->getSky()?></p1></td></tr>
		<tr>				<td>wind_degree:</td><td><?=$WSA->getWindDegree()?></td></tr>
		<tr>				<td>pressure_hpa:</td><td><?=$WSA->getPressureHpa()?></td></tr>
		<tr>				<td colspan="2" style="text-align: center;">	<?=$WSA->getLastUpdate()?></td></tr>
	</table>
	</div>

	<div id="weatherBox" style="float:left; margin-right: 5%;">
	<p><b>OpenWeatherMap</b></p>
	<table width="250px">
		<tr>				<td colspan="2">					<?=($WSOWM->getCurlError())?('cURL Error: '.$WSOWM->getCurlErrorCode().' => '.$WSOWM->getCurlErrorMessage()):('')?></td></tr>
		<tr>				<td colspan="2">					<h1><?=$WSOWM->getTemperature()?>°<h1>								</td>													</tr>
		<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" width="100"></td>	<td><p1><?=$WSOWM->getWindSpeed()?> m/s</p1></td>	</tr>
		<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/humidity.png" alt="Wind Speed" height="38">	</td>	<td><p1><?=$WSOWM->getHumidity()?> %</p1></td>	</tr>
		<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage2"/></td><td><p1 id="weatherText2">Light sleet</p1></td></tr>
		<tr>				<td>wind_degree:</td><td><?=$WSOWM->getWindDegree()?></td></tr>
		<tr>				<td>pressure_hpa:</td><td><?=$WSOWM->getPressureHpa()?></td></tr>
		<tr>				<td colspan="2" style="text-align: center;">	<?=$WSOWM->getLastUpdate()?></td></tr>
	</table>
	</div>

	<div id="weatherBox">
	<p><b>Yahoo</b></p>
	<table width="250px">
		<tr>				<td colspan="2">					<?=($WSY->getCurlError())?('cURL Error: '.$WSY->getCurlErrorCode().' => '.$WSY->getCurlErrorMessage()):('')?></td></tr>
		<tr>				<td colspan="2">					<h1><?=$WSY->getTemperature()?>°<h1>								</td>													</tr>
		<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" width="100"></td>	<td><p1><?=$WSY->getWindSpeed()?> m/s</p1></td>	</tr>
		<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/humidity.png" alt="Wind Speed" height="38">	</td>	<td><p1><?=$WSY->getHumidity()?> %</p1></td>	</tr>
		<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage3"/></td><td><p1 id="weatherText3">Thundery outbreaks possible</p1></td></tr>
		<tr>				<td>wind_degree:</td><td><?=$WSY->getWindDegree()?></td></tr>
		<tr>				<td>pressure_hpa:</td><td><?=$WSY->getPressureHpa()?></td></tr>
		<tr>				<td colspan="2" style="text-align: center;">	<?=$WSY->getLastUpdate()?></td></tr>
	</table>
	</div>
	
	<script type="text/javascript" language="javascript">
	/* 
	Get's the weather condition and maps it to a new value.
	The new value is used to fetch the corresponding image.
	
	TODO: 
	- Add and map the conditions from OWM and Yahoo.
	- Change the placeholder text in "weatherText2" and "weatherText3" to actual values (in the weatherBoxes, not in this script).
	*/
	var conditionMap = new Map([
	['Patchy freezing drizzle possible', 	'rain'],
	['Patchy rain possible', 				'rain'],
	['Light rain shower', 					'rain'],
	['Moderate or heavy rain shower', 		'rain'],
	['Torrential rain shower', 				'rain'],
	['Patchy light drizzle', 				'rain'],
	['Light drizzle', 						'rain'],
	['Freezing drizzle', 					'rain'],
	['Heavy freezing drizzle', 				'rain'],
	['Patchy light rain', 					'rain'],
	['Light rain', 							'rain'],
	['Moderate rain at times', 				'rain'],
	['Moderate rain', 						'rain'],
	['Heavy rain at times', 				'rain'],
	['Heavy rain', 							'rain'],
	['Light freezing rain', 				'rain'],
	['Moderate or heavy freezing rain', 	'rain'],
	['Light sleet showers', 				'rain'],
	['Moderate or heavy sleet showers', 	'rain'],
	['Light snow showers', 					'rain'],
	['Moderate or heavy snow showers', 		'rain'],
	['Light showers of ice pellets', 		'rain'],
	['Moderate or heavy showers of ice pellets', 'rain'],
	
	['Patchy snow possible', 				'snow'],
	['Patchy sleet possible', 				'snow'],
	['Blowing snow', 						'snow'],
	['Blizzard', 							'snow'],
	['Light sleet', 						'snow'],
	['Moderate or heavy sleet', 			'snow'],
	['Patchy light snow', 					'snow'],
	['Light snow', 							'snow'],
	['Patchy moderate snow', 				'snow'],
	['Moderate snow', 						'snow'],
	['Patchy heavy snow', 					'snow'],
	['Heavy snow', 							'snow'],
	['Ice pellets', 						'snow'],

	['Thundery outbreaks possible', 		'thunder'],
	['Patchy light rain with thunder', 		'thunder'],
	['Moderate or heavy rain with thunder', 'thunder'],
	['Patchy light snow with thunder', 		'thunder'],
	['Moderate or heavy snow with thunder', 'thunder'],

	['Partly cloudy', 	'cloud'],
	['Cloudy', 			'cloud'],
	['Overcast', 		'cloud'],
	['Mist', 			'cloud'],
	['Fog', 			'cloud'],
	['Freezing fog', 	'cloud'],
	
	['Sunny', 'sunny'],
	['Clear', 'moon'],
	]);
	
	function getImage1(){
		var imgName = conditionMap.get(document.getElementById("weatherText1").textContent);
		document.getElementById("weatherImage1").src = "frontend/img/" + imgName + ".png";

	}
	function getImage2(){
		var imgName = conditionMap.get(document.getElementById("weatherText2").textContent);
		document.getElementById("weatherImage2").src = "frontend/img/" + imgName + ".png";

	}
	function getImage3(){
		var imgName = conditionMap.get(document.getElementById("weatherText3").textContent);
		document.getElementById("weatherImage3").src = "frontend/img/" + imgName + ".png";

	}
	getImage1();
	getImage2();
	getImage3();
	
	</script>
	
<?php } ?>
