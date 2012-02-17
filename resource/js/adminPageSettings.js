var adminPageSettings = {
		
		/*set global variables*/
		APP_NAME: $("#APP_NAME").val(),
		
		/*initialize*/
		initialize: function(){
			/*
			$.ajax({  
				url: usbuilder.getUrl("apiExec"),
				type: 'post',
				dataType: 'json',
				data: {
				action: 'setting_submit',
				get_position: position,
				get_title: title,
				get_size: size,
				get_count: count,
				get_target: target,
				get_icon: aIcons
				
			},
				success: function(data){
				}
				}
				*/
		},
		
	
		
		/*
		 * get the locations from the div
		 */
		get_icons: function(){
			
			var aIcons = new Array();
			var iContainerSize = $("."+adminPageSettings.APP_NAME+"_icon_wrap").size();
			$.each($("."+adminPageSettings.APP_NAME+"_icon_wrap"), function(i){	
				 bChecked = $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icons[]']").attr("checked");
				 name = $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_name[]']").val();
				 url = $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").val();
				
				/*push array*/
				aIcons.push({
					checked: (bChecked == "checked") ? 1: 0,
					name: name,
					url: url
				});
	
			});

		
			return aIcons;		

		},
		
		/*save settings*/
		setting_submit: function(form){
			
			/*gather variables*/
			var bValid = oValidator.formName.getMessage(adminPageSettings.APP_NAME+'_form');
			
			var position = $("input[name='"+adminPageSettings.APP_NAME+"_icon_position']:checked").val();
			var title = $("input[name='"+adminPageSettings.APP_NAME+"_icon_title']:checked").val
			var size = $("input[name='"+adminPageSettings.APP_NAME+"_icon_size']:checked").val();
			var count = $("#"+adminPageSettings.APP_NAME+"_icon_count").val();
			var target = $("input[name='"+adminPageSettings.APP_NAME+"_icon_target']:checked").val();
			var aIcons = adminPageSettings.get_icons();
			
			if(bValid === true){
					/*ajax submit*/
					$.ajax({  
						url: usbuilder.getUrl("apiExec"),
						type: 'post',
						dataType: 'json',
						data: {
						action: 'setting_submit',
						get_position: position,
						get_title: title,
						get_size: size,
						get_count: count,
						get_target: target,
						get_icon: aIcons
						
					},
						success: function(data){
						
						if(data.Data === true){
							oValidator.generalPurpose.getMessage(true, "Saved successfully");
							scroll(0,0);
							
							}else{
								oValidator.generalPurpose.getMessage(false, "Failed");
								scroll(0,0);
							}
					
						}
					});
			}
			
		},
		
		
		/*reset to default*/
		reset_default: function(){
			$("#"+adminPageSettings.APP_NAME+"_form_reset").submit();
			
		},
		/*
		 * display a dialog box
		 * @param aDecs = define the description for the dialog box
		 */
		open_popup: function(sContainer,iWidth,sTitle){
			
			//empty the result list
			$("#googlemapmarker_result").empty();
			
			/*create popup*/
			popup.load(sContainer).skin("admin").layer({
				width: iWidth,
				title: sTitle,
				resize: false,
				draggable: true	
			});
			
		},
		
		/*
		 * close dialog box 
		 *  @param sConId = dialog box container id
		 */
		close_popup: function(sConId){
			popup.close(sConId);
		},
		
		validURL: function(str) {
			var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
			return regexp.test(str);
		}
	
};


$(document).ready(function(){
	
	adminPageSettings.initialize();
	

});
