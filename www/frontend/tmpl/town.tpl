<!-- first version -->
<!--
<div class="row rstyle">
	<div class="col-md-4 col-sm-6 box">
		<div class="cstyle">
			<p>Wheather: </p>
		</div>
	</div>
	<div class="col-md-4 col-sm-6 box">
		<div class="cstyle">
			Wheather
		</div>
	</div>
	<div class="col-md-4 col-sm-6 box">
		<div class="cstyle">
			Wheather
		</div>
	</div>
</div>
-->

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
	*/
	function avgTemp(){
		var numOne = <?=$WSA->getTemperature()?>;
		var numTwo = <?=$WSOWM->getTemperature()?>;
		var numThree = <?=$WSY->getTemperature()?>;
		var total = numOne + numTwo + numThree;
		var avg = total / 3;
		return avg.toFixed(0);
	}
	function avgWind(){
		var numOne = <?=$WSA->getWindSpeed()?>;
		var numTwo = <?=$WSOWM->getWindSpeed()?>;
		var numThree = <?=$WSY->getWindSpeed()?>;
		var total = numOne + numTwo + numThree;
		var avg = total / 3;
		return avg.toFixed(1);
	}
	function avgHum(){
		var numOne = <?=$WSA->getHumidity()?>;
		var numTwo = <?=$WSOWM->getHumidity()?>;
		var numThree = <?=$WSY->getHumidity()?>;
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
		<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage2"/></td><td><p1 id="weatherText2"><?=$WSOWM->getSky()?></p1></td></tr>
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
		<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage3"/></td><td><p1 id="weatherText3"><?=$WSY->getSky()?></p1></td></tr>
		<tr>				<td>wind_degree:</td><td><?=$WSY->getWindDegree()?></td></tr>
		<tr>				<td>pressure_hpa:</td><td><?=$WSY->getPressureHpa()?></td></tr>
		<tr>				<td colspan="2" style="text-align: center;">	<?=$WSY->getLastUpdate()?></td></tr>
	</table>
	</div>
	
	<script type="text/javascript" language="javascript">
	/* 
	Get's the weather condition and maps it to a new value.
	The new value is used to fetch the corresponding image.
	*/
	var conditionMap = new Map([
	['clear sky',	'sun'],
	['fair (day)',	'sun'],
	['hot', 		'sun'],
	['sunny',		'sun'],
	['clear', 					'moon'],
	['clear (night)',			'moon'],
	['fair (night)',			'moon'],
	['mostly cloudy (night)',	'moon'],
	['partly cloudy (night)',	'moon'],
	['blustery', 		'wind'],
	['hurricane', 		'wind'],
	['tornado', 		'wind'],
	['tropical storm', 	'wind'],
	['windy', 			'wind'],
	['broken clouds',			'cloud'],
	['cloudy', 					'cloud'],
	['dust', 					'cloud'],
	['few clouds', 				'cloud'],
	['fog', 					'cloud'],
	['foggy', 					'cloud'],
	['freezing fog',			'cloud'],
	['haze',					'cloud'],
	['mist', 					'cloud'],
	['mostly cloudy (day)',		'cloud'],
	['overcast',				'cloud'],
	['overcast clouds',			'cloud'],
	['partly cloudy',			'cloud'],
	['partly cloudy (day)',		'cloud'],
	['sand',					'cloud'],
	['sand, dust whirls',		'cloud'],
	['scattered clouds',		'cloud'],
	['smoke',					'cloud'],
	['smoky',					'cloud'],
	['squalls',					'cloud'],
	['volcanic ash',			'cloud'],
	['heavy thunderstorm', 					'thunder'],
	['isolated thundershowers', 			'thunder'],
	['isolated thunderstorms', 				'thunder'],
	['light thunderstorm', 					'thunder'],
	['moderate or heavy rain with thunder',	'thunder'],
	['moderate or heavy snow with thunder',	'thunder'],
	['patchy light rain with thunder', 		'thunder'],
	['patchy light snow with thunder', 		'thunder'],
	['ragged thunderstorm', 				'thunder'],
	['scattered thunderstorms', 			'thunder'],
	['severe thunderstorms', 				'thunder'],
	['thundershowers', 						'thunder'],
	['thunderstorm', 						'thunder'],
	['thunderstorm with drizzle', 			'thunder'],
	['thunderstorm with heavy drizzle', 	'thunder'],
	['thunderstorm with heavy rain', 		'thunder'],
	['thunderstorm with light drizzle', 	'thunder'],
	['thunderstorm with light rain', 		'thunder'],
	['thunderstorm with rain', 				'thunder'],
	['thunderstorms', 						'thunder'],
	['thundery outbreaks possible', 		'thunder'],
	['blizzard', 									'snow'],
	['blowing snow', 								'snow'],
	['cold', 										'snow'],
	['hail', 										'snow'],
	['heavy shower snow', 							'snow'],
	['heavy snow', 									'snow'],
	['ice pellets', 								'snow'],
	['light rain and snow', 						'snow'], 
	['light shower snow', 							'snow'],
	['light showers of ice pellets', 				'snow'],
	['light sleet', 								'snow'],
	['light sleet showers', 						'snow'],
	['light snow', 									'snow'],
	['light snow showers', 							'snow'],
	['mixed rain and hail', 						'snow'],
	['mixed rain and sleet', 						'snow'],
	['mixed rain and snow', 						'snow'],
	['mixed snow and sleet', 						'snow'],
	['moderate or heavy showers of ice pellets',	'snow'],
	['moderate or heavy sleet', 					'snow'],
	['moderate or heavy sleet showers', 			'snow'],
	['moderate or heavy snow showers', 				'snow'],
	['moderate snow', 								'snow'],
	['patchy heavy snow', 							'snow'],
	['patchy light snow', 							'snow'],
	['patchy moderate snow', 						'snow'],
	['patchy sleet possible', 						'snow'],
	['patchy snow possible', 						'snow'],
	['rain and snow', 								'snow'],
	['scattered snow showers', 						'snow'],
	['shower sleet', 								'snow'],
	['shower snow', 								'snow'], 
	['sleet', 										'snow'],
	['snow', 										'snow'],
	['snow flurries', 								'snow'],
	['snow showers', 								'snow'],
	['freezing drizzle', 				'rain'],
	['heavy freezing drizzle', 			'rain'],
	['heavy rain', 						'rain'],
	['heavy rain at times', 			'rain'],
	['light drizzle', 					'rain'],
	['light freezing rain', 			'rain'],
	['light rain', 						'rain'],
	['light rain shower', 				'rain'],
	['moderate or heavy freezing rain', 'rain'],
	['moderate or heavy rain shower', 	'rain'],
	['moderate rain', 					'rain'],
	['moderate rain at times', 			'rain'],
	['patchy freezing drizzle possible','rain'],
	['patchy light drizzle', 			'rain'],
	['patchy light rain', 				'rain'],
	['patchy rain possible', 			'rain'],
	['torrential rain shower', 			'rain'],
	['drizzle', 						'rain'],
	['drizzle rain', 					'rain'],
	['extreme rain', 					'rain'],
	['freezing drizzle', 				'rain'],
	['freezing rain', 					'rain'],
	['heavy intensity drizzle', 		'rain'],
	['heavy intensity drizzle rain', 	'rain'], 
	['heavy intensity rain', 			'rain'],
	['heavy intensity shower rain', 	'rain'],
	['heavy shower rain and drizzle',	'rain'],
	['light intensity drizzle', 		'rain'],
	['light intensity drizzle rain', 	'rain'], 
	['light intensity shower rain', 	'rain'], 
	['light rain', 						'rain'],
	['moderate rain', 					'rain'],
	['ragged shower rain', 				'rain'],
	['scattered showers', 				'rain'],
	['shower drizzle', 					'rain'],
	['shower rain', 					'rain'],
	['shower rain and drizzle', 		'rain'],
	['showers', 						'rain'],
	['very heavy rain', 				'rain'],
	]);
	
	function getImage1(){
		var imgName = conditionMap.get((document.getElementById("weatherText1").textContent).toLowerCase());
		document.getElementById("weatherImage1").src = "frontend/img/" + imgName + ".png";

	}
	function getImage2(){
		var imgName = conditionMap.get((document.getElementById("weatherText2").textContent).toLowerCase());
		document.getElementById("weatherImage2").src = "frontend/img/" + imgName + ".png";

	}
	function getImage3(){
		var imgName = conditionMap.get((document.getElementById("weatherText3").textContent).toLowerCase());
		document.getElementById("weatherImage3").src = "frontend/img/" + imgName + ".png";

	}
	getImage1();
	getImage2();
	getImage3();
	
	</script>
	
<?php } ?>
