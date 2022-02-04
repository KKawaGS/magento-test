define(
    [
        'uiComponent',
        'ko',
        'Magento_Customer/js/customer-data',
    ],
    function (
        Component,
        ko,
        Data
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'GateSoftware_GateModule/gate-message'
            },
            initialize: function () {
                this._super();
            },

            getCartItemsAmount: function () {
                return Data.get('cart')().summary_count;
            },

            getDiffBetweenAmount: function () {
                return this.amount - this.getCartItemsAmount();
            },

            isAmountReached: function () {
                return this.amount > this.getCartItemsAmount();
            }

        });
    }
);
