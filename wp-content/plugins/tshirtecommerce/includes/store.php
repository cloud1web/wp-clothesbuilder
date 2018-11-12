<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-07-01
 *
 * Store
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
 
// add design idea to url design tool
add_filter( 'tshirt_set_url_designer', 'designer_url_idea');
function designer_url_idea($url)
{
	if(isset($_GET['idea_id']))
	{
		$url = add_query_arg( array('idea_id'=> $_GET['idea_id']), $url);
	}
	
	return $url;
}
 
// show art using in each design
add_filter( 'tshirtecommerce_order_html', 'tshirtecommerce_order_html', 10, 4 );
function tshirtecommerce_order_html($html, $type, $data, $order_item)
{
	if(isset($data['design_id']))
	{
		if (defined('ROOT') == false)
			define('ROOT', ABSPATH .'tshirtecommerce');
		
		if (defined('DS') == false)
			define('DS', DIRECTORY_SEPARATOR);
		
		include_once (ROOT .DS. 'includes' .DS. 'functions.php');
		$dg = new dg();
		
		$cache 		= $dg->cache('cart');
		$data['design_id'] = str_replace('cart:', '', $data['design_id']);
		$design 	= $cache->get($data['design_id']);
		
		$arts = array();
		$prices = 0;
		$qty	= (int) ($order_item['qty']/10);
		if($qty < 1) $qty = 1;
		
		if(isset($design['vectors']))
		{
			$design['vector'] = $design['vectors'];
			unset($design['vectors']);
		}
		
		if(isset($design['vector']))
		{
			$ids = array();
			if(is_array($design['vector']))
			{
				$vectors	= $design['vector'];
			}
			else
			{
				$vectors = json_decode($design['vector'], true);
			}		

			if(count($vectors))
			{
				foreach($vectors as $view => $items)
				{
					if (count($items))
					{
						foreach($items as $item)
						{
							if (isset($item['clipar_type']) && empty($item['clipar_paid']))
							{
								if(empty($item['price'])) $item['price'] = 0;
								if(is_string($item['file']))
									$file = $item['file'];
								if(isset($item['file']['type']))
									$file = $item['file']['type'];
								$arts[$item['clipart_id']] = array(
									'view'	=> $view,
									'id'	=> $item['clipart_id'],
									'title'	=> $item['title'],
									'thumb'	=> $item['thumb'],
									'file'	=> $file,
									'price'	=> $item['price'],
								);
								if (!in_array($item['clipart_id'].'-'.$qty, $ids))
								{
									$prices = $prices + ($item['price'] * $qty);
									$ids[] 	= $item['clipart_id'].'-'.$qty;
								}
							}
						}
						
						// update info after paid
						if(isset($_GET['e_order_id']) && isset($_GET['params']) && count($ids) > 0 && $data['design_id'] == $_GET['e_order_id'])
						{
							$e_order_id 	= $_GET['e_order_id'];
							$params 		= $_GET['params'];
							$settings 	= $dg->getSetting();
							$api 		= $settings->store->api;
							store_art_update($e_order_id, $params, array(), $api);
							return $html;
						}
					}
				}
			}
		}
		if(count($arts))
		{
			$settings 	= $dg->getSetting();
			if(empty($settings->store))
				return '<p style="background-color: #fcf8e3;color:#8a6d3b;border: 1px solid #faebcc;padding:14px 12px;margin: 0px;">Your order using arts of <a href="http://store.9file.net/" target="_blank">store</a>. You missing setup API Store. Please go to <strong>T-Shirt eCommerce > Settings > Configuration > Tab Config</strong> setup store to download file output.</p>';
			
			if( empty($settings->store->api) || (isset($settings->store->api) && $settings->store->api == '') )
			{
				return '<p style="background-color: #fcf8e3;color:#8a6d3b;border: 1px solid #faebcc;padding:14px 12px;margin: 0px;">Your order using arts of <a href="http://store.9file.net/" target="_blank">store</a>. You missing setup API Store. Please go to <strong>T-Shirt eCommerce > Settings > Configuration > Tab Config</strong> setup store to download file output.</p>';
			}
			$api = $settings->store->api;
		
			$html .= '<div style="border:1px solid #ccc;"><div style="background:#f8f8f8;border-bottom: 1px solid #ccc;padding: 12px;font-weight: bold;font-size: 14px;text-transform: uppercase;">Arts of Store</div><div>';
			wp_enqueue_script( 'designer_app_store', plugins_url( 'assets/js/store.js', dirname(__FILE__ )), array(), '1.0.0', true );
			wp_enqueue_style( 'designer_app_store_css', plugins_url( 'assets/css/store.css', dirname(__FILE__ ) ) );
			if($prices > 0)
			{
				$html .= '<p style="background-color: #fcf8e3;color:#8a6d3b;border: 1px solid #faebcc;padding:14px 12px;margin: 0px;">Your order using arts of store with cost <strong>'.$prices.' Credits</strong>. Please payment before download file output. <a href="http://store.9file.net/" target="_blank">Read More</a></p>';
				$html .= '<p style="text-align: center;"><a href="javascript:void(0);" data-id="'.$api.'/'.implode('_', $ids).'/'.$data['design_id'].'" onclick="payment.load(this);" style="background-color: #337ab7;border-radius: 4px;padding: 10px 15px;color: #fff;text-decoration: none;font-weight: bold;">Payment Now!</a></p>';
				$html .= '<script type="text/javascript">jQuery(document).ready(function(){payment.removeLink(\''.$data['design_id'].'\');});</script>';
			}
			else
			{
				$html .= '<script type="text/javascript">jQuery(document).ready(function(){payment.key(\''.$api.'\', \''.implode('_', $ids).'\', \''.$data['design_id'].'\')});</script>';
			}
			$html .= '<div class="arts-store"style="overflow: hidden;"><table cellpadding="0" cellspacing="0" width="100%">';
			$html .= 	'<thead>';
			$html .= 		'<tr>';
			$html .= 			'<th>Position</th>';
			$html .= 			'<th>Image</th>';
			$html .= 			'<th>Price & Info</th>';
			$html .= 		'</tr>';
			$html .= 	'</thead>';
			$html .= 	'<tbody>';
			
			foreach($arts as $art)
			{
				if ($art['price'] == 0)
				{
					$price = '<strong>FREE</strong>';
				}
				else
				{
					$price	= 'Price: <strong>'.($art['price']*$qty).' Credits</strong><br />';
					$price	.= 'Quantity: <strong>'.$qty.'</strong>';
				}
				$html .= 	'<tr>';
				$html .= 	'<td>'.$art['view'].'</td>';
				$html .= 	'<td><a href="http://store.9file.net/arts/detail/'.$art['id'].'" target="_blank"><img width="100" src="'.$art['thumb'].'" alt="'.$art['title'].'" title="'.$art['title'].'"></a></td>';
				$html .= 	'<td> '.$price.'<br />File type: <strong>'.strtoupper($art['file']).'</strong></td>';
				$html .= 	'</tr>';
			}
			
			$html .= 	'</tbody>';
			$html .= '</table><div class="arts-store-payment"></div></div>';
			$html .= '</div></div>';
		}
	}
	
	return $html;
}

// call ajax
add_action( 'wp_ajax_store_ajax_key', 'store_ajax_key' );
add_action( 'wp_ajax_nopriv_store_ajax_key', 'store_ajax_key' );
function store_ajax_key()
{
	$data = array(
		'error'		=> 0,
		'msg'		=> '',
		'reload'	=> 0
	);
	if( empty($_GET['api_key']) || empty($_GET['arts']) || empty($_GET['order_id']) )
	{
		$data['error']	= 1;
		$data['msg']	= 'Data design not found!';
	}
	else
	{
		$ids 	= str_replace(':', '-', $_GET['arts']);
		$url 	= 'http://api.9file.net/api/order/ids/'.$ids.'/order_number/'.$_GET['order_id'].'/api_key/'.$_GET['api_key'];
		$result = openURL($url);
		if($result != false)
		{
			$arts 	= json_decode($result, true);
			if(isset($arts['error']))
			{
				$data['error']	= 1;
				$data['msg']	= 'Data design not found!';
			}
			else
			{
				$params		= '';
				$art_prices	= array();
				foreach($arts as $id => $art)
				{
					if($art['price'] > 0)
					{
						$art_prices[$id]	= $art['price'];
						$art['key']			= 0;
					}
					if($params == '')
						$params		= $id.':'.$art['key'];
					else
						$params		.= ';'.$id.':'.$art['key'];
				}
				if(count($art_prices) > 0)
				{
					$data['error']	= 1;
					$data['reload']	= 1;
				}
				store_art_update($_GET['order_id'], $params, $art_prices, $_GET['api_key']);
			}
		}
		else
		{
			$data['error']	= 1;
			$data['msg']	= 'Data design not found!';
		}		
	}
	echo json_encode($data); exit;
	exit;
}

// update to design info after paid
function store_art_update($design_id, $params, $art_prices = array(), $api = '')
{
	$array 		= explode(';', $params);
	
	$arts 		= array();
	for($i=0; $i<count($array); $i++)
	{
		$art 	= explode(':', $array[$i]);
		if(count($art) > 1)
		{
			$arts[$art[0]] = $art[1];
		}
	}
	if (count($arts))
	{
		if (defined('ROOT') == false)
			define('ROOT', ABSPATH .'tshirtecommerce');
		
		if (defined('DS') == false)
			define('DS', DIRECTORY_SEPARATOR);
		
		include_once (ROOT .DS. 'includes' .DS. 'functions.php');
		$dg = new dg();
		
		// update sales
		$file = ROOT .DS. 'data' .DS. 'store' .DS. 'arts_info.json';
		if(file_exists($file))
		{
			// call cache
			$cache 		= $dg->cache('store');
			$sales 		= $cache->get('sales');
			if($sales == null)
				$sales 	= array();
			
			$time 		= time();
			$month 		= date('Y_m', $time);
			$day 		= date('d', $time);
			
			if(empty($sales[$month]))
				$sales[$month]	= array();
			
			if(empty($sales[$month][$day]))
				$sales[$month][$day]	= array();
		
			$rows = json_decode( file_get_contents($file), true );
			foreach($arts as $clipar_id => $value)
			{
				if(isset($art_prices[$clipar_id])) continue;
				
				if(isset($rows[$clipar_id]))
				{
					if(isset($rows[$clipar_id]['sales']))
						$rows[$clipar_id]['sales'] = $rows[$clipar_id]['sales'] + 1;
					else
						$rows[$clipar_id]['sales']	= 1;
					
					if( isset($sales[$month][$day][$clipar_id]) )
					{
						$sales[$month][$day][$clipar_id] = $sales[$month][$day][$clipar_id] + 1;
					}
					else
					{
						$sales[$month][$day][$clipar_id] = 1;
					}
				}
			}
			$dg->WriteFile($file, json_encode($rows));
			$cache->set('sales', $sales, 933120000);
		}
		
		$cache 		= $dg->cache('cart');
		$design 	= $cache->get($design_id);
		
		if(isset($design['vectors']))
		{
			$design['vector'] = $design['vectors'];
			unset($design['vectors']);
		}
		
		if(isset($design['vector']))
		{
			if(is_array($design['vector']))
			{
				$vectors = $design['vector'];
			}
			else
			{
				$vectors = json_decode($design['vector'], true);
			}			
			if(count($vectors))
			{
				foreach($vectors as $view => $items)
				{
					if (count($items))
					{
						foreach($items as $id => $item)
						{
							if (isset($item['clipar_type']) && empty($item['clipar_paid']))
							{
								if( isset($art_prices[$item['clipart_id']]) )
								{
									$items[$id]['price'] = $art_prices[$item['clipart_id']];
									continue;
								}
								if( isset( $arts[ $item['clipart_id'] ] ) )
								{									
									$items[$id]['clipar_paid'] = 1;
									if((isset($item['file']) && is_string($item['file']) && $item['file'] == 'svg') || (isset($item['file']['type']) && $item['file']['type'] == 'svg'))
									{
										$svg 	= StorestrSVG($item['svg'], $arts[ $item['clipart_id'] ]);
									}
									else
									{
										$key_active 	= str_replace(' ', '+', $arts[ $item['clipart_id'] ]);
										$svg			= $item['svg'];
										$key 			= md5( $key_active );
										
										$url 			= 'http://api.9file.net/api/orderPNG/id/'.$item['clipart_id'].'/key/'.$key.'/api_key/'.$api;
										$result 		= openURL($url);
										if($result != false)
										{
											$data	= json_decode($result, true);
											if(isset($data['content']))
											{										
												$png 	= encrypt_compress($key_active, base64_decode($data['content']));
												$img 	= 'data:image/png;base64,' . base64_encode($png);
									
												$temp1 = explode('xlink:href="', $item['svg']);
												if(count($temp1) > 1)
												{
													$temp2 = explode('">', $temp1[1]);
													if(count($temp2) > 1)
													{
														$svg 	= $temp1[0] .'xlink:href="'. $img .'">'. $temp2[1];
													}
													
												}
											}
										}
									}
									$items[$id]['svg'] = $svg;
								}
							}
						}
						$vectors[$view]	= $items;
					}
				}
				
			}
			$design['vector'] = json_encode($vectors);
			$cache->set($design_id, $design);
		}
	}
}

// page store
add_shortcode( 'tshirtecommerce_store', 'tshirtecommerce_store_page');
function tshirtecommerce_store_page($atts, $content)
{
	if(isset($atts['product_id']))
		$product_id = $atts['product_id'];
	
	if (defined('ROOT') == false)
		define('ROOT', ABSPATH .'tshirtecommerce');
	
	if (defined('DS') == false)
		define('DS', DIRECTORY_SEPARATOR);
	
	include_once (ROOT .DS. 'includes' .DS. 'functions.php');
	$dg = new dg();
	
	$settings 	= $dg->getSetting();
	
	$is_store = false;
	if( 
		isset($settings->store) 
		&& isset($settings->store->enable) 
		&& $settings->store->enable == 1
		&& isset($settings->store->verified) 
		&& $settings->store->verified == 1
		&& isset($settings->store->api) 	
		&& $settings->store->api != ''		
	)
	{
		$is_store = true;
	}
	
	if($is_store == false)
		return 'Please active store in admin page!';
	
	global $wc_cpdf, $wp_query;
		
	// find all product design
	$args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1);
	$data = get_posts( $args );
		
	//get product design
	$product_ids 			= array();
	$products 		= array();
	foreach ($data as $product)
	{	
		$ids = $wc_cpdf->get_value($product->ID, '_product_id');
		if ($ids != '')
		{
			$temp = explode(':', $ids);
			if (count($temp) == 1)
			{
				$product->design_id = $temp[0];
				$products[$product->ID]	= $product;				
				$product_ids[]	= $product->ID;
			}
		}	
	}
	
	if (isset($_GET['view']) && $_GET['view'] == 'products')
	{
		$view = 'products';
	}
	else
	{
		$view = '';
	}
		
	if( isset($_GET['product_id']) )
	{
		$product_id	= $_GET['product_id'];
		$_SESSION['store_product_id'] = $product_id;
	}
	elseif(isset($product_id))
	{
		$_SESSION['store_product_id'] = $product_id;
	}
	elseif( isset($_SESSION['store_product_id']) )
	{
		$product_id	= $_SESSION['store_product_id'];
	}
	$lang = $dg->lang('lang.ini', false); 
	
	ob_start();
	if( isset($product_id) && isset($products[$product_id]) && $view == '' )
	{
		$product = $products[$product_id];
		
		// get ideas
		if(isset($product->design_id))
		{
			$design_id	= $product->design_id;
			include_once (ROOT .DS. 'includes' .DS. 'store.php');
			$store		= new store($settings);
			$store->dg 	= $dg;
			$ideas		= $store->getIdeas($design_id);
			
			//search
			$options = array();
			if( isset($_GET['store_cate_id']) )
			{
				$options['cate_id'] 	= $_GET['store_cate_id'];
			}
			if( isset($_GET['store_keyword']) )
			{
				$options['keyword'] 	= $_GET['store_keyword'];
			}
			$ideas		= $store->ideas($ideas, $options);
			
			// get product design data
			$products 		= $dg->getProducts();
			for($i=0; $i < count($products); $i++)
			{
				if ($design_id == $products[$i]->id)
				{
					$product = $products[$i];
					break;
				}
			}
			if( isset($product->design) && isset($product->design->front) )
			{			
				$front = $product->design->front;
				
				// get area design
				$area 			= json_decode(str_replace("'", '"', $product->design->area->front));
				$width 			= str_replace('px', '', $area->width);
				$area->width 	= ($width * 200)/500;
				
				$height 		= str_replace('px', '', $area->height);
				$area->height 	= ($height * 200)/500;
				
				$top 			= str_replace('px', '', $area->top);
				$area->top 		= ($top * 200)/500;
				
				$left 			= str_replace('px', '', $area->left);
				$area->left 	= ($left * 200)/500;
				
				if($area->zIndex < 1)
				{
					$area->zIndex = 100;
				}
			}
		}
		include_once(dirname(__FILE__).'/store/ideas.php');		
	}
	else
	{
		$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
		$wp_query = new WP_Query(
			array(
				'post_type'=> 'product', 
				'paged'=> $paged,
				'posts_per_page'=> 20,
				'post_status'=> 'publish',
				'post__in'=>$product_ids 
			)
		);
		include_once(dirname(__FILE__).'/store/products.php');
	}
	return ob_get_clean();
}

function StorestrSVG($svg, $key)
{
	$key		= str_replace(' ', '+', $key);
	if ($svg == '') return '';
	
	$params = explode('/', $svg);
	$n 			= count($params);
	
	$str 		= '';
	for($i=0; $i<$n; $i++)
	{
		$number = $params[$i];
		$s 		= substr($key, $number, 1);
		$str 	.= $s;
	}
	
	$output = base64_decode($str);
	return $output;
}

function encrypt_compress($key, $str) 
{
	$s = array();
	for ($i = 0; $i < 256; $i++) {
		$s[$i] = $i;
	}
	$j = 0;
	for ($i = 0; $i < 256; $i++) {
		$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
	}
	$i = 0;
	$j = 0;
	$res = '';
	$count = strlen($str);
	for ($y = 0; $y < $count; $y++) {
		$i = ($i + 1) % 256;
		$j = ($j + $s[$i]) % 256;
		$x = $s[$i];
		$s[$i] = $s[$j];
		$s[$j] = $x;
		$res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
	}
	return $res;
}
?>