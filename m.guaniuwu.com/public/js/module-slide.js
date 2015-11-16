(function($) {
	WEB.Img_Slide = function(){
		var numargs = arguments.length;
		if (numargs != 1 && numargs != 4 && numargs !=6) {
			return;
		};
	  	var options = {};

		if (numargs == 4) {
			options = {
				listID : arguments[0],
		        listBtnID : arguments[1],
		        feedClassName : arguments[2],
		        feedWidth : arguments[3]
			};
		} else if(numargs == 1){
			options = arguments[0];
		}else{
			options = {
				listID : arguments[0],
		        listBtnID : arguments[1],
		        feedClassName : arguments[2],
		        feedWidth : arguments[3],
				slideLeftID : arguments[4],
				slideRightID : arguments[5]
			};
		}

		if (WEBTOOL.empty(options) || WEBTOOL.empty(options.listID) || WEBTOOL.empty(options.listBtnID) || WEBTOOL.empty(options.feedClassName) || WEBTOOL.empty(options.feedWidth)) {
			return;
		};

		if ($("#"+options.listID).size() == 0) {
			return;
		};
			
		var btnCurClass = 'c';
		var pageNo = 0;
		var pageCur = 1;
		var switchTimer = null;
		var switchInterval = 3500;
		var aTime = 500;
		var topicArray = new Array();
			
			
		var init = function() {
			pageNo = $('#'+options.listID+' .'+options.feedClassName).size();
			if(pageNo > 0){
				if(pageNo > 1){
					createTopicArray();
					bindBtnEvents();
					resetHtml();
				}
			}
		};
		var start = function() {
			if(pageNo > 1){
				switchTimer = setInterval(switchTopic, switchInterval);
			}
		};
		var switchTopic = function() {
			$('#'+options.listBtnID+' li').removeClass(btnCurClass);
			$('#'+options.listBtnID+' li').eq(pageCur%pageNo).addClass(btnCurClass);
				
			$('#'+options.listID +" ul").animate({ 
				left: "-"+options.feedWidth*2+"px"
			}, aTime ,resetHtml());
			pageCur++;
				
			if(pageCur > pageNo){
				pageCur = 1;
			}
		};
		
		if($('#'+options.slideLeftID).size()>0){
			$('#'+options.slideLeftID).live('click',function(){
				clearInterval(switchTimer);
				
				if(pageCur == 1){
					prevPage = pageNo;	
				}else{
					prevPage = pageCur -1;	
				}
			
				$('#'+options.listBtnID+' li').removeClass(btnCurClass);
				$('#'+options.listBtnID+' li').eq(pageCur%pageNo).addClass(btnCurClass);
				
								
				$('#'+options.listID +" ul").animate({ 
					left: "0px"
				}, aTime,resetHtml(0,prevPage));
				
				if(pageCur == 1){
					pageCur = pageNo;	
				}else{
					pageCur = pageCur -1;	
				}

				switchTimer = setInterval(switchTopic, switchInterval);
			});
		
		}
		
		if($('#'+options.slideRightID).size()>0){
			$('#'+options.slideRightID).live('click',function(){
				clearInterval(switchTimer);
				$('#'+options.listBtnID+' li').removeClass(btnCurClass);
				$('#'+options.listBtnID+' li').eq(pageCur%pageNo).addClass(btnCurClass);


				$('#'+options.listID +" ul").animate({ 
					left: "-"+options.feedWidth*2+"px"
				}, aTime ,resetHtml());
				pageCur++;
				
				if(pageCur > pageNo){
					pageCur = 1;
				}
				switchTimer = setInterval(switchTopic, switchInterval);
			});
		
		}

		var bindBtnEvents = function() {
			$('#'+options.listBtnID+' li').each(function(i){
				$(this).mouseover(function(){
					clearInterval(switchTimer);
					$('#'+options.listBtnID+' li').removeClass(btnCurClass);
					$(this).addClass(btnCurClass);
					if (i + 1 > pageCur) {
						resetHtml(i + 1);
						$('#'+options.listID +" ul").animate({ 
							left: "-"+options.feedWidth*2+"px"
						}, aTime);
					} else if(i + 1 < pageCur){
						resetHtml(null,i + 1);
						$('#'+options.listID +" ul").animate({ 
							left: "0px"
						}, aTime);
					}
					pageCur = i+1;
				});
				$(this).mouseout(function(){
					switchTimer = setInterval(switchTopic, switchInterval);
				});
			 });
		};
		var resetHtml = function(nextPage,prevPage) {
				
			if (nextPage == null || nextPage == undefined) {
				nextPage = pageCur%pageNo + 1;
			}
			if (prevPage == null || prevPage == undefined) {
				prevPage = pageCur - 1 == 0 ? pageNo : pageCur - 1;
			}

			$('#'+options.listID +" ul").html("<li class='" +options.feedClassName + "'>" + topicArray[prevPage-1] + "</li><li class='" +options.feedClassName + "'>" + topicArray[pageCur - 1] + "</li><li class='" +options.feedClassName + "'>" + topicArray[nextPage-1] + "</li>");
			$('#'+options.listID +" ul").css("left","-"+options.feedWidth+"px");
		};
		var createTopicArray = function() {
			var topicArrayTmp = $('#'+options.listID+' .'+options.feedClassName).toArray();
			for (var i = 0; i < topicArrayTmp.length; i++){
				topicArray[i] = $(topicArrayTmp[i]).html();
			};
		};
		init();
		start();	
	};

})(jQuery);

