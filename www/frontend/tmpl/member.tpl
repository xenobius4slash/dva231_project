<script type="text/javascript" language="javascript" src="<?=JS_PATH?>town.js"></script> <!-- JavaScript function getWindDir() -->
<script type="text/javascript" language="javascript">

</script>
<div class="background"></div>
<div id="form-box">
	<form method="POST" action="index.php?page=town">
		<div id="input-group">
			<label for="search" class="glyphicon glyphicon-search"></label>
			<input type="text" name="town" placeholder="Search for a town.." id="search">
		</div>
		<br/>
		<button id="btn-search" name="town_search">Search</button>
	</form>
	<br/><br/>
	<form method="post" action="index.php?page=town">
		<?php for($i=0; $i<count($data['towns']); $i++) { ?>
			<input type="submit" class="btn btn-lg" name="town" value="<?=$data['towns'][$i]?>" />
		<?php } ?>
		<input type="hidden" name="town_search" />
	</form>
</div>
