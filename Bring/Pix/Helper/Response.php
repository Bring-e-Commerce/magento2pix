<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Helper;

/**
 * Class Response
 *
 * Http codes
 *
 * @package Bring\Pix\Helper
 */
class Response
{
    /*
     * HTTP Response Codes
     */
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_MULTI_STATUS = 207;

    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 500; // changed the status because the Pix only renotify when it is 500 - old status:401;
    const HTTP_FORBIDDEN = 500; // changed the status because the Pix only renotify when it is 500 - old status:403;
    const HTTP_NOT_FOUND = 500; // changed the status because the Pix only renotify when it is 500 - old status:404;
    const HTTP_METHOD_NOT_ALLOWED = 500; // changed the status because the Pix only renotify when it is 500 - old status:405;
    const HTTP_NOT_ACCEPTABLE = 500; // changed the status because the Pix only renotify when it is 500 - old status:406;

    const HTTP_INTERNAL_ERROR = 500;

    const INFO_MERCHANT_ORDER_NOT_FOUND = 'Merchant Order not found';
    const INFO_STATUS_NOT_FINAL = 'Status not final';
    const INFO_EXTERNAL_REFERENCE_NOT_FOUND = 'External reference not found';
    const INFO_ORDER_CANCELED = 'The order is canceled';

    const TOPIC_RECURRING_PAYMENT = 'preapproval';
    const TOPIC_PAYMENT = 'payment';


    const PAYMENT_CREATION_ERRORS = array(
        "1" => "Params Error.",
        "3" => "Token must be for test.",
        "4" => "The caller is not authorized to access this resource.",
        "5" => "Must provide your access_token to proceed.",
        "1000" => "Number of rows exceeded the limits.",
        "1001" => "Date format must be yyyy-MM-dd'T'HH:mm:ss.SSSZ.",
        "2000" => "Payment not found",
        "2001" => "Already posted the same request in the last minute.",
        "2002" => "Customer not found.",
        "2004" => "POST to Gateway Transactions API fail.",
        "2006" => "Card Token not found.",
        "2007" => "Connection to Card Token API fail.",
        "2009" => "Card token issuer can't be null.",
        "2060" => "The customer can't be equal to the collector.",
        "3000" => "You must provide your cardholder_name with your card data.",
        "3001" => "You must provide your cardissuer_id with your card data.",
        "3002" => "The caller is not authorized to perform this action.",
        "3003" => "Invalid card_token_id.",
        "3004" => "Invalid parameter site_id.",
        "3005" => "Not valid action, the resource is in a state that does not allow this operation. For more information see the state that has the resource.",
        "3006" => "Invalid parameter cardtoken_id.",
        "3007" => "The parameter client_id can not be null or empty.",
        "3008" => "Not found Cardtoken.",
        "3009" => "unauthorized client_id.",
        "3010" => "Not found card on whitelist.",
        "3011" => "Not found payment_method.",
        "3012" => "Invalid parameter security_code_length.",
        "3013" => "The parameter security_code is a required field can not be null or empty.",
        "3014" => "Invalid parameter payment_method.",
        "3015" => "Invalid parameter card_number_length.",
        "3016" => "Invalid parameter card_number.",
        "3017" => "The parameter card_number_id can not be null or empty.",
        "3018" => "The parameter expiration_month can not be null or empty.",
        "3019" => "The parameter expiration_year can not be null or empty.",
        "3020" => "The parameter cardholder.name can not be null or empty.",
        "3021" => "The parameter cardholder.document.number can not be null or empty.",
        "3022" => "The parameter cardholder.document.type can not be null or empty.",
        "3023" => "The parameter cardholder.document.subtype can not be null or empty.",
        "3024" => "Not valid action - partial refund unsupported for this transaction.",
        "3025" => "Invalid Auth Code.",
        "3026" => "Invalid card_id for this payment_method_id.",
        "3027" => "Invalid payment_type_id.",
        "3028" => "Invalid payment_method_id.",
        "3029" => "Invalid card expiration month.",
        "3030" => "Invalid card expiration year.",
        "4000" => "card atributte can't be null.",
        "4001" => "payment_method_id atributte can't be null.",
        "4002" => "transaction_amount atributte can't be null.",
        "4003" => "transaction_amount atributte must be numeric.",
        "4004" => "installments atributte can't be null.",
        "4005" => "installments atributte must be numeric.",
        "4006" => "payer atributte is malformed.",
        "4007" => "site_id atributte can't be null.",
        "4012" => "payer.id atributte can't be null.",
        "4013" => "payer.type atributte can't be null.",
        "4015" => "payment_method_reference_id atributte can't be null.",
        "4016" => "payment_method_reference_id atributte must be numeric.",
        "4017" => "status atributte can't be null.",
        "4018" => "payment_id atributte can't be null.",
        "4019" => "payment_id atributte must be numeric.",
        "4020" => "notificaction_url atributte must be url valid.",
        "4021" => "notificaction_url atributte must be shorter than 500 character.",
        "4022" => "metadata atributte must be a valid JSON.",
        "4023" => "transaction_amount atributte can't be null.",
        "4024" => "transaction_amount atributte must be numeric.",
        "4025" => "refund_id can't be null.",
        "4026" => "Invalid coupon_amount.",
        "4027" => "campaign_id atributte must be numeric.",
        "4028" => "coupon_amount atributte must be numeric.",
        "4029" => "Invalid payer type.",
        "4037" => "Invalid transaction_amount.",
        "4038" => "application_fee cannot be bigger than transaction_amount.",
        "4039" => "application_fee cannot be a negative value.",
        "4050" => "payer.email must be a valid email.",
        "4051" => "payer.email must be shorter than 254 characters.",

        // Ticket
        "4094" => "Payer firstname required.",
        "4095" => "Payer lastname required.",
        "4096" => "Payer required.",
        "4097" => "Payer identification type required.",
        "4098" => "Payer identification number required.",

        // 'default' errors
        "NOT_IDENTIFIED" => "An error occurred when creating the payment. Please refresh the page and try again.",
        "TOKEN_EMPTY" => "Verify the form data or wait until the validation of the payment data.",
        "INTERNAL_ERROR_MODULE" => "There was an internal error when creating the payment."

    );
}