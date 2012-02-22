$(document).ready(function(){
	
	frontPageSocialslider.follow();
	
	frontPageSocialslider.def_width();
	
	
	/*jquery carousel
	 * */
	$(".socialslider_slide").jCarouselLite({
      btnNext: ".next",
       btnPrev: ".prev",
	      vertical: true,
		  visible: parseInt($("#socialslider_icon_visible").val())
	});	
	
	/*hide button*/
	$(".btn_prev, .btn_next").hide();
	
	/*show button if hover*/
	$(".socialslider, .socialslider_container, .socialslider_expand, .socialslider_slide, .socialslider_right, .socialslider_right_container, .socialslider_right_expand").mouseover(function(){
		$(".btn_prev, .btn_next").show();
	
	});
	
	/*hide button if blur*/
	$(".socialslider, .socialslider_container, .socialslider_expand, .socialslider_slide, .socialslider_right, .socialslider_right_container, .socialslider_right_expand").mouseout(function(){
		$(".btn_prev, .btn_next").hide();
	});
	
});

var frontPageSocialslider ={
		
	/*give defined width *solve IE7 issue*/
	def_width: function(sUrl,sType){
		var bTitle = $("#socialslider_icon_title").val();
		if(bTitle == 1){
			$("#socialslider_wrap").css("width",180);
		}else{
			$("#socialslider_wrap").css("width",80);
		}
	},
	
	/*set the target for the linkers*/
	target: function(sUrl,sType){
	
		switch(sType){
		
		case "self":
		window.location.href = sUrl;
		break;
		
		case "tab":
		popupWin = window.open(sUrl,'open_window');
		break;
		
		case "window":
		NewWindow = window.open(sUrl,"_blank","toolbar=no,menubar=0,status=0,copyhistory=0,scrollbars=yes,resizable=1,location=0,Width=600,Height=600") ;
		NewWindow.location = sUrl;
		break;
		
		}
	
	},
	
	/*float the main div for the linkers*/
	follow: function(){
		/*show the div*/
		var speed = 1500;
		var current_top = parseInt($("#socialslider_wrap").css("top"));
		var position = $("#socialslider_icon_position").val();
		$("#socialslider_wrap").fadeIn(speed);
		
		if(position == "left"){
			floatingMenu.add('socialslider_wrap', {targetLeft: 10,  targetTop: 50});  
		}else{
			floatingMenu.add('socialslider_wrap', {targetRight: 10,  targetTop: 50});
		}
		
	},
	
	/*float the main div for the linkers*/
	manual_follow: function(){
		/*show the div*/
		var speed = 2000;
		var current_top = parseInt($("#socialslider_wrap").css("top"));
		$("#socialslider_wrap").fadeIn(speed);
		
		/*follow on scroll dowm*/
		$(window).scroll(function(){
			var top = $(window).scrollTop();
			$("#socialslider_wrap").css("top",top + current_top);
		});
	
	}
	

}


