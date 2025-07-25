<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName.
define( 'STM_FREEMIUS_CHECKOUT_LINK', 'https://checkout.freemius.com/mode/dialog/plugin/' );
define( 'STM_FREEMIUS_CHECKOUT_UTM_SOURCE', 'utm_source=wpadmin&utm_medium=buynow&utm_campaign=motors-plugin' );
define( 'STM_FREEMIUS_PLUGIN_INFO_URL', 'https://stylemixthemes.com/api/freemius/motors-car-dealership-classified-listings-pro.json' );

/**
 * Gets Fremius Info for upgrade
 */
function get_freemius_info() {
	$response = wp_remote_get( STM_FREEMIUS_PLUGIN_INFO_URL );

	$body = wp_remote_retrieve_body( $response );

	$body = json_decode( $body );

	if ( empty( $body ) ) {
		return '';
	}

	$freemius_info = array();

	/**
	 * Set to Array Premium Plan's Prices
	 *
	 * @param array          $plans - Tariff plan for sale.
	 * @param integer|string $plugin_id - Plugin ID.
	 */
	function set_premium_plan_prices( $plans, $plugin_id ) {
		$plan_info = array();

		$features = array(
			'license' => array(
				'icon'        => 'globe.svg',
				'description' => __( '1 Site License', 'stm_vehicles_listing' ),
			),
			'addons'  => array(
				'icon'        => 'puzzle.svg',
				'description' => __( 'Premium Addons', 'stm_vehicles_listing' ),
				'link'        => esc_url( admin_url( 'admin.php?page=stm-addons' ) ),
				'link_target' => '_self',
			),
			'update'  => array(
				'icon'        => 'cloud-download.svg',
				'description' => __( 'Updates for 1 year', 'stm_vehicles_listing' ),
			),
			'support' => array(
				'icon'        => 'headset.svg',
				'description' => __( 'Priority Ticket Support', 'stm_vehicles_listing' ),
			),
			'product' => array(
				'icon'        => 'browser.svg',
				'description' => __( 'Starter Theme', 'stm_vehicles_listing' ),
				'link'        => 'https://stylemixthemes.com/wordpress-lms-plugin/starter/',
			),
		);

		$plan_data = array(
			'1'    => array(
				'text'      => __( 'Single Site', 'stm_vehicles_listing' ),
				'classname' => '',
				'type'      => '',
				'features'  => array(
					'annual'   => $features,
					'lifetime' => array_merge(
						$features,
						array(
							'update' => array(
								'icon'        => 'cloud-download.svg',
								'description' => __( 'Lifetime Updates', 'stm_vehicles_listing' ),
							),
						),
					),
				),
			),
			'10'   => array(
				'classname' => 'stm_plan--popular',
				'text'      => __( 'Up to 10 Sites', 'stm_vehicles_listing' ),
				'type'      => __( 'Most Popular', 'stm_vehicles_listing' ),
				'features'  => array(
					'annual'   => array_merge(
						$features,
						array(
							'license' => array(
								'icon'        => 'globe.svg',
								'description' => __( '10 Sites License', 'stm_vehicles_listing' ),
							),
						),
					),
					'lifetime' => array_merge(
						$features,
						array(
							'license' => array(
								'icon'        => 'globe.svg',
								'description' => __( '10 Sites License', 'stm_vehicles_listing' ),
							),
							'update'  => array(
								'icon'        => 'cloud-download.svg',
								'description' => __( 'Lifetime Updates', 'stm_vehicles_listing' ),
							),
						),
					),
				),
			),
			'5000' => array(
				'classname' => 'stm_plan--developer',
				'text'      => __( 'Unlimited', 'stm_vehicles_listing' ),
				'type'      => __( 'Developer Oriented', 'stm_vehicles_listing' ),
				'features'  => array(
					'annual'   => array_merge(
						$features,
						array(
							'license' => array(
								'icon'        => 'globe.svg',
								'description' => __( 'Unlimited License', 'stm_vehicles_listing' ),
							),
						),
					),
					'lifetime' => array_merge(
						$features,
						array(
							'license' => array(
								'icon'        => 'globe.svg',
								'description' => __( 'Unlimited License', 'stm_vehicles_listing' ),
							),
							'update'  => array(
								'icon'        => 'cloud-download.svg',
								'description' => __( 'Lifetime Updates', 'stm_vehicles_listing' ),
							),
						),
					),
				),
			),
		);

		foreach ( $plans as $plan ) {
			if ( 'premium' === $plan->name ) {
				if ( isset( $plan->pricing ) ) {
					foreach ( $plan->pricing as $pricing ) {
						if ( ! empty( $pricing->is_hidden ) ) {
							continue;
						}
						$plan_info[ 'licenses_' . $pricing->licenses ]      = $pricing;
						$plan_info[ 'licenses_' . $pricing->licenses ]->url = STM_FREEMIUS_CHECKOUT_LINK . "{$plugin_id}/plan/{$pricing->plan_id}/licenses/{$pricing->licenses}/";

						if ( ! isset( $plan_data[ $pricing->licenses ] ) ) {
							$plan_data[ $pricing->licenses ] = array(
								'text'      => esc_html__( 'Up to ', 'stm_vehicles_listing' ) . $pricing->licenses . esc_html__( ' Sites', 'stm_vehicles_listing' ),
								'classname' => '',
								'type'      => '',
							);
						}
						$plan_info[ 'licenses_' . $pricing->licenses ]->data = $plan_data[ $pricing->licenses ];
					}
				}
				break;
			}
		}

		return $plan_info;
	}

	/**
	 * Set to Array Latest Plugin's Info
	 */
	function set_latest_info( $latest ) {
		$latest_info['version']           = $latest->version;
		$latest_info['tested_up_to']      = $latest->tested_up_to_version;
		$latest_info['created']           = date( 'M j, Y', strtotime( $latest->created ) );
		$latest_info['last_update']       = date( 'M j, Y', strtotime( $latest->updated ) );
		$latest_info['wordpress_version'] = $latest->requires_platform_version;

		return $latest_info;
	}

	if ( isset( $body->plans ) && ! empty( $body->plans ) ) {
		$freemius_info['plan'] = set_premium_plan_prices( $body->plans, $body->id );
	}

	if ( isset( $body->latest ) && ! empty( $body->latest ) ) {
		$freemius_info['latest'] = set_latest_info( $body->latest );
	}

	if ( isset( $body->info ) && ! empty( $body->info ) ) {
		$freemius_info['info'] = $body->info;
	}

	return $freemius_info;
}

$freemius_info = get_freemius_info();
$start_date    = new DateTime( '2025-06-23 00:00:00' );
$deadline      = new DateTime( '2025-07-01 23:59:00' );
$current_time  = time();
$is_promotion  = $current_time >= $start_date->format('U') && $current_time < $deadline->format('U'); //phpcs:ignore
$only_annual   = true;

if ( $is_promotion && ! empty( $freemius_info ) ) {
	$freemius_info['plan']['licenses_5000']->annual_price = 159;
	$freemius_info['plan']['licenses_10']->annual_price   = 99;
	$freemius_info['plan']['licenses_1']->annual_price    = 49;

	$freemius_info['plan']['licenses_5000']->lifetime_price = 399;
	$freemius_info['plan']['licenses_10']->lifetime_price   = 249;
	$freemius_info['plan']['licenses_1']->lifetime_price    = 129;
}
?>
<div class="mvl-go_pro">
	<section class="stm_go_pro">
		<div class="container">
			<div class="stm_go_pro_plugin">
				<h2 class="stm_go_pro_plugin__title">
					<span>
						<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/motors-logo-new.png' ); ?>" width="50" height="50" />
					</span>
					<?php esc_html_e( 'Motors Pro', 'stm_vehicles_listing' ); ?>
				</h2>
				<p class="stm_go_pro_plugin__content">
					<?php if ( isset( $freemius_info['info'] ) ) : ?>
						<?php
						if ( isset( $freemius_info['info']->short_description ) ) {
							nl2br( $freemius_info['info']->short_description );
						}
						?>
						<?php if ( $freemius_info['info']->url ) : ?>
							<a href="<?php echo esc_url( $freemius_info['info']->url ) . '?utm_source=wpadmin-ms&utm_medium=buynow&utm_campaign=learn-more'; ?>">
								<?php esc_html_e( 'Learn more.', 'stm_vehicles_listing' ); ?>
							</a>
						<?php endif; ?>
					<?php endif; ?>
				</p>
				<?php if ( $is_promotion ) : ?>
					<div class="stm-discount"><a href="<?php echo esc_url( 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wpadmin&utm_medium=push&utm_campaign=motors&utm_content=gopro&utm_term=midsummersale2025' ); ?>" target="_blank"></a></div>
				<?php endif; ?>
			</div>
			<?php if ( isset( $freemius_info['plan'] ) ) : ?>
				<h2><?php esc_html_e( 'Choose the package that suits your business', 'stm_vehicles_listing' ); ?></h2>
				<div class="stm-type-pricing" data-pricing="<?php echo esc_attr( $only_annual ? 'annual' : 'lifetime' ); ?>" data-promotion="<?php echo esc_attr( $is_promotion ? 'true' : 'false' ); ?>">
					<div class="left active"><?php esc_html_e( 'Annual', 'stm_vehicles_listing' ); ?></div>
					<div class="stm-type-pricing__switch">
						<input type="checkbox" id="GoProStmTypePricing">
						<label for="GoProStmTypePricing"></label>
					</div>
					<div class="right "><?php esc_html_e( 'Lifetime', 'stm_vehicles_listing' ); ?></div>
				</div>
				<div class="row">
					<?php foreach ( $freemius_info['plan'] as $plan ) : ?>
						<div class="col-md-4">
							<div class="stm_plan <?php echo esc_attr( $plan->data['classname'] ); ?>">
								<?php if ( ! empty( $plan->data['type'] ) ) : ?>
									<div class="stm_plan__type">
										<?php echo esc_html( $plan->data['type'] ); ?>
									</div>
								<?php endif; ?>
								<div class="stm_price">
									<?php
									if ( $is_promotion ) :
										?>
										<sup>$</sup>
										<span class="stm_price__value"
											data-price-annual="<?php echo esc_attr( number_format( $plan->annual_price * 0.70, 0, '.', '' ) ); ?>"
											data-price-lifetime="<?php echo esc_attr( number_format( $plan->lifetime_price * 0.70, 0, '.', '' ) ); ?>"
											data-price-old-annual="<?php echo esc_attr( number_format( $plan->annual_price, 2, '.', '' ) ); ?>">
											<?php echo esc_html( number_format( $plan->annual_price * 0.70, 0, '.', '' ) ); ?>
										</span>
										<div class="discount">
											<sup>$</sup>
											<span class="stm_price__value" style="font-size: 20px;"
												data-price-annual="<?php echo esc_attr( $plan->annual_price ); ?>"
												data-price-lifetime="<?php echo esc_attr( $plan->lifetime_price ); ?>">
												<?php echo esc_html( $plan->annual_price ); ?>
											</span>
										</div>
										<small style="float: left; width: 100%; text-align: center;">/<span class="stm_price__value"
												data-price-annual="<?php echo esc_attr__( 'per year', 'stm_vehicles_listing' ); ?>"
												data-price-lifetime="<?php echo esc_attr__( '1-time', 'stm_vehicles_listing' ); ?>"><?php echo esc_html__( 'per year', 'stm_vehicles_listing' ); ?>
											</span>
										</small>
									<?php else : ?>
									<sup>$</sup>
									<span class="stm_price__value"
										data-price-annual="<?php echo esc_attr( $plan->annual_price ); ?>"
										data-price-lifetime="<?php echo esc_attr( $plan->lifetime_price ); ?>">
										<?php echo esc_html( $plan->annual_price ); ?>
									</span>
									<small>/<span class="stm_price__value"
										data-price-annual="<?php echo esc_attr__( 'per year', 'stm_vehicles_listing' ); ?>"
										data-price-lifetime="<?php echo esc_attr__( '1-time', 'stm_vehicles_listing' ); ?>"><?php echo esc_html__( 'per year', 'stm_vehicles_listing' ); ?>
										</span>
									</small>
									<?php endif; ?>
								</div>
								<p class="stm_plan__title"><?php echo esc_html( $plan->data['text'] ); ?></p>

								<?php if ( ! empty( $plan->data['features'] ) ) : ?>
									<ul class="stm_plan__features">
										<?php foreach ( $plan->data['features'] as $license_type => $features ) : ?>
											<?php foreach ( $features as $feature ) : ?>
												<?php
												$icon         = $feature['icon'] ?? '';
												$link         = $feature['link'] ?? '';
												$link_target  = $feature['link_target'] ?? '_blank';
												$hidden_class = 'annual' !== $license_type ? 'hidden' : '';
												?>

												<li data-license-type="<?php echo esc_attr( $license_type ); ?>" class="<?php echo esc_attr( $hidden_class ); ?>">
													<?php if ( ! empty( $link ) ) : ?>
														<a href="<?php echo esc_url( $link ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
													<?php endif; ?>

													<?php if ( ! empty( $icon ) ) : ?>
														<img src="<?php echo esc_url( STM_LISTINGS_URL . "/assets/images/files/{$icon}" ); ?>">
													<?php endif; ?>

													<span><?php echo esc_html( $feature['description'] ?? '' ); ?></span>

													<?php if ( ! empty( $link ) ) : ?>
														</a>
													<?php endif; ?>
												</li>
											<?php endforeach; ?>
										<?php endforeach; ?>
									</ul>
									<?php
								endif;
								if ( 'mvl-go-pro' === $_GET['page'] ) {
									$base_url     = 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=motorswpadmin&utm_campaign=motors-plugin&licenses=' . esc_attr( $plan->licenses );
									$utm_medium   = isset( $_GET['source'] ) ? esc_attr( htmlspecialchars( $_GET['source'] ) ) : 'unlock-pro-button';
									$annual_url   = $base_url . '&utm_medium=' . $utm_medium . '&billing_cycle=annual';
									$lifetime_url = $base_url . '&utm_medium=' . $utm_medium . '&billing_cycle=lifetime';
									$coupon       = $is_promotion ? '&plugin_coupon=MIDSUMMER25' : '';
									$annual_url  .= $coupon;
									?>
									<a href="<?php echo esc_url( $annual_url ); ?>" class="stm_plan__btn stm_plan__btn--buy" data-checkout-url-annual="<?php echo esc_url( $annual_url ); ?>" data-checkout-url-lifetime="<?php echo esc_url( $lifetime_url ); ?>" target="_blank">
										<?php esc_html_e( 'Get now', 'stm_vehicles_listing' ); ?>
									</a>
									<?php
								}
								?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<p class="stm_terms_content">
				<?php
				$url = 'https://stylemixthemes.com/subscription-policy/';
				printf(
					wp_kses_post(
						/* translators: %s: string */
						__( 'You get <a href="%1$s"><span class="stm_terms_content_support" data-support-lifetime="%2$s" data-support-annual="%3$s">1 year</span> updates and support</a> from the date of purchase. We offer 14 days Money Back Guarantee based on <a href="%1$s">Refund Policy</a>.', 'stm_vehicles_listing' )
					),
					esc_url( $url ),
					esc_attr__( 'Lifetime', 'stm_vehicles_listing' ),
					esc_attr__( '1 year', 'stm_vehicles_listing' )
				);
				?>
			</p>

			<?php if ( ! empty( $freemius_info['latest'] ) ) : ?>
				<ul class="stm_last_changelog_info">
					<li>
						<span class="stm_last_changelog_info__label">
							<?php esc_html_e( 'Version:', 'stm_vehicles_listing' ); ?>
						</span>
						<span class="stm_last_changelog_info__value">
							<?php echo esc_html( $freemius_info['latest']['version'] ); ?>
							<a href="https://docs.stylemixthemes.com/motors-car-dealer-classifieds-and-listing/changelog/release-notes" target="_blank">
								<?php esc_html_e( 'View Changelog', 'stm_vehicles_listing' ); ?>
							</a>
						</span>
					</li>
					<li>
						<span class="stm_last_changelog_info__label">
							<?php esc_html_e( 'Last Update:', 'stm_vehicles_listing' ); ?>
						</span>
						<span class="stm_last_changelog_info__value">
							<?php echo esc_html( $freemius_info['latest']['created'] ); ?>
						</span>
					</li>
					<li>
						<span class="stm_last_changelog_info__label">
							<?php esc_html_e( 'WordPress Version:', 'stm_vehicles_listing' ); ?>
						</span>
						<span class="stm_last_changelog_info__value">
							<?php echo esc_html( $freemius_info['latest']['wordpress_version'] ); ?> or higher
						</span>
					</li>
					<li>
						<span class="stm_last_changelog_info__label">
							<?php esc_html_e( 'Tested up to:', 'stm_vehicles_listing' ); ?>
						</span>
						<span class="stm_last_changelog_info__value">
							<?php echo esc_html( $freemius_info['latest']['tested_up_to'] ); ?>
						</span>
					</li>
				</ul>
			<?php endif; ?>
		</div>
	</section>
	<?php
	if ( ! stm_is_motors_theme() ) {
		$feature_list_free = array(
			__( 'Advanced Search Filters', 'stm_vehicles_listing' ),
			__( 'Unlimited Custom Fields', 'stm_vehicles_listing' ),
			__( 'Custom Field’s Filters', 'stm_vehicles_listing' ),
			__( 'User Registration', 'stm_vehicles_listing' ),
			__( 'Email Confirmation', 'stm_vehicles_listing' ),
			__( 'Listings Management', 'stm_vehicles_listing' ),
			__( 'Frontend Listing Submission', 'stm_vehicles_listing' ),
			__( 'Comparing Listings', 'stm_vehicles_listing' ),
			__( 'Test Drive Form', 'stm_vehicles_listing' ),
			__( 'Add to Favorites', 'stm_vehicles_listing' ),
			__( 'Free Listing Submission', 'stm_vehicles_listing' ),
			__( 'Import Listings', 'stm_vehicles_listing' ),
		);

		$feature_list_pro = array(
			__( 'Dealer Registration', 'stm_vehicles_listing' ),
			__( 'Similar Listings', 'stm_vehicles_listing' ),
			__( 'Paid Featured Listing', 'stm_vehicles_listing' ),
			__( 'Mark as Sold', 'stm_vehicles_listing' ),
			__( 'Pay Per Submit', 'stm_vehicles_listing' ),
			__( 'Loan Calculator', 'stm_vehicles_listing' ),
			__( 'Sell a Listing Online', 'stm_vehicles_listing' ),
			__( 'Listing Statistics', 'stm_vehicles_listing' ),
			__( 'Search by location', 'stm_vehicles_listing' ),
			__( 'WooCommerce Payment Gateways', 'stm_vehicles_listing' ),
			__( 'Search by keywords', 'stm_vehicles_listing' ),
			__( 'Social Login Addon', 'stm_vehicles_listing' ),
			__( 'Radius Distance Search', 'stm_vehicles_listing' ),
			__( 'Saved Searches Addon', 'stm_vehicles_listing' ),
			__( 'Custom Sorting Options', 'stm_vehicles_listing' ),
			__( 'VIN Decoder Addon', 'stm_vehicles_listing' ),
			__( 'SEO-friendly URLs', 'stm_vehicles_listing' ),
			__( 'Premium Elementor Widgets', 'stm_vehicles_listing' ),
			__( 'Listing Templates', 'stm_vehicles_listing' ),
			__( 'SEO-friendly URLs', 'stm_vehicles_listing' ),
			__( 'Motors Skins', 'stm_vehicles_listing' ),
			__( 'VIN Decoder Addon', 'stm_vehicles_listing' ),
			__( 'Inventory Skins', 'stm_vehicles_listing' ),
			__( 'Premium Elementor Widgets', 'stm_vehicles_listing' ),
			__( 'Listing Card Skins', 'stm_vehicles_listing' ),
			__( 'WhatsApp Integration', 'stm_vehicles_listing' ),
			__( 'Google Maps', 'stm_vehicles_listing' ),
		);

		?>
		<section class="mvl-compare">
			<div class="container">
				<div class="mvl-compare-wrapper">
					<div class="mvl-compare-title">
						<h2 class="heading mvl-compare-heading"><?php esc_html_e( 'Сomparison Table', 'stm_vehicle_listings' ); ?></h2>
						<p class="mvl-compare-subtitle">
							<?php esc_html_e( 'Choose the best option. Upgrade to Pro version just for', 'stm_vehicle_listings' ); ?>
							<?php if ( $is_promotion ) : ?>
								<span class="mvl-compare-price">
									<?php echo esc_html( '$' . number_format( $freemius_info['plan']['licenses_1']->annual_price * 0.70, 0, '.', '' ) ); ?>
								</span>
							<?php else : ?>
								<span class="mvl-compare-price">
									<?php echo esc_html( '$' . number_format( $freemius_info['plan']['licenses_1']->annual_price, 0, '.', '' ) ); ?>
								</span>
							<?php endif; ?>
						</p>
					</div>
					<div class="mvl-compare-items-header-wrapper">
						<div class="mvl-compare-items-header-wrapper-item">
							<h1 class="heading mvl-compare-item-name"><?php esc_html_e( 'Free', 'stm_vehicle_listings' ); ?></h1>
							<p class="mvl-compare-item-descr"><?php esc_html_e( 'Motors Plugin', 'stm_vehicle_listings' ); ?></p>
						</div>
						<div class="mvl-compare-items-header-wrapper-item pro">
							<h1 class="heading mvl-compare-item-name"><?php esc_html_e( 'Pro', 'stm_vehicle_listings' ); ?></h1>
							<p class="mvl-compare-item-descr"><?php esc_html_e( 'including everything in the Free Plan plus:', 'stm_vehicle_listings' ); ?></p>
						</div>
					</div>
					<div class="mvl-compare-table-wrapper">
						<div class="mvl-compare-table-item">
							<div class="mvl-compare-lists">
								<ul class="mvl-compare-features-list">
									<?php foreach ( $feature_list_free as $feature ) : ?>
										<li class="mvl-compare-features-list-item">
											<span class="mvl-compare-features-list-item-name"><i class="fa-solid fa-circle-check"></i><?php echo esc_html( $feature ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="mvl-compare-table-item pro">
							<div class="mvl-compare-lists">
								<ul class="mvl-compare-features-list">
									<?php foreach ( $feature_list_pro as $index => $feature ) : ?>
										<li class="mvl-compare-features-list-item <?php echo $index >= count( $feature_list_pro ) - 2 ? 'full-width' : ''; ?>">
											<span class="mvl-compare-features-list-item-name"><i class="fa-solid fa-circle-check"></i><?php echo esc_html( $feature ); ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div class="mvl-compare-get-wrapper">
								<a href="<?php echo esc_url( 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=motorswpadmin&utm_campaign=motors-plugin&licenses=1' ); ?>" class="mvl-compare-get-btn" target="_blank">
									<?php esc_html_e( 'Get From', 'stm_vehicle_listings' ); ?>
									<?php if ( $is_promotion ) : ?>
										<span class="mvl-compare-price">
											<?php echo esc_html( '$' . number_format( $freemius_info['plan']['licenses_1']->annual_price * 0.70, 0, '.', '' ) ); ?>
										</span>
									<?php else : ?>
										<span class="mvl-compare-price">
											<?php echo esc_html( '$' . number_format( $freemius_info['plan']['licenses_1']->annual_price, 0, '.', '' ) ); ?>
										</span>
									<?php endif; ?>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php } ?>
</div>

<script>
	jQuery(document).ready(function ($) {
		let promotion = $('.stm-type-pricing').attr('data-promotion');
		if ( promotion === 'false' ) {
			$('.stm_price small').css('float', '');
		}
		$('#GoProStmTypePricing').on('change', function () {

			let parent = $(this).closest('.stm-type-pricing');
			let pricing_for = parent.attr('data-pricing');
			let left = parent.find('.left'); //Annual
			let right = parent.find('.right'); //Lifetime
			let stm_price = $('.stm_price small');

			left.toggleClass('active', !this.checked);
			right.toggleClass('active', this.checked);

			let typePrice = 'annual';

			if (this.checked) typePrice = 'lifetime';

			let support = $('.stm_terms_content_support');
			support.text(support.attr('data-support-' + typePrice));

			$('.stm_plan__btn--buy').each(function () {
				let $this = $(this);
				let checkoutUrl = $this.attr('data-checkout-url-annual');
				if ('lifetime' === typePrice) {
					checkoutUrl = $this.attr('data-checkout-url-lifetime');
				}
				$this.attr('href', checkoutUrl);
			})

			$('.stm_price__value').each(function () {
				$(this).text($(this).attr('data-price-' + typePrice));
			})

			$('.stm_plan__features li').each(function () {
				if ( typePrice !== $(this).attr('data-license-type') ) {
					$(this).slideUp();
				} else {
					$(this).slideDown();
				}
			})

		});

	});
</script>
