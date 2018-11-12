<?php
/**
 * @author tshirtecommerce - www.tshirtecommerce.com
 * @date: 2016-07-10
 *
 *
 * @copyright  Copyright (C) 2015 tshirtecommerce.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 *
 */
?>
<hr />
<h4>Auto Import Design</h4>
<p class="help-block">Save last design and auto import when customer access design page</small></p>
<div class="form-group row">
	<label class="col-sm-3 control-label">Allow Auto Import</label>
	<div class="col-sm-6">
		<?php
			echo displayRadio('enableAutoImport', $data['settings'], 'enableAutoImport', 0);
		?>
	</div>
</div>