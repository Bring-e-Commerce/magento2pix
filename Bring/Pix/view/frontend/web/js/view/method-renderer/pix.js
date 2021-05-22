define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-list',
        'Magento_Checkout/js/action/get-totals',
        'jquery',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/translate',
        'Magento_Checkout/js/model/cart/totals-processor/default',
        'Magento_Checkout/js/model/cart/cache',
        'Magento_Checkout/js/model/payment/additional-validators'
    ],
    function (Component, quote, paymentService, paymentMethodList, getTotalsAction, $, fullScreenLoader, setAnalyticsInformation, $t, defaultTotal, cartCache) {
        'use strict';

        var configPayment = window.checkoutConfig.payment.bring_pix;

        return Component.extend({
            defaults: {
                template: 'Bring_Pix/payment/pix',
                paymentReady: true
            },

            initializeMethod: function () {
                var payer_email = "";

                if (typeof quote == 'object' && typeof quote.guestEmail == 'string') {
                    payer_email = quote.guestEmail
                }

                this.setBillingAddress();

                //get action change payment method
                //quote.paymentMethod.subscribe(self.changePaymentMethodSelector, null, 'change');
            },

            setBillingAddress: function (t) {
                if (typeof quote == 'object' && typeof quote.billingAddress == 'function') {
                    var billingAddress = quote.billingAddress();
                    var address = "";
                    var number = "";

                    if ("street" in billingAddress) {
                        if (billingAddress.street.length > 0) {
                            address = billingAddress.street[0]
                        }
                        if (billingAddress.street.length > 1) {
                            number = billingAddress.street[1]
                        }
                    }
                }
            },

            getInitialTotal: function () {
                var initialTotal = quote.totals().base_subtotal
                    + quote.totals().base_shipping_incl_tax
                    + quote.totals().base_tax_amount
                    + quote.totals().base_discount_amount;

                return initialTotal;
            },

            setValidateHandler: function (handler) {
                this.validateHandler = handler;
            },

            context: function () {
                return this;
            },

            /**
             * Get url to logo
             * @returns {String}
             */
            getLogoUrl: function () {
                if (window.checkoutConfig.payment[this.getCode()] != undefined) {
                    return configPayment['logoUrl'];
                }
                return '';
            },

            setPlaceOrderHandler: function (handler) {
                this.placeOrderHandler = handler;
            },

            getCountryId: function () {
                return configPayment['country'];
            },

            existBanner: function () {
                if (window.checkoutConfig.payment[this.getCode()] != undefined) {
                    if (window.checkoutConfig.payment[this.getCode()]['bannerUrl'] != null) {
                        return true;
                    }
                }
                return false;
            },

            getBannerUrl: function () {
                if (window.checkoutConfig.payment[this.getCode()] != undefined) {
                    return window.checkoutConfig.payment[this.getCode()]['bannerUrl'];
                }
                return '';
            },

            getCode: function () {
                return 'bring_pix';
            },

            getTicketsData: function () {
                return configPayment['options'];
            },

            getCountTickets: function () {
                //var options = this.getTicketsData();
                //return options.length;

                return(0);
            },

            getFirstTicketId: function () {

                var options = this.getTicketsData();

                return options[0]['id'];
            },

            getInitialGrandTotal: function () {
                if (configPayment != undefined) {
                    return configPayment['grand_total'];
                }
                return '';
            },

            getSuccessUrl: function () {
                var ret = '';
                if (configPayment != undefined && configPayment['success_url'] != null) {
                    ret = configPayment['success_url'];
                }

                return ret;
            },

            isActive: function() {
                return true;
            },

            getPaymentSelected: function () {

                if (this.getCountTickets() == 1) {
                    var input = document.getElementsByName("pix_pix[payment_method_ticket]")[0];
                    return input.value;
                }

                var element = document.querySelector('input[name="pix_pix[payment_method_ticket]"]:checked');
                if (this.getCountTickets() > 1 && element) {
                    return element.value;

                } else {
                    return false;
                }

            },

            /**
             * @override
             */
            getData: function () {
                var dataObj = {
                    'method': this.item.method,
                    'additional_data': {
                        'method': this.getCode()
                        //'site_id': this.getCountryId(),
                        //'payment_method_ticket': this.getPaymentSelected(),
                    }
                };

                // return false;
                return dataObj;
            },

            afterPlaceOrder: function () {
                window.location = this.getSuccessUrl();
            },

            validate: function () {
                return(true);
                //return this.validateHandler();
            },

            /*
             *
             * Events
             *
             */

            changePaymentMethodSelector: function (paymentMethodSelected) {
                /*
                if (paymentMethodSelected.method != 'bring_pix') {
                    if (CPv1Ticket.coupon_of_discounts.status) {
                        CPv1Ticket.removeCouponDiscount();
                    }
                }
                */
            },

            updateSummaryOrder: function () {               
                cartCache.set('totals', null);
                defaultTotal.estimateTotals();
            },
        });
    }
);
