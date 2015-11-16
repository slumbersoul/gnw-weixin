<div id="header">
	<div class="header-wrap">
		<ul class="header-top">
			<li class="stext">
				<a href="/register" target="_self">注册</a>
			</li>
			<li class="stext">
				<a href="/login" target="_self" class="c-green">登录</a>
			</li>
			<li class="stext wline last">
				<a href="/home" target="_blank">我的光魔</a>
			</li>
		</ul>
		<div class="header-mid clearfix">
			<a href="<?php echo $GLOBALS['HOST'];?>" class="logo" title="光魔网首页">光魔网</a>
		</div>
	</div>
	<div class="header-nav">
		<div class="nav-wrap">
			<div class="nav-list-wrap clearfix">
				<ul class="nav-list">
					<li><a <?php if($controller == 'index'){?>class="on"<?php }?> href="/">首页</a></li>
					<li><a <?php if($controller == 'film'){?>class="on"<?php }?> href="/film">影视交易</a></li>
					<li><a <?php if($controller == 'novel'){?>class="on"<?php }?> href="/novel">文学版权</a></li>
					<li><a <?php if($controller == 'site'){?>class="on"<?php }?> href="/site">拍摄场地</a></li>
					<li><a <?php if($controller == 'equipment'){?>class="on"<?php }?> href="/equipment">器材道具</a></li>
					<?php /**
					<li><a <?php if($controller == 'service'){?>class="on"<?php }?> href="/service">影视服务</a></li>
					*/ ?>
					<li><a <?php if($controller == 'company'){?>class="on"<?php }?> href="/company">企业库</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>


