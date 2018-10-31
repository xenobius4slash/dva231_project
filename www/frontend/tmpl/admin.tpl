<div class="background"></div>
	<div id="admin-box" style="margin-top: 50px;">
		<h1 style="text-align:left; margin-left:25%">Admin settings</h1>
		<div id="input-group" style="text-align:left; padding-left:10px; width: 50%;">
			<ol style="list-style-type:none; ">
				<?php for($i=0; ($users!==false && $i<count($users)); $i++) { ?>
					<li style="padding:2px 0px" id="del_user_<?=$i?>">
						<span id="del_user_<?=$i?>_name"><?=$users[$i]['name']?></span> (<span id="del_user_<?=$i?>_email"><?=$users[$i]['email']?></span>)
						<button id="btn-del" style="float:right; margin-right:20px; padding: 0px 20px;border: none;border-radius: 5px;background-color: rgb(212, 10, 20);color: #fff;" onclick="deleteUser(<?=$users[$i]['id']?>, <?=$i?>)">Delete</button>
					</li>
				<?php } ?>
			</ol>
		</div>
	</div>
</div>
<script src="<?=JS_PATH?>admin.js"></script>
