<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! isset( $placeholder ) ) {
	if ( isset( $label ) ) {
		$placeholder = $label;
	}
} ?>
<template id="mvl-confirmation-modal-template">
	<div class="mvl-confirmation-modal">
		<div class="mvl-confirmation-modal-content">
			<div class="mvl-confirmation-modal-content-top">
				<h4>
					{{title}}
				</h4>
				<div id="close-confirmation-modal" class="mvl-listing-manager-close-modal">
					<i class="motors-icons-close-times"></i>
				</div>
			</div>
			<div class="mvl-listing-manager-modal-content-description">
				{{message}}
			</div>
			<div class="mvl-listing-manager-confirmation-modal-btns">
				<button id="stay-btn" class="mvl-confirmation-modal-content-button mvl-secondary-btn">
					{{accept}}
				</button>
				<button id="discard-btn" class="mvl-confirmation-modal-content-button mvl-delete-btn" data-slug="{{slug}}">
					{{deleteBtnIcon}}
					{{cancel}}
				</button>
			</div>
		</div>
	</div>
</template>
