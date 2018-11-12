<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-01-10
 * 
 * API
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
error_reporting(0);
if ( isset($_GET['session_id']) )
{
	$session_id = $_GET['session_id'];
	session_id($session_id);			
}
session_start();

define('ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT .DS. 'includes' .DS. 'functions.php';
$dg = new dg();

if ( empty($_GET['key']) || empty($_GET['view']) )
{
	echo 'Directory access is forbidden'; exit;
}

// check is download
if (!isset($_SESSION['download_design']) || !in_array($_GET['key'], $_SESSION['download_design']))
{
	echo 'Directory access is forbidden'; exit;
}

function openURL($url)
{
	$data = false;
	if( function_exists('curl_exec') )
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
	}
	
	if( $data == false && function_exists('file_get_contents') )
	{
		$data = file_get_contents($url);
	}
	
	return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">   
    <title>Download File Design</title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/print.css" type="text/css" media="print">
	<style type="text/css">
	.container.loading{opacity: 0.2;}
	svg{max-width: 100%;height: auto;}
	@media print {
		body{background: transparent none repeat scroll 0 0!important;}
		body .container {visibility: hidden;height: 1px; overflow: hidden;}
		body a{display:none;}
		#download-pdf{
			background: transparent none repeat scroll 0 0;
			height: 100%;
			width: 100%;
			position: fixed;
			top: 0;
			left: 0;
			margin: 0;
			padding: 0;
			visibility: visible;
			display: block!important;
		}
	}
	.img-thumbnail span.glyphicon-download-alt{
		background: #fff;
		color: #ff0000;
		cursor: pointer;
		padding: 5px;
		position: absolute;
	}
	</style>
  </head>
  <body>
    <div class="container">
		<div class="row">
			<center><h4>Download File Design</h4></center>
		</div>
		
		<div class="row">
			<?php				
				$key = $_GET['key'];
				$position = $_GET['view'];
				$is_admin = 0;
				
				include_once ROOT .DS. 'includes' .DS. 'functions.php';				
				
				$dg = new dg();			
				$data = array();			
				
				if (empty($_GET['idea']))
				{
					$key		= str_replace('cart:', '', $key);
					$file 		= 'download.php';
					$cache		= $dg->cache('cart');
					$data 		= $cache->get($key);
				}
				else
				{
					$file 		= 'download_idea.php';
					if(strpos($key, 'cart:') !== false)
					{
						
						$key		= str_replace('cart:', '', $key);
						$cache 		= $dg->cache('cart');
						$params 	= explode(':', $key);
						$data 		= $cache->get($params[0]);
					}
					else
					{
						$cache 		= $dg->cache('design');
						
						$params 	= explode(':', $key);
						$user_id 	= $cache->get($params[0]);
						
						if ($user_id != false && isset($user_id[$params[1]]) && count($user_id[$params[1]]) > 0)
						{
							$data = $user_id[$params[1]];
						}
						else
						{
							$cache = $dg->cache('admin');
							$params = explode(':', $key);
							$user_id = $cache->get($params[0]);
							if ($user_id != false && count($user_id[$params[1]]) > 0)
							{
								$data = $user_id[$params[1]];
								$is_admin = 1;
							}
						}
					}
				}
				if ( isset($data['vectors']) || isset($data['vector']) )
				{
					if(isset($data['vectors']))
					{
						$vectors = json_decode(json_encode($data['vectors']));
					}
					else
					{
						if (isset($data['vector']))
							$vectors 		= json_decode($data['vector']);			
						else
							$vectors 		= json_decode($data['vectors']);
					}
					
					if (isset($vectors->$position))
					{
						$views = (array) $vectors->$position;
						if (count($views) == 0)
							$data = array();
					}
					else
					{
						$data = array();
					}
				}
				
				$file = $dg->url().'tshirtecommerce/'.$file;
				
				if ( count($data) > 0)
				{
					// get product design
					$check = true;
					$products = $dg->getProducts();
					
					for($i=0; $i<count($products); $i++)
					{
						$product_a = $products[$i];
						if ( isset($data['product_id']) )
							$product_id 		= $data['product_id'];
						else if ( isset ($data['item']['product_id']) )
							$product_id 		= $data['item']['product_id'];
						else
							$product_id			= 0;
							
						if ($products[$i]->id == $product_id)
						{
							$product	 = $products[$i];
							break;
						}
					}
					
					// call store
					$items			= $vectors->$position;
					$items			= json_decode ( json_encode($items), true);
					if(count($items))
					{
						$store_ids = array();
						foreach($items as $i => $item)
						{
							if (isset($item['clipar_type']) && isset($item['price']) && empty($item['clipar_paid']))
							{
								$store_ids[] = $item['clipart_id'];
							}
						}
						if (count($store_ids))
						{
							echo '<center>Please payment cliparts after click download file design again.</center>';
							exit;
						}
					}
					
					// get size info of area design
					$zoom = 1;
					if (isset($product) && isset($product->design))
					{
						$design = $product->design;
						if ( isset($design->area) && isset($design->params) )
						{
							$area 		= json_decode( json_encode($design->area), true );
							$params 	= json_decode( json_encode($design->params), true );
							if ( isset($area[$position]) && isset($params[$position]) )
							{
								$sizes_cm = json_decode( str_replace("'", '"', $params[$position]), true );
								$sizes_px = json_decode( str_replace("'", '"', $area[$position]), true );
							
								$zoom = $sizes_cm['height'] / $sizes_px['height'];
								$check = false;
							}
						}
					}
					
					if ($check === true)
					{
						echo '<div class="col-md-12 alert alert-danger" role="alert"><strong>Product Not Found!</strong></div>';
						return false;
					}
					
					$download_pdf = openURL($file.'?key='.$_GET['key'].'&view='.$position.'&type=pdf&is_admin='.$is_admin);
					if (is_string($data['images']))
					{
						$images = str_replace('\"', '"', $data['images']);
						$data['images'] = json_decode($images, true);
					}
					if (count($data['images']))
						$data['images'] = json_decode ( json_encode($data['images']), true );
				?>
				<style type="text/css" media="print">
					@page { size: <?php echo $sizes_cm['width']*10; ?>mm <?php echo $sizes_cm['height']*10; ?>mm; margin: 0;}
				</style>
				<div class="col-sm-6 col-md-6">
					<?php if (isset($data['images'][$position])) { ?>
					<center>
					<img src="<?php echo $data['images'][$position]; ?>" width="400" class="img-responsive" alt="Responsive image">
					</center>
					<?php }else{ ?>
					<img src="<?php echo $data['image']; ?>" class="img-responsive" alt="Responsive image">
					<?php } ?>
					<span style="display:none;" id="download-png">
					<?php echo openURL($file.'?key='.$_GET['key'].'&view='.$position.'&type=png&is_admin='.$is_admin); ?>
					</span>					
					<hr />
					<center>Click to download: 						
						<a href="<?php echo $file.'?key='.$_GET['key'].'&view='.$position.'&type=svg&download=1&is_admin='.$is_admin; ?>"><strong>SVG</strong></a>
						 or 
						<a href="#" onclick="window.print();"><strong>PDF</strong></a>						
						 or 
						<a href="#" onclick="downloadPNG('<?php echo $position; ?>')"><strong>PNG</strong></a>
						
						<hr />
						<div style="text-align: left;">
							<strong>Note:</strong>
							<ol style="padding-left: 20px;">
								<li>Download file PDF only works on Chrome.</li>
								<li>Download file PNG only support file size < 2MB. If file is large, please download PDF.</li>
							</ol>
						</div>
						<br />
						<a href="https://youtu.be/yJ9qMW28O_Q" target="_blank" class="btn btn-primary">View Video Guide</a>
						
					</center>
				</div>
				
				<div class="col-sm-6 col-md-6">
					<div class="panel panel-default">
						<div class="panel-heading">Design detail <small class="text-danger">(Click font name to download and install font to your computer)</small></div>
						<div class="panel-body">
							<p>
								<strong>Area Design: </strong>
								<br />
								<div class="row">
									<div class="col-md-3">Width: <strong><?php echo number_format($sizes_cm['width'], 2); ?></strong>CM</div>
									<div class="col-md-3">Height: <strong><?php echo number_format($sizes_cm['height'], 2); ?></strong>CM</div>
									<div class="col-md-3">Top: <strong><?php echo number_format(($sizes_px['top'] * $zoom), 2); ?></strong>CM</div>
									<div class="col-md-3">Left: <strong><?php echo number_format(($sizes_px['left'] * $zoom), 2); ?></strong>CM</div>
								</div>
								<hr />
							</p>
							
							<?php 
							if (isset($data['print']) && isset($data['print']['sizes']) && $data['print']['sizes'] != '') {
								$print_size = json_decode($data['print']['sizes'], true);
								$print_colors = json_decode($data['print']['colors'], true);
								
								if (isset($print_size[$position])) {
							?>
							<p>
								<strong>Size Design:</strong>
								<br />
								<div class="row">
									
									<?php if ( isset($print_size[$position]['width']) ) { ?>
									<div class="col-md-3">Width: <strong><?php echo number_format($print_size[$position]['width'], 2); ?></strong>CM</div>
									<?php } ?>
									
									<?php if ( isset($print_size[$position]['height']) ) { ?>
									<div class="col-md-3">Height: <strong><?php echo number_format($print_size[$position]['height'], 2); ?></strong>CM</div>
									<?php } ?>
									
									<?php if ( isset($print_size[$position]['size']) ) { ?>
									<div class="col-md-3">Page: <strong>A<?php echo $print_size[$position]['size']; ?></strong></div>
									<?php } ?>
								</div>
								
								<?php if (count($print_colors) && isset($print_colors[$position])) { ?>
								<br />
								<p>									
									<?php foreach($print_colors[$position] as $p_color) { if($p_color == 'none' || strlen($p_color)>6) continue; ?>
										<button type="button" style="background-color:#<?php echo $p_color; ?>" class="btn btn-default btn-xs"><?php echo $p_color; ?></button>
									<?php } ?>
									
								</p>
								<?php } ?>
								
								<hr />
							</p>
							<?php }} ?>
							<div class="row">
							<?php
							
							$items			= $vectors->$position;
							$items			= json_decode ( json_encode($items), true);
							
							if (count($items))
							{
								foreach($items as $item)
								{
									$item['svg'] = str_replace('\\"', '"', $item['svg']);
									
									preg_match_all("/xlink:href=\"(.*)\">/i", $item['svg'], $src); // add allow download image upload.
									echo '<div class="col-md-6">';
									if ($item['type'] == 'text')
									{
										$font = $item['fontFamily'];
										echo "<link href='https://fonts.googleapis.com/css?family=".str_replace(' ', '+', $font)."' rel='stylesheet' type='text/css'>";
										echo '<p><strong>Add text:</strong></p>';
										
										if(strpos($item['svg'], '<tspan dy="0" x="50%"></tspan>') > 0)
										{
											$svg = strstr($item['svg'], '<tspan dy="0" x="50%"></tspan>');
											
											$repsvg = '';
											$svgs = explode('<tspan dy="24" x="50%">', $svg);
											if(count($svgs))
											{
												$check = false; 
												foreach($svgs as $val)
												{ 
													if(strpos($val, 'defs') == false)
													{
														if($check)
														{
															$repsvg .= '<tspan dy="24" x="50%">'.strip_tags($val).'</tspan>';
														}elseif(strip_tags($val) != '')
														{
															$check = true;
															$repsvg = '<tspan dy="0" x="50%">'.$val.'</tspan>';
														}
													}else
													{
														$repsvg .= $val;
													}
												}
												
												$item['svg'] = str_replace($svg, $repsvg, $item['svg']);
												$item['svg'] = str_replace('</tspan></tspan>', '</tspan>', $item['svg']);
											}
										}
										
										echo '<p style="position: relative;"><a href="javascript:void(0);" onclick="downloadSVG(this)" title="click to download">'.$item['svg'].'</a></p>';
										
										echo '<p>Font name: <a title="click here to download font" target="_blank" href="https://www.google.com/fonts/specimen/'.str_replace(' ', '+', $font).'"><strong>'.$font.'</strong></a></p>';
										
										if (isset($item['color']))
											echo '<p>Color: <strong>'.$item['color'].'</strong></p>';
										
										if (isset($item['outlineC']) && isset($item['outlineW']))
											echo '<p>Outline: <strong>'.$item['outlineC'].' '.number_format(($item['outlineW'] * $zoom), 2).'CM</strong></p>';
									}
									else if($item['type'] == 'team')
									{
										echo '<p><strong>Add Team:</strong></p>';
										if(isset($src[1][0]) && strpos($src[1][0], '://')) // add allow download image upload.
											echo '<p class="img-thumbnail" style="position: relative;"><span class="glyphicon glyphicon-download-alt" onclick="downloadImage(\''.$src[1][0].'\')"></span><a href="javascript:void(0);" onclick="downloadSVG(this)" title="click to download">'.$item['svg'].'</a></p>';
										else
											echo '<p class="img-thumbnail style="position: relative;"><a href="javascript:void(0);" onclick="downloadSVG(this)" title="click to download">'.$item['svg'].'</a></p>';
									}
									else
									{
										echo '<p><strong>Add art:</strong></p>';
										if(isset($src[1][0]) && strpos($src[1][0], '://')) // add allow download image upload.
											echo '<p class="img-thumbnail" style="position: relative;"><span class="glyphicon glyphicon-download-alt" onclick="downloadImage(\''.$src[1][0].'\')"></span><a href="javascript:void(0);" onclick="downloadSVG(this)" title="click to download">'.$item['svg'].'</a></p>';
										else
											echo '<p class="img-thumbnail" style="position: relative;"><a href="javascript:void(0);" onclick="downloadSVG(this)" title="click to download">'.$item['svg'].'</a></p>';
									}
									
									if (isset($item['colors']) && is_array($item['colors']) && count($item['colors']) > 0)
									{
										echo '<p>';
										$colors = $item['colors'];
										foreach($colors as $itemColor)
										{
											$itemColor = str_replace('#', '', $itemColor);
											
											if($itemColor == 'none'  || strlen($itemColor)>6) continue;
											echo '<button type="button" style="background-color:#'.$itemColor.'" class="btn btn-default btn-xs">#'.$itemColor.'</button>';
										}
										echo '</p>';
									}
									echo '<hr />';
									
									echo '<p>Width: <strong>'.number_format(($item['width'] * $zoom), 2).'CM</strong></p>';									
									echo '<p>Height: <strong>'.number_format(($item['height'] * $zoom), 2).'CM</strong></p>';									
									echo '<p>Top: <strong>'.number_format(($item['top'] * $zoom), 2).'CM</strong></p>';									
									echo '<p>Left: <strong>'.number_format(($item['left'] * $zoom), 2).'CM</strong></p>';

									if (empty($item['rotate'])) $item['rotate'] = 0;
									echo '<p>Rotate: '.$item['rotate'].'</p>';
									
									echo '</div>';
								}
							}
							?>
							</div>
						</div>
					</div>
				</div>
			<?php 
				}
				else
				{
					echo '<div class="col-md-12 alert alert-danger" role="alert"><strong>Design Not Found!</strong></div>';
				}	
			?>
		</div>
	</div>
	
	<span id="download-pdf" style="display:none;">
		<?php echo $download_pdf; ?>		
	</span>
	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>    
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<span id="download"></span>
	<script type="text/javascript">	
	function downloadPNG(view)
	{
		var mySVG = document.getElementById('download-png').innerHTML;
		var mySrc 	= 'data:image/svg+xml,'+encodeURIComponent(mySVG);
		var img = new Image();

		$('.container').addClass('loading');
		img.onload = function(){	
			var canvas = document.createElement("canvas");
			canvas.width = img.width;
			canvas.height = img.height;    
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0);
			// Fixed download png on chrome.
			canvas.toBlob(function (blob) {
				var url = (window.webkitURL || window.URL).createObjectURL(blob);
				var link = document.createElement('a');
				link.href = url;
				link.download = view+'.png';
				document.body.appendChild(link);
				link.click();
				$('.container').removeClass('loading');
			});
		}
		img.src = mySrc;
	}
	
	function downloadImage(src)
	{
		var file_name = src.split('/').pop();
		var a = jQuery("<a>").attr("href", src).attr("download", file_name).appendTo("body");

		a[0].click();
		a.remove();
	}
	
	function downloadSVG(e)
	{
		var svg      = jQuery(e).html();
		var imgsrc = 'data:image/svg+xml;base64,'+ btoa(svg);
		var a = jQuery("<a>").attr("href", imgsrc).attr("download", 'test.svg').appendTo("body");

		a[0].click();
		a.remove();
	}
	
	function changeShapView(svg, zoom, type) {
		var img = svg.find('image');
		if(img.length > 0)
		{
			if(img.css('clip-path') != 'none')
			{
				var clipId  = img.css('clip-path').split('#')[1].split('"')[0];
				var clipEle = svg.find('#' + clipId);
				var viewBox = svg[0].getAttribute('viewBox').split(' ');
				for(i = 0; i < viewBox.length; i ++) {
					viewBox[i] = viewBox[i] * zoom;
				}
				svg[0].setAttribute('viewBox', viewBox.join(' '));
				var transform = jQuery(clipEle.children()[0]).attr('transform');
				if(transform.indexOf(',') != -1)
				{
					var matrixT = jQuery(clipEle.children()[0]).attr('transform').split('(')[1].split(')')[0].split(',');
				} 
				else
				{
					var matrixT = jQuery(clipEle.children()[0]).attr('transform').split('(')[1].split(')')[0].split(' ');
				}
				matrixT[0] = matrixT[0] * zoom;
				matrixT[3] = matrixT[3] * zoom;
				matrixT[4] = matrixT[4] * zoom;
				matrixT[5] = matrixT[5] * zoom;
				clipEle.children().each(function() {
					jQuery(this).attr('transform', 'matrix(' + matrixT[0] + ',0,0,' + matrixT[3] + ',' + matrixT[4] + ',' + matrixT[5] + ')');
				});
				jQuery(img[0]).css('clip-path', 'url(#' + clipId + '-' + type + ')' );
				jQuery(clipEle[0]).attr('id', clipId + '-' + type);
			}
			var g = svg.children('g')[0];
			var transform = g.getAttribute('transform');
			if(transform != null)
			{
				if(transform.indexOf('scale(-1,1)') != -1)
				{
					var matrix = transform.split('(')[1].split(')')[0].split(',');
					var newT   = matrix[0] * zoom;
					g.setAttribute('transform', 'translate(' + newT + ', 0) scale(-1,1)');
				}
			}
		}
	}

	jQuery(document).ready(function() {
		var svgPdf  = jQuery('#download-pdf').children('svg')[0];
		var svgPng  = jQuery('#download-png').children('svg')[0];
		var pdfZoom = jQuery(svgPdf).data('zoom');
		var pngZoom = jQuery(svgPng).data('zoom');
		jQuery(svgPdf).find('svg').each(function() {
			changeShapView(jQuery(this), pdfZoom, 'pdf');
		});
		jQuery(svgPng).find('svg').each(function() {
			changeShapView(jQuery(this), pngZoom, 'png');
		});
	});
	</script>
  </body>
</html>