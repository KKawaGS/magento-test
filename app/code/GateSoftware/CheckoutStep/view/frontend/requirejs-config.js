var config = {
    map: {
        '*': {
            'Magento_Checkout/template/billing-address.html': 'GateSoftware_CheckoutStep/template/billing-address.html',
        }
    },
    config: {
            mixins: {
                'Magento_Checkout/js/view/billing-address' : {
                    'GateSoftware_CheckoutStep/js/view/billing-address-mixin': true
                }
            }

    }
}
