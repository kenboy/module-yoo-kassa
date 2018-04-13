/**
 * Copyright (c) 2018. All rights reserved.
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
            yandexType = 'yandex_cc';

        if (config[yandexType].isActive) {
            rendererList.push(
                {
                    type: yandexType,
                    component: 'Kenboy_YandexCheckout/js/view/payment/method-renderer/cc-form'
                }
            );
        }

        /** Add view logic here if needed */
        return Component.extend({});
    }
);
