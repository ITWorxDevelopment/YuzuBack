<?php

function yuzuback_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_editor_style( 'assets/css/site.css' );
	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'yuzuback' ),
		)
	);
}
add_action( 'after_setup_theme', 'yuzuback_setup' );

function yuzuback_enqueue_assets() {
	wp_enqueue_style(
		'yuzuback-site',
		get_theme_file_uri( '/assets/css/site.css' ),
		array(),
		wp_get_theme()->get( 'Version' )
	);
	wp_enqueue_script(
		'yuzuback-carousel',
		get_theme_file_uri( '/assets/js/carousel.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'yuzuback_enqueue_assets' );

function yuzuback_document_title_parts( $parts ) {
	$parts['site'] = 'YuzuBack';
	if ( is_front_page() ) {
		$parts['title'] = 'Backups, without the busywork.';
	}
	return $parts;
}
add_filter( 'document_title_parts', 'yuzuback_document_title_parts' );

function yuzuback_meta_description() {
	$descriptions = array(
		'front'     => 'YuzuBack is a free desktop app that makes restic backups easier to create, monitor, and restore. Built for Windows and Linux.',
		'downloads' => 'Download YuzuBack for Windows or Linux. YuzuBack is free to download and use.',
		'changelog' => 'Read YuzuBack release notes and changelog.',
		'contact'   => 'Contact the YuzuBack project with questions, feedback, and bug reports.',
		'donate'    => 'Support the development of YuzuBack with an optional donation.',
	);
	$key = is_front_page() ? 'front' : ( is_page() ? get_post_field( 'post_name', get_queried_object_id() ) : '' );
	if ( isset( $descriptions[ $key ] ) ) {
		echo '<meta name="description" content="' . esc_attr( $descriptions[ $key ] ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'yuzuback_meta_description', 1 );

function yuzuback_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

function yuzuback_block( $name, $attributes, $content ) {
	$encoded_attributes = empty( $attributes ) ? '' : ' ' . wp_json_encode( $attributes );
	return "<!-- wp:$name$encoded_attributes -->\n$content\n<!-- /wp:$name -->\n";
}

function yuzuback_group_block( $classes, $content ) {
	$attributes = array();
	$class_attribute = '';
	if ( $classes ) {
		$attributes['className'] = $classes;
		$class_attribute = ' ' . esc_attr( $classes );
	}
	$wrapper = '<div class="wp-block-group' . $class_attribute . '">' . $content . '</div>';
	return yuzuback_block( 'group', $attributes, $wrapper );
}

function yuzuback_paragraph_block( $content, $classes = '' ) {
	$attributes = $classes ? array( 'className' => $classes ) : array();
	$class_attribute = $classes ? ' class="' . esc_attr( $classes ) . '"' : '';
	return yuzuback_block( 'paragraph', $attributes, '<p' . $class_attribute . '>' . $content . '</p>' );
}

function yuzuback_heading_block( $level, $content ) {
	return yuzuback_block( 'heading', array( 'level' => $level ), '<h' . $level . ' class="wp-block-heading">' . $content . '</h' . $level . '>' );
}

function yuzuback_button_block( $label, $url, $classes = '' ) {
	$attributes = array( 'url' => $url );
	if ( $classes ) {
		$attributes['className'] = $classes;
	}
	$outer_class = 'wp-block-button' . ( $classes ? ' ' . esc_attr( $classes ) : '' );
	$link = '<div class="' . $outer_class . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $url ) . '">' . $label . '</a></div>';
	return yuzuback_block( 'button', $attributes, $link );
}

function yuzuback_buttons_block( $content, $classes = '' ) {
	$attributes = array();
	$class_attribute = '';
	if ( $classes ) {
		$attributes['className'] = $classes;
		$class_attribute = ' ' . esc_attr( $classes );
	}
	$wrapper = '<div class="wp-block-buttons' . $class_attribute . '">' . $content . '</div>';
	return yuzuback_block( 'buttons', $attributes, $wrapper );
}

function yuzuback_image_block( $filename, $alt ) {
	$url = yuzuback_image( $filename );
	$attributes = array( 'sizeSlug' => 'large', 'linkDestination' => 'none' );
	$image = '<figure class="wp-block-image size-large"><img src="' . $url . '" alt="' . esc_attr( $alt ) . '"/></figure>';
	return yuzuback_block( 'image', $attributes, $image );
}

function yuzuback_section_heading_blocks( $eyebrow, $title, $description ) {
	$heading = yuzuback_group_block(
		'section-head-copy',
		yuzuback_paragraph_block( esc_html( $eyebrow ), 'kicker' ) .
		yuzuback_heading_block( 2, esc_html( $title ) )
	);
	return yuzuback_group_block( 'section-head', $heading . yuzuback_paragraph_block( esc_html( $description ) ) );
}

function yuzuback_carousel_shortcode() {
	$slides = array(
		array( 'yuzuback-main-window.png', 'YuzuBack main window showing backup profiles and snapshot controls', 'YuzuBack running on Linux' ),
		array( 'yuzuback-snapshots.png', 'YuzuBack snapshot list with backup and restore actions', 'Review and restore snapshots' ),
		array( 'yuzuback-theme-settings.png', 'YuzuBack appearance settings with dark theme selected', 'Choose an appearance that suits you' ),
	);
	$markup = '<div class="carousel" data-carousel aria-label="YuzuBack app screenshots"><div class="carousel-stage">';
	foreach ( $slides as $index => $slide ) {
		$active = 0 === $index ? ' is-active' : '';
		$markup .= '<article class="carousel-slide' . $active . '" data-caption="' . esc_attr( $slide[2] ) . '"><img src="' . yuzuback_image( $slide[0] ) . '" alt="' . esc_attr( $slide[1] ) . '"></article>';
	}
	$markup .= '</div><div class="carousel-controls"><button class="carousel-arrow" type="button" data-carousel-prev aria-label="Previous screenshot">&#8592;</button><div class="carousel-dots" aria-label="Choose a screenshot">';
	foreach ( $slides as $index => $slide ) {
		$active = 0 === $index ? ' is-active' : '';
		$pressed = 0 === $index ? 'true' : 'false';
		$markup .= '<button class="carousel-dot' . $active . '" type="button" data-carousel-slide="' . $index . '" aria-label="Show screenshot ' . ( $index + 1 ) . ' of 3" aria-pressed="' . $pressed . '"></button>';
	}
	$markup .= '</div><button class="carousel-arrow" type="button" data-carousel-next aria-label="Next screenshot">&#8594;</button></div></div><p class="preview-caption" data-carousel-caption aria-live="polite">YuzuBack running on Linux</p>';
	return $markup;
}
add_shortcode( 'yuzuback_carousel', 'yuzuback_carousel_shortcode' );

function yuzuback_default_page_content( $slug ) {
	$hero_content = array(
		'downloads' => array( 'Choose your platform', 'Get YuzuBack.', 'YuzuBack is free to download and use. Choose the version for your computer; donations are optional and help support continued development.' ),
		'changelog' => array( "What's new", 'Changelog.', 'Release notes and updates for YuzuBack.' ),
		'contact'   => array( 'Get in touch', 'Contact YuzuBack.', "Questions, feedback, and bug reports help make the app better. This page is ready for the project's preferred support channels." ),
		'donate'    => array( 'Optional, always appreciated', 'Help YuzuBack keep going.', 'YuzuBack is free to use. If it has made your backup routine easier, an optional donation can help support future maintenance and improvements.' ),
	);

	if ( 'overview' === $slug ) {
		$hero_copy = yuzuback_group_block(
			'hero-copy',
			yuzuback_paragraph_block( 'A calmer way to manage backups', 'eyebrow' ) .
			yuzuback_heading_block( 1, 'Backups, without the <em>busywork.</em>' ) .
			yuzuback_paragraph_block( 'YuzuBack is a free desktop app that makes restic backups easier to create, monitor, and restore. Built for Windows and Linux.' ) .
			yuzuback_buttons_block( yuzuback_button_block( 'Get YuzuBack ↗', yuzuback_page_url( 'downloads' ), 'button lime' ) . yuzuback_button_block( 'See how it works', '#how-it-works', 'button line' ), 'hero-actions' ) .
			yuzuback_paragraph_block( '<strong>Free to use</strong> · <strong>Windows + Linux</strong> · Donations optional', 'trust' )
		);
		$preview = yuzuback_group_block( 'preview-shell real-shot', yuzuback_block( 'shortcode', array(), '[yuzuback_carousel]' ) );
		$blocks = yuzuback_group_block( 'wrap hero', $hero_copy . $preview );

		$screenshots = array(
			array( 'yuzuback-snapshots.png', 'YuzuBack snapshot list with backup and restore actions', 'Browse snapshots', 'Review saved snapshots and open common actions from one screen.' ),
			array( 'yuzuback-import-export.png', 'YuzuBack preferences showing import and export settings', 'Import and export settings', 'Use the settings panel to move preferences between installations.' ),
			array( 'yuzuback-theme-settings.png', 'YuzuBack appearance settings with dark theme selected', 'Choose your appearance', 'Select a light or dark theme in preferences.' ),
		);
		$cards = '';
		foreach ( $screenshots as $screenshot ) {
			$cards .= yuzuback_group_block( 'screenshot-card', yuzuback_image_block( $screenshot[0], $screenshot[1] ) . yuzuback_group_block( '', yuzuback_heading_block( 3, esc_html( $screenshot[2] ) ) . yuzuback_paragraph_block( esc_html( $screenshot[3] ) ) ) );
		}
		$blocks .= yuzuback_group_block( 'section white', yuzuback_group_block( 'wrap', yuzuback_section_heading_blocks( 'A look inside YuzuBack', 'Real screens. Clear workflows.', 'Explore the backup list, snapshot history, and settings in the desktop app.' ) . yuzuback_group_block( 'screenshot-grid', $cards ) ) );

		$features = array(
			array( '◉', 'Profiles for your backups', 'Organize backup settings by task and run them again without rebuilding the command each time.' ),
			array( '◷', 'See your snapshots', 'Browse available snapshots and choose what you want to restore from a visual list.' ),
			array( '↶', 'Restore with a plan', 'Use a guided restore flow to choose the snapshot and destination before bringing files back.' ),
			array( '⌘', 'Use restic, visually', 'Manage core restic backup and repository tasks through a desktop interface built for everyday use.' ),
			array( '⚙', 'Make it fit your routine', 'Configure retention and optional pre-backup or post-backup scripts for your workflow.' ),
			array( '⇄', 'Windows and Linux', 'Use the same app across the two desktop platforms supported by YuzuBack.' ),
		);
		$feature_blocks = '';
		foreach ( $features as $feature ) {
			$feature_blocks .= yuzuback_group_block( 'feature', yuzuback_paragraph_block( esc_html( $feature[0] ), 'feature-icon' ) . yuzuback_heading_block( 3, esc_html( $feature[1] ) ) . yuzuback_paragraph_block( esc_html( $feature[2] ) ) );
		}
		$blocks .= yuzuback_group_block( 'section white', yuzuback_group_block( 'wrap', yuzuback_section_heading_blocks( 'The essentials', 'More control. Less command line.', 'Keep common backup and restore tasks in one clear desktop workflow, with restic doing the work underneath.' ) . yuzuback_group_block( 'feature-grid', $feature_blocks ) ) );

		$steps = array(
			array( '01 / CONFIGURE', 'Create a profile', 'Choose what to back up and configure the repository and options for that job.' ),
			array( '02 / RUN', 'Make and review backups', "Start a backup and keep an eye on the profile's snapshot history." ),
			array( '03 / RESTORE', 'Bring files back', 'Select a snapshot, choose a restore destination, and follow the guided process.' ),
		);
		$step_blocks = '';
		foreach ( $steps as $step ) {
			$step_blocks .= yuzuback_group_block( 'step', yuzuback_paragraph_block( esc_html( $step[0] ), 'step-label' ) . yuzuback_heading_block( 3, esc_html( $step[1] ) ) . yuzuback_paragraph_block( esc_html( $step[2] ) ) );
		}
		$blocks .= yuzuback_group_block( 'section', yuzuback_group_block( 'wrap', yuzuback_section_heading_blocks( 'A straightforward workflow', 'From setup to restore.', 'YuzuBack brings the steps into one place, so you can focus on protecting the files that matter.' ) . yuzuback_group_block( 'steps', $step_blocks ) ) );
		$callout = yuzuback_group_block( 'callout', yuzuback_group_block( 'callout-copy', yuzuback_paragraph_block( 'Free desktop software', 'kicker' ) . yuzuback_heading_block( 2, 'Give your backups a<br>clearer home.' ) . yuzuback_paragraph_block( 'Download YuzuBack for Windows or Linux. Donations are always optional.' ) ) . yuzuback_buttons_block( yuzuback_button_block( 'Explore downloads ↗', yuzuback_page_url( 'downloads' ), 'button lime' ) ) );
		$blocks .= yuzuback_group_block( 'section white', yuzuback_group_block( 'wrap', $callout ) );
		return $blocks;
	}

	if ( ! isset( $hero_content[ $slug ] ) ) {
		return '';
	}

	list( $eyebrow, $title, $description ) = $hero_content[ $slug ];
	$blocks = yuzuback_group_block( 'wrap page-hero', yuzuback_paragraph_block( esc_html( $eyebrow ), 'eyebrow' ) . yuzuback_heading_block( 1, esc_html( $title ) ) . yuzuback_paragraph_block( esc_html( $description ) ) );

	if ( 'downloads' === $slug ) {
		$windows = yuzuback_group_block( 'download-card', yuzuback_group_block( 'card-top', yuzuback_paragraph_block( '⊞', 'platform-icon' ) . yuzuback_heading_block( 2, 'Windows' ) ) . yuzuback_paragraph_block( 'Windows desktop', 'tag' ) . yuzuback_paragraph_block( 'Download the YuzuBack installer for Windows. Check the release notes for version details before installing.' ) . yuzuback_paragraph_block( 'Windows download link will be added here when the release URL is confirmed.', 'pending' ) );
		$linux = yuzuback_group_block( 'download-card', yuzuback_group_block( 'card-top', yuzuback_paragraph_block( '◈', 'platform-icon' ) . yuzuback_heading_block( 2, 'Linux' ) ) . yuzuback_paragraph_block( 'Linux desktop', 'tag' ) . yuzuback_paragraph_block( 'Download the Linux build of YuzuBack. Package format and system requirements will be listed with each release.' ) . yuzuback_paragraph_block( 'Linux download link will be added here when the release URL is confirmed.', 'pending' ) );
		$blocks .= yuzuback_group_block( 'wrap content', yuzuback_group_block( 'card-grid', $windows . $linux ) . yuzuback_paragraph_block( '<strong>Before installing:</strong> Use the release notes for the version number, platform requirements, and any upgrade guidance. YuzuBack is proprietary freeware; its source code is not published.', 'note' ) . yuzuback_buttons_block( yuzuback_button_block( 'Read the changelog ↗', yuzuback_page_url( 'changelog' ), 'button line' ) ) );
	} elseif ( 'changelog' === $slug ) {
		$current = '<!-- wp:list --><ul class="wp-block-list"><li>Updated the Windows package to use restic 0.19.1 instead of the earlier 0.18.x line.</li><li>Updated the application build for Qt 6.12.0 LTS.</li><li>Included minor bug fixes and compatibility updates.</li></ul><!-- /wp:list -->';
		$release_one = yuzuback_paragraph_block( 'CURRENT RELEASE<br>Version/date pending', 'release-date' ) . yuzuback_group_block( 'release-content', yuzuback_heading_block( 2, 'Compatibility and maintenance' ) . $current );
		$release_two = yuzuback_paragraph_block( 'PREVIOUS RELEASES<br>Archive pending', 'release-date' ) . yuzuback_group_block( 'release-content', yuzuback_heading_block( 2, 'Earlier releases' ) . yuzuback_paragraph_block( 'Previous YuzuBack release notes will be added as the project archive is prepared.' ) );
		$blocks .= yuzuback_group_block( 'wrap content', yuzuback_paragraph_block( '<strong>Release number and publication date are being confirmed.</strong> The notes below summarize changes planned for the current release; they will be updated with the official version details and links.', 'note' ) . yuzuback_group_block( 'release', $release_one ) . yuzuback_group_block( 'release', $release_two ) . yuzuback_paragraph_block( 'Release version numbers, dates, and detailed notes will be added after confirmation against the published release artifacts.', 'legal' ) . yuzuback_buttons_block( yuzuback_button_block( 'Go to downloads ↗', yuzuback_page_url( 'downloads' ), 'button line' ) ) );
	} elseif ( 'contact' === $slug ) {
		$topics = array(
			array( 'Bug report', 'Include your YuzuBack version, operating system, and steps to reproduce the issue.' ),
			array( 'Feature idea', 'Describe the task you want YuzuBack to make easier and how you handle it today.' ),
			array( 'General question', 'For setup and restore questions, include the relevant profile and restic details, without sharing passwords or repository keys.' ),
		);
		$topic_blocks = '';
		foreach ( $topics as $topic ) {
			$topic_blocks .= yuzuback_group_block( 'contact-row', yuzuback_paragraph_block( '<strong>' . esc_html( $topic[0] ) . '</strong><br>' . esc_html( $topic[1] ) ) );
		}
		$support = yuzuback_group_block( 'content-card', yuzuback_paragraph_block( 'Support', 'kicker' ) . yuzuback_heading_block( 2, 'How can we help?' ) . $topic_blocks );
		$channel = yuzuback_group_block( 'content-card', yuzuback_paragraph_block( 'Contact channel', 'kicker' ) . yuzuback_heading_block( 2, 'Support address pending' ) . yuzuback_paragraph_block( 'The official contact email or issue tracker has not been supplied yet.' ) . yuzuback_paragraph_block( "Add the project's support email or public issue tracker here before sharing this page with visitors.", 'notice' ) );
		$blocks .= yuzuback_group_block( 'wrap content contact-layout', $support . $channel );
	} else {
		$donation = yuzuback_group_block( 'donate-box', yuzuback_paragraph_block( 'Support independent software', 'kicker' ) . yuzuback_heading_block( 2, 'Use it for free. Give if you want.' ) . yuzuback_paragraph_block( 'Donations are voluntary and do not unlock paid features. The app remains free to use whether or not you contribute.' ) . yuzuback_paragraph_block( "Donation link will be added here when the project's preferred platform is confirmed.", 'pending' ) );
		$transparency = yuzuback_group_block( 'content-card transparency-card', yuzuback_paragraph_block( 'Transparency', 'kicker' ) . yuzuback_paragraph_block( 'YuzuBack is proprietary freeware. Donations support the project but do not change the app\'s licensing or provide ownership rights.' ) );
		$blocks .= yuzuback_group_block( 'wrap content', $donation . $transparency );
	}

	return $blocks;
}

function yuzuback_create_pages() {
	$pages = array(
		'overview'  => 'Overview',
		'downloads' => 'Downloads',
		'changelog' => 'Changelog',
		'contact'   => 'Contact',
		'donate'    => 'Donate',
	);
	$page_ids = array();

	foreach ( $pages as $slug => $title ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $page ) {
			$page_id = wp_insert_post(
				array(
					'post_title'  => $title,
					'post_name'   => $slug,
					'post_status' => 'publish',
					'post_type'   => 'page',
				)
			);
			if ( ! is_wp_error( $page_id ) ) {
				$page_ids[ $slug ] = $page_id;
			}
		} else {
			$page_ids[ $slug ] = $page->ID;
		}

		if ( isset( $page_ids[ $slug ] ) ) {
			$current_page = get_post( $page_ids[ $slug ] );
			if ( $current_page && '' === trim( $current_page->post_content ) ) {
				$default_content = yuzuback_default_page_content( $slug );
				if ( $default_content ) {
					wp_update_post(
						array(
							'ID'           => $page_ids[ $slug ],
							'post_content' => $default_content,
						)
					);
				}
			}
		}
	}

	if ( isset( $page_ids['overview'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['overview'] );
		update_option( 'page_for_posts', 0 );
	}
}
add_action( 'after_switch_theme', 'yuzuback_create_pages' );

function yuzuback_image( $filename ) {
	return esc_url( get_theme_file_uri( '/assets/images/' . $filename ) );
}

function yuzuback_current_page( $slug ) {
	return ( 'overview' === $slug && is_front_page() ) || is_page( $slug );
}