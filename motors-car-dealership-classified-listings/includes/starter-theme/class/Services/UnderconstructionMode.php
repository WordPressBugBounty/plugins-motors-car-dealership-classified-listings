<?php
namespace MotorsStarterTheme\Services;

use MotorsStarterTheme\Instance;

class UnderconstructionMode extends Instance {

	protected function __construct() {
		add_action( 'wp', array( $this, 'wp' ), 10 );
		add_filter( 'mst_underconstruction_mode', array( $this, 'activated' ), 10, 2 );
	}

	/**
	 * 'wp' Hook Callback
	 * Check if underconstruction mode is activated and display underconstruction page
	 * @return void
	 */
	public function wp() {
		if ( $this->is_excluded_request() ) {
			return;
		}

		if ( $this->activated() ) {
			$this->display();
			wp_footer();
			die();
		}
	}

	/**
	 * Display underconstruction page
	 * @return void
	 */
	protected function display() {
		get_header();
		$page_bg = wp_get_attachment_image_src( get_post_thumbnail_id( $this->page_id() ), 'full' );
		if ( ! empty( $page_bg[0] ) ) :
			?>
			<style>
				body {
					background-image: url("<?php echo esc_url( $page_bg[0] ); ?>");
					background-position: 50% 50%;
					background-size: cover;
					background-attachment: fixed;
				}
			</style>
			<?php
		endif;
		if ( defined( 'ELEMENTOR_VERSION' ) && $this->is_elementor_page( $this->page_id() ) ) {
			$this->display_elementor_page();
		}
	}

	/**
	 * Check if underconstruction page is elementor page
	 * @return bool
	 */
	protected function is_elementor_page() {
		return ! empty( get_post_meta( $this->page_id(), '_elementor_edit_mode', true ) );
	}

	/**
	 * Display elementor page
	 * @return void
	 */
	protected function display_elementor_page() {
		$page_id = $this->page_id();
		delete_post_meta( $page_id, '_wpb_vc_js_status' );
		$elementor = \Elementor\Plugin::instance();
		$elementor->frontend->init();
		$page_content = $elementor->frontend->get_builder_content( $page_id );
		echo do_shortcode( $page_content );
	}

	/**
	 * Check for excluded requests
	 * @return bool
	 */
	protected function is_excluded_request() {
		if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
			return true;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			return true;
		}
		$request_uri   = trailingslashit( strtolower( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ) );
		$excluded_uris = array(
			'/wp-admin/',
			'/feed/',
			'/feed/rss/',
			'/feed/rss2/',
			'/feed/rdf/',
			'/feed/atom/',
			'/admin/',
			'/wp-login.php',
		);
		if ( in_array( $request_uri, $excluded_uris, true ) || current_user_can( 'manage_options' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get underconstruction page id
	 * @return int
	 */
	protected function page_id() {
		return apply_filters( 'motors_vl_get_nuxy_mod', 0, 'mst_underconstruction_page_id' );
	}

	/**
	 * Check if underconstruction mode turn on in settings
	 * @return bool
	 */
	protected function turn_on() {
		return apply_filters( 'motors_vl_get_nuxy_mod', '', 'mst_underconstruction_mode' ) ? true : false;
	}

	/**
	 * Check if underconstruction mode is activated
	 * @return bool
	 */
	public function activated() {
		return $this->turn_on()
				&& $this->page_id()
				&& ! $this->is_excluded_request();
	}
}
