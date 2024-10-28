<?php
	$current_step = ( ! empty( $_GET['step'] ) ) ? $_GET['step'] : null;
	$steps = apply_filters( 'mvl_setup_wizard_steps_data', array() );
	$i = 0;
?>

<div class="mvl-welcome-nav">
	<ul>
		<?php
		foreach ( $steps as $slug => $step ) :
			$i++;
			$item_class = array();
			if ( $current_step == $slug ) {
				$item_class[] = 'active';
			}
			?>
			<li class="<?php echo esc_attr( implode( ' ', $item_class ) ); ?>">
				<a href="<?php echo esc_url( apply_filters( 'mvl_setup_wizard_step_url', $slug ) ); ?>" data-step="<?php echo esc_attr( $slug ); ?>">
					<span class="number"><?php echo esc_html( $i ); ?></span>
					<span class="stepname"><?php echo esc_html( $step['title'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
