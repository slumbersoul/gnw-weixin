/*
* 弹出层
* @author slumber 2011-07-18
*
*/
(function($) {
	
	WEBLightBox = function(options) {
		var lightbox = this;
        
		var defaults = {
			title : '', //弹出层标题
            lightBoxId : '', //弹出层的id
            ajax : false, //默认没有ajax请求
            contentHtml : '', //没有ajax请求时, 直接输出内容
            scroll : false, //默认不随页面滚动
            isBgClickClose : true, //点击覆盖层时是否关闭, 默认为true
        	closeCallBack : function(){}//关闭按钮时的回调方法
		};
        
        var _ajaxTimer = null;
        var _lightBoxFrame = '<div id="{id}" class="light_box"><iframe frameborder="0" scrolling="no" class="lb_fix"></iframe>{content}</div>';
		var _lightBoxContent = '<table class="lb_wrap clearfix r5"><tbody><tr><td><div class="lb_hd">{title}<a href="javascript:;" class="lb_close">×</a></div><div class="lb_bd">{body}</div></td></tr></tbody></table>';
		var _lightBoxBackground = '<div class="light_box_fullbg"></div>';
		
        var _lightBoxLoading = '<table class="lb_info r5"><tbody><tr><td><div class="lb_l">{text}......（<a class="lb_cs" href="javascript:;">取消</a>）</div></td></tr></tbody></table>';
        var _lightBoxSuccess = '<table class="lb_info r5"><tbody><tr><td><div class="lb_s">{text}</div></td></tr></tbody></table>';
        var _lightBoxFailure = '<table class="lb_info r5"><tbody><tr><td><div class="lb_f">{text}</div></td></tr></tbody></table>';

        var _options = $.extend(defaults, options);

        var _lb = null;
        var _lb_fix = null;
        var _lb_bg = null;

        var _hasBindScrollForIE6 = false;

        var _getTop = function() {
            return (document.documentElement.scrollTop || document.body.scrollTop) + ((document.documentElement.clientHeight || document.body.clientHeight) - _lb.height())/2;
        };

        lightbox.getBoxFrame = function() {
            return _lb;
        };

        lightbox.getFrameId = function() {
            return _options.lightBoxId;
        };

        lightbox.getBackground = function() {
            return _lb_bg;
        };
        
        lightbox.close = function() {
            _ajaxTimer&&_ajaxTimer.abort();
            _lb.hide();
			_options.closeCallBack();
			_lb.remove();
        	if($('.light_box').size() == 0){
				_lb_bg.remove();	
			}
			 $("body").unbind('keydown');
		};

		lightbox.hide = function() {
			_lb.hide();
			_lb_bg.hide();
			$("body").unbind('keydown');
		};

		lightbox.show = function() {
			_lb.show();
			_lb_bg.show();
		};

        //resize 
        lightbox.resize = function(){
            var win_w = $(window).width();
            var left = (win_w - _lb.width()) / 2;
            var top = _getTop();
            
            _lb_fix.css({'width':_lb.width(),'height':_lb.height()});
            if ($.browser.msie && $.browser.version == "6.0") {
                _lb_bg.css('height',(document.documentElement.clientHeight || document.body.clientHeight));
            };

            if(_options.scroll){
                if ($.browser.msie && $.browser.version == "6.0") {
                    _lb.css({'left':left,'top':top}).show();
                    if (!_hasBindScrollForIE6) {
                        $(window).scroll(function() {
                            var st = _getTop();
                            _lb.css('top',st);
                        });
                    };                    
                } else {
                    top = ((document.documentElement.clientHeight || document.body.clientHeight) - _lb.height())/2;
                    _lb.css({'left':left,'top':top,'position':'fixed'}).show();;
                }

            } else {
                _lb.css({'left':left,'top':top}).show();
            }
        };
        
        //初始化
        lightbox.init = function() {
            if (_options.lightBoxId == '') {
                return;
            };
			var html = _lightBoxFrame.replace(/{id}/g,_options.lightBoxId).replace(/{content}/,_lightBoxContent);
            
			if (_options.title && _options.title != '') {
				html = html.replace(/{title}/g,'<span class="lb_title">'+_options.title+'</span>');		
			}

			if(_options.ajax){//初始化有ajax请求
                html = html.replace(/{body}/g,'');
            } else {//没有ajax请求
                html = html.replace(/{body}/g,_options.contentHtml);
            }
            
            if($('.light_box_fullbg').size() == 0){
				$("body").append(html+_lightBoxBackground);
			}else{
				 $("body").append(html);
			}
            
            _lb = $('#'+_options.lightBoxId);
            _lb_fix = $('.lb_fix');
            _lb_bg = $('.light_box_fullbg');

            if(_options.ajax){//初始化有ajax请求
                lightbox.loading();
            }else{
            	lightbox.resize();

            	$(window).resize(function(){
                	lightbox.resize();
            	});
            	_lb.find('.lb_close').click(function() {
					lightbox.close();	
				})
			}
            
           	if(_options.isBgClickClose){
         		$('.light_box_fullbg').click(function() {
					lightbox.close();	 
				})
			}
                    
        };
        
        //成功后淡出
        lightbox.fadeout = function(){
            _ajaxTimer&&_ajaxTimer.abort();
        	_lb.fadeOut(500);
            _lb_bg.fadeOut(500, function(){lightbox.close();});
        };

        //开始请求后台数据
        lightbox.startAjax = function(ajax) {
            _ajaxTimer = ajax;
        };

        //填充内容
        lightbox.buildContent = function(content){
            if (_lb.find('.lb_wrap').size() == 0) {
				html = _lightBoxContent.replace(/{body}/,content);
				if (_options.title && _options.title != '') {
					html = html.replace(/{title}/g,'<span class="lb_title">'+_options.title+'</span>');	
				}
				if ( _options.titleLinkText != '' ) {
					 html = html.replace(/{title_link}/g,'<span class="lb_lnk">（<a href="'+_options.titleLink+'" target="_blank">'+_options.titleLinkText+'</a>）</span>');		 
				}else {
					html = html.replace(/{title_link}/g,'');	
				}
				_lb.find('.lb_info').after(html).remove();
				$('#'+_options.lightBoxId+' .lb_close').click(function() {
					lightbox.close();	
				});
			}else {
				_lb.find('.lb_bd').html(content);	
			}
			lightbox.resize();

        };
        
        //表单提交成功
        lightbox.success = function(text){
            var success_html = _lightBoxSuccess.replace(/{text}/,text);
             _lb.find('.lb_wrap').after(success_html).remove();
			lightbox.resize();
            setTimeout(function(){lightbox.fadeout();},1000);
        };
		
		//成功后停留一段时间，马上消失
		lightbox.success_close = function(text, time){
            var success_html = _lightBoxSuccess.replace(/{text}/,text);
             _lb.find('.lb_wrap').after(success_html).remove();
			lightbox.resize();
			var time = time || 1000;
            setTimeout(function(){lightbox.close();},time);
        };

        //失败
        lightbox.fail = function(text, time){
            var failure_html = _lightBoxFailure.replace(/{text}/,text);
            _lb.find('.lb_wrap').after(failure_html).remove();
			lightbox.resize();
            var time = time || 2000;
            setTimeout(function(){lightbox.close();},time);
        };
		
		//提交的时候ajax loading
		lightbox.loading = function(text){
            text = text || '请稍后';
            var loading_html = _lightBoxLoading.replace(/{text}/,text);
			_lb.find('.lb_wrap').after(loading_html).remove();
			lightbox.resize();
			_lb.find('.lb_l .lb_cs').click(function() {
				lightbox.close();	
			})
			 lightbox.resize();
		}
	};
})(jQuery);
