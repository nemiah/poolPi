
var poolPi = {
	ignore: [],
	
	init: function(){
		document.ontouchmove = function(event){
			event.preventDefault();
		}


		$(".touch").bind("mousedown touchstart", function(ev) {
			$(ev.target).css("opacity", ".5");
		});

		$(".touch").bind("mouseup touchend", function(ev) {
			$(ev.target).css("opacity", "1");
		});


		$(".colorLabel").bind("mousedown touchstart", function(ev) {
			$(ev.target).parent().css("opacity", ".5");
		});

		$(".colorLabel").bind("mouseup touchend", function(ev) {
			$(ev.target).parent().css("opacity", "1");
		});


		$('.manual').each(function(k, v){
			$(v).bind("touchend mouseup", function(ev) {
				var e = $(ev.target);
				poolPi.redeemer(control.server[e.data("server")], e.data("master"), e.data("value"),  function(){ });
			});
		});


		$('.load').each(function(k, v){
			$(v).bind("touchend mouseup", function(ev) {
				poolPi.tap($(ev.target));
			});
		});

		$('.load + .colorLabel').each(function(k, v){
			$(v).bind("touchend mouseup", function(ev) {
				poolPi.tap($(ev.target).prev());
			});
		});


		window.setInterval(function(){
			poolPi.pollAll();
		}, 30000);

		poolPi.pollAll();


		$(".right").css("height", $(window).height() - parseFloat($("body").css("padding-top")) * 2);
		$("#darkOverlay").css("height", $(window).height()).css("width", $(window).width());
		
		
		$(window).on("online", function(){
			Interface.online();
		});

		$(window).on("offline", function(){
			Interface.offline();
		});
		
		if(!navigator.onLine)
			Interface.offline();


	},	
	
	redeemer: function(target, id, value, onSuccess){
		if(poolPi.ignore[target+"_"+id])
			return;

		var audio = new Audio('./res/confirm.mp3');
		audio.play();

		poolPi.ignore[target+"_"+id] = true;

		$.ajax({
			dataType: "json",
			url: "redeemer.php?control="+target+"&id="+id+"&value="+value,
			timeout: 10000,
			success: function (data, textStatus, jqXHR) {
				if(data.type && data.type == "error"){
					$.notify(data.message, "error");
					poolPi.ignore[target+"_"+id] = false;
					return;
				}

				if(data.type && data.type == "success")
					$.notify(data.message, "success");

				onSuccess();

				window.setTimeout(function(){
					poolPi.ignore[target+"_"+id] = false;
				}, 1000);
			},
			error: function(){
				$.notify("Keine Server-Verbindung", "error");
			}
		});

	},
				
	update: function(v, delay){
		if(typeof delay == "undefined")
			delay = 500;

		window.setTimeout(function(){
			$.ajax({
				dataType: "json",
				url: "proxy.php?control="+control.server[$(v).data("server")],
				timeout: 3000,
				success: function(data) {
					poolPi.updateControl(v, data);
				},
				error: function(){
					//$.notify("Keine Server-Verbindung", "error");
				}
			});
		}, delay);
	},
				
	updateControl: function(v, data){
		var master = $(v).data("master");
		$(v).prop("src", "./img/"+data[master].replace("gif", "svg"));

		var status = "off";
		if(data[master].indexOf("_run") > 0)
			status = "on";

		if(data[master].indexOf("_on") > 0)
			status = "on";

		if(data[master].indexOf("-1") > 0)
			status = "on";

		$(v).data("status", status);
	},
				
	tap: function(e){
		poolPi.redeemer(control.server[e.data("server")], e.data("master"), typeof e.data("value") !== "undefined" ? e.data("value") : 'i',  function(){
			poolPi.update(e);

			if(e.data("update")){
				var u = e.data("update").split(",");
				u.forEach(function(k, v){
					$("[data-group="+k+"]").each(function(sk, sv){
						if($(sv).data("group-delay")){
							if($(sv).data("group-delayif") && $(sv).data("group-delayif") == $(sv).data("status"))
								poolPi.update(sv, $(sv).data("group-delay") * 1000);
							else
								poolPi.update(sv);

						} else
							poolPi.update(sv);
					});

				});
			}
		});
	},
				
	pollAll: function(){
		var names = Object.keys(control.server);
		var serverNames = {};

		var servers = [];
		for(var i = 0; i < names.length; i++){
			servers.push(control.server[names[i]]);
			serverNames[control.server[names[i]]] = names[i];
		}

		$.ajax({
			dataType: "json",
			url: "proxy.php?multi="+servers.join(","),
			timeout: 3000,
			success: function(data) {
				for(var i = 0; i < data.length; i++){
					var dataSub = data[i].data;
					var dataServer = data[i].server;

					var elements = Object.keys(dataSub);
					for(var j = 0; j < elements.length; j++){
						var v = $(".load[data-server='"+serverNames[dataServer]+"'][data-master='"+elements[j]+"']");

						if(v.length){
							poolPi.updateControl(v, dataSub);
							continue;
						}

						v = $(".value[data-server='"+serverNames[dataServer]+"'][data-master='"+elements[j]+"']");
						if(v.length)
							$(v).html(dataSub[elements[j]].replace(".", ","));

					}
				}
			},
			error: function(){

			}
		});
	}
};

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