$(document).ready(function(){

	frontPageSocialslider.follow();
	
	

	/*jquery carousel*/
	$(".socialslider_slide").jCarouselLite({
      btnNext: ".next",
       btnPrev: ".prev",
	      vertical: true,
		  visible: 4
	});	
	
	/*hide button*/
	$(".btn_prev, .btn_next").hide();
	
	/*show button if hover*/
	$(".socialslider, .socialslider_container, .socialslider_expand, .socialslider_slide").mouseover(function(){
		$(".btn_prev, .btn_next").show();
	});
	
	/*hide button if blur*/
	$(".socialslider, .socialslider_container, .socialslider_expand, .socialslider_slide").mouseout(function(){
		$(".btn_prev, .btn_next").hide();
	});
	
});

var frontPageSocialslider ={

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
		$("#socialslider_wrap").fadeIn(speed);
		
		floatingMenu.add('socialslider_wrap', {targetLeft: 10,  targetTop: 10});  
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


