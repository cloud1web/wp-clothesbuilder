<?php
$settings = $dg->getSetting();
if(isset($settings->store))
{
	$store 		= $settings->store;
	if(isset($store->api) && $store->api != '')
	{
		// save data
		if( empty($store->verified) || ( isset($store->verified) && $store->verified ==0 ) )
		{
			$settings->store->verified = 1;
			$file_settings 				= ROOT .DS. 'data' .DS. 'settings.json';
			$dg->WriteFile($file_settings, json_encode($settings));
		}
		
		include_once(ROOT .DS. 'api.php');
		$api 		= new API($store->api);
		$keys	 	= $api->ini();
	}
}
?>