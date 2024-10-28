<style>
	body {
		overflow: hidden;
	}
</style>
<script type="text/javascript">
	var steps = <?php echo json_encode( apply_filters( 'mvl_setup_wizard_steps_data', array() ) ); ?>;
</script>
<div class="mvl-welcome-screen">

	<div class="mvl-welcome-container">

		<?php do_action( 'mvl_setup_wizard_nav_steps' ); ?>

		<div class="mvl-welcome-main">

			<div class="mvl-welcome-content" id="mvl-welcome-content">
				<?php do_action( 'mvl_setup_wizard_load_step' ); ?>
			</div>

		</div>

		<div class="mvl-welcome-footer">
			StylemixThemes &copy; All rights reserved.
		</div>

	</div>

</div>
<?php
