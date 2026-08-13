(function () {
	'use strict';

	Vue.component('wpcfto_business_type_selector', {
		props: ['fields', 'field_name', 'field_id', 'field_value'],
		data: function () {
			return {
				isConfirming: false,
				originalValue: '',
				value: ''
			};
		},
		computed: {
			currentLabel: function () {
				if (this.fields.options && this.fields.options[this.originalValue]) {
					return this.fields.options[this.originalValue];
				}

				return this.originalValue;
			},
			optionKeys: function () {
				if (!this.fields.options) {
					return [];
				}

				return Object.keys(this.fields.options);
			}
		},
		template: `
			<div class="mvl-business-type-setting" :id="field_id">
				<div class="mvl-business-type-setting__intro">
					<h3>{{ fields.label }}</h3>
					<p>{{ fields.description }}</p>
				</div>

				<div class="mvl-business-type-setting__active">
					<div class="mvl-business-type-setting__active-main">
						<div class="mvl-business-type-setting__icon" aria-hidden="true"><span class="dashicons dashicons-store"></span></div>
						<div>
							<span class="mvl-business-type-setting__eyebrow">{{ fields.active_text }}</span>
							<strong>{{ currentLabel }}</strong>
						</div>
						<span class="mvl-business-type-setting__status">{{ fields.running_text }}</span>
					</div>
					<div class="mvl-business-type-setting__active-action">
						<div class="mvl-business-type-setting__inline-options">
							<div class="mvl-business-type-setting__grid">
								<div
									v-for="key in optionKeys"
									:key="key"
									class="mvl-business-type-setting__option"
									:class="{ 'is-selected': value === key, 'is-locked': isLocked(key) }">
									<button v-if="!isLocked(key)" type="button" @click="selectOption(key)">
										<span class="mvl-business-type-setting__radio" aria-hidden="true"></span>
										<strong>{{ fields.options[key] }}</strong>
									</button>
									<div v-else class="mvl-business-type-setting__locked-option">
										<span class="mvl-business-type-setting__radio" aria-hidden="true"></span>
										<strong>{{ fields.options[key] }}</strong>
										<a :href="fields.pro_url" target="_blank" rel="noopener noreferrer">PRO</a>
									</div>
								</div>
							</div>
							<div class="mvl-business-type-setting__inline-footer">
								<p>{{ fields.prompt_text }}</p>
								<div class="mvl-business-type-setting__options-actions">
									<a v-if="hasLockedOptions()" :href="fields.pro_url" target="_blank" rel="noopener noreferrer">{{ fields.upgrade_text }}</a>
									<button
										v-if="!hasLockedOptions()"
										type="button"
										:disabled="value === originalValue || isConfirming"
										@click="finishOptions">{{ fields.done_text }}</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<p class="mvl-business-type-setting__notice">
					<span class="dashicons dashicons-info-outline" aria-hidden="true"></span>
					{{ fields.notice_text }}
				</p>
			</div>
		`,
		mounted: function () {
			this.value = this.field_value;
			this.originalValue = this.field_value;
		},
		methods: {
			applySelection: function () {
				this.originalValue = this.value;
				this.isConfirming = false;
				this.$emit('wpcfto-get-value', this.value);
			},
			finishOptions: function () {
				var component = this;
				var selectionEvent;

				if (this.value === this.originalValue) {
					return;
				}

				selectionEvent = new CustomEvent('mvl-business-type-selection-finished', {
					bubbles: true,
					cancelable: true,
					detail: {
						value: this.value,
						apply: function () {
							component.applySelection();
						},
						close: function () {
							component.isConfirming = true;
						},
						reopen: function () {
							component.isConfirming = false;
						}
					}
				});

				if (this.$el.dispatchEvent(selectionEvent)) {
					this.applySelection();
				}
			},
			hasLockedOptions: function () {
				return this.fields.locked && Object.keys(this.fields.locked).length > 0;
			},
			isLocked: function (key) {
				return this.fields.locked && this.fields.locked[key];
			},
			selectOption: function (key) {
				if (this.isLocked(key)) {
					return;
				}

				this.value = key;
			}
		},
		watch: {
			field_value: function (value) {
				this.value = value;
				this.originalValue = value;
			}
		}
	});
}());
