<?php
namespace MotorsVehiclesListing\MagicKit;

use MotorsVehiclesListing\Libs\Traits\Instance;

class MagicKit {
	use Instance;

	public static function render( array $kit ) {
		echo '<div data-kit="' . esc_attr( $kit['id'] ) . '"></div>';
	}

	public static function js_files(): array {
		$url = STM_LISTINGS_URL . '/magic-kit/assets/js/';
		return array(
			'tinymce'             => STM_LISTINGS_URL . '/assets/libs/tinymce/js/tinymce/tinymce.min.js',
			'color-picker'        => $url . 'libs/color-picker.js',
			'color-picker-extend' => $url . 'libs/color-picker-extend.js',
			'jshelper'            => $url . 'libs/jshelper.js',
			'magic-kit-class'     => $url . 'classes/MagicClass.js',
			'magic-kit-item'      => $url . 'classes/MagicItem.js',
			'magic-kit-cache'     => $url . 'MagicCache.js',
			'magic-kit'           => $url . 'classes/MagicKit.js',
			'magic-kit-api'       => $url . 'MagicAPI.js',
			'magic-kit-modal'     => $url . 'classes/components/MagicModal.js',
			'magic-kit-message'   => $url . 'classes/components/MagicMessage.js',
			'magic-kit-smart-tag' => $url . 'classes/components/MagicSmartTag.js',
			'magic-kit-input'     => $url . 'classes/components/MagicInput.js',
			'magic-kit-textarea'  => $url . 'classes/components/MagicTextarea.js',
			'magic-kit-editor'    => $url . 'classes/components/MagicEditor.js',
			'magic-kit-wpimage'   => $url . 'classes/components/MagicWPImage.js',
			'magic-kit-checkbox'  => $url . 'classes/components/MagicCheckbox.js',
			'magic-kit-radio'     => $url . 'classes/components/MagicRadio.js',
			'magic-kit-color'     => $url . 'classes/components/MagicColor.js',
			'magic-kit-switch'    => $url . 'classes/components/MagicSwitch.js',
			'magic-kit-select'    => $url . 'classes/components/MagicSelect.js',
			'magic-kit-search'    => $url . 'classes/components/MagicSearch.js',
		);
	}

	public static function css_files(): array {
		$url = STM_LISTINGS_URL . '/magic-kit/assets/css/';

		return array(
			'color-picker' => STM_LISTINGS_URL . '/assets/libs/pickr/styles.css',
			'tinymce'      => STM_LISTINGS_URL . '/assets/libs/tinymce/css/tinymce.css',
			'magic-kit'    => $url . 'styles.css',
		);
	}
}
