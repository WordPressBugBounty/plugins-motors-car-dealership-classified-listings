<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$authors           = isset( $__vars['authors'] ) ? $__vars['authors'] : array();
$current_author_id = isset( $__vars['current_author_id'] ) ? $__vars['current_author_id'] : 0;
?>

<div class="listing-author-metabox">
	<select id="listing_author_select" name="listing_author_select">
		<?php
		foreach ( $authors as $author ) :
			$user_data  = get_userdata( $author->ID );
			$user_roles = ! empty( $user_data->roles ) ? implode( ', ', $user_data->roles ) : '';
			?>
			<option value="<?php echo esc_attr( $author->ID ); ?>" <?php selected( $current_author_id, $author->ID ); ?>>
			<?php echo esc_html( $author->display_name ); ?>
			<?php if ( $user_roles ) : ?>
				(<?php echo esc_html( $user_roles ); ?>)
			<?php endif; ?>
			</option>
		<?php endforeach; ?>
	</select>

	<input type="hidden" name="stm_car_user" id="stm_car_user" value="<?php echo esc_attr( $current_author_id ); ?>" />
</div>
