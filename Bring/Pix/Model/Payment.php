<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Model;

/**
 * Class Payment
 *
 * @package Bring\Pix\Model
 */
//class Payment extends \Bring\Pix\Model\Core
class Payment extends \Magento\Payment\Model\Method\Cc
{
    /**
     * Define payment method code
     */
    const CODE = 'bring_pix';

    protected $_infoBlockType = \Bring\Pix\Block\Info::class;

    protected $_code = self::CODE;
    protected $_isGateway                   = true;
    protected $_canCapture                  = false;
    protected $_canCapturePartial           = false;
    protected $_canVoid		                = true;
    protected $_canCancel                   = true;
    protected $_canRefund                   = true;
    protected $_canRefundInvoicePartial     = false;

    protected $_countryFactory;
    protected $_minAmount = null;
    protected $_maxAmount = null;
    protected $_supportedCurrencyCodes = array('BRL', 'USD');
    //protected $_supportedCurrencyCodes = array('BRL'); //TODO

    /**
     * Backend Auth Session
     *
     * @var Magento\Backend\Model\Auth\Session $adminSession
     */ 
    protected $adminSession;

    protected $_helperData;

    protected $_debugReplacePrivateDataKeys = [];

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Payment\Helper\Data $paymentData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Method\Logger $logger,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Backend\Model\Auth\Session $adminSession,
        \Bring\Pix\Helper\Data $helperData,
        array $data = array()
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $paymentData,
            $scopeConfig,
            $logger,
            $moduleList,
            $localeDate,
            null,
            null,
            $data
        );

        $this->_countryFactory = $countryFactory;

        // $this->_minAmount = 1;
        // $this->_maxAmount = 999999999; 
        $this->adminSession = $adminSession;   
        $this->_helperData = $helperData;
    }

    /**
     * @param \Magento\Framework\DataObject $data
     * @return $this|\Bring\Pix\Model\Custom\Payment
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        $this->_helperData->log("Payment::assigndata - Init");

        /*if (!($data instanceof \Magento\Framework\DataObject)) {
            $data = new \Magento\Framework\DataObject($data);
        }*/

        /*
        $infoForm = $data->getData();

        if (isset($infoForm['additional_data']) && !empty($infoForm['additional_data'])) {
            $additionalData = $infoForm['additional_data'];

            $info = $this->getInfoInstance();
            $info->setAdditionalInformation('method', $infoForm['method']);
            $info->setAdditionalInformation('payment_method', $additionalData['payment_method_ticket']);
            $info->setAdditionalInformation('payment_method_id', $additionalData['payment_method_ticket']);

            if (!empty($additionalData['coupon_code'])) {
                $info->setAdditionalInformation('coupon_code', $additionalData['coupon_code']);
            }
        }
        */
        
        $this->_helperData->log("Payment::assigndata - End");

        return $this;
    }

    /**
     * @param string $paymentAction
     * @param object $stateObject
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function initialize($paymentAction, $stateObject)
    {      
        return $this;
    }

    /**
     * Return tickets options availables
     *
     * @return array
     */
    public function getTicketsOptions()
    {

        //$excludePaymentMethods = $this->_scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_CUSTOM_EXCLUDE_PAYMENT_METHODS, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        //$listExclude = explode(",", $excludePaymentMethods);
        //$payment_methods = $this->_coreModel->getPaymentMethods();
        //$tickets = array();

        $pm['id'] = 1;
        $pm['payment_type_id'] = 'pix';

        $tickets[] = $pm;

        return $tickets;
    }

    /**
     * is payment method available?
     *
     * @param \Magento\Quote\Api\Data\CartInterface|null $quote
     *
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {

        $isActive = $this->_scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_PIX_ACTIVE, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (empty($isActive)) {
            return false;
        }

        return $isActive;
    }

    /**
     * Validate payment method information object
     *
     * @return Payment Model
     */
    public function validate()
    {
        //parent::validate();
        //$missingInfo = $this->getInfoInstance();

        return $this;
    }

    /**
     * @return string
     */
    /*
    public function getOrderPlaceRedirectUrl()
    {
        //$successUrl = $successPage ? 'bringpix/checkout/page' : 'checkout/onepage/success';
        return $this->_urlBuilder->getUrl($successUrl, ['_secure' => true]);
    }
    */
}