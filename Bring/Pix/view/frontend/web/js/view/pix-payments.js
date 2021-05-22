define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (Component, rendererList) {
        'use strict';

        rendererList.push(
            {
                type: 'bring_pix',
                component: 'Bring_Pix/js/view/method-renderer/pix'
            }
        );
        return Component.extend({});
    }
);
