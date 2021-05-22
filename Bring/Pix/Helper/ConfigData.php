<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Helper;

class ConfigData
{
    //credentials path
    const PATH_PUBLIC_KEY = 'payment/pix/public_key';
    const PATH_DEST_NAME = 'payment/pix/dest_name';
    const PATH_DEST_CITY = 'payment/pix/dest_city';
    const PATH_DESCRIPTION = 'payment/pix/description';
    const PATH_SANDBOX = 'payment/pix/sandbox';

    //configuration hidden path
    const PATH_SITE_ID = 'payment/pix/site_id';
    const PATH_SPONSOR_ID = 'payment/pix/sponsor_id';

    //custom method credit and debit card
    const PATH_CUSTOM_ACTIVE = 'payment/pix_custom/active';
    const PATH_CUSTOM_BINARY_MODE = 'payment/pix_custom/binary_mode';
    const PATH_CUSTOM_STATEMENT_DESCRIPTOR = 'payment/pix_custom/statement_descriptor';
    const PATH_CUSTOM_BANNER = 'payment/pix_custom/banner_checkout';
    const PATH_CUSTOM_COUPON = 'payment/pix_custom/coupon_pix';
    const PATH_CUSTOM_GATEWAY_MODE = 'payment/pix_custom/gateway_mode';

    //custom method ticket
    const PATH_PIX_ACTIVE = 'payment/bring_pix/active';
    const PATH_CUSTOM_TICKET_COUPON = 'payment/bring_pix/coupon_pix';
    const PATH_CUSTOM_TICKET_BANNER = 'payment/bring_pix/banner_checkout';
    const PATH_CUSTOM_EXCLUDE_PAYMENT_METHODS = 'payment/bring_pix/excluded_payment_methods';
    const PATH_CUSTOM_INSTRUCTIONS = 'payment/bring_pix/instructions';

    //basic method
    const PATH_BASIC_ACTIVE = 'payment/pix_basic/active';
    const PATH_BASIC_TITLE = 'payment/pix_basic/title';
    const PATH_BASIC_URL_FAILURE = 'payment/pix_basic/url_failure';
    const PATH_BASIC_MAX_INSTALLMENTS = 'payment/pix_basic/max_installments';
    const PATH_BASIC_AUTO_RETURN = 'payment/pix_basic/auto_return';
    const PATH_BASIC_EXCLUDE_PAYMENT_METHODS = 'payment/pix_basic/excluded_payment_methods';
    const PATH_BASIC_STATEMENT_DESCRIPTION = 'payment/pix_basic/statement_desc';
    const PATH_BASIC_EXPIRATION_TIME_PREFERENCE = 'payment/pix_basic/exp_time_pref';
    const PATH_BASIC_ORDER_STATUS = 'payment/pix_basic/order_status';
    const PATH_BASIC_BINARY_MODE = 'payment/pix_basic/binary_mode';
    const PATH_BASIC_GATEWAY_MODE = 'payment/pix_basic/gateway_mode';

    //order configuration
    const PATH_ORDER_AUTHORIZED = 'payment/pix/order_status_approved';
    const PATH_ORDER_IN_PROCESS = 'payment/pix/order_status_in_process';
    const PATH_ORDER_NEW = 'payment/pix/order_status_pending';
    const PATH_ORDER_COMPLETED = 'payment/pix/order_status_approved';
    const PATH_ORDER_DECLINED = 'payment/pix/order_status_rejected';
    const PATH_ORDER_CANCELLED = 'payment/pix/order_status_cancelled';
    const PATH_ORDER_VOIDED = 'payment/pix/order_status_cancelled';
    const PATH_ORDER_CHARGED_BACK = 'payment/pix/order_status_chargeback';
    const PATH_ORDER_CHARGEBACK_RESOLVED = 'payment/pix/order_status_approved';
    const PATH_ORDER_IN_MEDIATION = 'payment/pix/order_status_in_mediation';
    const PATH_ORDER_TERMINATED = 'payment/pix/order_status_cancelled';
    const PATH_ORDER_REFUNDED = 'payment/pix/order_status_refunded';
    const PATH_ORDER_PARTIALLY_REFUNDED = 'payment/pix/order_status_partially_refunded';
    const PATH_ORDER_REFUND_AVAILABLE = 'payment/pix/refund_available';
    const PATH_ORDER_CANCEL_AVAILABLE = 'payment/pix/cancel_payment';

    //advanced configuration
    const PATH_ADVANCED_LOG = 'payment/pix/logs';
    const PATH_ADVANCED_COUNTRY = 'payment/pix/country';
    const PATH_ADVANCED_CATEGORY = 'payment/pix/category_id';
    const PATH_ADVANCED_SUCCESS_PAGE = 'payment/pix/use_successpage';
    const PATH_ADVANCED_CONSIDER_DISCOUNT = 'payment/pix/consider_discount';
    const PATH_ADVANCED_EMAIL_CREATE = 'payment/pix/email_order_create';
    const PATH_ADVANCED_EMAIL_UPDATE = 'payment/pix/email_order_update';
}