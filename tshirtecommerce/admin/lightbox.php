<html lang="en" class="no-js">
<head>
	<meta charset="utf-8" />
	<link href="<?php echo site_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="<?php echo site_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/fonts/style.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/main.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/main-responsive.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/css/theme_light.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('assets/plugins/jquery-ui/jquery-ui.min.css'); ?>">
	
	<?php echo $addons->css(); ?>
	<!--[if IE 7]>
	<link rel="stylesheet" href="<?php echo site_url('assets/plugins/font-awesome/css/font-awesome-ie7.min.css'); ?>">
	<![endif]-->
	<!-- end: MAIN CSS -->		
	<script src="<?php echo site_url('assets/js/jquery.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>	
	<?php echo $addons->js(); ?>
</head>
<body style="background: #fff!important;">
	<!-- start: MAIN CONTAINER -->
	<div class="main-container" style="background: #fff!important;">		
		<?php echo $content; ?>
	</div>
	<!-- end: MAIN CONTAINER -->
	<script src="<?php echo site_url('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/blockUI/jquery.blockUI.js'); ?>"></script>
	<script src="<?php echo site_url('assets/js/main.js'); ?>"></script>
	<script src="<?php echo site_url('assets/plugins/validate/validate.js'); ?>"></script>
	<script>
		jQuery(document).ready(function() {
			Main.init();				
		});
	</script>			
</body>
<!-- end: BODY -->
</html>