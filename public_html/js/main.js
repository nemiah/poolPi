

$(window).on("online", function(){
	Interface.online();
});

$(window).on("offline", function(){
	Interface.offline();
});

$("#darkOverlay").css("height", $(window).height()).css("width", $(window).width());
		
var Interface = {
	offline: function(){
		$("#darkOverlay").fadeIn();
		
		$('body').append("<div id='offlineMessage' style='z-index:100000;color:white;font-size:40px;width:400px;position:absolute;'>Sie sind offline</div>");
		$('#offlineMessage').css("top", ($(window).height() - $('#offlineMessage').outerHeight()) / 2);
		$('#offlineMessage').css("left", ($(window).width() - 400) / 2);
	},
	
	online: function(){
		$("#darkOverlay").fadeOut();
		
		$('#offlineMessage').remove();
	}
};

$(function(){
	if(!navigator.onLine)
		Interface.offline();
});