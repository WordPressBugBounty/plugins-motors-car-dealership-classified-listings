<?php
global $listing_id;

$listing_id = ( is_null( $listing_id ) ) ? get_the_ID() : $listing_id;
if ( function_exists( 'stm_get_listing_seller_note' ) ) {
	$content = apply_filters( 'stm_get_listing_seller_note', $listing_id );
} else {
	$content = get_the_content( null, null, $listing_id );
}
if ( htmlspecialchars_decode( $content ) !== $content ) {
	$content = htmlspecialchars_decode( $content );
}
?>
<div class="post-content mvl-listing-description">
	<?php
	echo wp_kses( $content, mvl_wp_kses_allowed_html_in_content( wp_kses_allowed_html( 'post' ) ) );
	?>
</div>
