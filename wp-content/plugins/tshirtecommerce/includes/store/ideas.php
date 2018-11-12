<?php
$page 			= get_page_link();
$link 			= add_query_arg( array('view'=>'products'), $page);

$designer = get_option( 'online_designer' );
if (isset($designer['url']) && $designer['url'] > 0)
{
	$id = $designer['url'];
}
else
{
	$id = 'design-your-own';
}
$product_url = get_page_link($id);

if(isset($_GET['color']))
{
	$color = add_query_arg( array('view'=>$_GET['color']), $page);
}

$paged 		= ( get_query_var('paged') ) ? get_query_var('paged') : 1;
?>
<div class="store-page woocommerce">
	<h4><?php echo $lang['designer_store_find_design']; ?> <?php echo $product->title; ?> <a href="<?php echo $link; ?>" class="button"><small><?php echo $lang['designer_product_change_product']; ?></small></a></h4>
	
	<div class="store-search designer-attributes">
		<form action="" method="GET" id="form-store-search">
		<?php
		$color_hex = $product->design->color_hex;
		$color_title = $product->design->color_title;
		?>
		<?php if(count($color_hex) > 1) { ?>
		<div class="store-search-color box-search">
			<label><?php echo $lang['designer_right_choose_product_color']; ?></label>
			<div class="list-colors">
				
				<?php
				if(isset($_GET['color']))
					$color_index = $_GET['color'];
				else
					$color_index = 0;
				for($i=0; $i<count($color_hex); $i++) {
					$colors = explode(';', $color_hex[$i]);
					$width = (int) (24/count($colors));
					
					$url_color = add_query_arg( array('product_id'=>$product_id, 'color'=>$i), $page);
					
					if($paged > 1)
					{
						$url_color = add_query_arg( array('paged'=>$paged, 'product_id'=>$product_id, 'color'=>$i), $url_color);
					}
					if(isset($_GET['store_cate_id']))
					{
						$url_color = add_query_arg( array('store_cate_id'=>$_GET['store_cate_id']), $url_color);
					}
					if(isset($_GET['store_keyword']) && $_GET['store_keyword'] != '')
					{
						$url_color = add_query_arg( array( 'store_keyword'=>urlencode($_GET['store_keyword']) ), $url_color);
					}
				?>
				<a href="<?php echo $url_color; ?>" title="<?php echo $color_title[$i]; ?>" class="bg-colors <?php if($i== $color_index) echo 'active'; ?>">
				
					<?php for($j=0; $j<count($colors); $j++) { ?>
					<span style="width:<?php echo $width; ?>px; background-color:#<?php echo $colors[$j]; ?>;"></span>
					<?php } ?>
					
				</a>
				<?php } ?>
				<input type="hidden" name="color" value="<?php echo $color_index; ?>">
			</div>
		</div>
		<?php } ?>
		
		<input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
		
		<?php if(isset($ideas['categories']) && count($ideas['categories']) > 0){ ?>
		<div class="store-search-categories box-search">
			<label><?php echo $lang['designer_store_find_categories']; ?></label>
			
			<?php
			if(isset($_GET['store_cate_id']))
				$store_cate_id = $_GET['store_cate_id'];
			else
				$store_cate_id	= 0;
			?>
			<select name="store_cate_id" onchange="jQuery('#form-store-search').submit()">
				<option value="0"><?php echo $lang['designer_store_find_categories_all'];?></option>
				
				<?php foreach($ideas['categories'] as $cate) { ?>
					
					<option value="<?php echo $cate['id']; ?>" <?php if($store_cate_id == $cate['id']) echo 'selected="selected"'; ?> ><?php echo $cate['title']; ?></option>
									
					<?php if( isset($cate['children']) && count($cate['children']) > 0 ) { ?>
						
						<?php foreach($cate['children'] as $children) { ?>
							<option value="<?php echo $children['id']; ?>" <?php if($store_cate_id == $children['id']) echo 'selected="selected"'; ?>> &nbsp;&nbsp;&nbsp;- <?php echo $children['title']; ?></option>
						<?php } ?>
					
					<?php } ?>
				
				<?php } ?>
				
			</select>
		</div>
		<?php } ?>
		
		<div class="store-search-keyword box-search">
			<label><?php echo $lang['designer_store_find']; ?></label>
			
			<?php
			if(isset($_GET['store_keyword']))
				$store_keyword = $_GET['store_keyword'];
			else
				$store_keyword	= '';
			?>
			<input type="text" name="store_keyword" value="<?php echo $store_keyword; ?>" placeholder="<?php echo $lang['designer_clipart_search']; ?>">
		</div>
		</form>
	</div>
	
	<?php if( isset($ideas) && isset($ideas['count']) && $ideas['count'] > 0 ){ ?>
	<div class="store-ideas">
	
		<?php
		$thumbs = '';
			
		// get image of product
		$index = $color_index;
		if(isset($front[$index]) && $front[$index] != '')
		{
			$design = json_decode(str_replace("'", '"', $front[$index]));
		}
		if(isset($design))
		{
			foreach($design as $item)
			{
				if($item->id != 'area-design')
				{
					$width 		= str_replace('px', '', $item->width);
					$width		= ($width * 200)/500;
					
					$height 	= str_replace('px', '', $item->height);
					$height		= ($height * 200)/500;
					
					$top 		= str_replace('px', '', $item->top);
					$top		= ($top * 200)/500;
					
					$left 		= str_replace('px', '', $item->left);
					$left		= ($left * 200)/500;
					
					if($item->zIndex == 'auto')
					{
						$item->zIndex = 0;
					}
					
					if( strpos($item->img, 'http') === false)
					{
						$img = site_url('tshirtecommerce/'. $item->img);
					}
					else
					{
						$img = $item->img;
					}
					
					$thumbs	.= '<img class="product-img" src="'.$img.'" alt="" style="width:'.$width.'px; height:'.$height.'px; top:'.$top.'px; left:'.$left.'px; z-index:'.$item->zIndex.';">';
				}
			}
		}
		
		// list page
		$number 	= 24;
		$start 		= ($paged - 1) * $number;
		$end 		= $paged*24;
		$i 			= 0;
		foreach($ideas['rows'] as $idea) {
			$i++;
			if($i<$start) continue;
			
			if($start > $end) break;
			$start++;
			
			$product_url 	= add_query_arg( array('product_id'=>$product_id, 'idea_id'=> $idea['id']), $product_url);
		?>
		
		<div class="store-idea">
			
			<div class="store-idea-thumb" style="background-color:#<?php echo $idea['color']; ?>">
				<a href="<?php echo $product_url; ?>" target="_bank" title="<?php echo $idea['title']; ?>">
					<img src="<?php echo $idea['thumb']; ?>" atl="<?php echo $idea['title']; ?>" width="200">
				</a>
			</div>
			
			<div class="store-idea-product">
				<a href="<?php echo $product_url; ?>" target="_bank" title="<?php echo $idea['title']; ?>">
					<?php echo $thumbs; ?>
					
					<img class="item-design" src="<?php echo $idea['thumb']; ?>" atl="<?php echo $idea['title']; ?>" style="width:<?php echo $area->width; ?>px;max-height:<?php echo $area->width; ?>px; left:<?php echo $area->left; ?>px; z-index:<?php echo $area->zIndex; ?>;">
				</a>
				
				<a href="<?php echo $product_url; ?>" target="_bank" class="store-idea-title"><?php echo $lang['designer_cart_edit']; ?></a>
			</div>
			
		</div>
		
		<?php } ?>
		
		<?php
		$pages = (int) (count($ideas['rows'])/24);
		if(count($ideas['rows']) % 24 > 0)
		{
			$pages = $pages + 1;
		}
		
		$args = array(
			'base'               => '%_%',
			'format'             => '?paged=%#%',
			'total'              => $pages,
			'current' 			 => max( 1, get_query_var('paged') ),
			'type' 				 => 'list',
		);
		?>
	</div>
	
	<br />
	<hr />
	
	<nav class="woocommerce-pagination">
		<?php echo paginate_links( $args ); ?>
	</nav>
	
	<?php }else{ echo $lang['design_msg_save_found']; } ?>
</div>