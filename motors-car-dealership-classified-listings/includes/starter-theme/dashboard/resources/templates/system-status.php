<?php
use MotorsVehiclesListing\StarterTheme\Dashboard\Motors_Templates_System_Status;
?>
<div class="mst-starter-wizard mst-starter-system-status" style="display: none;">
	<div class="mst-starter-system-status__title">System status</div>
	<table cellspacing="0">
		<thead>
		<tr>
			<th>WordPress Environment</th>
			<th></th>
			<th>Your System</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( Motors_Templates_System_Status::get_wp_env() as $item ) : ?>
			<tr>
				<td data-export-label="<?php echo esc_attr( $item['title'] ); ?>"><?php echo esc_html( $item['title'] ); ?>:</td>
				<td></td>
				<td><?php echo wp_kses_post( $item['system'] ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<table cellspacing="0">
		<thead>
		<tr>
			<th>Server Environment</th>
			<th>Requirement</th>
			<th>Your System</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ( Motors_Templates_System_Status::get_server_env() as $item ) : ?>
			<tr>
				<td data-export-label="<?php echo esc_attr( $item['title'] ); ?>"><?php echo esc_html( $item['title'] ); ?>:</td>
				<td class="help"><?php echo wp_kses_post( $item['recommend'] ); ?></td>
				<td><?php echo wp_kses_post( $item['system'] ); ?></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<table cellspacing="0">
		<thead>
			<tr>
				<th>Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
				<th>Version</th>
				<th>Plugin Author</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$active_plugins = (array) get_option( 'active_plugins', array() );
		$allPlugins     = get_site_transient( 'update_plugins' );

		if ( is_multisite() ) {
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		foreach ( $active_plugins as $plugin ) {
			$plugin_data        = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
			$dirname            = dirname( $plugin );
			$badge              = '<span class="mst-icon-check"></span>';
			$version_number     = $plugin_data['Version'];
			$version_upd_number = $plugin_data['Version'];

			if ( isset( $allPlugins->response[ $plugin ] ) ) {
				Motors_Templates_System_Status::$notification = true;

				$badge              = '<span class="mst-icon-cancel"></span>';
				$version_number     = '<span class="plug_bold">' . $plugin_data['Version'] . '</span>';
				$version_upd_number = $allPlugins->response[ $plugin ]->new_version;
			}

			if ( ! empty( $plugin_data['Name'] ) ) {
				$plugin_name = esc_html( $plugin_data['Name'] );

				if ( ! empty( $plugin_data['PluginURI'] ) ) {
					$plugin_name = '<a target="_blank" href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="Visit plugin homepage">' . $plugin_name . '</a>';
				}
				?>
				<tr>
					<td><?php echo esc_html( sanitize_text_field( $plugin_name ) ); ?></td>
					<td><?php echo sprintf( '%s %s', wp_kses_post( $badge ), wp_kses_post( $version_number ) ); ?></td>
					<td>
						<?php
							echo wp_kses_post(
								apply_filters(
									'stm_theme_esc_variable',
									str_replace( '">', '" target="_blank">', $plugin_data['Author'] )
								)
							);
						?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
