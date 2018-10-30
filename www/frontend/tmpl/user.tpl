<div class="background"></div>
	<div id="form-box" style="margin-top: 50px;">
		<form method="POST" action="index.php?page=user">
			<h1 style="text-align:left; margin-left:25%">User settings</h1>
			<div id="input-group" style="text-align:left; padding-left:10px; width: 50%;">
				<label for="username" class="">Change username:</label>
				<input type="username" name="username" placeholder="Enter new username..." id="username" style="border:none; line-height: 40px; width: 70%;">
			</div>
			<br>
			<div id="input-group" style="text-align:left; padding-left:10px; width: 12%;">
				<input type="radio" name="degreeType" value="celsius"><b> Celsius</b><br>
				<input type="radio" name="degreeType" value="fahrenheit"><b> Fahrenheit</b><br>
			</div>
			<br>
			<button name="settings_saved" id="btn-apply" style="padding: 6px 20px;border: none;border-radius: 5px;background-color: rgb(212, 10, 20);color: #fff;">Apply changes</button>
		</form>
	  </div>
</div>
