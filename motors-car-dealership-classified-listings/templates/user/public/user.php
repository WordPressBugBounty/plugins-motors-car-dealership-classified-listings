<?php
mvl_enqueue_header_scripts_styles( 'stmselect2' );
mvl_enqueue_header_scripts_styles( 'app-select2' );
$user_page      = get_queried_object();
$user_id        = $user_page->data->ID;
$user_image     = get_the_author_meta( 'stm_user_avatar', $user_id );
$image          = '';
$user_show_mail = '';
$user_show_mail = get_the_author_meta( 'stm_show_email', $user_id );
$user_phone     = get_the_author_meta( 'stm_phone', $user_id );
$user_desc      = get_the_author_meta( 'description', $user_id );
$user_fb        = get_the_author_meta( 'stm_user_facebook', $user_id );
$user_tw        = get_the_author_meta( 'stm_user_twitter', $user_id );
$user_linkedin  = get_the_author_meta( 'stm_user_linkedin', $user_id );
$user_youtube   = get_the_author_meta( 'stm_user_youtube', $user_id );

if ( ! empty( $user_image ) ) {
	$image = $user_image;
}

$show_more_button = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_more_button_user_profile' );
$posts_per_page   = apply_filters( 'motors_vl_get_nuxy_mod', 6, 'post_per_page_user_inventory' );
$page             = ( ! empty( $_GET['page'] ) ) ? intval( $_GET['page'] ) : 1; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
$offset           = ! empty( $_GET['sort_by'] ) && 'recent' === $_GET['sort_by'] ? $posts_per_page * ( $page - 1 ) : 0;
$offset_popular   = ! empty( $_GET['sort_by'] ) && 'popular' === $_GET['sort_by'] ? $posts_per_page * ( $page - 1 ) : 0;

$row    = 'row row-3';
$active = 'grid';
$list   = '';
$grid   = 'active';
if ( ! empty( $_GET['view_type'] ) && 'list' === $_GET['view_type'] ) {
	$list   = 'active';
	$grid   = '';
	$active = 'list';
	$row    = 'row-no-border-last';
}

$additional_class = '';
$skin             = apply_filters( 'motors_vl_get_nuxy_mod', 'default', $active . '_card_skin' );

if ( 'default' !== $skin ) {
	$additional_class = 'mvl-card-skin';
}

$query         = stm_user_listings_query( $user_id, 'publish', $posts_per_page, false, $offset );
$query_popular = stm_user_listings_query( $user_id, 'publish', $posts_per_page, true, $offset_popular );
?>

<div class="container stm-user-public-profile">
	<div class="row">
		<div class="col-md-12">
			<div class="clearfix stm-user-public-profile-top">
				<div class="stm-user-name">
					<div class="user-main-wrap">
						<div class="image">
							<?php if ( ! empty( $image ) ) : ?>
								<img src="<?php echo esc_url( $image ); ?>"/>
							<?php else : ?>
								<i class="motors-icons-user"></i>
							<?php endif; ?>
						</div>
						<div class="title">
							<h4><?php echo wp_kses_post( apply_filters( 'stm_display_user_name', $user_page->ID, '', '', '' ) ); ?></h4>
							<div class="stm-title-desc"><?php esc_html_e( 'Private Seller', 'stm_vehicles_listing' ); ?></div>
						</div>
					</div>
					<div class="stm-user-description">
						<h3><?php esc_html_e( 'About this User', 'stm_vehicles_listing' ); ?></h3>
						<div class="author-description">
							<?php echo wp_kses_post( $user_desc ); ?>
						</div>
					</div>
				</div>
				<div class="stm-user-data-right">
					<h3><?php esc_html_e( 'Contact Info', 'stm_vehicles_listing' ); ?></h3>
					<?php if ( ! empty( $user_page->data->user_email ) && ! empty( $user_show_mail ) ) : ?>
						<div class="stm-user-email">
							<i class="fas fa-envelope-open"></i>
							<div class="mail-label"><?php esc_html_e( 'Seller email', 'stm_vehicles_listing' ); ?></div>
							<a href="mailto:<?php echo esc_attr( $user_page->data->user_email ); ?>" class="mail h4"><?php echo esc_attr( $user_page->data->user_email ); ?></a>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $user_phone ) ) : ?>
						<div class="stm-user-phone">
							<i class="fas fa-phone"></i>
							<div class="phone-label"><?php esc_html_e( 'Seller phone', 'stm_vehicles_listing' ); ?></div>
							<div class="phone h3"><?php echo esc_attr( $user_phone ); ?></div>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $user_fb ) || ! empty( $user_tw ) || ! empty( $user_linkedin ) || ! empty( $user_youtube ) ) : ?>
						<div class="stm-user-socials">
							<?php if ( ! empty( $user_fb ) ) : ?>
								<a href="<?php echo esc_url( $user_fb ); ?>" target="_blank" class="sm-icon fb"><i
											class="fab fa-facebook-f"></i></a>
							<?php endif; ?>
							<?php if ( ! empty( $user_tw ) ) : ?>
								<a href="<?php echo esc_url( $user_tw ); ?>" target="_blank" class="sm-icon tw"><i
											class="fab fa-x-twitter"></i></a>
							<?php endif; ?>
							<?php if ( ! empty( $user_linkedin ) ) : ?>
								<a href="<?php echo esc_url( $user_linkedin ); ?>" target="_blank" class="sm-icon linkedin"><i
											class="fab fa-linkedin-in"></i></a>
							<?php endif; ?>
							<?php if ( ! empty( $user_youtube ) ) : ?>
								<a href="<?php echo esc_url( $user_youtube ); ?>" target="_blank" class="sm-icon youtube"><i
											class="fab fa-youtube"></i></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div> <!-- top profile -->

			<div class="stm-user-public-listing">
				<?php if ( $query->have_posts() ) : ?>
					<div class="stm-user-public-listing-top">
						<div class="stm-user-public-listing-top-left">
							<h3 class="stm-seller-title"><?php esc_html_e( 'Sellers Inventory', 'stm_vehicles_listing' ); ?></h3>
						</div>
						<div class="stm-user-public-listing-top-right">
							<?php if ( stm_is_multilisting() ) : ?>
								<div class="multilisting-select">
									<?php
									$listings = stm_listings_multi_type_labeled( true );
									if ( ! empty( $listings ) ) :
										?>
										<div class="select-type select-listing-type" style="margin-right: 15px;">
											<div class="stm-label-type"><?php esc_html_e( 'Listing type', 'stm_vehicles_listing' ); ?></div>
											<select data-user="<?php echo esc_attr( $user_id ); ?>" data-posts-per-page="<?php echo esc_attr( $posts_per_page ); ?>" data-offset="<?php echo esc_attr( $offset ); ?>">
												<option value="all"
														selected><?php esc_html_e( 'All listing types', 'stm_vehicles_listing' ); ?></option>
												<?php foreach ( $listings as $slug => $label ) : ?>
													<option value="<?php echo esc_attr( $slug ); ?>" <?php echo ( isset( $_GET['listing_type'] ) && ! empty( $_GET['listing_type'] ) && $_GET['listing_type'] === $slug ) ? 'selected' : ''; ?>><?php echo esc_html( $label );//phpcs:ignore WordPress.Security.NonceVerification.Recommended ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>

							<div class="stm-sort-by-options clearfix">
								<span class="sort-by-label"><?php esc_html_e( 'Sort by', 'stm_vehicles_listing' ); ?>:</span>
								<div class="stm-select-sorting">
									<select id="stm-dealer-view-type">
										<option value="popular"><?php esc_html_e( 'Popular items', 'stm_vehicles_listing' ); ?></option>
										<option value="recent" selected=""><?php esc_html_e( 'Recent items', 'stm_vehicles_listing' ); ?></option>
									</select>
								</div>
							</div>

							<div class="stm-view-by">
								<a href="?view_type=grid#stm_us_inv" class="view-grid view-type <?php echo esc_attr( $grid ); ?>" data-view="grid">
									<i class="motors-icons-grid"></i>
								</a>
								<a href="?view_type=list#stm_us_inv" class="view-list view-type <?php echo esc_attr( $list ); ?>" data-view="list">
									<i class="motors-icons-list"></i>
								</a>
							</div>
							<input type="hidden" id="stm_user_dealer_view_type" value="<?php echo esc_attr( $active ); ?>" />
						</div>
					</div>
					<div class="archive-listing-page row">
						<div class="col-md-12">
							<div class="user-listings-wrapper active" id="recent">
								<div class="car-listing-row <?php echo esc_attr( $row ); ?> <?php echo esc_attr( $additional_class ); ?>">
									<?php
									while ( $query->have_posts() ) :
										$query->the_post();
										$__vars = array( 'columns' => 3 );
										if ( 'default' !== $skin && is_mvl_pro() && 'list' === $active ) {
											$__vars['list_action_buttons']    = apply_filters( 'mvl_list_action_buttons', array() );
											$__vars['list_action_popup_btns'] = apply_filters( 'mvl_list_action_popup_buttons', array() );
										}
										?>
										<?php do_action( 'stm_listings_load_template', 'listing-' . $active, $__vars ); ?>
									<?php endwhile; ?>
								</div>
								<?php if ( $query->found_posts > $posts_per_page && $show_more_button ) : ?>
								<div class="stm-load-more-dealer-cars">
									<a class="button" data-offset="<?php echo esc_attr( $posts_per_page ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>" data-popular="no" href="#" class="heading-font">
										<span><?php esc_html_e( 'Show more', 'stm_vehicles_listing' ); ?></span>
									</a>
								</div>
								<?php endif; ?>
							</div>
							<div class="user-listings-wrapper" id="popular">
								<div class="car-listing-row <?php echo esc_attr( $row ); ?> <?php echo esc_attr( $additional_class ); ?>">
									<?php
									while ( $query_popular->have_posts() ) :
										$query_popular->the_post();
										?>
										<?php do_action( 'stm_listings_load_template', 'listing-' . $active, array( 'columns' => 3 ) ); ?>
									<?php endwhile; ?>
								</div>
								<?php if ( $query_popular->found_posts > $posts_per_page && $show_more_button ) : ?>
								<div class="stm-load-more-dealer-cars">
									<a class="button" data-offset="<?php echo esc_attr( $offset_popular ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>" data-popular="yes" href="#" class="heading-font">
										<span><?php esc_html_e( 'Show more', 'stm_vehicles_listing' ); ?></span>
									</a>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
					if ( ! $show_more_button ) :
						?>
						<div class="recent user-listings-pagination active">
							<?php
							do_action( 'delear_public_page_pagination', $query, $page, $posts_per_page, 'recent' );
							?>
						</div>
						<div class="popular user-listings-pagination">
							<?php
							do_action( 'delear_public_page_pagination', $query, $page, $posts_per_page, 'popular' );
							?>
						</div>
						<?php
					endif;
					?>
				<?php else : ?>
					<h4 class="stm-seller-title"><?php esc_html_e( 'No Inventory added yet.', 'stm_vehicles_listing' ); ?></h4>
				<?php endif; ?>
			</div>

		</div>
	</div>
</div>
