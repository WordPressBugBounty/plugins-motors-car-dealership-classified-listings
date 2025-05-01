<?php
namespace MotorsStarterTheme\Services;

use MotorsStarterTheme\Instance;

class SkinOptions extends Instance {
	protected static $inline_css_loaded = false;
	protected static $inline_css        = '';

	public const SETTINGS_FILES = array(
		'skin-settings/general',
		'skin-settings/header',
		'skin-settings/header/main',
		'skin-settings/header/logo',
		'skin-settings/header/menu',
		'skin-settings/header/buttons_and_actions',
		'skin-settings/header/socials',
		'skin-settings/socials',
	);

	protected const BREAKPOITNS = array(
		'desktop' => 'min-width: 1025px',
		'mobile'  => 'max-width: 1024px',
	);

	protected const CSS_FILE_OPT      = 'motors_starter_theme_options_css_filename';
	protected const CSS_FILE_NAME     = 'motors-starter-theme-options';
	protected const UPLOADS_SUBFOLDER = 'motors-starter-theme';

	/**
	 * All Skin settings fields data
	 * @var array
	 */
	protected $settings = array();

	protected function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 100 );
		add_action( 'wpcfto_after_settings_saved', array( $this, 'generate_css_file' ), 100, 2 );

		foreach ( static::SETTINGS_FILES as $file ) {
			if ( ! isset( static::$settings[ $file ] ) ) {
				require_once STM_LISTINGS_PATH . '/includes/class/Plugin/config/' . $file . '.php';
			}
		}

		$conf_map = apply_filters( 'mst_skin_settings_conf', array() );

		foreach ( $conf_map as $section ) {
			if ( isset( $section['fields'] ) && ! empty( $section['fields'] ) ) {
				$this->settings = array_merge( $this->settings, $section['fields'] );
			}
		}
	}

	protected function get_css_file_uri() {
		$file        = get_option( static::CSS_FILE_OPT, '' );
		$upload_dir  = wp_upload_dir();
		$uploads_uri = $upload_dir['baseurl'];

		if ( $file ) {
			return $uploads_uri . '/' . static::UPLOADS_SUBFOLDER . '/' . $file;
		}

		return '';
	}

	public function generate_css_file( $id, $settings_values ) {
		if ( 'mst_skin_settings' !== $id ) {
			return false;
		}

		if ( ! current_user_can( 'upload_files' ) ) {
			return new \WP_Error( 'permission_error', __( 'Insufficient rights to create file' ) );
		}

		if ( ! function_exists( 'request_filesystem_credentials' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			WP_Filesystem();
		}

		if ( ! $wp_filesystem ) {
			return new \WP_Error( 'filesystem_error', __( 'WP_Filesystem can`t initialize the file' ) );
		}

		//Different filenames need for correct working with caching plugins
		$filename     = static::CSS_FILE_NAME . '-' . time() . '.css';
		$old_filename = get_option( static::CSS_FILE_OPT );

		$upload_dir   = wp_upload_dir();
		$dir          = trailingslashit( $upload_dir['basedir'] ) . static::UPLOADS_SUBFOLDER;
		$content      = $this->get_inline_css( $settings_values );
		$filepath     = trailingslashit( $dir ) . $filename;
		$old_filepath = trailingslashit( $dir ) . $old_filename;

		if ( ! $wp_filesystem->is_dir( $dir ) ) {
			$wp_filesystem->mkdir( $dir, FS_CHMOD_DIR );
		}

		if ( file_exists( $old_filepath ) ) {
			unlink( $old_filepath );
		}

		if ( $wp_filesystem->is_dir( $dir ) ) {

			$success = $wp_filesystem->put_contents(
				$filepath,
				$content,
				FS_CHMOD_FILE
			);

			if ( $success ) {
				update_option( static::CSS_FILE_OPT, $filename );
				return $filepath;
			} else {
				return new \WP_Error( 'write_error', __( 'Save file error using WP_Filesystem' ) );
			}
		}
	}

	public function wp_enqueue_scripts() {
		$css_file_dir = $this->get_css_file_dir();

		if ( $css_file_dir && file_exists( $css_file_dir ) ) {
			wp_enqueue_style( static::CSS_FILE_NAME, $this->get_css_file_uri(), array(), MOTORS_STARTER_THEME_VERSION );
		}
	}

	public function get_css_file_dir() {
		$file        = get_option( static::CSS_FILE_OPT, '' );
		$upload_dir  = wp_upload_dir();
		$uploads_dir = $upload_dir['basedir'];

		if ( $file ) {
			return $uploads_dir . '/' . static::UPLOADS_SUBFOLDER . '/' . $file;
		}

		return '';
	}

	protected function get_inline_css( $settings_values = array() ) {
		$settings = $this->settings;

		if ( ! static::$inline_css_loaded && ! empty( $settings ) ) {
			$css_data = array();

			foreach ( $settings as $key => $field ) {
				$skin_option = SkinOption::get( $key );

				if ( isset( $settings_values[ $key ] ) ) {
					$skin_option_value = $settings_values[ $key ];
				} else {
					$skin_option_value = $skin_option->value();
				}

				if ( isset( $field['output_css'] ) && $skin_option->check() && $skin_option_value ) {
					foreach ( $field['output_css'] as $breakpoint => $css ) {
						$output_css      = new OutputCSS( $css, $skin_option_value );
						$output_css_data = $output_css->get_css_data();

						if ( ! empty( $output_css_data ) ) {
							foreach ( $output_css_data as $selector => $data ) {
								foreach ( $data as $prop => $prop_val ) {
									$css_data[ $breakpoint ][ $selector ][ $prop ] = $prop_val;
								}
							}
						}
					}
				}
			}

			if ( ! empty( $css_data ) ) {
				foreach ( $css_data as $breakpoint => $data ) {
					$breakpoit_css = array();

					foreach ( $data as $selector => $props ) {
						$css[ $selector ] = '';
						$properties_list  = array();

						foreach ( $props as $prop => $prop_val ) {
							$properties_list[] = $prop . ':' . $prop_val . ';';
						}

						$breakpoit_css[ $selector ] = $selector . '{' . implode( '', $properties_list ) . '}';
					}

					$breakpoit_css = implode( '', $breakpoit_css );

					if ( isset( static::BREAKPOITNS[ $breakpoint ] ) ) {
						static::$inline_css .= '@media(' . static::BREAKPOITNS[ $breakpoint ] . '){' . $breakpoit_css . '}';
					} else {
						static::$inline_css = $breakpoit_css . static::$inline_css;
					}
				}
			}

			static::$inline_css_loaded = true;
		}

		return static::$inline_css;
	}

	public static function get_setting_data( $id ) {
		if ( isset( static::instance()->settings[ $id ] ) ) {
			return static::instance()->settings[ $id ];
		}
		return array();
	}
}
