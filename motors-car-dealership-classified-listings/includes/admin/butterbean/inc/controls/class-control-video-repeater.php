<?php
class ButterBean_Control_Video_Repeater extends ButterBean_Control {
	/**
	 * The type of control.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'video_repeater';

	/**
	 * Array of text labels to use for the media upload frame.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $l10n = array();

	/**
	 * Creates a new control object.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @param  string  $name
	 * @param  array   $args
	 * @return void
	 */
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

	/**
	 * Adds custom data to the json array.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function to_json() {
		parent::to_json();

		$this->json['l10n'] = $this->l10n;

		$post_id       = $this->manager->post_id;
		$video_links   = get_post_meta( $post_id, 'gallery_videos', true );
		$video_posters = get_post_meta( $post_id, 'gallery_videos_posters', true );

		$values = array();
		if ( ! empty( $video_links ) && is_array( $video_links ) ) {
			foreach ( $video_links as $key => $link ) {
				$values[] = array(
					'link'    => $link,
					'img'     => isset( $video_posters[ $key ] ) ? $video_posters[ $key ] : '',
					'img_url' => isset( $video_posters[ $key ] ) ? wp_get_attachment_url( $video_posters[ $key ] ) : '',
				);
			}
		}

		$this->json['values'] = $values;
	}
}
