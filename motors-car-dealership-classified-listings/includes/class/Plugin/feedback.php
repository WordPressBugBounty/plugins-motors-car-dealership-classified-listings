<div id="mvl-feedback-modal" class="mvl-feedback-modal" style="display: none">
	<div class="feedback-modal-content">
		<span class="feedback-modal-close">&times;</span>
		<span class="feedback-thank-you" style="display: none;">
			<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/class/Plugin/assets/img/thank-you.svg' ); ?>">
		</span>
		<h2>Please leave a Feedback</h2>

		<div class="feedback-rating-stars">
			<ul id="feedback-stars">
				<li class="star selected" title="Poor" data-value="1">
					<i class="feedback-star"></i>
				</li>
				<li class="star selected" title="Bad" data-value="2">
					<i class="feedback-star"></i>
				</li>
				<li class="star selected" title="Fair" data-value="3">
					<i class="feedback-star"></i>
				</li>
				<li class="star selected" title="Good" data-value="4">
					<i class="feedback-star"></i>
				</li>
				<li class="star selected" title="Excellent!" data-value="5">
					<i class="feedback-star"></i>
				</li>
			</ul>
			<span class="rating-text">Excellent!</span>
		</div>

		<p class="feedback-review-text" style="display: none;"></p>
		<div class="feedback-extra">
			<textarea id="feedback-review" rows="5" placeholder="Please enter your Review..."></textarea>
		</div>
		<a href="https://wordpress.org/support/plugin/motors-car-dealership-classified-listings/reviews/?filter=5#new-post" class="feedback-submit" target="_blank">
			Submit
			<img src="<?php echo esc_url( STM_LISTINGS_URL . '/includes/class/Plugin/assets/img/external-link.svg' ); ?>">
		</a>
	</div>
</div>
