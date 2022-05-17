define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/redirect-on-success'
    ],
    function (Component, redirectOnSuccessAction) {
        'use strict'
        return Component.extend({
            defaults: {
                template: 'GateSoftware_GatePay/payment/form',
            },

            afterPlaceOrder: function () {
                redirectOnSuccessAction.redirectUrl = "payu/redirect/payment"
                this.redirectAfterPlaceOrder  = true;
            },

            getCode: function () {
                return 'gatePaymentGateway'
            },

            getData: function () {
                return {
                    'method': this.item.method
                }
            },
        });
    }
);
