var Restore = {

	User: {

		dp: null,

		initialize: function(dp)
		{
			this.dp = dp;
		}
	},

	Character: {

		name: null,
		guid: null,
		realm: null,

		initialize: function(name, guid, realm)
		{
			this.name = name;
			this.guid = guid;
			this.realm = realm;
		}
	},

	selectCharacter: function(button, realm, guid, name)
	{
		var CharSection = $("#select_character");
		
		Restore.Character.initialize(name, guid, realm);
		
		$(".item_group", CharSection).each(function()
		{
			$(this).removeClass("item_group").addClass("select_character");
			$(this).find(".nice_active").removeClass("nice_active").html("Select");
		});
		
		$(button).parents(".select_character").removeClass("select_character").addClass("item_group");
		$(button).addClass("nice_active").html("Selected");
	},
	
	IsLoading: false,

	submit: function(button){

		if (Restore.IsLoading)
			return;

		//Check if we have selected character
		if (Restore.Character.guid == null){
			Swal.fire({
						icon:  'error',
						title: 'Restore',
						text:  'Please select a character first.',
					})
			return;
		}
			$.ajax({
			  	beforeSend: function(xhr)
				{
					Restore.IsLoading = true;
					$(button).parents(".select_tool").addClass("active_tool");
					$(button).html('Please Wait ...');

			  	}
			});
			
			// Execute the service
			$.post(Config.URL + "restore/submit",
			{
				 
				guid: Restore.Character.guid,
				realm: Restore.Character.realm,
				csrf_token_name: Config.CSRF
			},
			function(data)
			{
				Restore.IsLoading = false;
				
				if (data == 1)
				{

					Swal.fire({
						icon:  'success',
						title: 'Restore',
						text:  'Character ' + Restore.Character.name + ' restore successfully.',
						willClose: () => {
								window.location.reload();
						}
					});

				}
				else
				{
				Swal.fire({
						icon: 'error',
						title: 'Restore',
						text: data,
					})
				}

				$(button).parents(".select_tool").addClass("active_tool");
				$(button).html('Restore');
			});
		
	}
}

function redirect(url) 
{
	
	window.location=url; 
}