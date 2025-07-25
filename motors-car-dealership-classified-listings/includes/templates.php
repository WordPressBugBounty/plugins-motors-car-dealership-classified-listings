<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Locate template in listings scope
 *
 * @param string|array $templates Single or array of template files
 *
 * @return string
 */
function stm_listings_locate_template( $templates ) {
	$located = false;

	foreach ( (array) $templates as $template ) {
		if ( substr( $template, - 4 ) !== '.php' ) {
			$template .= '.php';
		}

		if ( str_contains( $template, 'partials/' ) ) {
			$located = locate_template( $template );
		} else {
			$located = locate_template( 'listings/' . $template );
		}

		if ( ! ( $located ) ) {
			if ( file_exists( realpath( apply_filters( 'stm_listings_template_file', STM_LISTINGS_PATH, $template ) . '/templates/' . $template ) ) ) {
				$located = realpath( apply_filters( 'stm_listings_template_file', STM_LISTINGS_PATH, $template ) . '/templates/' . $template );
			}
		}

		if ( file_exists( $located ) ) {
			break;
		}
	}

	return apply_filters( 'stm_listings_locate_template', $located, $templates );
}

/**
 * Load template
 *
 * @param $__template
 * @param array $__vars
 */
function stm_listings_load_template( $__template, $__vars = array() ) {
	extract( $__vars );
	include stm_listings_locate_template( $__template );
}

add_action( 'stm_listings_load_template', 'stm_listings_load_template', 10, 2 );

/**
 * Load a template part into a template.
 *
 * The same as core WordPress get_template_part(), but also includes listings scope
 *
 * @param string $template
 * @param string $name
 * @param array $vars
 */
function stm_listings_template_part( $template, $name = '', $vars = array() ) {
	$templates = array();
	$name      = (string) $name;
	if ( '' !== $name ) {
		$templates[] = "{$template}-{$name}.php";
	}

	$templates[] = "{$template}.php";

	$located = stm_listings_locate_template( $templates );
	if ( $located ) {
		stm_listings_load_template( $located, $vars );
	}
}

add_filter( 'archive_template', 'stm_listings_archive_template', 5 );

function stm_listings_archive_template( $template ) {

	// Check for default listings post type
	if ( is_post_type_archive( apply_filters( 'stm_listings_post_type', 'listings' ) ) ) {
		$located = stm_listings_locate_template( 'archive.php' );
		if ( $located ) {
			$template = $located;
		}
	}

	// Check for motors-listing-types post types
	if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() ) {
		$slugs = STMMultiListing::stm_get_listing_type_slugs();
		if ( ! empty( $slugs ) && is_post_type_archive( $slugs ) ) {
			$located = stm_listings_locate_template( 'archive.php' );
			if ( $located ) {
				$template = $located;
			}
		}
	}

	return $template;
}

add_filter( 'page_template', 'stm_listings_archive_page_template' );

function stm_listings_archive_page_template( $template ) {
	global $post;
	if ( isset( $post->ID ) && apply_filters( 'stm_listings_user_defined_filter_page', '' ) === $post->ID ) {
		$content = $post->content;
		if ( has_shortcode( $content, 'stm_classic_filter' ) ) {
			$located = stm_listings_locate_template( 'archive.php' );
			if ( $located ) {
				$template = $located;
			}
		}
	}

	return $template;
}

add_filter( 'single_template', 'stm_get_single_listing_template', 5 );

function stm_get_single_listing_template( $template ) {
	mvl_enqueue_header_scripts_styles( 'motors-icons' );
	mvl_enqueue_header_scripts_styles( 'uniform' );
	mvl_enqueue_header_scripts_styles( 'motors-single-listing' );
	mvl_enqueue_header_scripts_styles( 'motors-datetimepicker' );

	$located = stm_listings_locate_template( 'single.php' );

	// Check for default listings post type
	if ( is_singular( 'listings' ) && $located ) {
		$template = $located;
	}

	// Check for motors-listing-types post types
	if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() ) {
		$slugs = STMMultiListing::stm_get_listing_type_slugs();
		if ( ! empty( $slugs ) && is_singular( $slugs ) && $located ) {
			$template = $located;
		}
	}

	return $template;
}

function stm_listings_load_results( $source = null, $type = null, $navigation_type = null ) {
	$GLOBALS['wp_query'] = stm_listings_query( $source );
	stm_listings_load_template(
		'filter/results',
		array(
			'type'            => $type,
			'navigation_type' => $navigation_type,
			'custom_img_size' => ( ! empty( $source['custom_img_size'] ) ) ? $source['custom_img_size'] : null,
			'posts_per_page'  => ( ! empty( $source['posts_per_page'] ) ) ? $source['posts_per_page'] : null,
		)
	);
}

add_action( 'stm_listings_load_results', 'stm_listings_load_results', 10, 3 );


function stm_listings_load_items_results( $source = null, $type = null, $navigation_type = null ) {
	$GLOBALS['wp_query'] = stm_listings_query( $source );
	stm_listings_load_template(
		'filter/results-items',
		array(
			'type'            => $type,
			'navigation_type' => $navigation_type,
		)
	);
}

/*
 * Used in Visual Composer stm_listing_search_with_car_rating
 * */
function stm_listings_vc_modul_load_results( $attr ) {
	$GLOBALS['wp_query'] = stm_listings_query( $attr );
	stm_listings_load_template( 'filter/result_with_rating' );
	wp_reset_postdata();
}

function stm_listings_load_pagination( $post_per_page ) {
	$GLOBALS['wp_query'] = stm_listings_query();
	stm_listings_load_template( 'filter/pagination', array( 'post_per_page' => $post_per_page ) );
	wp_reset_postdata();
}

//Author
add_filter( 'template_include', 'stm_author_template_loader' );

function stm_author_template_loader( $template ) {
	if ( is_author() ) {

		$located = stm_listings_locate_template( 'author.php' );
		if ( $located ) {
			$template = $located;
		}
	}

	return $template;
}

add_action( 'stm_inventory_loop_items_before', 'stm_inventory_loop_items_before' );
function stm_inventory_loop_items_before( $view_type ) {
	$skin = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'grid_card_skin' );
	if ( 'grid' === $view_type ) {
		echo wp_kses_post( '<div class="row row-3 car-listing-row car-listing-modern-grid' . ( 'default' !== $skin ? ' mvl-card-skins ' . $skin : '' ) . '">' );
	}
}

add_action( 'stm_inventory_loop_items_after', 'stm_inventory_loop_items_after' );
function stm_inventory_loop_items_after( $view_type ) {
	if ( 'grid' === $view_type ) {
		echo '</div>';
	}
}

add_action( 'stm_listing_image_hover_gallery', 'stm_listing_image_hover_gallery', 10, 4 );
function stm_listing_image_hover_gallery( $thumbs, $img_size = 'stm-img-255', $img_attrs = array(), $wrapped = true ) {
	$array_keys    = array_keys( $thumbs['gallery'] );
	$last_item_key = array_pop( $array_keys );
	?>
	<?php if ( $wrapped ) : ?>
	<div class="interactive-hoverable">
	<?php endif; ?>
		<div class="hoverable-wrap">
			<?php foreach ( $thumbs['gallery'] as $key => $img_url ) : ?>
				<div class="hoverable-unit <?php echo ( 0 === $key ) ? 'active' : ''; ?>">
					<div class="thumb">
						<?php if ( $key === $last_item_key && 5 === count( $thumbs['gallery'] ) && 0 < $thumbs['remaining'] ) : ?>
							<div class="remaining">
								<i class="stm-icon-album"></i>
								<p>
									<?php
									echo esc_html(
										sprintf(
										/* translators: number of remaining photos */
											_n( '%d more photo', '%d more photos', $thumbs['remaining'], 'stm_vehicles_listing' ),
											$thumbs['remaining']
										)
									);
									?>
								</p>
							</div>
						<?php endif; ?>
						<?php echo wp_kses_post( wp_get_attachment_image( $thumbs['ids'][ $key ], $img_size, false, $img_attrs ) ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="hoverable-indicators">
			<?php foreach ( $thumbs['gallery'] as $key => $thumb ) : ?>
				<div class="indicator <?php echo ( 0 === $key ) ? 'active' : ''; ?>"></div>
			<?php endforeach; ?>
		</div>
	<?php if ( $wrapped ) : ?>
	</div>
	<?php endif; ?>
	<?php
}
