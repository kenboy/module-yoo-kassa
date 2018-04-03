/**
 * See LICENSE.txt for license details.
 */
/*browser:true*/
/*global define*/
define([
    'jquery',
    'yandex',
    'Magento_Ui/js/model/messageList',
    'mage/translate'
], function ($, yandex, globalMessageList, $t) {
    'use strict';

    return {
        /**
         * {Object}
         */
        client: null,
        config: {},
        checkout: null,

        /**
         * Get Yandex api client
         * @returns {Object}
         */
        getClient: function () {
            if (!this.getShopId()) {
                this.showError($t('Sorry, but something went wrong.'));
            }

            if (!this.client) {
                this.client = YandexCheckout(this.getShopId());
            }

            return this.client;
        },

        /**
         * Set configuration
         * @param {Object} config
         */
        setConfig: function (config) {
            this.config = config;
        },

        /**
         * tokenize Yandex SDK
         */
        tokenize: function () {
            var self = this;

            this.getClient()
                .tokenize({
                    number: $(self.config.hostedFields.number.selector).val(),
                    cvc: $(self.config.hostedFields.cvv.selector).val(),
                    month: $(self.config.hostedFields.month.selector).val(),
                    year: $(self.config.hostedFields.year.selector).val()
                })
                .then(function (response) {
                    if (response.status === 'success') {
                        self.config.onReceived(response);
                    } else {
                        self.config.onError(response);
                    }
                });
        },

        /**
         * Get payment name
         * @returns {String}
         */
        getCode: function () {
            return 'yandex_cc';
        },

        /**
         * Get client token
         * @returns {String|*}
         */
        getShopId: function () {
            return window.checkoutConfig.payment[this.getCode()].shopId;
        },

        /**
         * Show error message
         *
         * @param {String} errorMessage
         */
        showError: function (errorMessage) {
            globalMessageList.addErrorMessage({
                message: errorMessage
            });
        }
    };
});
