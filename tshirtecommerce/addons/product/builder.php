<?php
$product = $GLOBALS['product'];
?>
<?php 
if (isset($product->design) && isset($product->design->elements)) {
	$elements = str_replace("'", '"', $product->design->elements);
	$elements	= json_decode($elements, true);
	
?>
	<?php if (count($elements) > 0) { ?>
		<div class="row product-elements">
			<div class="col-md-12">
				
				<?php foreach($elements as $keyElement => $element) { ?>
				<div class="form-group key-<?php echo $keyElement; ?>">
					<label><?php echo $element['title']; ?></label>
					
					<br />
					<?php if(count($element['colors'])) { ?>
					<div class="list-colors">
						
						<?php foreach($element['colors'] as $color) { ?>
							<?php if(isset($color['img'])) { ?>
								<span data-color="img" title="img" onclick="design.products.build(this, <?php echo $keyElement; ?>, 'img')" class="bg-colors"><img src="<?php echo $color['img']; ?>" width="25" height="25"></span>
							<?php } else { ?>
								<span style="background-color:#<?php echo $color['color']; ?>" data-color="<?php echo $color['color']; ?>" title="<?php echo $color['title']; ?>" onclick="design.products.build(this, <?php echo $keyElement; ?>, '<?php echo $element['title']; ?>')" class="bg-colors bg-colors-<?php echo $color['color']; ?>"></span>
							<?php } ?>
							
						<?php } ?>
						
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	<?php }else{ echo '<div class="row product-elements"></div>'; } ?>
	
<?php }else{ echo '<div class="row product-elements"></div>'; } ?>
<script type='text/javascript'>
jQuery(document).ready(function(){
	jQuery('#product-details').perfectScrollbar();
});
</script>