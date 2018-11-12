<?php
add_action( 'admin_footer', 'tshirtecommerce_introduction', 10 );
function tshirtecommerce_introduction()
{
	$settings 	= get_option('online_designer');
	$screen		= get_current_screen();
	if(empty($settings['url']) && $screen->post_type != 'page' && $screen->post_type != 'product'){
?>
	<style>
	.dg-mask-introduction{position:fixed; width:100%; height:100%; background:#000; top:0px; left:0px; opacity:0.5; z-index:100000;}
	.dg-introduction{position:fixed; top:50px; width:70%; max-width:600px; z-index:100000; margin:auto; left:0px; right:0px; background:#FAFAFA; box-shadow:0 5px 15px rgba(0,0,0,.5); transition:transform .3s ease-out; padding-bottom:15px;}
	.dg-introduction-head{width:100%; text-align:center; padding-top:12px; background:#FAFAFA; border-bottom:1px solid #ccc;}
	.dg-introduction-content{float:left; width:100%; background:#fff; padding-bottom:15px;}
	.carousel.slide{width:100%; position:relative; text-align:center;}
	.carousel-inner{padding:15px;}.carousel-inner .item.active{display:block;}.carousel-inner .item{display:none; text-align:left;}
	.carousel-inner .item h3{font-size:20px; text-align:left;}ol.carousel-indicators{text-align:center; display:inline-block; margin:0px;}
	.carousel-indicators li{width:12px; height:12px; display:inline-block; border:1px solid #ccc; background-color:#fff; border-radius:50%; margin:0 4px; padding:0px; cursor:pointer;}
	.carousel-indicators li.active{background-color:#3C8DBC; border-color:#3C8DBC;}
	.dg-introduction-footer{text-align:center; padding:15px 15px 0 15px; overflow:hidden;}
	.dg-introduction-footer .pull-left{float:left;}
	.dg-introduction-footer .pull-right{float:right;}
	</style>
	<div class="dg-temp-introduction" style="display:none;">
		<div class="dg-introduction-head">
			<img src="https://tshirtecommerce.com/wp-content/uploads/2015/09/logo1.png" alt="tshirtecommerce">
		</div>
		
		<div class="dg-introduction-content">
			<div class="carousel slide" data-ride="carousel">
				 <div class="carousel-inner" role="listbox">
					
					<div class="item active" id="carousel-item0">
						<h3><?php echo get_bloginfo(); ?>, welcome!</h3>
						<div class="carousel-caption">
							<p>Thank you for choosing TShirt eCommerce. This quick setup wizard will help you configure the basic settings.</p>							
							<p>Let's get started!</p>
						</div>
					</div>
					
					<div class="item" id="carousel-item1">
						<h3>Instruction</h3>
						<p>Please read and do step by step to setup and use system:</p>
						<div class="carousel-caption">
							<ol>
								<li><a href="<?php echo admin_url('edit.php?post_type=page'); ?>" target="_blank">Add page designer</a> to show design tool. <a href="http://docs.tshirtecommerce.com/knowledgebase/add-page-designer/" target="_blank">Read More</a></li>
								<li><a href="<?php echo admin_url('post-new.php?post_type=product'); ?>" target="_blank">Add product design</a> in woocommerce. <a href="http://docs.tshirtecommerce.com/knowledgebase/add-new-product-design/" target="_blank">Read More</a></li>
							</ol>
						</div>
					</div>
					
					<div class="item" id="carousel-item2">
						<h3>Support Center</h3>
						<p>If you have any question or problem:</p>
						<div class="carousel-caption">
							<ol>
								<li><a href="http://docs.tshirtecommerce.com/kb/woocommerce-custom-product-designer/" target="_blank">Read document online</a></li>
								<li><a href="https://tshirtecommerce.com/submit-ticket" target="_blank">Open ticket on our site.</a></li>
							</ol>
						</div>
					</div>
					
				 </div>
				 
				 <ol class="carousel-indicators">
					<li data-id="0" onclick="tshirt_introduction(this)" class="active"></li>
					<li data-id="1" onclick="tshirt_introduction(this)"></li>
					<li data-id="2" onclick="tshirt_introduction(this)"></li>
				 </ol>
			</div>
		</div>
		
		<div class="dg-introduction-footer">
			<button type="button" class="button button-primary pull-left" onclick="tshirt_introduction_close()">End the Introduction</button>
			<button type="button" class="button button-default pull-right" onclick="tshirt_introduction_close()">Close Now</button>
		</div>
		
	</div>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('body').append('<div class="dg-mask-introduction"></div><div class="dg-introduction" style="display:none;"></div>');
		jQuery('.dg-introduction').html(jQuery('.dg-temp-introduction').html()).show('slow');		
	});
	function tshirt_introduction(e){
		jQuery('.carousel-indicators li').removeClass('active');
		jQuery(e).addClass('active');
		jQuery('.carousel-inner .item').removeClass('active').hide();
		var index = jQuery('.carousel-indicators li.active').data('id');
		jQuery('.dg-introduction-content #carousel-item'+index).show('slow');
	}
	function tshirt_introduction_close(){
		jQuery('.dg-mask-introduction').hide();
		jQuery('.dg-introduction').hide('slow');
	}
	</script>
<?php
}}
?>