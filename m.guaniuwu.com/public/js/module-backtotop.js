/*
* 回到顶部
* @author slumber 2011-06-03
*
*/

(function($) {

	//回到顶部按钮初始化
	WEB.Globe_Back_To_Top_Init = function(){
		if ($(".back2top_fat")[0]){
			var obj=$(".back2top_fat");
		    $(window).scroll(function(){
				sroll_func(obj);
		    });
		    
		}
		
		var sroll_func = function(obj){
			var time_out = false;
			if(time_out){
			    clearTimeout(time_out);
	    	}
			time_out = setTimeout(
	    		function(){
		    		if($(window).scrollTop()==0){
			            obj.hide();
			        }else{
			            obj.show();
			        }	
	    		}, 200
	    	);
			obj.live('click',function(){
				$('html, body').animate( { scrollTop: 0 }, 0 );	
			});
		}
	}

	WEB.Globe_Back_To_Top_Init();

})(jQuery);
