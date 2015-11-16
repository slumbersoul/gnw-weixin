(function($) {
	WEB.Calendar = function(){
		$('.cal-sel-left').on('click',function(){
			var $this = $(this);
			if($this.attr('disable') == 1){
				return;	
			}else{
				$this.attr('disable',1);	
			}
			var itemid = $this.attr('data-item');
			var month = $this.attr('data-month');
			var text = $this.attr('data-text');
			var data={itemid:itemid,month:month};
			$.ajax({
        		url: "/site/getbookrecordshtml", 
        		type: "POST", 
        		timeout:60000,
        		data: data,
				async:false,
        		dataType: 'json', 
        		success: function(json){
        			if (json == null) {
        				alert(WEBLANG.msgTimeout);
        			} else {
        				var code = json.status.code;
        				var msg = json.status.msg;
        				if(code == 1001) {
        					var html = json.result.html;
							var a_month = json.result.a_month;
							var a_text = json.result.a_text;
							var b_month = json.result.b_month;
							var b_text = json.result.b_text;
							$(".cal-body").html(html);
							$('.cal-sel-text').html(text);
							$('.cal-sel-left').attr('data-month',b_month);
							$('.cal-sel-left').attr('data-text',b_text);
							$('.cal-sel-right').attr('data-month',a_month);
							$('.cal-sel-right').attr('data-text',a_text);
						}else{
        					//alert(msg);
						}
        			}
        		},
        	   	error:function (XMLHttpRequest, textStatus, errorThrown) {
        	    	if ("timeout" == textStatus) {
        	   			alert(WEBLANG.msgTimeout);
        	   		}
        	   	}
        	});
			$this.attr('disable',0);	
			
		});
	
		$('.cal-sel-right').on('click',function(){
			var $this = $(this);
			if($this.attr('disable') == 1){
				return;	
			}else{
				$this.attr('disable',1);	
			}
			var itemid = $this.attr('data-item');
			var month = $this.attr('data-month');
			var text = $this.attr('data-text');
			var data={itemid:itemid,month:month};
			$.ajax({
        		url: "/site/getbookrecordshtml", 
        		type: "POST", 
        		timeout:60000,
        		data: data,
				async:false,
        		dataType: 'json', 
        		success: function(json){
        			if (json == null) {
        				alert(WEBLANG.msgTimeout);
        			} else {
        				var code = json.status.code;
        				var msg = json.status.msg;
        				if(code == 1001) {
        					var html = json.result.html;
							var a_month = json.result.a_month;
							var a_text = json.result.a_text;
							var b_month = json.result.b_month;
							var b_text = json.result.b_text;
							$(".cal-body").html(html);
							$('.cal-sel-text').html(text);
							$('.cal-sel-left').attr('data-month',b_month);
							$('.cal-sel-left').attr('data-text',b_text);
							$('.cal-sel-right').attr('data-month',a_month);
							$('.cal-sel-right').attr('data-text',a_text);
						}else{
        					//alert(msg);
						}
        			}
        		},
        	   	error:function (XMLHttpRequest, textStatus, errorThrown) {
        	    	if ("timeout" == textStatus) {
        	   			alert(WEBLANG.msgTimeout);
        	   		}
        	   	}
        	});
			$this.attr('disable',0);	
			
		});

	}
	WEB.Calendar();

})(jQuery);
