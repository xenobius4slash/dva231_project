<div class="background"></div>
	<p><h1><?=$town?></h1>
		<form method="POST" action="index.php?page=town">
			<input type="hidden" name="town" value="<?=$town?>" />
			<input type="submit" name="town_search" value="refresh" />
		</form>
	</p>

<div id="weatherBox" style="float: left; margin-right: 5%; width:1014px; height:220px; margin-bottom:5%">
	<table style="text-align:center; margin-top:40px;">
	<tr><td rowspan="2" height="120px" width="300px"><h1 id="printAvgTemp" style="font-size:100px;"></h1></td><td width="200px"><img src="frontend/img/wind_speed.png" alt="Wind Speed" width="100"></td><td width="200px"><img src="frontend/img/humidity.png" alt="Humidity" width="100"></td><td width="200px"><img src="frontend/img/pressure.png" alt="Pressure" width="100"></td></td></tr>
	<tr style="font-size:20px;"><td id="printAvgWind" style="color:#333;">Wind</td><td id="printAvgHum" style="color:#333;">Hum</td><td id="printAvgPre" style="color:#333;">Pre</td></tr>
	</table>
</div>

<script type="text/javascript" language="javascript">
/*
Calculates the average values.
*/
document.getElementById("printAvgTemp").innerHTML = ((<?=$WSA->getTemperature()?> + <?=$WSOWM->getTemperature()?> + <?=$WSY->getTemperature()?>)/3).toFixed(0) + "°";
document.getElementById("printAvgWind").innerHTML = ((<?=$WSA->getWindSpeed()?> + <?=$WSOWM->getWindSpeed()?> + <?=$WSY->getWindSpeed()?>)/3).toFixed(1) + " m/s";
document.getElementById("printAvgHum").innerHTML = ((<?=$WSA->getHumidity()?> + <?=$WSOWM->getHumidity()?> + <?=$WSY->getHumidity()?>)/3).toFixed(0) + " %";
document.getElementById("printAvgPre").innerHTML = ((<?=$WSA->getPressureHpa()?>+<?=$WSOWM->getPressureHpa()?>+<?=$WSY->getPressureHpa()?>)/3).toFixed(0) + " hPa";
</script>

<div id="weatherBox" style="float: left; margin-right: 5%;">
<p><b>Apixu</b></p>
<table width="250px">
	<tr>				<td colspan="2">					<?=($WSA->getCurlError())?('cURL Error: '.$WSA->getCurlErrorCode().' => '.$WSA->getCurlErrorMessage()):('')?></td></tr>
	<tr>				<td colspan="2">					<h1><?=$WSA->getTemperature()?>° <?=$WSA->getTempUnitSign()?><h1>								</td>													</tr>
	<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage1"/></td><td ><p1 id="weatherText1"><?=$WSA->getSky()?></p1></td></tr>
	<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" height="38"></td>	<td id="wind1" style="font-size:20px; color:#333333;"><?=$WSA->getWindSpeed()?> m/s<br></td>	</tr>
	<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/humidity.png" alt="Wind Speed" height="38">	</td>	<td ><p1><?=$WSA->getHumidity()?> %</p1></td>	</tr>		
	<tr>				<td style="text-align: center;">	<img src="frontend/img/pressure.png" alt="Pressure" height="30"></td><td style="font-size:20px; color:#333333;"><?=$WSA->getPressureHpa()?> hPa</td></tr>
	<tr>				<td colspan="2" style="text-align: center; font-size:20px; color:#333333;"><br><br>Last updated:<br><?=$WSA->getLastUpdate()?></td></tr>
</table>
</div>

<div id="weatherBox" style="float:left; margin-right: 5%;">
<p><b>OpenWeatherMap</b></p>
<table width="250px">
	<tr>				<td colspan="2">					<?=($WSOWM->getCurlError())?('cURL Error: '.$WSOWM->getCurlErrorCode().' => '.$WSOWM->getCurlErrorMessage()):('')?></td></tr>
	<tr>				<td colspan="2">					<h1><?=$WSOWM->getTemperature()?>° <?=$WSOWM->getTempUnitSign()?><h1>								</td>													</tr>
	<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage2"/></td><td ><p1 id="weatherText2"><?=$WSOWM->getSky()?></p1></td></tr>
	<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" height="38"></td>	<td id="wind2" style="font-size:20px; color:#333333;"><?=$WSOWM->getWindSpeed()?> m/s<br></td>	</tr>
	<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/humidity.png" alt="Wind Speed" height="38">	</td>	<td><p1><?=$WSOWM->getHumidity()?> %</p1></td>	</tr>
	<tr>				<td style="text-align: center;">	<img src="frontend/img/pressure.png" alt="Pressure" height="30"></td><td style="font-size:20px; color:#333333;"><?=$WSOWM->getPressureHpa()?> hPa</td></tr>
	<tr>				<td colspan="2" style="text-align: center; font-size:20px; color:#333333;"><br><br>Last updated:<br><?=$WSOWM->getLastUpdate()?></td></tr>
</table>
</div>

<div id="weatherBox">
<p><b>Yahoo</b></p>
<table width="250px">
	<tr>				<td colspan="2">					<?=($WSY->getCurlError())?('cURL Error: '.$WSY->getCurlErrorCode().' => '.$WSY->getCurlErrorMessage()):('')?></td></tr>
	<tr>				<td colspan="2">					<h1><?=$WSY->getTemperature()?>° <?=$WSY->getTempUnitSign()?><h1>
	<tr>				<td style="text-align: center;">	<img src="frontend/img/error.png" height="38" id="weatherImage3"/></td><td ><p1 id="weatherText3"><?=$WSY->getSky()?></p1></td></tr>		</td>													</tr>
	<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/wind_speed.png" alt="Wind Speed" height="38"></td>	<td id="wind3" style="font-size:20px; color:#333333;"><?=$WSY->getWindSpeed()?> m/s<br></td>	</tr>
	<tr height="50px">	<td style="text-align: center;">	<img src="frontend/img/humidity.png" alt="Wind Speed" height="38">	</td>	<td><p1><?=$WSY->getHumidity()?> %</p1></td>	</tr>
	<tr>				<td style="text-align: center;">	<img src="frontend/img/pressure.png" alt="Pressure" height="30"></td><td style="font-size:20px; color:#333333;"><?=$WSY->getPressureHpa()?> hPa</td></tr>
	<tr>				<td colspan="2" style="text-align: center; font-size:20px; color:#333333;"><br><br>Last updated:<br><?=$WSY->getLastUpdate()?></td></tr>
</table>
</div>

<script type="text/javascript" language="javascript">
function getWindDir(dirDeg){
	/* var dirDeg = <?=$WSA->getWindDegree()?>; */
	var dirName;
	if (0 <= dirDeg && dirDeg <= 22) dirName = "North";
	else if (23 <= dirDeg && dirDeg <=67) dirName = "Northeast";
	else if (68 <= dirDeg && dirDeg <=112) dirName = "East";
	else if (113 <= dirDeg && dirDeg <=157) dirName = "Southeast";
	else if (158 <= dirDeg && dirDeg <=202) dirName = "South";
	else if (203 <= dirDeg && dirDeg <=247) dirName = "Shouthwest";
	else if (248 <= dirDeg && dirDeg <=292) dirName = "West";
	else if (293 <= dirDeg && dirDeg <=337) dirName = "Northwest";
	else if (338 <= dirDeg && dirDeg <=360) dirName = "North";
	
	/* document.getElementById("wind1").innerHTML += dirName; */
	return dirName;
}
var temp = <?=$WSA->getWindDegree()?>;
document.getElementById("wind1").innerHTML += getWindDir(temp);
temp = <?=$WSOWM->getWindDegree()?>;
document.getElementById("wind2").innerHTML += getWindDir(temp);
temp = <?=$WSY->getWindDegree()?>;
document.getElementById("wind3").innerHTML += getWindDir(temp);
temp = (<?=$WSA->getWindDegree()?> + <?=$WSOWM->getWindDegree()?> + <?=$WSY->getWindDegree()?>)/3;
document.getElementById("printAvgWind").innerHTML += "<br>" + getWindDir(temp);
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
['rain', 							'rain'],
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

