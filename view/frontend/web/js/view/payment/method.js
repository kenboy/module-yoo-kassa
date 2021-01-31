/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';

        var config = window.checkoutConfig.payment,
            yooType = 'yoomoney_cc';

        if (config[yooType].isActive) {
            rendererList.push(
                {
                    type: yooType,
                    component: 'Kenboy_YooKassa/js/view/payment/method-renderer/cc-form'
                }
            );
        }

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
