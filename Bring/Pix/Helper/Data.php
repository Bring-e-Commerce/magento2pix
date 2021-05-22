<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Helper;

use Magento\Framework\View\LayoutFactory;


/**
 * Class Data
 *
 * @package Bring\Pix\Helper
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data
    extends \Magento\Payment\Helper\Data
{

    /**
     *sandbox url
     */
    const SANDBOX_URL = 'https://sandbox.pix.com';
    const PRODUCTION_URL = 'https://pix.com';
    /**
     *api platform openplatform
     */
    const PLATFORM_OPENPLATFORM = 'pixbcb';
    /**
     *api platform stdplatform
     */
    const PLATFORM_STD = 'std';
    /**
     *type
     */
    const TYPE = 'magento';
    //end const platform

    /**
     * Pix Logging instance
     *
     * @var \Bring\Pix\Logger\Logger
     */
    protected $_logger;

    /**
     * @var \Bring\Pix\Helper\Cache
     */
    protected $_mpCache;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Status\Collection
     */
    protected $_statusFactory;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Backend\Block\Store\Switcher
     */
    protected $_switcher;
    protected $_composerInformation;


    /**
     * @var \Magento\Framework\Module\ResourceInterface $moduleResource
     */
    protected $_moduleResource;

    protected $_timezoneInterface;

    /**
     * Data constructor.
     * @param Cache $cpCache
     * @param \Magento\Framework\App\Helper\Context $context
     * @param LayoutFactory $layoutFactory
     * @param \Magento\Payment\Model\Method\Factory $paymentMethodFactory
     * @param \Magento\Store\Model\App\Emulation $appEmulation
     * @param \Magento\Payment\Model\Config $paymentConfig
     * @param \Magento\Framework\App\Config\Initial $initialConfig
     * @param \Bring\Pix\Logger\Logger $logger
     * @param \Magento\Sales\Model\ResourceModel\Status\Collection $statusFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Backend\Block\Store\Switcher $switcher
     * @param \Magento\Framework\Composer\ComposerInformation $composerInformation
     * @param \Magento\Framework\Module\ResourceInterface $moduleResource
     */
    public function __construct(
        Cache $cpCache,
        \Magento\Framework\App\Helper\Context $context,
        LayoutFactory $layoutFactory,
        \Magento\Payment\Model\Method\Factory $paymentMethodFactory,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Payment\Model\Config $paymentConfig,
        \Magento\Framework\App\Config\Initial $initialConfig,
        \Bring\Pix\Logger\Logger $logger,
        \Magento\Sales\Model\ResourceModel\Status\Collection $statusFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Backend\Block\Store\Switcher $switcher,
        \Magento\Framework\Composer\ComposerInformation $composerInformation,
        \Magento\Framework\Module\ResourceInterface $moduleResource,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    )
    {

        parent::__construct($context, $layoutFactory, $paymentMethodFactory, $appEmulation, $paymentConfig, $initialConfig);
        $this->_logger = $logger;
        $this->_mpCache = $cpCache;
        $this->_statusFactory = $statusFactory;
        $this->_orderFactory = $orderFactory;
        $this->_switcher = $switcher;
        $this->_composerInformation = $composerInformation;
        $this->_moduleResource = $moduleResource;
        $this->_timezone = $timezone;
    }

    /**
     * Log custom message using Pix logger instance
     *
     * @param        $message
     * @param string $name
     * @param null $array
     */
    public function log($message, $name = 'pix', $array = null)
    {
        //load admin configuration value, default is true
        $actionLog = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_ADVANCED_LOG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$actionLog) {
            return;
        }
        //if extra data is provided, it's encoded for better visualization
        if (!is_null($array)) {
            $message .= " - " . json_encode($array);
        }

        //set log
        $this->_logger->setName($name);
        $this->_logger->debug($message);
    }

    /**
     * @param null $accessToken
     * @return \Bring\Pix\Lib\Api
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getApiInstance($clientId = null, $clientSecret = null)
    {
        //get client id
        if (is_null($clientId)) {
            $clientId = $this->getPublicKey();
        }

        //get client secret
        if (is_null($clientSecret)) {
            $clientSecret = $this->getSecretKey();
        }
        
        $api = new \Bring\Pix\Lib\Api($clientId, $clientSecret);

        $api->setHelperData($this);

        $api->set_platform(self::PLATFORM_OPENPLATFORM);

        $api->set_type(self::TYPE);

        $api->set_host($this->getHost()); 
        $api->set_clientId($this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_PUBLIC_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $api->set_clientSecret($this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_ACCESS_TOKEN, \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        \Bring\Pix\Lib\RestClient::setHelperData($this);
        \Bring\Pix\Lib\RestClient::setModuleVersion((string)$this->getModuleVersion());
        \Bring\Pix\Lib\RestClient::setUrlStore($this->getUrlStore());
        \Bring\Pix\Lib\RestClient::setEmailAdmin($this->scopeConfig->getValue('trans_email/ident_sales/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        \Bring\Pix\Lib\RestClient::setCountryInitial($this->getCountryInitial());
        \Bring\Pix\Lib\RestClient::setSponsorID($this->scopeConfig->getValue('payment/pix/sponsor_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));

        //$api->set_so((string)$this->_moduleContext->getVersion()); //tracking

        return $api;
    }

    /**
     * @param $accessToken
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isValidAccessToken($accessToken)
    {
        return true;
    }

    /**
     * ClientId and Secret valid?
     *
     * @param $clientId
     * @param $clientSecret
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function isValidClientCredentials($clientId, $clientSecret)
    {
        $cp = $this->getApiInstance($clientId, $clientSecret);
        try {
            $cp->get_access_token();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $scopeCode
     * @return bool|mixed
     */
    public function getAllConfig($scopeCode = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        $ret = array();

        $ret[\Bring\Pix\Helper\ConfigData::PATH_PUBLIC_KEY] = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_PUBLIC_KEY, $scopeCode);
        $ret[\Bring\Pix\Helper\ConfigData::PATH_DEST_NAME] = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_DEST_NAME, $scopeCode);
        $ret[\Bring\Pix\Helper\ConfigData::PATH_DEST_CITY] = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_DEST_CITY, $scopeCode);
        $ret[\Bring\Pix\Helper\ConfigData::PATH_DESCRIPTION] = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_DESCRIPTION, $scopeCode);     

        return $ret;
    }

    /**
     * @param string $scopeCode
     * @return bool|mixed
     */
    public function getAccessToken($scopeCode = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        $accessToken = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_ACCESS_TOKEN, $scopeCode);
        if (empty($accessToken)) {
            return false;
        }

        return $accessToken;
    }

    /**
     * @param string $scopeCode
     * @return bool|mixed
     */
    public function getPublicKey($scopeCode = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        $publicKey = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_PUBLIC_KEY, $scopeCode);
        if (empty($publicKey)) {
            return false;
        }

        return $publicKey;
    }

    /**
     * @param string $scopeCode
     * @return bool|mixed
     */
    public function getSecretKey($scopeCode = \Magento\Store\Model\ScopeInterface::SCOPE_STORE)
    {
        $accessToken = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_ACCESS_TOKEN, $scopeCode);
        if (empty($accessToken)) {
            return false;
        }

        return $accessToken;
    }

    public function getHost($scopeCode = \Magento\Store\Model\ScopeInterface::SCOPE_STORE) {
        $sandbox = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_SANDBOX, $scopeCode);

        if (!$sandbox) {
            return self::PRODUCTION_URL;
        }
        else {
            return self::SANDBOX_URL;
        }
    }
	
	public function getInstructions($scopeCode = \Magento\Store\Model\ScopeInterface::SCOPE_STORE) {
        $instrucoes = $this->scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_CUSTOM_INSTRUCTIONS, $scopeCode);
        if (empty($instrucoes)) {
            return false;
        }

        return $instrucoes;
    }

    /**
     * Calculate and set order Pix specific subtotals based on data values
     *
     * @param $data
     * @param $order
     */
    public function setOrderSubtotals($data, $order)
    {
        $couponAmount = $this->_getMultiCardValue($data, 'coupon_amount');
        $transactionAmount = $this->_getMultiCardValue($data, 'transaction_amount');

        if (isset($data['total_paid_amount'])) {
            $paidAmount = $this->_getMultiCardValue($data, 'total_paid_amount');
        } else {
            $paidAmount = $data['transaction_details']['total_paid_amount'];
        }

        $shippingCost = $this->_getMultiCardValue($data, 'shipping_cost');
        $originalAmount = $transactionAmount + $shippingCost;

        if ($couponAmount
            && $this->scopeConfig->isSetFlag(\Bring\Pix\Helper\ConfigData::PATH_ADVANCED_CONSIDER_DISCOUNT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $order->setDiscountCouponAmount($couponAmount * -1);
            $order->setBaseDiscountCouponAmount($couponAmount * -1);
            $financingCost = $paidAmount + $couponAmount - $originalAmount;
        } else {
            //if a discount was applied and should not be considered
            $paidAmount += $couponAmount;
            $financingCost = $paidAmount - $originalAmount;
        }

        if ($shippingCost > 0) {
            $order->setBaseShippingAmount($shippingCost);
            $order->setShippingAmount($shippingCost);
        }

        $order->setTotalPaid($paidAmount);
        $order->save();
    }

    /**
     * Modify payment array adding specific fields
     *
     * @param $payment
     *
     * @return mixed
     * @refactor
     */
    public function setPayerInfo(&$payment)
    {
        $this->log("setPayerInfo", 'pix-custom.log', $payment);

        if ($payment['payment_method_id']) {
            $payment["payment_method"] = $payment['payment_method_id'];
        }

        if ($payment['installments']) {
            $payment["installments"] = $payment['installments'];
        }
        if ($payment['id']) {
            $payment["payment_id_detail"] = $payment['id'];
        }
        if (isset($payment['trunc_card'])) {
            $payment["trunc_card"] = $payment['trunc_card'];
        } else if (isset($payment['card']) && isset($payment['card']['last_four_digits'])) {
            $payment["trunc_card"] = "xxxx xxxx xxxx " . $payment['card']["last_four_digits"];
        }

        if (isset($payment['card']["cardholder"]["name"])) {
            $payment["cardholder_name"] = $payment['card']["cardholder"]["name"];
        }

        if (isset($payment['payer']['first_name'])) {
            $payment['payer_first_name'] = $payment['payer']['first_name'];
        }

        if (isset($payment['payer']['last_name'])) {
            $payment['payer_last_name'] = $payment['payer']['last_name'];
        }

        if (isset($payment['payer']['email'])) {
            $payment['payer_email'] = $payment['payer']['email'];
        }

        return $payment;
    }

    /**
     * Return sum of fields separated with |
     *
     * @param $fullValue
     *
     * @return int
     */
    protected function _getMultiCardValue($data, $field)
    {
        $finalValue = 0;
        if (!isset($data[$field])) {
            return $finalValue;
        }
        $amountValues = explode('|', $data[$field]);
        $statusValues = explode('|', $data['status']);
        foreach ($amountValues as $key => $value) {
            $value = (float)str_replace(' ', '', $value);
            if (str_replace(' ', '', $statusValues[$key]) == 'approved') {
                $finalValue = $finalValue + $value;
            }
        }

        return $finalValue;
    }

    /**
     * return the list of payment methods or null
     *
     * @param mixed|null $accessToken
     *
     * @return mixed
     */
    public function getPixPaymentMethods($accessToken)
    {
        try {
            $cp = $this->getApiInstance($accessToken);

            $response = $cp->get("/v1/payment_methods");
            if ($response['status'] == 401 || $response['status'] == 400) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        return $response['response'];
    }

    public function getCountryInitial()
    {
        try {

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $store = $objectManager->get('Magento\Framework\Locale\Resolver');
            $locale = $store->getLocale();
            $locale = explode("_", $locale);
            $locale = $locale[1];

            return $locale;

        } catch (\Exception $e) {
            return "US";
        }
    }

    public function getUrlStore()
    {

        try {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
            $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');
            $currentStore = $storeManager->getStore();
            $baseUrl = $currentStore->getBaseUrl();
            return $baseUrl;

        } catch (\Exception $e) {
            return "";
        }

    }

    public function getModuleVersion()
    {
        $version = $this->_moduleResource->getDbVersion('Bring_Pix');
        return $version;
    }

    /**
     * Summary: Get client id from access token.
     * Description: Get client id from access token.
     *
     * @param String $at
     *
     * @return String client id.
     */
    public static function getClientIdFromAccessToken($at)
    {
        $t = explode('-', $at);
        if (count($t) > 0) {
            return $t[1];
        }

        return '';
    }

    /**
     * @param $order
     * @return array
     */
    public function getAnalyticsData($order)
    {
        $analyticsData = [];

        if (!empty($order->getPayment())) {
            $additionalInfo = $order->getPayment()->getData('additional_information');

            if ($order->getPayment()->getData('method')) {
                /*$accessToken = $this->scopeConfig->getValue(
                    \Bring\Pix\Helper\ConfigData::PATH_ACCESS_TOKEN,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );*/

                /*$publicKey = $this->scopeConfig->getValue(
                    \Bring\Pix\Helper\ConfigData::PATH_PUBLIC_KEY,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                );*/

                //$methodCode = $order->getPayment()->getData('method');
                $paymenType = !empty($additionalInfo['payment_method_id']) ? $additionalInfo['payment_method_id'] : '';
                $checkoutType= !empty($additionalInfo['method']) ? $additionalInfo['method'] : '';

                $analyticsData = [
                    'payment_id' => $this->getPaymentId($additionalInfo),
                    'payment_type' => $paymenType,
                    'checkout_type' => $checkoutType
                    //'analytics_key' => $this->getClientIdFromAccessToken($accessToken)
                ];

                /*if ($methodCode == \Bring\Pix\Model\Custom\Payment::CODE) {
                    $analyticsData['public_key'] = $publicKey;
                }*/
            }
        }

        return $analyticsData;
    }

    /**
     * @param $additionalInfo
     * @return string|null
     */
    public function getPaymentId($additionalInfo)
    {
        //$this->log('additional info', 'pix', $additionalInfo);

        /*if (isset($additionalInfo['payment_id_detail']) && !empty($additionalInfo['payment_id_detail'])) {
            return $additionalInfo['payment_id_detail'];
        }*/

        if (isset($additionalInfo['paymentResponse'])) {
            $additionalInfo = $additionalInfo['paymentResponse'];
        }

        if (isset($additionalInfo['payment_data']) && isset($additionalInfo['payment_data']['id'])) {
            return $additionalInfo['payment_data']['id'];
        }

        return null;
    }

    public function getTimezone() 
    {
        return ($this->_timezoneInterface);
    }

    /**
     * @param string $dateTime
     * @return string $dateTime as time zone
    */
    public function getTimeAccordingToTimeZone() 
    {
        // for get current time according to time zone
        $today = $this->_timezoneInterface->date()->format('y-m-dTH:m:iZ'); 
 
        return $today;
    }

    /**
     * Return array with preference data by default to refund method
     *
     * @param int $idPayment
     * @param $order
     *
     * @return array
     */
    public function makeDefaultPreferenceRefundV1($idPayment, $order)
    {
        //$this->log("makeDefaultPreferenceRefundV1 idPayment", 'pix-helper.log', $idPayment);

        /* INIT PREFERENCE */
        $preference = [];

        $preference['request']['id'] = time();       
        $preference['request']['time'] = date("c"); 

        $preference['merchant_order']['id'] = $order->getIncrementId();
        $preference['merchant_order']['description'] = __("Refund Order # %1 in store %2", $order->getIncrementId(), $this->getStoreManager()->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK));
        
        $preference['payment_data']['id'] = $idPayment;       

        //$this->log("makeDefaultPreferenceRefundV1 preference", 'pix-helper.log', $preference);

        return($preference);   
    }

    public function getStoreManager() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
        $storeManager = $objectManager->get('Magento\Store\Model\StoreManagerInterface');

        return($storeManager);
    }

}