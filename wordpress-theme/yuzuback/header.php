<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#f6f7f2">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="wrap nav">
	<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="YuzuBack home">
		<span class="brand-mark" aria-hidden="true">Y</span>
		YuzuBack
	</a>
	<nav class="nav-links" aria-label="Main navigation">
		<a class="<?php echo yuzuback_current_page( 'overview' ) ? 'active' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo yuzuback_current_page( 'overview' ) ? 'aria-current="page"' : ''; ?>>Overview</a>
		<a class="<?php echo yuzuback_current_page( 'downloads' ) ? 'active' : ''; ?>" href="<?php echo esc_url( yuzuback_page_url( 'downloads' ) ); ?>" <?php echo yuzuback_current_page( 'downloads' ) ? 'aria-current="page"' : ''; ?>>Downloads</a>
		<a class="<?php echo yuzuback_current_page( 'changelog' ) ? 'active' : ''; ?>" href="<?php echo esc_url( yuzuback_page_url( 'changelog' ) ); ?>" <?php echo yuzuback_current_page( 'changelog' ) ? 'aria-current="page"' : ''; ?>>Changelog</a>
		<a class="<?php echo yuzuback_current_page( 'contact' ) ? 'active' : ''; ?>" href="<?php echo esc_url( yuzuback_page_url( 'contact' ) ); ?>" <?php echo yuzuback_current_page( 'contact' ) ? 'aria-current="page"' : ''; ?>>Contact</a>
		<a class="nav-cta" href="<?php echo esc_url( yuzuback_page_url( 'donate' ) ); ?>">Support the project <span aria-hidden="true">↗</span></a>
	</nav>
	<details class="mobile-menu">
		<summary aria-label="Open navigation">Menu</summary>
		<nav class="mobile-menu-list" aria-label="Mobile navigation">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Overview</a>
			<a href="<?php echo esc_url( yuzuback_page_url( 'downloads' ) ); ?>">Downloads</a>
			<a href="<?php echo esc_url( yuzuback_page_url( 'changelog' ) ); ?>">Changelog</a>
			<a href="<?php echo esc_url( yuzuback_page_url( 'contact' ) ); ?>">Contact</a>
			<a href="<?php echo esc_url( yuzuback_page_url( 'donate' ) ); ?>">Donate</a>
		</nav>
	</details>
</header>
<main>