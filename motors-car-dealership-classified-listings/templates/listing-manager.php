<?php
// phpcs:ignoreFile

/**
 * {link} - ссылка куда вести пользователя Admin -> в Listings, User -> в User Account
 * {listing name} - название листинга Post Title
 * {listing id} - ID листинга
 * {page_id} - ID страницы (ХЗ нужно будет или нет)
 * {Menu Item Title} - название пункта меню
 */

$styles = array(
	'font-awesome'  => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css',
	'motors-icons'  => STM_LISTINGS_URL . '/assets/css/frontend/icons.css',
	'google-fonts'  => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
);

$user      = wp_get_current_user();
$user_role = ( ! empty( $user->roles ) ) ? $user->roles[0] : '';
$is_admin  = false;
if ( 'administrator' === $user_role ) {
	$is_admin = true;
}
$preview = true;
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php esc_html_e( 'Edit/Add', 'stm_vehicles_listing' ); ?> {Listing Name}</title>
	<meta charset="utf-8">
	<?php
	foreach ( $styles as $handle => $href ) {
		printf(
			'<link rel="stylesheet" id="%s-css" href="%s" type="text/css" media="all" />' . "\n",
			esc_attr( $handle ),
			esc_url( $href )
		);
	}
	$style_url = STM_LISTINGS_URL . '/assets/css/listing-manager/listing-manager.css';
	printf(
		'<link rel="stylesheet" id="listing-manager-css" href="%s" type="text/css" media="all" />' . "\n",
		esc_url( $style_url )
	);
	?>
</head>
<body>
	<div id="mvl-listing-manager">
		<div class="mvl-listing-manager-page">
			<div class="mvl-listing-manager-wrapper">
				<div class="mvl-listing-manager-sidebar">
					<div class="mvl-listing-manager-sidebar-header">
						<a class="mvl-listing-manager-sidebar-back-link" href="{link}">
							<i class="fa-solid fa-arrow-left"></i>
						</a>
						<div class="mvl-listing-manager-sidebar-title">
							<?php esc_html_e( 'Listing Manager', 'stm_vehicles_listing' ); ?>
						</div>
					</div>
					<div class="mvl-listing-manager-sidebar-content">
						<div class="mvl-listing-manager-sidebar-menu-wrapper">
							<div class="mvl-listing-manager-sidebar-menu">
								<div class="mvl-listing-manager-sidebar-menu-title">
									<?php esc_html_e( 'Menu', 'stm_vehicles_listing' ); ?>
								</div>
								<ul class="mvl-mvl-listing-manager-sidebar-menu-list">
									<li class="mvl-listing-manager-sidebar-menu-item active">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
									<li class="mvl-listing-manager-sidebar-menu-item">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
									<li class="mvl-listing-manager-sidebar-menu-item">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
									<li class="mvl-listing-manager-sidebar-menu-item">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
									<li class="mvl-listing-manager-sidebar-menu-item">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
									<li class="mvl-listing-manager-sidebar-menu-item">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
									<li class="mvl-listing-manager-sidebar-menu-item">
										<a href="#{page_id}">
											<i class="fa-solid fa-list"></i>
											<?php esc_html_e( '{Menu Item Title}', 'stm_vehicles_listing' ); ?>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<?php if ( ! apply_filters( 'is_mvl_pro', false ) && $is_admin ) : ?>
						<div class="mvl-listing-manager-sidebar-upgrade-pro">
							<div class="mvl-listing-manager-sidebar-upgrade-pro-wrapper">
								<div class="mvl-listing-manager-sidebar-upgrade-pro-title">
									<?php esc_html_e( 'Upgrade to ', 'stm_vehicles_listing' ); ?>
									<span><?php esc_html_e( 'MOTORS ', 'stm_vehicles_listing' ); ?></span>
									<img src="<?php echo esc_url( STM_LISTINGS_URL . '/assets/images/pro/mvl_pro_badge.svg' ); ?>" alt="<?php esc_attr_e( 'MOTORS PRO PLUGIN', 'stm_vehicles_listing' ); ?>" class="mvl-listing-manager-sidebar-upgrade-pro-logo" />
								</div>
								<div class="mvl-listing-manager-sidebar-upgrade-pro-text">
									<?php esc_html_e( 'Advanced features, seamless integrations, and premium support.', 'stm_vehicles_listing' ); ?>
								</div>
								<a href="<?php echo esc_url( 'https://stylemixthemes.com/car-dealer-plugin/pricing/?utm_source=wp-admin&utm_medium=push&utm_campaign=motors&utm_content=gopro' ); ?>" class="mvl-primary-btn mvl-listing-manager-sidebar-upgrade-pro-btn">
									<?php esc_html_e( 'Upgrate to PRO', 'stm_vehicles_listing' ); ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<form class="mvl-listing-manager-content" action="{link}" method="post">
					<div class="mvl-listing-manager-content-header">
						<div class="mvl-listing-manager-content-header-inner">
							<div class="mvl-listing-manager-content-header-search-field">
								<input type="text" class="mvl-lm-search-field-input mvl-input-field" placeholder="<?php esc_attr_e( 'Search', 'stm_vehicles_listing' ); ?>" />
							</div>
							<div class="mvl-listing-manager-content-header-actions">
								<a href="{link}" class="mvl-secondary-btn mvl-listing-manager-content-header-action-btn">
									<?php esc_html_e( 'View Site', 'stm_vehicles_listing' ); ?>
								</a>
								<a href="{link}" class="mvl-primary-btn mvl-listing-manager-content-header-action-btn">
									<?php esc_html_e( 'Publish', 'stm_vehicles_listing' ); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="mvl-listing-manager-content-body">
						<div class="mvl-listing-manager-content-body-inner">
							<div class="mvl-listing-manager-content-body-page">
								<div class="mvl-listing-manager-content-body-page-header">
									<div class="mvl-listing-manager-content-body-page-title-wrapper">
										<div class="mvl-listing-manager-content-body-page-title">
											<?php esc_html_e( 'Page Title', 'stm_vehicles_listing' ); ?>
										</div>
										<?php if ( $preview ) : ?>
											<div class="mvl-listing-manager-content-body-page-preview-wrapper">
												<div class="mvl-listing-manager-content-body-page-preview">
													<i class="motors-icons-mvl-eye"></i>
													<?php esc_html_e( 'Preview', 'stm_vehicles_listing' ); ?>
												</div>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="mvl-listing-manager-content-body-page-text">
									<?php
									do_action( 'stm_listings_load_template', 'listing-manager/parts/input' );
									do_action( 'stm_listings_load_template', 'listing-manager/parts/textarea' );
									?>
								</div>
							</div>
							<div class="mvl-listing-manager-content-body-card">
								<?php do_action( 'stm_listings_load_template', 'listing-manager/parts/listing-preview-card' ); ?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
<footer>
	<script>
	</script>
</footer>
</html>
