<?php
	$current_step = ( ! empty( $_GET['step'] ) ) ? $_GET['step'] : null;
	$steps = apply_filters( 'mvl_setup_wizard_steps_data', array() );
	$i = 0;
?>

<div class="mvl-welcome-nav">
	<ul>
		<?php
		$done = true;
		foreach ( $steps as $slug => $step ) :
			$i++;
			$item_class = array();
			if ( $current_step == $slug ) {
				$item_class[] = 'active';
				$done = false;
			} else if ( $done && ! empty( $current_step ) ) {
				$item_class[] = 'done';
			}
			if ( ! empty( $step['disabled'] ) ) {
				$item_class[] = 'hidden';
			}
			?>
			<li class="<?php echo esc_attr( implode( ' ', $item_class ) ); ?>">
				<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $slug ) ); ?>" data-step="<?php echo esc_attr( $slug ); ?>">
					<span class="bullet">
						<span class="number"></span>
					</span>
					<span class="stepname"><?php echo esc_html( $step['title'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
