<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="en"> 		   <![endif]-->

<head>
	<meta charset="utf-8">

	<!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $template['title'].' - '.lang('cp:admin_title') ?></title>

	<base href="<?php echo base_url(); ?>" />

	<!-- Mobile viewport optimized -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

	<!-- CSS. No need to specify the media attribute unless specifically targeting a media type, leaving blank implies media=all -->
	<?php echo Asset::css('plugins.css'); ?>
	<?php echo Asset::css('workless/workless.css'); ?>
	<?php echo Asset::css('workless/application.css'); ?>
	<?php echo Asset::css('workless/responsive.css'); ?>
        <?php
        $vars = $this->load->_ci_cached_vars;
        if ($vars['lang']['direction']=='rtl'){
            echo Asset::css('workless/rtl/rtl.css');
        }
        ?>
	<!-- End CSS-->

	<!-- Load up some favicons -->
	<link rel="shortcut icon" href="<?php echo Asset::get_filepath_img('favicon.ico'); ?>">
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-57x57-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon" href="apple-touch-icon-114x114-precomposed.png">

	<!-- metadata needs to load before some stuff -->
	<?php file_partial('metadata'); ?>

</head>

<body>

	<div id="container">

		<section id="content">
			
			<header class="hide-on-ckeditor-maximize">
			<?php file_partial('header'); ?>
			</header>

			<div id="content-body">
				<?php file_partial('notices'); ?>
				<?php echo $template['body']; ?>
			</div>

		</section>

	</div>

	<aside id="nav-aside">
		<ul class="nav">

			<li><?php echo anchor('admin', lang('global:dashboard'), 'class="top-link '.( ! $this->module ? 'current ' : '').'"') ?></li>

			<?php

			// Display the menu items.
			// We have already vetted them for permissions
			// in the Admin_Controller, so we can just
			// display them now.
			foreach ($menu_items as $key => $menu_item)
			{
				if (is_array($menu_item))
				{
					echo '<li><a href="'.current_url().'#" class="top-link">'.lang_label($key).'</a><ul>';

					foreach ($menu_item as $lang_key => $uri)
					{
						echo '<li><a href="'.site_url($uri).'" class="">'.lang_label($lang_key).'</a></li>';

					}

					echo '</ul></li>';

				}
				elseif (is_array($menu_item) and count($menu_item) == 1)
				{
					foreach ($menu_item as $lang_key => $uri)
					{
						echo '<li><a href="'.site_url($menu_item).'" class="top-link no-submenu">'.lang_label($lang_key).'</a></li>';
					}
				}
				elseif (is_string($menu_item))
				{
					echo '<li><a href="'.site_url($menu_item).'" class="top-link no-submenu">'.lang_label($key).'</a></li>';
				}

			}

			?>

		</ul>
	</aside>

	<footer class="clearfix">
		<div class="wrapper">
			<p class="credits">Copyright &copy;<?php echo date('Y'); ?> HongliangIT.com 鴻量資訊有限公司</p>

			<ul id="lang">
				<form action="<?php echo current_url(); ?>" id="change_language" method="get">
					<select class="chzn" name="lang" onchange="this.form.submit();">
						<?php foreach(config_item('supported_languages') as $key => $lang): ?>
							<option value="<?php echo $key; ?>" <?php echo CURRENT_LANGUAGE == $key ? ' selected="selected" ' : ''; ?>>
								 <?php echo $lang['name']; ?>
							</option>
						<?php endforeach; ?>
					</select>
				</form>
			</ul>
		</div>
	</footer>

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6. chromium.org/developers/how-tos/chrome-frame-getting-started -->
	<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
	<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

</body>
</html>