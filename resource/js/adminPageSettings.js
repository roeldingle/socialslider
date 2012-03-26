var adminPageSettings = {
		
		/*set global variables*/
		APP_NAME: $("#APP_NAME").val(),
		
		/*initialize*/
		initialize: function(){
			
			/*check icons*/
			$.each($("input[name='socialslider_icons[]']"), function(i){
				
				adminPageSettings.icon_checkbox_check(this);
			});
			
			
		},
		
		/*check if icon name and url is readonly*/
		icon_checkbox_check: function(selector){
			
			var i = $(selector).val();
			
			if($(selector).is(':checked')){
				$("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").removeClass(adminPageSettings.APP_NAME+"_readonly");
				 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").removeClass(adminPageSettings.APP_NAME+"_readonly");
				 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").attr("readonly",false);
				 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").attr("readonly",false);

			}else{
				$("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").addClass(adminPageSettings.APP_NAME+"_readonly");
				 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").addClass(adminPageSettings.APP_NAME+"_readonly");
				 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").attr("readonly",true);
				 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").attr("readonly",true);
			}
			
			
		},
		
		
		
		/*
		 * get the locations from the div
		 */
		get_icons: function(){
			
			var bValid = true;
			var aIcons = new Array();
			var aReturn = new Array();
			var iContainerSize = $("."+adminPageSettings.APP_NAME+"_icon_wrap").size();
			$.each($("."+adminPageSettings.APP_NAME+"_icon_wrap"), function(i){	
				 bChecked = $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icons[]']").is(':checked');
				 name = $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_name[]']").val();
				 title = $.trim($("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").val());
				 url = $.trim($("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").val());
				 
				 /*validate*/
				 
				 if(bChecked){
					
					 if(title == ""){
						 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").addClass("invalid");
						 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_title[]']").focus();
						 bValid = false; 
					 }
					 
					 if(adminPageSettings.validURL(url) == false){
						 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").addClass("invalid");
						 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").parent().append("<span class='err_mess' >*Invalid URL</span>");
						 $(".err_mess").delay(1500).fadeOut(400).slideUp();
						 $("."+adminPageSettings.APP_NAME+"_icon_wrap:eq("+i+") input[name='"+adminPageSettings.APP_NAME+"_icon_url[]']").focus();
						 bValid = false; 
					 }
					 
					 

				 }
				
				/*push array*/
				aIcons.push({
					checked: (bChecked == true) ? 1: 0,
					name: name,
					title: title,
					url: url
				});
	
			});
			
			 aReturn['data'] = aIcons;
			 aReturn['valid'] = bValid;
		
			return aReturn;		

		},
		
		/*save settings*/
		setting_submit: function(form){
			
			/*gather variables*/
			var aIconData = adminPageSettings.get_icons();
			var bValid = aIconData['valid'];
			
			var position = $("input[name='"+adminPageSettings.APP_NAME+"_icon_position']:checked").val();
			var title = $("input[name='"+adminPageSettings.APP_NAME+"_icon_title']:checked").val();
			var size = $("input[name='"+adminPageSettings.APP_NAME+"_icon_size']:checked").val();
			var count = $("#"+adminPageSettings.APP_NAME+"_icon_count").val();
			var target = $("input[name='"+adminPageSettings.APP_NAME+"_icon_target']:checked").val();
			var aIcons = aIconData['data'];
			
			
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
	
	$("input[name='socialslider_icon_title[]']").keyup(function(){
		 if($.trim($(this).val()) == ""){
			 $(this).addClass("invalid");
		 }else{
			 $(this).removeClass("invalid");
		 }
	});
	
	$("input[name='socialslider_icon_url[]']").keyup(function(){
		 if(adminPageSettings.validURL($.trim($(this).val())) == false){
			 $(this).addClass("invalid");
		 }else{
			 $(this).removeClass("invalid");
		 }
	});
	
	$("input[name='socialslider_icons[]']").click(function(){
		
		adminPageSettings.icon_checkbox_check(this);
	});
	
	$("."+adminPageSettings.APP_NAME+"_all").click(function(){
		
		if($(this).is(':checked')){
			$("input[type='checkbox']").attr("checked",true);
			adminPageSettings.initialize();
		}else{
			$("input[type='checkbox']").attr("checked",false);
			adminPageSettings.initialize();
			
		}
	
	});
	
	
	

});
