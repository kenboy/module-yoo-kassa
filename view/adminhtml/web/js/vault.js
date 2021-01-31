/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
define([
  'jquery',
  'uiComponent'
], function ($, Class) {
  'use strict';

  return Class.extend({
      defaults: {
          $selector: null,
          selector: 'edit_form',
          $container: null
      },

      /**
       * Set list of observable attributes
       * @returns {exports.initObservable}
       */
      initObservable: function () {
          var self = this;

          self.$selector = $('#' + self.selector);
          self.$container =  $('#' + self.container);
          self.$selector.on(
              'setVaultNotActive.' + self.getCode(),
              function () {
                  self.$selector.off('submitOrder.' + self.getCode());
              }
          );
          self._super();

          self.initEventHandlers();

          return self;
      },

      /**
       * Get payment code
       * @returns {String}
       */
      getCode: function () {
          return this.code;
      },

      /**
       * Init event handlers
       */
      initEventHandlers: function () {
          this.$container.find('[name="payment[token_switcher]"]')
              .on('click', this.setPaymentDetails.bind(this));
      },

      /**
       * Store payment details
       */
      setPaymentDetails: function () {
          this.$selector.find('[name="payment[public_hash]"]').val(this.publicHash);
      }
  });
});