/**
 * See LICENSE.txt for license details.
 */
/*browser:true*/
/*global define*/
define([
    'underscore',
    'jquery',
    'mage/translate',
    'Magento_Payment/js/view/payment/cc-form',
    'Magento_Checkout/js/model/quote',
    'Kenboy_YandexCheckout/js/view/payment/adapter',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Vault/js/view/payment/vault-enabler'
],
function (_, $, $t, Component, quote, yandex, fullScreenLoader, VaultEnabler) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Kenboy_YandexCheckout/payment/form',
            active: false,
            paymentMethodToken: null,
            lastBillingAddress: null,
            ccCode: null,
            ccMessageContainer: null,
            code: 'yandex_cc',

            /**
             * Additional payment data
             *
             * {Object}
             */
            additionalData: {},

            /**
             * Yandex client configuration
             *
             * {Object}
             */
            clientConfig: {

                /**
                 * {String}
                 */
                id: 'co-transparent-form-yandex',

                /**
                 * {Object}
                 */
                hostedFields: {},

                /**
                 * Triggers on any Yandex error
                 * @param {Object} response
                 */
                onError: function (response) {
                    yandex.showError($t('Payment ' + this.getTitle() + ' can\'t be initialized'));
                    throw response.message;
                },

                /**
                 * Triggers on payment token receive
                 * @param {Object} response
                 */
                onReceived: function (response) {
                    this.beforePlaceOrder(response);
                }
            },
            imports: {
                onActiveChange: 'active'
            }
        },

        /**
         * @returns {exports.initialize}
         */
        initialize: function () {
            this._super();
            this.vaultEnabler = new VaultEnabler();
            this.vaultEnabler.setPaymentCode(this.getVaultCode());

            return this;
        },

        /**
         * Set list of observable attributes
         *
         * @returns {exports.initObservable}
         */
        initObservable: function () {
            this._super()
                .observe(['active']);
            this.initClientConfig();

            return this;
        },

        /**
         * Get payment name
         *
         * @returns {String}
         */
        getCode: function () {
            return this.code;
        },

        /**
         * Check if payment is active
         *
         * @returns {Boolean}
         */
        isActive: function () {
            var active = this.getCode() === this.isChecked();
            this.active(active);
            return active;
        },

        /**
         * Triggers when payment method change
         * @param {Boolean} isActive
         */
        onActiveChange: function (isActive) {
            if (!isActive) {
                return;
            }

            this.restoreMessageContainer();
            this.restoreCode();

            this.initYandex();
        },

        /**
         * Restore original message container for cc-form component
         */
        restoreMessageContainer: function () {
            this.messageContainer = this.ccMessageContainer;
        },

        /**
         * Restore original code for cc-form component
         */
        restoreCode: function () {
            this.code = this.ccCode;
        },

        /** @inheritdoc */
        initChildren: function () {
            this._super();
            this.ccMessageContainer = this.messageContainer;
            this.ccCode = this.code;

            return this;
        },

        /**
         * Init config
         */
        initClientConfig: function () {
            _.each(this.clientConfig, function (fn, name) {
                if (typeof fn === 'function') {
                    this.clientConfig[name] = fn.bind(this);
                }
            }, this);

            // Hosted fields settings
            this.clientConfig.hostedFields = this.getHostedFields();
        },

        /**
         * Get Yandex Hosted Fields
         * @returns {Object}
         */
        getHostedFields: function () {
            var self = this;
            return {
                number: {
                    selector: self.getSelector('cc_number')
                },
                month: {
                    selector: self.getSelector('expiration')
                },
                year: {
                    selector: self.getSelector('expiration_yr')
                },
                cvv: {
                    selector: self.getSelector('cc_cid')
                }
            };
        },

        /**
         * Init Yandex configuration
         */
        initYandex: function () {
            fullScreenLoader.startLoader();
            yandex.setConfig(this.clientConfig);
        },

        /**
         * Get full selector name
         *
         * @param {String} field
         * @returns {String}
         */
        getSelector: function (field) {
            return '#' + this.getCode() + '_' + field;
        },

        /**
         * Get data
         *
         * @returns {Object}
         */
        getData: function () {
            var data = {
                'method': this.getCode(),
                'additional_data': {
                    'payment_method_token': this.paymentMethodToken
                }
            };

            data['additional_data'] = _.extend(data['additional_data'], this.additionalData);

            this.vaultEnabler.visitAdditionalData(data);

            return data;
        },

        /**
         * @returns {Boolean}
         */
        isVaultEnabled: function () {
            return this.vaultEnabler.isVaultEnabled();
        },

        /**
         * Set payment nonce
         * @param {String} paymentMethodToken
         */
        setPaymentMethodToken: function (paymentMethodToken) {
            this.paymentMethodToken = paymentMethodToken;
        },

        /**
         * Prepare data to place order
         * @param {Object} response
         */
        beforePlaceOrder: function (response) {
            this.setPaymentMethodToken(response.data.response.paymentToken);
            //this.placeOrder();
        },

        /**
         * Validate current credit card type
         * @returns {Boolean}
         */
        validateCardType: function () {
            return this.selectedCardType() !== null;
        },

        /**
         * Returns state of place order button
         * @returns {Boolean}
         */
        isButtonActive: function () {
            return this.isActive() && this.isPlaceOrderActionAllowed();
        },

        /**
         * Triggers order placing
         */
        placeOrderClick: function () {
            if (this.validateCardType()) {
                this.isPlaceOrderActionAllowed(false);
                yandex.tokenize();
            }
        },

        /**
         * @returns {String}
         */
        getVaultCode: function () {
            return window.checkoutConfig.payment[this.getCode()].ccVaultCode;
        }
    });
});
