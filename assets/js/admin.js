/**
 * ShopOS WooCommerce Manager - Admin JavaScript
 *
 * @package ShopOS_WooCommerce_Manager
 * @version 1.0.0
 */

(function($) {
	'use strict';

	/**
	 * ShopOS Admin Object
	 */
	const ShopOSAdmin = {
		/**
		 * Initialize admin functionality
		 */
		init: function() {
			this.bindEvents();
			this.initDashboard();
			console.log('ShopOS Admin initialized');
		},

		/**
		 * Bind event handlers
		 */
		bindEvents: function() {
			// Handle refresh buttons
			$(document).on('click', '.shopos-refresh-stats', this.refreshStats.bind(this));
			
			// Handle bulk actions
			$(document).on('click', '.shopos-bulk-action', this.handleBulkAction.bind(this));
			
			// Handle form submissions
			$(document).on('submit', '.shopos-ajax-form', this.handleAjaxForm.bind(this));
		},

		/**
		 * Initialize dashboard functionality
		 */
		initDashboard: function() {
			// Check if we're on the dashboard page
			if (!$('.shopos-dashboard').length) {
				return;
			}

			// Add any dashboard-specific initialization here
			this.updateStatistics();
		},

		/**
		 * Update statistics on the dashboard
		 */
		updateStatistics: function() {
			// Placeholder for future AJAX-based stats updates
			console.log('Statistics updated');
		},

		/**
		 * Refresh statistics via AJAX
		 *
		 * @param {Event} e Click event
		 */
		refreshStats: function(e) {
			e.preventDefault();
			const $button = $(e.currentTarget);
			
			// Disable button and show loading
			$button.prop('disabled', true);
			$button.html('<span class="shopos-loading"></span> Refreshing...');

			// Make AJAX request
			$.ajax({
				url: shoposAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'shopos_refresh_stats',
					nonce: shoposAdmin.nonce
				},
				success: function(response) {
					if (response.success) {
						ShopOSAdmin.showNotice('success', shoposAdmin.strings.success);
						location.reload();
					} else {
						ShopOSAdmin.showNotice('error', response.data.message || shoposAdmin.strings.error);
					}
				},
				error: function() {
					ShopOSAdmin.showNotice('error', shoposAdmin.strings.error);
				},
				complete: function() {
					$button.prop('disabled', false);
					$button.html('Refresh');
				}
			});
		},

		/**
		 * Handle bulk actions
		 *
		 * @param {Event} e Click event
		 */
		handleBulkAction: function(e) {
			e.preventDefault();
			const $button = $(e.currentTarget);
			const action = $button.data('action');
			const items = this.getSelectedItems();

			if (items.length === 0) {
				this.showNotice('error', 'Please select at least one item.');
				return;
			}

			if (!confirm('Are you sure you want to perform this action?')) {
				return;
			}

			// Disable button and show loading
			$button.prop('disabled', true);
			$button.html('<span class="shopos-loading"></span> Processing...');

			// Make AJAX request
			$.ajax({
				url: shoposAdmin.ajaxUrl,
				type: 'POST',
				data: {
					action: 'shopos_bulk_action',
					bulk_action: action,
					items: items,
					nonce: shoposAdmin.nonce
				},
				success: function(response) {
					if (response.success) {
						ShopOSAdmin.showNotice('success', response.data.message || shoposAdmin.strings.success);
						location.reload();
					} else {
						ShopOSAdmin.showNotice('error', response.data.message || shoposAdmin.strings.error);
					}
				},
				error: function() {
					ShopOSAdmin.showNotice('error', shoposAdmin.strings.error);
				},
				complete: function() {
					$button.prop('disabled', false);
					$button.html($button.data('original-text') || 'Apply');
				}
			});
		},

		/**
		 * Handle AJAX form submissions
		 *
		 * @param {Event} e Submit event
		 */
		handleAjaxForm: function(e) {
			e.preventDefault();
			const $form = $(e.currentTarget);
			const $submitButton = $form.find('[type="submit"]');

			// Disable submit button
			$submitButton.prop('disabled', true);

			// Serialize form data
			const formData = $form.serialize();

			// Make AJAX request
			$.ajax({
				url: shoposAdmin.ajaxUrl,
				type: 'POST',
				data: formData + '&nonce=' + shoposAdmin.nonce,
				success: function(response) {
					if (response.success) {
						ShopOSAdmin.showNotice('success', response.data.message || shoposAdmin.strings.success);
					} else {
						ShopOSAdmin.showNotice('error', response.data.message || shoposAdmin.strings.error);
					}
				},
				error: function() {
					ShopOSAdmin.showNotice('error', shoposAdmin.strings.error);
				},
				complete: function() {
					$submitButton.prop('disabled', false);
				}
			});
		},

		/**
		 * Get selected items from checkboxes
		 *
		 * @return {Array} Selected item IDs
		 */
		getSelectedItems: function() {
			const items = [];
			$('input.shopos-select-item:checked').each(function() {
				items.push($(this).val());
			});
			return items;
		},

		/**
		 * Show admin notice
		 *
		 * @param {string} type Notice type (success, error, info)
		 * @param {string} message Notice message
		 */
		showNotice: function(type, message) {
			// Remove existing notices
			$('.shopos-notice').remove();

			// Create notice element
			const $notice = $('<div>', {
				class: 'shopos-notice ' + type,
				html: '<p>' + message + '</p>'
			});

			// Prepend to the page
			$('.wrap').prepend($notice);

			// Auto-dismiss after 5 seconds
			setTimeout(function() {
				$notice.fadeOut(function() {
					$(this).remove();
				});
			}, 5000);

			// Scroll to top
			$('html, body').animate({
				scrollTop: 0
			}, 300);
		},

		/**
		 * Format currency
		 *
		 * @param {number} amount Amount to format
		 * @return {string} Formatted currency string
		 */
		formatCurrency: function(amount) {
			return new Intl.NumberFormat('en-US', {
				style: 'currency',
				currency: 'USD'
			}).format(amount);
		},

		/**
		 * Format date
		 *
		 * @param {string} dateString Date string
		 * @return {string} Formatted date string
		 */
		formatDate: function(dateString) {
			const date = new Date(dateString);
			return date.toLocaleDateString('en-US', {
				year: 'numeric',
				month: 'short',
				day: 'numeric'
			});
		}
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		ShopOSAdmin.init();
	});

	// Expose to global scope for debugging
	window.ShopOSAdmin = ShopOSAdmin;

})(jQuery);
