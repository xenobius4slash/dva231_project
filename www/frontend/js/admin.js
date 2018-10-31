/** start the deleting process
*	@param		userId		Integer
*	@param		htmlId		Integer
*/
function deleteUser(userId, htmlId) {
	var username = $('#del_user_'+htmlId+'_name').text();
	var useremail = $('#del_user_'+htmlId+'_email').text();
	console.log("deleteUser("+userId+", "+htmlId+") => username: "+username+" // useremail: "+useremail);
	if( confirm('Do you really want to delete the user?') ) {
		$.ajax({
			method: 'POST',
			url: 'backend/ajax/admin.php',
			dataType: 'json',
			data: { op: 'del_user', user_id: userId },
			success: function(answer) {
				console.log(answer);
				if(answer.status == true) {
					console.log("remove mit htmlId: "+htmlId);
					$('#del_user_'+htmlId).remove();
				}
			}
		});
	}
}

