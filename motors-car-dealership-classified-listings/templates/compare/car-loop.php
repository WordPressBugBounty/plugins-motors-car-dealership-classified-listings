<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

while ( $compares->have_posts() ) :
	$compares->the_post(); ?>
	<div class="col-md-3 col-sm-3 col-xs-4 compare-col-stm">
		<?php if ( ! empty( $filter_options ) ) : ?>
			<div class="compare-values">
				<div class="stm_compare_col_top">
					<button class="button add-to-compare stm_remove_after"
						data-id="<?php echo esc_attr( get_the_ID() ); ?>"
						data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
						data-title="<?php echo esc_attr( get_the_title() ); ?>">
						<?php echo esc_html__( 'Remove', 'stm_vehicles_listing' ); ?>
					</button>
					<h4 class="text-transform compare-car-visible"><?php the_title(); ?></h4>
				</div>
				<table>
					<?php foreach ( $filter_options as $filter_option ) : ?>
						<?php if ( false === apply_filters( 'stm_is_listing_price_field', $filter_option['slug'] ) ) { ?>
							<tr>
								<?php $compare_option = get_post_meta( get_the_ID(), $filter_option['slug'], true ); ?>
								<td class="compare-value-hover"
									data-value="<?php echo esc_attr( 'compare-value-' . $filter_option['slug'] ); ?>">
									<div class="h5">
										<?php
										if ( ! empty( $compare_option ) ) {
											// if numeric get value from meta
											if ( ! empty( $filter_option['numeric'] ) && $filter_option['numeric'] ) {
												echo esc_attr( $compare_option );
											} else {
												// not numeric, get category name by meta
												$data_meta_array = explode( ',', $compare_option );
												$datas           = array();

												if ( ! empty( $data_meta_array ) ) {
													foreach ( $data_meta_array as $data_meta_single ) {
														$data_meta = get_term_by( 'slug', $data_meta_single, $filter_option['slug'] );
														if ( ! empty( $data_meta->name ) ) {
															$datas[] = esc_html( $data_meta->name );
														}
													}
												}
												if ( ! empty( $datas ) ) {
													echo implode( ', ', $datas );

												} else {
													esc_html_e( 'None', 'stm_vehicles_listing' );
												}
											}
										} else {
											esc_html_e( 'None', 'stm_vehicles_listing' );
										}
										?>
									</div>
								</td>
							</tr>
						<?php } ?>
					<?php endforeach; ?>
				</table>
			</div>
		<?php endif; ?>
	</div> <!--md-3-->
<?php endwhile; ?>
