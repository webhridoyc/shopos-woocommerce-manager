/**
 * ShopOS Admin JavaScript
 * 
 * @package ShopOS
 * @since 1.0.0
 */

(function($) {
    'use strict';
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initShopOS();
    });
    
    /**
     * Initialize ShopOS functionality
     */
    function initShopOS() {
        // Add animation to stat cards
        animateStatCards();
        
        // Handle settings form submission
        handleSettingsForm();
        
        // Add tooltips
        addTooltips();
        
        console.log('ShopOS Admin initialized');
    }
    
    /**
     * Animate stat cards on load
     */
    function animateStatCards() {
        $('.shopos-stat-card').each(function(index) {
            $(this).delay(index * 100).fadeIn(400);
        });
    }
    
    /**
     * Handle settings form submission
     */
    function handleSettingsForm() {
        var $form = $('.shopos-settings form');
        
        if ($form.length) {
            $form.on('submit', function() {
                var $submitButton = $(this).find('input[type="submit"]');
                var originalValue = $submitButton.val();
                
                $submitButton.val(shoposData.strings.saving);
                $submitButton.prop('disabled', true);
                
                // Re-enable after submission
                setTimeout(function() {
                    $submitButton.val(originalValue);
                    $submitButton.prop('disabled', false);
                }, 1000);
            });
        }
    }
    
    /**
     * Add tooltips to elements
     */
    function addTooltips() {
        // Add title attributes where needed
        $('.shopos-stat-card').each(function() {
            var title = $(this).find('p').text();
            $(this).attr('title', title);
        });
    }
    
    /**
     * AJAX helper function
     * 
     * @param {string} action - AJAX action name
     * @param {object} data - Data to send
     * @param {function} callback - Success callback
     */
    function ajaxRequest(action, data, callback) {
        data.action = action;
        data.nonce = shoposData.nonce;
        
        $.ajax({
            url: shoposData.ajaxUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (typeof callback === 'function') {
                    callback(response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert(shoposData.strings.error);
            }
        });
    }
    
    /**
     * Show notification
     * 
     * @param {string} message - Message to display
     * @param {string} type - Notice type (success, error, warning, info)
     */
    function showNotice(message, type) {
        type = type || 'success';
        
        var $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        
        $('.wrap h1').after($notice);
        
        // Auto dismiss after 3 seconds
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 3000);
    }
    
    // Expose functions to global scope if needed
    window.ShopOS = {
        ajaxRequest: ajaxRequest,
        showNotice: showNotice
    };
    
})(jQuery);
