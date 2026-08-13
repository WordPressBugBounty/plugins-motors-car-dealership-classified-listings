<?php
$settings = get_option( \MotorsVehiclesListing\Plugin\MVL_Const::MVL_PLUGIN_OPT_NAME, array() );

if ( ! is_array( $settings ) ) {
	$settings = array();
}

$current_type = 'dealership';

if ( ! empty( $settings['motors_business_type'] ) && 'dealership' === $settings['motors_business_type'] ) {
	$current_type = 'dealership';
}

$business_type_options = array(
	'dealership' => array(
		'title'       => esc_html__( 'Dealership', 'stm_vehicles_listing' ),
		'description' => esc_html__( 'Sell your own inventory of vehicles. One brand, one storefront.', 'stm_vehicles_listing' ),
		'icon_class'  => 'mvl-business-type-card__icon--dealership',
		'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" stroke="#1565c0" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 11l2-5h16l2 5"></path><path d="M4 11v12h20V11"></path><path d="M4 11h20"></path><path d="M10 23v-6h8v6"></path></svg>',
		'features'    => array(
			esc_html__( 'Inventory & pricing fields', 'stm_vehicles_listing' ),
			esc_html__( 'Test drive bookings', 'stm_vehicles_listing' ),
			esc_html__( 'Dealer reviews & trade-in', 'stm_vehicles_listing' ),
		),
		'disabled'    => false,
	),
	'classified' => array(
		'title'       => esc_html__( 'Classified', 'stm_vehicles_listing' ),
		'description' => esc_html__( 'A marketplace where many sellers post their own listings.', 'stm_vehicles_listing' ),
		'icon_class'  => 'mvl-business-type-card__icon--classified',
		'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" stroke="#6d4bc4" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="4" y="5" width="9" height="9" rx="1.5"></rect><rect x="15" y="5" width="9" height="9" rx="1.5"></rect><rect x="4" y="16" width="9" height="7" rx="1.5"></rect><rect x="15" y="16" width="9" height="7" rx="1.5"></rect></svg>',
		'features'    => array(
			esc_html__( 'Multi-vendor accounts', 'stm_vehicles_listing' ),
			esc_html__( 'Paid & featured listings', 'stm_vehicles_listing' ),
			esc_html__( 'Memberships & submissions', 'stm_vehicles_listing' ),
		),
		'disabled'    => true,
	),
	'rental'     => array(
		'title'       => esc_html__( 'Rental', 'stm_vehicles_listing' ),
		'description' => esc_html__( 'Rent vehicles out by the day or for a set period.', 'stm_vehicles_listing' ),
		'icon_class'  => 'mvl-business-type-card__icon--rental',
		'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" stroke="#1f9d63" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="4" y="6" width="20" height="18" rx="2"></rect><path d="M4 11h20"></path><path d="M9 4v4M19 4v4"></path><circle cx="11" cy="17" r="2"></circle><path d="M11 19l4 0"></path></svg>',
		'features'    => array(
			esc_html__( 'Availability calendar', 'stm_vehicles_listing' ),
			esc_html__( 'Daily / period pricing', 'stm_vehicles_listing' ),
			esc_html__( 'Bookings & deposits', 'stm_vehicles_listing' ),
		),
		'disabled'    => true,
		'is_new'      => true,
	),
);
?>
<style>
	@media (min-width: 1024px) {
		.mvl-welcome-main:has(.mvl-business-type-step) {
			width: min(1040px, calc(100vw - 80px));
			margin-left: 50%;
			transform: translateX(-50%);
		}
	}

	.mvl-business-type-step {
		padding-inline: 40px;
	}

	.mvl-business-type-step h2,
	.mvl-business-type-step > p {
		text-align: center;
	}

	.mvl-business-type-step h2 {
		margin-bottom: 10px;
		color: #11151c;
		font-size: 30px;
		font-weight: 800;
		line-height: 1.2;
	}

	.mvl-business-type-step > p {
		max-width: 680px;
		margin: 0 auto;
		color: #5e6675;
		font-size: 16px;
		line-height: 1.6;
	}

	.mvl-business-type-options {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 22px;
		max-width: 1040px;
		margin: 38px auto 0;
	}

	.mvl-business-type-option {
		position: relative;
		display: block;
	}

	.mvl-business-type-option input {
		position: absolute;
		opacity: 0;
		pointer-events: none;
	}

	.mvl-business-type-card {
		position: relative;
		display: flex;
		flex-direction: column;
		min-height: 292px;
		padding: 30px 26px 26px;
		border: 1px solid #e2e6ec;
		border-radius: 16px;
		background: #fff;
		cursor: pointer;
		transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
	}

	.mvl-business-type-card:hover {
		box-shadow: 0 12px 30px rgba(20, 30, 50, .1);
		transform: translateY(-2px);
	}

	.mvl-business-type-option.is-disabled .mvl-business-type-card {
		cursor: not-allowed;
		opacity: .72;
	}

	.mvl-business-type-option.is-disabled .mvl-business-type-card:hover {
		box-shadow: none;
		transform: none;
	}

	.mvl-business-type-card__selected {
		position: absolute;
		inset: -1px;
		display: none;
		border: 2.5px solid #1280df;
		border-radius: 16px;
		pointer-events: none;
	}

	.mvl-business-type-card__check {
		position: absolute;
		top: -11px;
		right: 18px;
		display: none;
		width: 26px;
		height: 26px;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		background: #1280df;
		box-shadow: 0 3px 8px rgba(18, 128, 223, .4);
		color: #fff;
		font-size: 15px;
		font-weight: 700;
		line-height: 1;
	}

	.mvl-business-type-card__badge,
	.mvl-business-type-card__pro-badge {
		position: absolute;
		top: 18px;
		right: 18px;
		padding: 4px 9px;
		border-radius: 6px;
		color: #fff;
		font-size: 11px;
		font-weight: 800;
		letter-spacing: .05em;
		line-height: 1;
	}

	.mvl-business-type-card__badge {
		background: #ff8a00;
	}

	.mvl-business-type-card__pro-badge {
		background: #111827;
	}

	.mvl-business-type-card__icon {
		display: flex;
		width: 54px;
		height: 54px;
		align-items: center;
		justify-content: center;
		margin-bottom: 18px;
		border-radius: 13px;
	}

	.mvl-business-type-card__icon--dealership {
		background: #eef3fb;
	}

	.mvl-business-type-card__icon--classified {
		background: #f1eefb;
	}

	.mvl-business-type-card__icon--rental {
		background: #eafaf2;
	}

	.mvl-business-type-option strong {
		display: block;
		margin-bottom: 7px;
		color: #11151c;
		font-size: 20px;
		font-weight: 800;
		line-height: 1.25;
	}

	.mvl-business-type-option small {
		display: block;
		min-height: 45px;
		color: #5e6675;
		font-size: 14.5px;
		line-height: 1.55;
	}

	.mvl-business-type-card__divider {
		height: 1px;
		margin: 20px 0 16px;
		background: #eef0f3;
	}

	.mvl-business-type-card__features {
		display: flex;
		flex-direction: column;
		gap: 9px;
		margin: 0;
		padding: 0;
		list-style: none;
	}

	.mvl-business-type-card__features li {
		display: flex;
		align-items: center;
		gap: 9px;
		color: #3a414d;
		font-size: 13.5px;
		line-height: 1.35;
	}

	.mvl-business-type-card__features span {
		color: #22a06b;
		font-weight: 700;
	}

	.mvl-business-type-option input:checked + .mvl-business-type-card .mvl-business-type-card__selected,
	.mvl-business-type-option input:checked + .mvl-business-type-card .mvl-business-type-card__check {
		display: flex;
	}

	.mvl-business-type-note,
	.mvl-business-type-pro-notice {
		display: flex;
		max-width: 1040px;
		align-items: flex-start;
		gap: 9px;
		margin: 30px auto 0;
		color: #7b8493;
		font-size: 13px;
		line-height: 1.5;
	}

	.mvl-business-type-pro-notice {
		display: none;
		margin-top: 18px;
		padding: 12px 14px;
		border-left: 4px solid #dba617;
		background: #fff8e5;
		color: #5c4400;
	}

	.mvl-business-type-pro-notice.is-visible {
		display: flex;
	}

	.mvl-dealership-demo-status {
		display: none;
		max-width: 1040px;
		margin: 18px auto 0;
		padding: 12px 14px;
		border-left: 4px solid #2271b1;
		background: #f0f6fc;
		color: #1d2327;
		font-size: 13px;
		line-height: 1.5;
	}

	.mvl-dealership-demo-status.is-visible {
		display: block;
	}

	.mvl-dealership-demo-status.is-error {
		border-left-color: #d63638;
		background: #fcf0f1;
		color: #8a2424;
	}

	@media (max-width: 900px) {
		.mvl-business-type-step {
			padding-inline: 25px;
		}

		.mvl-business-type-options {
			grid-template-columns: 1fr;
		}

		.mvl-business-type-option small {
			min-height: 0;
		}
	}
</style>

<div class="mvl-welcome-content-body mvl-business-type-step">
	<h2><?php echo esc_html__( 'Choose your business type', 'stm_vehicles_listing' ); ?></h2>
	<p><?php echo esc_html__( 'This shapes your listing fields, search filters and front-end layouts. Pick the one that matches how you operate.', 'stm_vehicles_listing' ); ?></p>

	<form class="mvl-settings-form" id="mvl-settings-form">
		<div class="mvl-business-type-options">
			<?php foreach ( $business_type_options as $type => $option ) : ?>
				<?php
				$option_classes = array( 'mvl-business-type-option' );
				$disabled       = ! empty( $option['disabled'] );

				if ( $disabled ) {
					$option_classes[] = 'is-disabled';
				}
				?>
				<label class="<?php echo esc_attr( implode( ' ', $option_classes ) ); ?>" data-mvl-business-type-option="<?php echo esc_attr( $type ); ?>">
					<input type="radio" name="motors_business_type" value="<?php echo esc_attr( $type ); ?>" <?php checked( $current_type, $type ); ?> <?php disabled( $disabled ); ?> />
					<div class="mvl-business-type-card">
						<div class="mvl-business-type-card__selected"></div>
						<div class="mvl-business-type-card__check">&#10003;</div>
						<?php if ( ! empty( $option['is_new'] ) && ! $disabled ) : ?>
							<div class="mvl-business-type-card__badge"><?php echo esc_html__( 'NEW', 'stm_vehicles_listing' ); ?></div>
						<?php endif; ?>
						<?php if ( $disabled ) : ?>
							<div class="mvl-business-type-card__pro-badge"><?php echo esc_html__( 'PRO', 'stm_vehicles_listing' ); ?></div>
						<?php endif; ?>
						<div class="mvl-business-type-card__icon <?php echo esc_attr( $option['icon_class'] ); ?>">
							<?php echo $option['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<strong><?php echo esc_html( $option['title'] ); ?></strong>
						<small><?php echo esc_html( $option['description'] ); ?></small>
						<div class="mvl-business-type-card__divider"></div>
						<ul class="mvl-business-type-card__features">
							<?php foreach ( $option['features'] as $feature ) : ?>
								<li><span>&#10003;</span><?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</label>
			<?php endforeach; ?>
		</div>
	</form>

	<div class="mvl-business-type-note">
		<svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="#9aa2b0" stroke-width="1.5" aria-hidden="true" focusable="false"><circle cx="8" cy="8" r="6.5"></circle><path d="M8 7.2v3.6M8 5.2v.1"></path></svg>
		<span><?php echo esc_html__( 'Dealership is available in the free plugin. Classified and Rental require Motors Pro to be installed and active.', 'stm_vehicles_listing' ); ?></span>
	</div>
	<div class="mvl-business-type-pro-notice" data-mvl-business-type-pro-notice>
		<span><?php echo esc_html__( 'Install and activate Motors Pro to choose Classified or Rental business type.', 'stm_vehicles_listing' ); ?></span>
	</div>
	<div class="mvl-dealership-demo-status" data-mvl-dealership-demo-status aria-live="polite"></div>
</div>

<div class="mvl-welcome-nav-actions">
	<div>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', 'welcome' ) ); ?>" class="button" id="mvl-prev-step-link" data-step="welcome">
			<?php echo esc_html__( 'Back', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
	<div>
		<?php $next_step_slug = apply_filters( 'mvl_setup_wizard_next_step', 'fields', 'business-type' ); ?>
		<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $next_step_slug ) ); ?>" class="button button-primary" id="mvl-next-step-link" data-step="<?php echo esc_attr( $next_step_slug ); ?>">
			<?php echo esc_html__( 'Next Step', 'stm_vehicles_listing' ); ?>
		</a>
	</div>
</div>

<?php
do_action( 'mvl_setup_wizard_data_fields' );
