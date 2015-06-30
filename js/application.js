
//Graph type always change if different "graph page" is opened
var _GRAPH_TYPE = "default";

(function($) {
	$(window).load(function(e) {

		var id_user = 0;
		var graph_github_per_project = "graph_github_per_project.php";
		var graph_graphviz = "graph_graphviz.php";
		var users_data = "crud/user.data.php";
		var projects_data = "crud/projects.data.php";

		loadContent('data-user', users_data);
		loadContent('data-projects', projects_data);

		/*loadContent('graph-view-githubDeveloper', graph_github_per_project);
		loadContent('graph-view-induced', graph_graphviz); */

		$('.edit, .add').live("click", function(){

			var url = "crud/user.form.php";
			id_user = this.id;

			if(id_user != 0) {
				$("#myModalLabel").html("Edit User Data");
			} else {
				$("#myModalLabel").html("Add User Data");
			}

			$.post(url, {id: id_user} ,function(data) {
				$(".modal-body").html(data).show();
			});
		});

		$('.delete').live("click", function(){
			var url = "crud/user.input.php";
			id_user = this.id;

			var answer = confirm("Are you sure?");

			if (answer) {
				$.post(url, {'delete': id_user} ,function() {
					loadContent('data-user', users_data);
				});
			}
		});

		$("#save-user").bind("click", function(event) {
			form = $("#form-user").valid();
			if(form){
				var url = "crud/user.input.php";
				var v_username = $('input:text[name=username]').val();
				var v_password = $('input:text[name=password]').val();
				var v_email = $('input:text[name=email]').val();
				var v_name = $('input:text[name=name]').val();
				var v_address = $('textarea[name=address]').val();
				var v_affiliation = $('select[name=affiliation]').val();
				var v_admin = $('select[name=admin]').val();

				$.post(url, {username: v_username, password: v_password, email: v_email, name: v_name, address: v_address, affiliation: v_affiliation, admin: v_admin, id: id_user} ,function() {
					loadContent('data-user', users_data);

					$('#dialog-user').modal('hide');

					$("#myModalLabel").html("Add User Data");
				});
			}
		});

	});
}) (jQuery);

function loadContent(id_element, content){
	$("#"+id_element).html('<p><img src="img/ajax-loader.gif" width="220" height="19" /></p>');
	$("#"+id_element).load(content);
}

function pagination(table_name, page){
	var url = "crud/projects.data.php?page="+page;
	loadContent('data-'+table_name, url);
}
