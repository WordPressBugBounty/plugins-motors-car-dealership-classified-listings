<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$posts_per_page   = apply_filters( 'motors_vl_get_nuxy_mod', 6, 'post_per_page_user_inventory' );
$page             = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;//phpcs:ignore WordPress.Security.NonceVerification.Recommended
$offset           = $posts_per_page * ( $page - 1 );
$show_more_button = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_more_button_user_profile' );

$query = stm_user_listings_query( $user_id, 'any', $posts_per_page, false, $offset );

if ( $query->have_posts() ) : ?>
	<div class="archive-listing-page">
		<h1><?php esc_html_e( 'My Listings', 'stm_vehicles_listing' ); ?></h1>
		<div class="car-listing-row">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<div class="stm_listing_edit_car <?php echo esc_attr( get_post_status( get_the_id() ) ); ?>">
					<?php do_action( 'stm_listings_load_template', 'listing-cars/listing-list-directory-edit-loop' ); ?>
				</div>
			<?php endwhile; ?>
		</div>

		<?php
		if ( $query->found_posts > $posts_per_page && $show_more_button ) :
			?>
			<div class="stm-load-more-dealer-cars">
				<a data-offset="<?php echo esc_attr( $posts_per_page ); ?>"
					data-profile="1"
					data-user="<?php echo esc_attr( get_current_user_id() ); ?>" data-popular="no" href="#"
					class="heading-font"><span><?php esc_html_e( 'Show more', 'stm_vehicles_listing' ); ?></span></a>
			</div>
			<?php
			else :
				echo paginate_links( //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'type'           => 'list',
						'format'         => '?page=%#%',
						'current'        => $page,
						'total'          => $query->max_num_pages,
						'posts_per_page' => $posts_per_page,
						'prev_text'      => '<i class="fas fa-angle-left"></i>',
						'next_text'      => '<i class="fas fa-angle-right"></i>',
					)
				);
		endif;
			?>
	</div>
<?php else : ?>
	<h4 class="stm-seller-title"><?php esc_html_e( 'No Inventory yet', 'stm_vehicles_listing' ); ?></h4>
<?php endif; ?>
