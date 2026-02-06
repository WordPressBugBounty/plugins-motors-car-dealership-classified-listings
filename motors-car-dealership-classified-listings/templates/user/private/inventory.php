<?php
mvl_enqueue_header_scripts_styles( 'stmselect2' );
mvl_enqueue_header_scripts_styles( 'app-select2' );
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$posts_per_page   = apply_filters( 'motors_vl_get_nuxy_mod', 6, 'post_per_page_user_inventory' );
$page             = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1;//phpcs:ignore WordPress.Security.NonceVerification.Recommended
$offset           = $posts_per_page * ( $page - 1 );
$show_more_button = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_more_button_user_profile' );

$query = stm_user_listings_query( $user_id, 'any', $posts_per_page, false, $offset );
$skin  = apply_filters( 'motors_vl_get_nuxy_mod', 'default', 'list_card_skin' );

if ( $query->have_posts() ) : ?>
	<div class="archive-listing-page">
		<div class="archive-listing-page-heading">
			<div class="stm-sort-private-my-cars">
			<?php if ( stm_is_multilisting() ) : ?>
				<?php
				$listings = stm_listings_multi_type( true );
				if ( ! empty( $listings ) ) :
					?>
					<div class="select-type select-listing-type" style="margin-right: 15px;">
						<div class="stm-label-type"><?php esc_html_e( 'Listing type', 'stm_vehicles_listing' ); ?></div>
						<select data-user="<?php echo esc_attr( $user_id ); ?>" data-user-private="1">
							<option value="all" selected><?php esc_html_e( 'All listing types', 'stm_vehicles_listing' ); ?></option>
							<?php foreach ( $listings as $slug => $label ) : ?>
								<option value="<?php echo esc_attr( $slug ); ?>" <?php echo ( isset( $_GET['listing_type'] ) && $_GET['listing_type'] === $slug ) ? 'selected' : ''; ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="select-type select-order-by">
				<div class="stm-label-type"><?php esc_html_e( 'Sort by', 'stm_vehicles_listing' ); ?></div>
				<select id="sort_by_select" data-user="<?php echo esc_attr( $user_id ); ?>" data-user-private="1" data-posts-per-page="<?php echo esc_attr( $posts_per_page ); ?>" data-page="<?php echo esc_attr( $page ); ?>">
					<option value="all"><?php esc_html_e( 'All', 'stm_vehicles_listing' ); ?></option>
					<option value="pending"><?php esc_html_e( 'Pending', 'stm_vehicles_listing' ); ?></option>
					<option value="draft"><?php esc_html_e( 'Disabled', 'stm_vehicles_listing' ); ?></option>
				</select>
			</div>
		</div>
		<h1><?php esc_html_e( 'My Listings', 'stm_vehicles_listing' ); ?></h1>
		</div>
		<div class="car-listing-row">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				?>
				<div class="stm_listing_edit_car <?php echo esc_attr( get_post_status( get_the_id() ) ); ?>">
					<?php
					if ( 'default' !== $skin && is_mvl_pro() ) {
						do_action(
							'stm_listings_load_template',
							'listing-list',
							apply_filters( 'mvl_add_list_settings_to_array', array() )
						);
						?>
						<div class="mvl-custom-skin-actions listing-list-loop">
							<div class="content">
								<div class="meta-bottom">
								<?php do_action( 'stm_listings_load_template', 'listing-cars/listing-list-owner-actions' ); ?>
								</div>	
							</div>
						</div>
						<?php
					} else {
						do_action( 'stm_listings_load_template', 'listing-cars/listing-list-directory-edit-loop' );
					}
					?>
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
				?>
				<div class="pagination-container">
				<?php
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
				?>
			</div>
				<?php
		endif;
			?>
	</div>
<?php else : ?>
	<h4 class="stm-seller-title"><?php esc_html_e( 'No Inventory yet', 'stm_vehicles_listing' ); ?></h4>
<?php endif; ?>
