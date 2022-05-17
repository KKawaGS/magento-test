
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
        rendererList.push(
            {
                type: 'gatePaymentGateway',
                component: 'GateSoftware_GatePay/js/view/payment/method-renderer/gatePaymentGateway'
            }
        );
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
