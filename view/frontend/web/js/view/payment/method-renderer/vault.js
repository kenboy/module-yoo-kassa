/**
 * Copyright (c) 2018. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
    'Magento_Vault/js/view/payment/method-renderer/vault'
], function (VaultComponent) {
    'use strict';

    return VaultComponent.extend({
        /**
         * @inheritDoc
         */
        getMaskedCard: function () {
            return this.details.maskedCC;
        },

        /**
         * @inheritDoc
         */
        getExpirationDate: function () {
            return this.details.expirationDate;
        },

        /**
         * @inheritDoc
         */
        getCardType: function () {
            return this.details.type;
        },

        /**
         * @inheritDoc
         */
        getToken: function () {
            return this.publicHash;
        }
  });
});