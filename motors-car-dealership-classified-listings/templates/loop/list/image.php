<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$dynamic_class_photo         = 'stm-car-photos-' . get_the_id() . '-' . wp_rand( 1, 99999 );
$dynamic_class_video         = 'stm-car-videos-' . get_the_id() . '-' . wp_rand( 1, 99999 );
$car_media                   = apply_filters( 'stm_get_car_medias', array(), get_the_id() );
$gallery_hover_interaction   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
$interactive_hoverable_class = $gallery_hover_interaction ? 'interactive-hoverable' : '';
?>
<div class="image">
	<div class="stm-car-medias">
		<?php if ( ! empty( $car_media['car_photos_count'] ) ) : ?>
			<div class="stm-listing-photos-unit stm-car-photos-<?php echo esc_attr( get_the_ID() ); ?> <?php echo esc_attr( $dynamic_class_photo ); ?>">
				<i class="stm-service-icon-photo"></i>
				<span><?php echo esc_html( $car_media['car_photos_count'] ); ?></span>
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(".<?php echo esc_attr( $dynamic_class_photo ); ?>").on('click', function() {
						jQuery(this).lightGallery({
							dynamic: true,
							dynamicEl: [
								<?php foreach ( $car_media['car_photos'] as $car_photo ) : ?>
								{
									src  : "<?php echo esc_url( $car_photo ); ?>",
									thumb: "<?php echo esc_url( $car_photo ); ?>"
								},
								<?php endforeach; ?>
							],
							download: false,
							mode: 'lg-fade',
						})
					});
				});

			</script>
		<?php endif; ?>
		<?php if ( ! empty( $car_media['car_videos_count'] ) ) : ?>
			<div class="stm-listing-videos-unit stm-car-videos-<?php echo esc_attr( get_the_ID() ); ?> <?php echo esc_attr( $dynamic_class_video ); ?>">
				<i class="fas fa-film"></i>
				<span><?php echo esc_html( $car_media['car_videos_count'] ); ?></span>
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery(".<?php echo esc_attr( $dynamic_class_video ); ?>").on('click', function() {

						jQuery(this).lightGallery({
							selector: 'this',
							dynamic: true,
							dynamicEl: [
								<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
								{
									src : "<?php echo esc_url( $car_video ); ?>",
									thumb: ''
								},
								<?php endforeach; ?>
							],
							download: false,
							mode: 'lg-video',
						})
					}); //click
				}); //ready

			</script>
		<?php endif; ?>
	</div>
	<!--Favourite-->
	<?php do_action( 'stm_listings_load_template', 'loop/favorite' ); ?>

	<!--Video-->
	<?php do_action( 'stm_listings_load_template', 'loop/video' ); ?>

	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
		<div class="image-inner <?php echo esc_attr( $interactive_hoverable_class ); ?>">
			<!--Badge-->
			<?php do_action( 'stm_listings_load_template', 'loop/badge' ); ?>

			<?php do_action( 'stm_listings_load_template', 'loop/image-preview', array( 'view_type' => 'list' ) ); ?>

		</div>
	</a>
</div>
