<?php
class ButterBean_Control_Media_Repeater extends ButterBean_Control {
	public $type           = 'media_repeater';
	public $second_preview = array();
	public $l10n           = array();

	public function __construct( $manager, $name, $args = array() ) {
		parent::__construct( $manager, $name, $args );

		$this->l10n = wp_parse_args(
			$this->l10n,
			array(
				'add'    => esc_html__( 'Add', 'stm_vehicles_listing' ),
				'remove' => esc_html__( 'Delete', 'stm_vehicles_listing' ),
			)
		);
	}

	public function to_json() {
		parent::to_json();

		$this->json['l10n']           = $this->l10n;
		$this->json['second_preview'] = $this->second_preview;

		$post_id = $this->manager->post_id;

		$history          = get_post_meta( $post_id, 'history', true );
		$history_link     = get_post_meta( $post_id, 'history_link', true );
		$certified_logo_1 = get_post_meta( $post_id, 'certified_logo_1', true );

		$history_2             = get_post_meta( $post_id, 'history_2', true );
		$certified_logo_2_link = get_post_meta( $post_id, 'certified_logo_2_link', true );
		$certified_logo_2      = get_post_meta( $post_id, 'certified_logo_2', true );

		$values   = array();
		$values[] = array(
			'media_file_name' => $history,
			'media_link'      => $history_link,
			'media_img'       => $certified_logo_1,
			'media_img_url'   => ! empty( $certified_logo_1 ) ? wp_get_attachment_url( $certified_logo_1 ) : '',
		);

		if ( ! empty( $certified_logo_2_link ) ) {
			$values[] = array(
				'media_file_name' => $history_2,
				'media_link'      => $certified_logo_2_link,
				'media_img'       => $certified_logo_2,
				'media_img_url'   => ! empty( $certified_logo_2 ) ? wp_get_attachment_url( $certified_logo_2 ) : '',
			);
		};

		$this->json['values'] = $values;
	}
}
