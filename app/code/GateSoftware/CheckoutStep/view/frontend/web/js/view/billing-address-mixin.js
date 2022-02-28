define(['Magento_Checkout/js/model/quote'],
    function (quote) {
        'use strict';

        var mixin = {
            //if biling address is disabled default billing addres is the same as shipping
            currentBillingAddress: window.checkoutConfig.isBillingAddressDisabled ? quote.shippingAddress : quote.billingAddress,

            //return billing address config value
            isBillingAddressDisabled: function () {
                return window.checkoutConfig.isBillingAddressDisabled;
            }
        };

        return function (target) {
            return target.extend(mixin);
        }
    }
)
