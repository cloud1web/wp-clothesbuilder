<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: November 26 2015; December 01 2015
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
*/
if(isset($params['data']['variation_id']))
{
	$product = $params['product'];
	if(isset($product->prices_variations))
	{
		$prices			= json_decode($product->prices_variations, true);
		$variation_id 	= $params['data']['variation_id'];
		
		if( isset($prices[$variation_id]) )
		{
			$product->price = $prices[$variation_id];
		}
		
		$GLOBALS['product'] = $product;
	}
}
?>