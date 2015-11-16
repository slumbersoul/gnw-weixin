(function($) {
	WEB.Globe_Search_Act = function(){
		WEB.Globe_Input_Text_Hide($('#nav-search-input'));
		
		$('#nav-search-box .selectbox').on('hover',function(){
			var $this = $(this);
			if($this.hasClass('sele-hover')){
				$this.removeClass('sele-hover');
			}else{
				$this.addClass('sele-hover');
			}
		});
		
		$("#nav-search-box .selectbox li").on('click',function(){
			var $this = $(this);
			var $stext = $this.attr('data-t');
			$("#nav-search-box .selectbox li").removeClass("current");
			$this.addClass("current");
			$("#nav-search-box .selectbox .selected").html($stext);
			$("#nav-search-form").attr('action',$this.attr('data-hf'));
		});
	}
	WEB.Globe_Search_Act();
})(jQuery);
