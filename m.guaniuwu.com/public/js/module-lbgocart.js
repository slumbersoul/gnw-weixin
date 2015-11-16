/*
* 弹出购物车结算框
* @author slumber 2014-11-28
*
*/

(function($) {

    var Cart_Light_Box = {};

    /*
    * 结算框初始化
    * @author slumber
    */
    WEB.Cart_Box_Init = function(){

        $('.light_box').remove(); 
        var title = ' ';
        var cb_id = 'go-cart-lb';
		
        var lightBoxCartHtml = '<div class="module-cart-box"><p class="mb10"><span class="module-cart-tip">已将商品添加到购物车</span></p><p><a href="/trade/cart/mycart" class="module-cart-buy">去购物车结算</a></p></div>';

        var options = {
            title : title, 
            lightBoxId : cb_id, 
            contentHtml : lightBoxCartHtml, 
        };

        Cart_Light_Box = new WEBLightBox(options);
        Cart_Light_Box.init();
	};

})(jQuery);
