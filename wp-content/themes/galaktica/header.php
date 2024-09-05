<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="bg-dark text-white py-3">
	<div class="container">
		<div class="d-flex justify-content-between align-items-center">
			<div class="logo">
				<?php
				if (function_exists('the_custom_logo')) {
					the_custom_logo();
				}
				?>
			</div>
			<nav class="navbar navbar-expand-lg navbar-dark">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'primary',
						'container' => false,
						'menu_class' => 'navbar-nav ml-auto',
						'fallback_cb' => false,
						'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					));
					?>
				</div>
			</nav>
		</div>
	</div>
</header>
<?php wp_footer(); ?>
</body>
</html>
