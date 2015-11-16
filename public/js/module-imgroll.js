(function($) {
	WEB.Img_Roll = function(){
		var numargs = arguments.length;
		if (numargs != 3) {
			return;
		};
	  	var options = {};

		options = {
			closureId : arguments[0],
		    innerimgId : arguments[1],
		    hiddenimgId : arguments[2]
		};
		
		if(WEBTOOL.empty(options)||WEBTOOL.empty(options.closureId)||WEBTOOL.empty(options.innerimgId)||WEBTOOL.empty(options.hiddenimgId)){
			return;	
		}

		var speed = 50;
		var closure = $("#"+options.closureId);
		var innerimg = $("#"+options.innerimgId);
		var hiddenimg = $("#"+options.hiddenimgId);
		hiddenimg.html(innerimg.html());
		function imgslide(){
			if(hiddenimg.width()<=closure.scrollLeft()){
				closure.scrollLeft(closure.scrollLeft()-hiddenimg.width());	
			}else{
				closure.scrollLeft(closure.scrollLeft()+1);	
			}
		}
		var MyMar=setInterval(imgslide,speed);
		closure.live('mouseover',function() {clearInterval(MyMar);});
		closure.live('mouseout',function() {MyMar=setInterval(imgslide,speed);});



	}
})(jQuery);

