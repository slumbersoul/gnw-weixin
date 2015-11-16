(function($) {
	WEB = {
		//是否已经登录
		Globe_Check_Login:function() {
			if (WEBPROFILE.userid == ''||WEBPROFILE.userid == null) {
				 //弹出登录框
                WEB.user_handsome_login_init();
                WEB.user_handsome_login();
				return false;
			}
			return true;
		},
		/*
		 * 绑定Ctrl+Enter提交事件
		 * create : slumber 2011-10-09
		 * last-modify: slumber 2011-10-09
		 * params: 1、$input_text:jQuery对象，输入框
		 *  	   2、$sub_obj:jQuery对象，提交按钮
		 *         3、need_focus: string, need_focus || not_need_focus
		*/
		Globe_Bind_Keybord_Submit:function($input_text, $sub_obj, need_focus){
			need_focus = need_focus || 'need_focus'; 
			if(need_focus == 'need_focus'){
				$input_text.focus(function(){
					$("body").unbind('keydown');
					$("body").bind('keydown',function(event) {
						if(event.ctrlKey && event.keyCode == 13){
							$sub_obj.click();
						} 
					});
				});
				
				$input_text.blur(function(){
					$("body").unbind('keydown');
				});
			} 
			
			if(need_focus == 'not_need_focus'){
				$(document).bind('keydown',function(event) {
					if(event.ctrlKey && event.keyCode == 13){
						$sub_obj.click();
						$("body").unbind('keydown');
					} 
				});
			}
			
		},

		//输入框点击提示文字消失效果 需加属性 def-v
		Globe_Input_Text_Hide:function(jqobj) {
			jqobj.focus(function() {
				var self = $(this);
				if($.trim(self.val()) == $.trim(self.attr('def-v'))){
					self.val("");
				}
				self.css("color","#000");
			});
			jqobj.blur(function() {
				var self = $(this);
				if($.trim(self.val()) == ""){
					self.val(self.attr('def-v'));
					self.css("color","#ccc");
				}
			});
		}
	};
 	WEBTOOL = {
		trim:function(str) {
			var t = str.replace(/(^\s*)|(\s*$)/g, "");   
	    	return t.replace(/(^　*)|(　*$)/g, ""); 
		},
		empty:function(v) {
			return ( undefined === v || null === v || "" === v );
		}
	};
	WEBLANG = {
		msgTimeout:'网络链接超时！',
		msgNologin:'您未登录！'
	};

	WEBUI = {
		getTemplate : function(template, data){
			try{
				return doT.template(template)(data);
			}catch(err){
				return err;
			}
		},
		getdoT : function(){
			return doT;
		}
	};

})(jQuery);
