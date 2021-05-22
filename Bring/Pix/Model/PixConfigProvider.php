<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Payment\Helper\Data as PaymentHelper;

/**
 * Return configs to Standard Method
 *
 * Class StandardConfigProvider
 *
 * @package Bring\Pix\Model
 */
class PixConfigProvider
    implements ConfigProviderInterface
{
    /**
     * @var \Magento\Payment\Model\MethodInterface
     */
    protected $methodInstance;

    /**
     * @var string
     */
    protected $methodCode = Payment::CODE;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;
    protected $_coreHelper;
    protected $_productMetaData;

    /**
     * @param PaymentHelper $paymentHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        PaymentHelper $paymentHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Bring\Pix\Helper\Data $coreHelper,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    )
    {
        $this->_request = $context->getRequest();
        $this->methodInstance = $paymentHelper->getMethodInstance($this->methodCode);
        $this->_checkoutSession = $checkoutSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_urlBuilder = $context->getUrl();
        $this->_storeManager = $storeManager;
        $this->_assetRepo = $assetRepo;
        $this->_coreHelper = $coreHelper;
        $this->_productMetaData = $productMetadata;
    }

    /**
     * @return array
     */
    public function getConfig()
    {

        if (!$this->methodInstance->isAvailable()) {

            return [];
        }

        $paymentMethods = $this->methodInstance->getTicketsOptions();

        $data = [
            'payment' => [
                $this->methodCode => [
                    'country' => strtoupper($this->_scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_SITE_ID, \Magento\Store\Model\ScopeInterface::SCOPE_STORE)),
                    'bannerUrl' => $this->_scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_CUSTOM_TICKET_BANNER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                    'logEnabled' => $this->_scopeConfig->getValue(\Bring\Pix\Helper\ConfigData::PATH_ADVANCED_LOG, \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                    'options' => $paymentMethods,
                    'grand_total' => $this->_checkoutSession->getQuote()->getGrandTotal(),
                    'success_url' => $this->methodInstance->getConfigData('order_place_redirect_url'),
                    'route' => $this->_request->getRouteName(),
                    'base_url' => $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK),
                    'loading_gif' => $this->_assetRepo->getUrl('Bring_Pix::images/loading.gif'),
                    'logoUrl' => $this->_assetRepo->getUrl("Bring_Pix::images/pix_logo.png"),
                    'platform_version' => $this->_productMetaData->getVersion(),
                    'module_version' => $this->_coreHelper->getModuleVersion()
                ]
            ]
        ];

        return $data;
    }
}