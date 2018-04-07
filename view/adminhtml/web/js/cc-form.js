/**
 * See LICENSE.txt for license details.
 */
/*browser:true*/
/*global define*/
define([
    'jquery',
    'uiComponent',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/lib/view/utils/dom-observer',
    'mage/translate'
], function ($, Class, alert, domObserver, $t) {
    'use strict';

    return Class.extend({

        defaults: {
            $selector: null,
            selector: 'edit_form',
            container: 'payment_form_yandex_cc',
            active: false,
            scriptLoaded: false,
            selectedCardType: null,
            imports: {
                onActiveChange: 'active'
            }
        },

        /**
         * Set list of observable attributes
         * @returns {exports.initObservable}
         */
        initObservable: function () {
            var self = this;

            self.$selector = $('#' + self.selector);
            this._super()
                .observe([
                    'active',
                    'scriptLoaded',
                    'selectedCardType'
                ]);

            // re-init payment method events
            self.$selector.off('changePaymentMethod.' + this.code)
                .on('changePaymentMethod.' + this.code, this.changePaymentMethod.bind(this));

            // listen block changes
            domObserver.get('#' + self.container, function () {
                if (self.scriptLoaded()) {
                    self.$selector.off('submit');
                }
            });

            return this;
        },

        /**
         * Enable/disable current payment method
         * @param {Object} event
         * @param {String} method
         * @returns {exports}
         */
        changePaymentMethod: function (event, method) {
            this.active(method === this.code);
            return this;
        },

        /**
         * Triggered when payment changed
         * @param {Boolean} isActive
         */
        onActiveChange: function (isActive) {
            if (!isActive) {
                this.$selector.off('submitOrder.' + this.code);
                return;
            }

            this.disableEventListeners();
            window.order.addExcludedPaymentMethod(this.code);

            if (!this.shopId) {
                this.error($t('This payment is not available'));
                return;
            }

            this.enableEventListeners();

            if (!this.scriptLoaded()) {
                this.loadScript();
            }
        },

        /**
         * Load external Yandex SDK
         */
        loadScript: function () {
            var self = this,
                state = self.scriptLoaded;

            $('body').trigger('processStart');
            require([this.sdkUrl], function () {
                state(true);
                self.yandex = window.YandexCheckout(self.shopId);
                $('body').trigger('processStop');
            });
        },

        /**
         * Show alert message
         * @param {String} message
         */
        error: function (message) {
            alert({
                content: message
            });
        },

        /**
         * Enable form event listeners
         */
        enableEventListeners: function () {
            this.$selector.on('submitOrder.' + this.code, this.submitOrder.bind(this));
        },

        /**
         * Disable form event listeners
         */
        disableEventListeners: function () {
            this.$selector.off('submitOrder');
            this.$selector.off('submit');
        },

        /**
         * Store payment details
         * @param {String} token
         */
        setPaymentDetails: function (token) {
            $('#' + this.container)
                .find('[name="payment[payment_token]"]')
                .val(token);
        },

        /**
         * Trigger order submit
         */
        submitOrder: function () {
            var self = this;

            this.$selector.validate().form();
            this.$selector.trigger('afterValidate.beforeSubmit');
            $('body').trigger('processStop');

            // validate parent form
            if (this.$selector.validate().errorList.length) {
                return false;
            }

            this.yandex.tokenize({
                number: $(self.getSelector('cc_number')).val(),
                cvc: $(self.getSelector('cc_cid')).val(),
                month: $(self.getSelector('expiration')).val(),
                year: $(self.getSelector('expiration_yr')).val()
            })
            .then(function (response) {
                if (response.status === 'success') {
                    self.setPaymentDetails(response.data.response.paymentToken);
                    self.placeOrder();
                } else {
                    self.error(response.error.message);
                }
            });
        },

        /**
         * Place order
         */
        placeOrder: function () {
            $('#' + this.selector).trigger('realOrder');
        },

        /**
         * Get jQuery selector
         * @param {String} field
         * @returns {String}
         */
        getSelector: function (field) {
            return '#' + this.code + '_' + field;
        }
    });
});
