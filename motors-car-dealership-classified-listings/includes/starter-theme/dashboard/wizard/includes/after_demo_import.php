<?php

// Update Elementor header footer settings
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_trigger_resave_after_demo_import', 10, 4 );

function mvl_motors_trigger_resave_after_demo_import() {
	$hf_query = new WP_Query(
		array(
			'post_type'      => 'elementor-hf',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		)
	);

	if ( $hf_query->have_posts() ) {
		remove_action( 'save_post', 'motors_resave_elementor_template' );

		while ( $hf_query->have_posts() ) {
			$hf_query->the_post();
			$hf_id = get_the_ID();

			if ( ! wp_is_post_revision( $hf_id ) ) {
				wp_update_post(
					array(
						'ID'          => $hf_id,
						'post_status' => 'publish',
					)
				);
			}
		}

		add_action( 'save_post', 'motors_resave_elementor_template' );
		wp_reset_postdata();
	}
}

//Update menu location
add_action( 'mvl_motors_starter_after_demo_import', 'mvl_motors_starter_update_menu_location', 10, 4 );

function mvl_motors_starter_update_menu_location() {
	$locations = get_theme_mod( 'nav_menu_locations' );
	$menus     = wp_get_nav_menus();

	if ( ! empty( $menus ) ) {
		foreach ( $menus as $menu ) {
			$menu_names = array(
				'Motors Skins Main Menu',
			);

			if ( is_object( $menu ) && in_array( $menu->name, $menu_names, true ) ) {
				$locations['motors-starter-theme-main-menu'] = $menu->term_id;
			}
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
