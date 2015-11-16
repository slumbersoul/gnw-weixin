/*
* 弹出登录框
* @author slumber 2011-06-03
*
*/

(function($) {

    var Login_Light_Box = {};

    /*
    * 帅气登录框初始化
    * @fix slumber 添加登录框标题自定义
    */
    WEB.user_handsome_login_init = function(title){

        $('.light_box').remove(); 
        title = title || '用户登录';
        var lb_id = 'lb_login';
		
		$.ajax({
			url: "/user/recaptcha", 
			type: "POST",
			timeout:60000,
			async:false,
			data:"{}",
			dataType: 'json', 
			success: function(json){
				if (json == null) {
					alert(WEBLANG.msgTimeout);
				} else {
					var code = json.status.code;
					var msg = json.status.msg;
					if(code == 1001) {
						captcha = json.result.data.captchaSrc;
					}else{
						alert(msg);
					}
				}			
			},
	   		error:function (XMLHttpRequest, textStatus, errorThrown) {
			    if ("timeout" == textStatus) {
	   				alert(WEBLANG.msgTimeout);
	   			}
	   		}
		});

        var lightBoxLogin = '<div class="login_box clearfix"><p class="login_fail">请输入密码！</p><div class="login_form"><form><dl><dd>手机号码：</dd><dt><input type="text" name="user_phone" class="text"></dt><dd>密　码：</dd><dt class="u_passwd"><input type="password" name="user_pass" class="text"></dt><dd>&nbsp;</dd><dt class="rem"><input type="checkbox" name="remember" class="checkbox" checked="">两周内免登录</dt><dd>&nbsp;</dd><dt><input type="button" value="登录" class="login_btn"><a href="/users/forgetpassword">忘记密码？</a></dt></dl></form></div><div class="reg_or_weibo"><div class="register_box"><span>还没有注册？</span><a href="/register" class="reg_btn">轻松注册</a></div><div class="l_o"><a href="/tencent/login" style="visibility:hidden;" class="signqq">用腾讯帐号登录</a></div></div></div>';

        var options = {
            title : title, 
            lightBoxId : lb_id, 
            contentHtml : lightBoxLogin, 
            scroll : true
        };

        Login_Light_Box = new WEBLightBox(options);
        Login_Light_Box.init();
		if (typeof captcha !== 'undefined') {
			$('.u_passwd').after('<dd class="check_title">验证码：</dd><dt><input type="text" name="check" class="text check_code" maxlength="4" /><img src="'+captcha+'" id="img_checkcode" alt="验证码" /><a href="javascript:void(0);" id="checkcode_change">换一张</a></dt>');	
		}
        
        $('#lb_login input[name=user_phone]').focus();
    };


    /*
    * 帅气的登录弹出框提交
    * @author slumber 2011-04-03
    */
    WEB.user_handsome_login = function(reload, _option){
        var reload = reload || false;
        var lb_id = Login_Light_Box.getFrameId();
    	WEB.Globe_Bind_Keybord_Submit('', $("#"+lb_id+" .login_btn"), 'not_need_focus');


    	$("#"+lb_id+" .login_btn").live('click',function() {
    		var user_phone = $("#lb_login input[name='user_phone']");
    		var name = user_phone.val();
    		if (WEBTOOL.trim(name) == "") {
    			$('#'+lb_id+' .login_fail').text('请输入手机号码！').css('visibility','visible');
    			user_phone.focus();
    			return false;
    		}
    		var user_pass = $("#lb_login input[name='user_pass']");
    		var password = user_pass.val();
    		if (WEBTOOL.trim(password) == "") {
    			$('#'+lb_id+' .login_fail').text('请输入密码！').css('visibility','visible');
    			user_pass.focus();
    			return false;
    		}
    		var twoweeks = $("#"+lb_id+" :checkbox").prop("checked");
    		var data = {user_phone:name,user_pass:password,remember:twoweeks};
			var check = $("#lb_login input[name='check']");
			if(check.size() > 0){
				var check_val = check.val();
				if(check_val === ''){
					$('#'+lb_id+' .login_fail').text('请输入验证码!').css('visibility','visible');
					check.focus();
					return false;
				}else{
					data.check = check.val();
				}
			}

    		$.ajax({
    			url:'/user/zlogin',
    			type: "POST", 
    			timeout:60000,
    			data:data, 
    			dataType: 'json', 
    			beforeSend: function(XMLHttpRequest){
    				//todo
    			},
    			success: function(json){
    				if (json == null) {
    					alert(WEBLANG.msgTimeout);
    				} else {
    					var code = json.status.code;
    					var msg = json.status.msg;
    					if(code == 1001) {
    						//todo
    						WEBPROFILE.userid = json.result.data.userid;
							Login_Light_Box.success_close('登录成功！', 200);
    						location.reload();
    						if(_option.callback){
    							_option.callback();
    						}
    							
    					}else{
    						//用户名密码之类的错误
    						$('#'+lb_id+' .login_fail').text(msg).css('visibility','visible');
    					}
    				}
    			},
    			error:function (XMLHttpRequest, textStatus, errorThrown) {
    				if ("timeout" == textStatus) {
    					alert(WEBLANG.msgTimeout);
    					//todo
    				} 
    			},
    			complete:function(XHR, TS) {
    				//todo
    			}
    		});
    	});
    };
                      
    //多方登录
    WEB.User_Other_login = function(){
        var timer;
        var fix = 15;
        if ($.browser.msie && $.browser.version == '8.0') {
            fix = 25;
        }

        $("#info_bar .more").hover(function() {
            clearTimeout(timer);
            var offset = $(this).find('img').offset();
            $("#login_menu").css({left:offset.left-120,top:offset.top+fix});
            $("#login_menu").slideDown(100);    
        },function() {
            clearTimeout(timer);
            timer = setTimeout(function() {$("#login_menu").slideUp(100)},500);
        });
        $("#login_menu").hover(function() {
            clearTimeout(timer);
            $(this).show();
        },function() {
            clearTimeout(timer);
            timer = setTimeout(function() {$("#login_menu").slideUp(100)},500); 
        });
    };
		
	//换验证码
	WEB.Globe_Checkcode_Change = function() {
		if ($("#checkcode_change").size == 0) { return;};
		$("#checkcode_change").live('click',function() {
			$.ajax({
				url: "/user/recaptcha", 
				type: "POST",
				timeout:60000,
				data:"{}",
				dataType: 'json', 
				success: function(json){
					if (json == null) {
						alert(WEBLANG.msgTimeout);
					} else {
						var code = json.status.code;
						var msg = json.status.msg;
						if(code == 1001) {
							var id = json.result.data.captchaId;
							var src = json.result.data.captchaSrc;
							$("#img_checkcode").attr("src",src);
						}else{
							alert(msg);
						}
					}			
				},
		   		error:function (XMLHttpRequest, textStatus, errorThrown) {
				    if ("timeout" == textStatus) {
		   				alert(WEBLANG.msgTimeout);
		   			}
		   		}
			});
		});
	};
    
	WEB.User_Other_login();
	WEB.Globe_Checkcode_Change();
})(jQuery);
