/**
 * Copyright (c) 2018. All rights reserved.
 * See LICENSE.txt for license details.
 */
/* @api */
define([
    'jquery',
    'mageUtils',
    'mage/utils/wrapper'
], function ($, utils, wrapper) {
    'use strict';

    var types = [
        {
            title: 'MIR',
            type: 'MIR',
            pattern: '^2\\d*$',
            gaps: [4, 8, 12],
            lengths: [16],
            code: {
                name: 'CVV',
                size: 3
            }
        }
    ];

    return function (creditCardType) {
        return wrapper.extend(creditCardType, {
            /**
             * @param {Function} originalAction
             * @param {String} cardNumber
             * @return {Array}
             */
            getCardTypes: function (originalAction, cardNumber) {
                var i, value,
                    result = originalAction(cardNumber);

                if (utils.isEmpty(cardNumber)) {
                    return result;
                }

                for (i = 0; i < types.length; i++) {
                    value = types[i];

                    if (new RegExp(value.pattern).test(cardNumber)) {
                        result.push($.extend(true, {}, value));
                    }
                }

                return result;
            }
        });
    };
});
