<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2015-12-10
 * 
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */

$addons->path_data = dirname(ROOT) .DS. 'data';
$settings 	= $addons->getSetting();
if (isset($settings->store) && isset($settings->store->enable) && $settings->store->enable == 1)
{
 ?>
<li <?php if($data[0] == 'store') echo 'class="active open"' ?>>
	<a href='javascript:void(0)'>
		<i class='fa fa-database'></i>
		<span class='title'><?php echo $addons->__('addon_store_menu_title') ?></span>
		<i class='icon-arrow'></i>
		<span class='selected'></span>
		<script type='text/javascript' src='<?php echo site_url('assets/plugins/stores/chart.min.js', false); ?>'></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				store.charts.ini();
			});
		</script>
	</a>
	<ul class='sub-menu'>
		<li <?php if($data[0] == 'store' && isset($data[2]) && $data[2] == 'art') echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/category/art'); ?>'>
				<i class="fa fa-folder-open-o" aria-hidden="true"></i> <span class='title'> Clipart Categories</span>
			</a>
		</li>
		<li <?php if( ($data[0] == 'store' && isset($data[1]) && $data[1] == 'arts') || (isset($data[2]) && $data[2] == 'arts') ) echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/arts'); ?>'>
				<i class="clip-pictures"></i> <span class='title'>Cliparts</span>
			</a>
		</li>
		<li <?php if($data[0] == 'store' && isset($data[2]) && $data[2] == 'idea') echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/category/idea'); ?>'>
				<i class="fa fa-folder-open-o" aria-hidden="true"></i> <span class='title'> Design Categories</span>
			</a>
		</li>
		<li <?php if($data[0] == 'store' && isset($data[1]) && ($data[1] == 'ideas' || $data[1] == 'idea')) echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/ideas'); ?>'>
				<i class="fa fa-lightbulb-o" aria-hidden="true"></i> <span class='title'> <?php echo $addons->__('addon_design_temp_menu') ?></span>
			</a>
		</li>
		
		<li <?php if($data[0] == 'store' && isset($data[1]) && $data[1] == 'types') echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/types'); ?>'>
				<i class="fa fa-folder" aria-hidden="true"></i> <span class='title'> Design Types</span>
			</a>
		</li>
		
		<li <?php if($data[0] == 'store' && isset($data[1]) && $data[1] == 'fields') echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/fields'); ?>'>
				<i class="clip-puzzle-4" aria-hidden="true"></i> <span class='title'> Fields</span>
			</a>
		</li>
		
		<li <?php if($data[0] == 'store' && isset($data[1]) && $data[1] == 'charts') echo 'class="active open"' ?>>
			<a href='<?php echo site_url('index.php/store/charts'); ?>'>
				<i class="clip-bars" aria-hidden="true"></i> <span class='title'> Charts</span>
			</a>
		</li>
	</ul>
</li>
<?php } ?>
 