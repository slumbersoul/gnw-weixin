<div id="index-wrap" class="clearfix">
	<div class="index-top">
		<div class="index-top-container">
			 <div class="top-navs fl">
				<div class="nav-wrap">
					<div class="primary-nav">
						<div class="nav-line">
							<a class="nav-parent" target="_blank" href="/film">影视交易</a>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/film/more?category=50002">电视剧</a>
								<a class="nav-cld" target="_blank" href="/film/more?category=50001">电影</a>
								<a class="nav-cld" target="_blank" href="/film/more?category=50008">动漫</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/film/more?category=50009">纪录片</a>
								<a class="nav-cld" target="_blank" href="/film/more?category=50007">综艺</a>
							</div>
						</div>
					</div>
					<div class="primary-nav">
						<div class="nav-line" style="border-bottom:none;">
							<a class="nav-parent" target="_blank" href="/novel">文学版权</a>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/novel/more?pcategory=50111">小说</a>
								<a class="nav-cld" target="_blank" href="/novel/more?pcategory=50112">剧本</a>
								<a class="nav-cld" target="_blank" href="/novel/more?pcategory=50114">人文社科</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/novel/more?pcategory=50113">漫画</a>
								<a class="nav-cld" target="_blank" href="/novel">公版版权</a>
							</div>
						</div>
					</div>
					<div class="primary-nav">
						<div class="nav-line">
							<a class="nav-parent" target="_blank" href="/site">拍摄场地</a>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/site/more?category=50012">室内场地</a>
								<a class="nav-cld" target="_blank" href="/site/more?category=50010">影视城</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/site/more?category=50013">虚拟影棚</a>
								<a class="nav-cld" target="_blank" href="/site/more?category=50011">外景地</a>
							</div>
						</div>
					</div>
					<div class="primary-nav">
						<div class="nav-line" style="border-bottom:none;">
							<a class="nav-parent" target="_blank" href="/equipment">器材道具</a>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/equipment?category=50016">辅助设备</a>
								<a class="nav-cld" target="_blank" href="/equipment?category=50016&child_category=50029">摇臂</a>
								<a class="nav-cld" target="_blank" href="/equipment?category=50015&child_category=50027">航拍机</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/equipment?category=50019">灯光录音</a>
								<a class="nav-cld" target="_blank" href="/equipment?category=50015&child_category=50024">数码电影摄影机</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/equipment?category=50015">摄影设备</a>
								<a class="nav-cld" target="_blank" href="/equipment?category=50016&child_category=50031">滑动轨道</a>
							</div>
						</div>
					</div>
					<?php /** ?>
					<div class="primary-nav">
						<div class="nav-line" style="border-bottom:none;">
							<a class="nav-parent" target="_blank" href="/service">影视服务</a>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/service/more?pcategory=30004">前期创意</a>
								<a class="nav-cld" target="_blank" href="/service/more?pcategory=30005">宣传发行</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/service/more?pcategory=30006">拍摄制作</a>
								<a class="nav-cld" target="_blank" href="/service/more?pcategory=30007">后期制作</a>
							</div>
							<div class="nav-cld-line">
								<a class="nav-cld" target="_blank" href="/service/more?pcategory=30008">剧组服务</a>
							</div>
						</div>
					</div>
					<?php */ ?>
				</div>
			 </div>
			 <div class="top-ps fl">
			 	<div id="film-ps-w" class="film-ps-w">
			  		<ul class="film-ps">
			   		<?php foreach($himgs as $k => $v ){ ?>
				 		<li class="film-ps-f">
				  			<a target="_blank" href="<?php echo $v['link_url']; ?>"><img src="<?php echo $v['img_url']; ?>"></a>
				  		</li>
				 	<?php } ?>
					</ul>
				</div>
				<div id="film-ps-btn" class="film-ps-btn">
					<ul>
					<?php $himg_count = count($himgs); for($i=1;$i<=$himg_count;$i++){ ?>
						<li class="<?php if($i == 1){echo 'c';} ?>"><?php echo $i;?></li>
					<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="index-content">
		<div class="content-detail clearfix film">
			<div class="detail-head-line">
				<div class="head-title">热门影视剧交易</div>
				<a href="/film" class="head-more" target="_blank">更多</a>
			</div>
			<div class="detail-con-line clearfix">
				<div class="con-line-top">
					<div class="con-line-left">
						<a href="<?php echo $film_items[0]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[0]['img_url'];?>"></a>	
						<div class="de-title-mask"></div>
						<?php /**
						<div class="de-title"><?php echo $film_items[0]['title']; ?>&nbsp;&nbsp;<?php echo $film_items[0]['price']; ?></div>
						*/ ?>
						<div class="de-title"><?php echo $film_items[0]['title']; ?></div>
					</div>
					<div class="con-line-mid">
						<div class="mid-line">
							<div class="mid-line-block">
								<a href="<?php echo $film_items[1]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[1]['img_url'];?>"></a>	
								<div class="de-title-mask"></div>
								<?php /**
								<div class="de-title"><?php echo $film_items[1]['title']; ?>&nbsp;&nbsp;<?php echo $film_items[1]['price']; ?></div>
								*/ ?>
								<div class="de-title"><?php echo $film_items[1]['title']; ?></div>
							</div>
							<div class="mid-line-block last">
								<a href="<?php echo $film_items[2]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[2]['img_url'];?>"></a>	
								<div class="de-title-mask"></div>
								<?php /**
								<div class="de-title"><?php echo $film_items[2]['title']; ?>&nbsp;&nbsp;<?php echo $film_items[2]['price']; ?></div>
								*/ ?>
								<div class="de-title"><?php echo $film_items[2]['title']; ?></div>
							</div>
						</div>
						<div class="mid-line last">
							<div class="mid-line-block">
								<a href="<?php echo $film_items[3]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[3]['img_url'];?>"></a>	
								<div class="de-title-mask"></div>
								<?php /**
								<div class="de-title"><?php echo $film_items[3]['title']; ?>&nbsp;&nbsp;<?php echo $film_items[3]['price']; ?></div>
								*/ ?>
								<div class="de-title"><?php echo $film_items[3]['title']; ?></div>
							</div>
							<div class="mid-line-block last">
								<a href="<?php echo $film_items[4]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[4]['img_url'];?>"></a>	
								<div class="de-title-mask"></div>
								<?php /**
								<div class="de-title"><?php echo $film_items[4]['title']; ?>&nbsp;&nbsp;<?php echo $film_items[4]['price']; ?></div>
								*/ ?>
								<div class="de-title"><?php echo $film_items[4]['title']; ?></div>
							</div>
						</div>
					</div>
				</div>
				<div class="con-line-bottom">
					<div class="con-line-left">
						<a href="<?php echo $film_items[6]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[6]['img_url'];?>"></a>	
					</div>
					<div class="con-line-mid">
						<div class="mid-line" style="margin-bottom:0;">
							<div class="mid-line-block last">
								<a href="<?php echo $film_items[5]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[5]['img_url'];?>"></a>	
								<div class="de-title-mask"></div>
								<?php /**
								<div class="de-title"><?php echo $film_items[5]['title']; ?>&nbsp;&nbsp;<?php echo $film_items[5]['price']; ?></div>
								*/ ?>
								<div class="de-title"><?php echo $film_items[5]['title']; ?></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="detail-con-ftop">
				<div class="ftop-title">热门影视剧排行</div>
				<div class="ftop-detail fl">
				<?php foreach($tops as $k => $top){?>
				<?php if($k == 5) { ?>
				</div>
				<div class="ftop-detail fl">
				<?php } ?>
					<div class="ftop-dline">
						<span class="topnum<?php if($k <3){echo ' topred';}?>"><?php echo $top['wp_id'];?></span>
						<a class="toptitle" href="<?php echo $top['top_url'];?>"><?php echo $top['top_title'];?></a>	
					</div>
				<?php } ?>
				</div>
			</div>
			<div class="detail-con-ab">
				<a href="<?php echo $film_items[7]['link_url'];?>" target="_blank"><img src="<?php echo $film_items[7]['img_url'];?>"></a>	
			</div>
		</div>
		<?php /**
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">拍摄场地</div>
				<a href="/site" class="head-more" target="_blank">更多</a>
			</div>
			<div class="bd">
				<div class="entrance">
					<dl>
						<dt>按地区划分</dt>
						<dd class="clearfix">
							<span class="entrance-item"><a href="/site/more?loc_province=31" target="_blank">浙江</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=3409" target="_blank">北京</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=25" target="_blank">上海</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=6" target="_blank">广东</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=4" target="_blank">福建</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=31" target="_blank">浙江</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=3409" target="_blank">北京</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=25" target="_blank">上海</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=6" target="_blank">广东</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=4" target="_blank">福建</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=31" target="_blank">浙江</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=3409" target="_blank">北京</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=25" target="_blank">上海</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=6" target="_blank">广东</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=4" target="_blank">福建</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=31" target="_blank">浙江</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=3409" target="_blank">北京</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=25" target="_blank">上海</a></span>
							<span class="entrance-item"><a href="/site/more?loc_province=6" target="_blank">广东</a></span>
						</dd>
					</dl>
					<dl>
						<dt>按年代分</dt>
						<dd class="clearfix">
							<span class="entrance-item"><a href="/site/more?category=50010&search=0_春秋战国" target="_blank">春秋战国</a></span>
							<span class="entrance-item"><a href="/site/more?category=50010&search=0_秦汉" target="_blank">秦汉</a></span>
							<span class="entrance-item"><a href="/site/more?category=50010&search=0_唐宋" target="_blank">唐宋</a></span>
							<span class="entrance-item"><a href="/site/more?category=50010&search=0_元明清" target="_blank">元明清</a></span>
							<span class="entrance-item"><a href="/site/more?category=50010&search=0_民国" target="_blank">民国</a></span>
							<span class="entrance-item"><a href="/site/more?category=50010&search=0_现代" target="_blank">现代</a></span>
						</dd>
					</dl>
					<dl>
						<dt>热门分类</dt>
						<dd class="clearfix">
							<span class="entrance-item"><a href="/site/more?category=50010" target="_blank">影视城</a></span>
							<span class="entrance-item"><a href="/site/more?category=50011" target="_blank">外景地</a></span>
							<span class="entrance-item"><a href="/site/more?category=50012" target="_blank">室内场地</a></span>
							<span class="entrance-item"><a href="/site/more?category=50013" target="_blank">虚拟影棚</a></span>
						</dd>
					</dl>
				</div>
				<div class="product">
					<div class="product-bd clearfix">
						<div class="tab-pannel">
							<div class="product-list">
								<div class="product-item">
									<div class="product-item-img">	
										<a href="<?php echo $site_items[0]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[0]['img_url'];?>"></a>
										<div class="product-title-mask"></div>
										<div class="product-title"><?php echo $site_items[0]['title']; ?>&nbsp;&nbsp;<?php echo $site_items[0]['price']; ?></div>
									</div>
								</div>
								<div class="product-item">
									<div class="product-item-img">	
										<a href="<?php echo $site_items[1]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[1]['img_url'];?>"></a>
										<div class="product-title-mask"></div>
										<div class="product-title"><?php echo $site_items[1]['title']; ?>&nbsp;&nbsp;<?php echo $site_items[1]['price']; ?></div>
									</div>
								</div>
								<div class="product-item">	
									<div class="product-item-img">	
										<a href="<?php echo $site_items[2]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[2]['img_url'];?>"></a>
										<div class="product-title-mask"></div>
										<div class="product-title"><?php echo $site_items[2]['title']; ?>&nbsp;&nbsp;<?php echo $site_items[2]['price']; ?></div>
									</div>
								</div>
								<div class="product-item">	
									<div class="product-item-img">	
										<a href="<?php echo $site_items[3]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[3]['img_url'];?>"></a>
										<div class="product-title-mask"></div>
										<div class="product-title"><?php echo $site_items[3]['title']; ?>&nbsp;&nbsp;<?php echo $site_items[3]['price']; ?></div>
									</div>
								</div>
								<div class="product-item">	
									<div class="product-item-img">	
										<a href="<?php echo $site_items[4]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[4]['img_url'];?>"></a>
										<div class="product-title-mask"></div>
										<div class="product-title"><?php echo $site_items[4]['title']; ?>&nbsp;&nbsp;<?php echo $site_items[4]['price']; ?></div>
									</div>
								</div>
								<div class="product-item">	
									<div class="product-item-img">	
										<a href="<?php echo $site_items[5]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[5]['img_url'];?>"></a>
										<div class="product-title-mask"></div>
										<div class="product-title"><?php echo $site_items[5]['title']; ?>&nbsp;&nbsp;<?php echo $site_items[5]['price']; ?></div>
									</div>
								</div>
								<div class="extra">
									<a href="<?php echo $site_items[6]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[6]['img_url'];?>"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		*/ ?>
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">小说版权</div>
				<a href="/novel" class="head-more" target="_blank">更多</a>
			</div>
			<div class="nv-wrap" style="margin-bottom:0;">
				<div class="nv-row clearfix">
					<a href="<?php echo $novel_items[0]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[0]['img_url'];?>" width="180" height="265">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[0]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[1]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[1]['img_url'];?>" width="180" height="265">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[1]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[2]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[2]['img_url'];?>" width="180" height="265">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[2]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[3]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[3]['img_url'];?>" width="180" height="265">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[3]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[4]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[4]['img_url'];?>" width="180" height="265">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[4]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[5]['link_url'];?>" class="nv-feed last-feed" target="_blank">
						<img src="<?php echo $novel_items[5]['img_url'];?>" width="180" height="265">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[5]['title'];?></p>
						</div>
					</a>
				</div>
			</div>
		</div>
			<?php /** ?>
			<div class="nv-wrap">
				<div class="nv-row clearfix">
					<a href="<?php echo $novel_items[0]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[0]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[0]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[1]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[1]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[1]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[2]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[2]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[2]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[3]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[3]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[3]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[4]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[4]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[4]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[5]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[5]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[5]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[6]['link_url'];?>" class="nv-feed last-feed" target="_blank">
						<img src="<?php echo $novel_items[6]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[6]['title'];?></p>
						</div>
					</a>
				</div>
				<div class="nv-row clearfix" style="margin-bottom:0;">
					<a href="<?php echo $novel_items[7]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[7]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[7]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[8]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[8]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[8]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[9]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[9]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[9]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[10]['link_url'];?>" class="nv-feed" target="_blank">
						<img src="<?php echo $novel_items[10]['img_url'];?>" width="156" height="208">
						<div class="nv-feed-mask"></div>
						<div class="nv-feed-text">
							<p class="nv-feed-title"><?php echo $novel_items[10]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $novel_items[11]['link_url'];?>" class="nv-feed nv-feed-w feed-last" target="_blank">
						<img src="<?php echo $novel_items[11]['img_url'];?>" width="504" height="208">
					</a>
				</div>
			</div>
		</div>
		<?php */?>
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">拍摄场地</div>
				<a href="/site" class="head-more" target="_blank">更多</a>
			</div>
			<div class="new-bd-sss">
				<div class="w-sss-lev1">
					<div class="w-sss-biao">
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">皇宫</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">宫殿衙署</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">餐厅咖啡厅</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">医院</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">舞厅酒馆</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">法院</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">农家小院</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">江南古镇</a>
					</div>
					<div class="w-sss-img">
						<a href="<?php echo $site_items[7]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[7]['img_url'];?>" width="200" height="268"></a>
					</div>
				</div>
				<div class="w-sss-lev2" style="width:600px;">
					<a href="<?php echo $site_items[1]['link_url'];?>" class="w-lev2-lit row1" target="_blank">
						<img src="<?php echo $site_items[1]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $site_items[1]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $site_items[2]['link_url'];?>" class="w-lev2-lit row1" target="_blank">
						<img src="<?php echo $site_items[2]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $site_items[2]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $site_items[3]['link_url'];?>" class="w-lev2-lit row1" target="_blank">
						<img src="<?php echo $site_items[3]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $site_items[3]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $site_items[4]['link_url'];?>" class="w-lev2-lit row2" target="_blank">
						<img src="<?php echo $site_items[4]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $site_items[4]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $site_items[5]['link_url'];?>" class="w-lev2-lit row2" target="_blank">
						<img src="<?php echo $site_items[5]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $site_items[5]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $site_items[6]['link_url'];?>" class="w-lev2-lit row2" target="_blank">
						<img src="<?php echo $site_items[6]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $site_items[6]['title'];?></p>
						</div>
					</a>
				</div>
				<div class="w-sss-lev2" style="width:400px;">
					<a href="<?php echo $site_items[0]['link_url'];?>" class="w-sss-bigb" target="_blank"><img src="<?php echo $site_items[0]['img_url'];?>" width="390" height="368"></a>
				</div>
			</div>
		</div>
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">器材道具</div>
				<a href="/equipment" class="head-more" target="_blank">更多</a>
			</div>
			<div class="new-bd-sss">
				<div class="w-sss-lev1">
					<div class="w-sss-biao">
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">单反照相机</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">三脚架</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">发电车</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">相机镜头</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">低色温照明灯</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">反光板</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">摇臂</a>
						<a class="link-tag" href="/equipment?category=50015&child_category=50025" target="_blank">汽车</a>
					</div>
					<div class="w-sss-img">
						<a href="<?php echo $equip_items[7]['link_url'];?>"><img src="<?php echo $equip_items[7]['img_url'];?>" width="200" height="268"></a>
					</div>
				</div>
				<div class="w-sss-lev2">
					<a href="<?php echo $equip_items[0]['link_url'];?>" class="w-sss-bigb" target="_blank"><img src="<?php echo $equip_items[0]['img_url'];?>" width="390" height="368"></a>
					<a href="<?php echo $equip_items[1]['link_url'];?>" class="w-lev2-lit row1" target="_blank">
						<img src="<?php echo $equip_items[1]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $equip_items[1]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $equip_items[2]['link_url'];?>" class="w-lev2-lit row1" target="_blank">
						<img src="<?php echo $equip_items[2]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $equip_items[2]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $equip_items[3]['link_url'];?>" class="w-lev2-lit row1" target="_blank">
						<img src="<?php echo $equip_items[3]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $equip_items[3]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $equip_items[4]['link_url'];?>" class="w-lev2-lit row2" target="_blank">
						<img src="<?php echo $equip_items[4]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $equip_items[4]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $equip_items[5]['link_url'];?>" class="w-lev2-lit row2" target="_blank">
						<img src="<?php echo $equip_items[5]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $equip_items[5]['title'];?></p>
						</div>
					</a>
					<a href="<?php echo $equip_items[6]['link_url'];?>" class="w-lev2-lit row2" target="_blank">
						<img src="<?php echo $equip_items[6]['img_url'];?>" width="190" height="179">
						<div class="w-lev2-litB-mask"></div>
						<div class="w-lev2-litB">
							<p class="w-lev2-litP1"><?php echo $equip_items[6]['title'];?></p>
						</div>
					</a>
				</div>
			</div>
		</div>
		<?php /**
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">拍摄场地</div>
				<a href="/site" class="head-more" target="_blank">更多</a>
			</div>
			<div class="detail-con-line clearfix">
				<div class="con-line-left">
					<a href="<?php echo $site_items[0]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[0]['img_url'];?>"></a>	
					<div class="item-desc">
					</div>
					<div class="de-title"><?php echo $site_items[0]['title']; ?></div>
					<div class="de-price"><?php echo $site_items[0]['price']; ?></div>
				</div>
				<div class="con-line-mid">
					<div class="mid-line">
						<div class="mid-line-block">
							<a href="<?php echo $site_items[1]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[1]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $site_items[1]['title']; ?></div>
							<div class="de-price"><?php echo $site_items[1]['price']; ?></div>
						</div>
						<div class="mid-line-block last">
							<a href="<?php echo $site_items[2]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[2]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $site_items[2]['title']; ?></div>
							<div class="de-price"><?php echo $site_items[2]['price']; ?></div>
						</div>
					</div>
					<div class="mid-line last">
						<div class="mid-line-block">
							<a href="<?php echo $site_items[3]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[3]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $site_items[3]['title']; ?></div>
							<div class="de-price"><?php echo $site_items[3]['price']; ?></div>
						</div>
						<div class="mid-line-block last">
							<a href="<?php echo $site_items[4]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[4]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $site_items[4]['title']; ?></div>
							<div class="de-price"><?php echo $site_items[4]['price']; ?></div>
						</div>
					</div>
				</div>
				<div class="con-line-right">
					<div class="right-line-block">
						<a href="<?php echo $site_items[5]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[5]['img_url'];?>"></a>	
						<div class="item-desc"></div>
						<div class="de-title"><?php echo $site_items[5]['title']; ?></div>
						<div class="de-price"><?php echo $site_items[5]['price']; ?></div>
					</div>
					<div class="right-line-block last">
						<a href="<?php echo $site_items[6]['link_url'];?>" target="_blank"><img src="<?php echo $site_items[6]['img_url'];?>"></a>	
						<div class="item-desc"></div>
						<div class="de-title"><?php echo $site_items[6]['title']; ?></div>
						<div class="de-price"><?php echo $site_items[6]['price']; ?></div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">器材道具</div>
				<a href="/equipment" class="head-more" target="_blank">更多</a>
			</div>
			<div class="detail-con-line clearfix">
				<div class="con-line-left">
					<a href="<?php echo $equip_items[0]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[0]['img_url'];?>"></a>	
					<div class="item-desc">
					</div>
					<div class="de-title"><?php echo $equip_items[0]['title']; ?></div>
					<div class="de-price"><?php echo $equip_items[0]['price']; ?></div>
				</div>
				<div class="con-line-mid">
					<div class="mid-line">
						<div class="mid-line-block">
							<a href="<?php echo $equip_items[1]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[1]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $equip_items[1]['title']; ?></div>
							<div class="de-price"><?php echo $equip_items[1]['price']; ?></div>
						</div>
						<div class="mid-line-block last">
							<a href="<?php echo $equip_items[2]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[2]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $equip_items[2]['title']; ?></div>
							<div class="de-price"><?php echo $equip_items[2]['price']; ?></div>
						</div>
					</div>
					<div class="mid-line last">
						<div class="mid-line-block">
							<a href="<?php echo $equip_items[3]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[3]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $equip_items[3]['title']; ?></div>
							<div class="de-price"><?php echo $equip_items[3]['price']; ?></div>
						</div>
						<div class="mid-line-block last">
							<a href="<?php echo $equip_items[4]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[4]['img_url'];?>"></a>	
							<div class="item-desc"></div>
							<div class="de-title"><?php echo $equip_items[4]['title']; ?></div>
							<div class="de-price"><?php echo $equip_items[4]['price']; ?></div>
						</div>
					</div>
				</div>
				<div class="con-line-right">
					<div class="right-line-block">
						<a href="<?php echo $equip_items[5]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[5]['img_url'];?>"></a>	
						<div class="item-desc"></div>
						<div class="de-title"><?php echo $equip_items[5]['title']; ?></div>
						<div class="de-price"><?php echo $equip_items[5]['price']; ?></div>
					</div>
					<div class="right-line-block last">
						<a href="<?php echo $equip_items[6]['link_url'];?>" target="_blank"><img src="<?php echo $equip_items[6]['img_url'];?>"></a>	
						<div class="item-desc"></div>
						<div class="de-title"><?php echo $equip_items[6]['title']; ?></div>
						<div class="de-price"><?php echo $equip_items[6]['price']; ?></div>
					</div>
				</div>
			</div>
		</div>
		*/ ?>
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">影视服务</div>
				<a href="/service" class="head-more" target="_blank">更多</a>
			</div>
			<div class="detail-con-line clearfix">
				<img class="fl" style="width:1200px;" src="http://img.igmoo.cn/20150314/41281_944_1426326771.jpg">
			</div>
		</div>
		<?php /** 
		<div class="content-detail clearfix">
			<div class="detail-head-line">
				<div class="head-title">合作伙伴</div>
				<a href="/company" class="head-more" target="_blank">更多</a>
			</div>
			<div class="detail-con-line clearfix">
				<img src="<?php $cimg = current($company_items); echo $cimg['img_url'];?>">
			</div>
		</div>
		*/ ?>
	</div>
</div>
