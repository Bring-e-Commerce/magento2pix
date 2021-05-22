<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Controller\Checkout;

use Exception;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use Bring\Pix\Helper\ConfigData;
use Bring\Pix\Helper\Data;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Page
 * @package Bring\Pix\Controller\Checkout
 */
class Page
    extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $_orderSender;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Bring\Pix\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_catalogSession;

    /**
     * @var
     */
    protected $_configData;

    /**
     * Page constructor.
     * @param Context $context
     * @param Session $checkoutSession
     * @param OrderFactory $orderFactory
     * @param OrderSender $orderSender
     * @param LoggerInterface $logger
     * @param Data $helperData
     * @param ScopeConfigInterface $scopeConfig
     * @param CatalogSession $catalogSession
     */

    public function __construct(
        Context $context,
        Session $checkoutSession,
        OrderFactory $orderFactory,
        OrderSender $orderSender,
        LoggerInterface $logger,
        Data $helperData,
        ScopeConfigInterface $scopeConfig,
        CatalogSession $catalogSession
    )
    {
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;
        $this->_orderSender = $orderSender;
        $this->_logger = $logger;
        $this->_helperData = $helperData;
        $this->_scopeConfig = $scopeConfig;
        $this->_catalogSession = $catalogSession;

        parent::__construct($context);
    }

    /**
     * Controller action
     */
    public function execute()
    {
        //$order = $this->_getOrder();
        //$payment = $order->getPayment();

        //$this->approvedValidation($paymentResponse);
        $this->_redirect('checkout/onepage/success');
    }

    /**
     * @return mixed
     */
    protected function _getOrder()
    {
        $orderIncrementId = $this->_checkoutSession->getLastRealOrderId();
        $order = $this->_orderFactory->create()->loadByIncrementId($orderIncrementId);

        return $order;
    }

    /**
     * Return handle name, depending on payment method used in the order placed
     *
     * @return string
     */
    public function getCheckoutHandle()
    {
        $handle = '';
        $order = $this->_getOrder();
        if (!empty($order->getId())) {
            $handle = $order->getPayment()->getMethod();
        }
        $handle .= '_success';

        return $handle;
    }

    /**
     * Dispatch checkout_onepage_controller_success_action
     */
    public function dispatchSuccessActionObserver()
    {
        $this->_eventManager->dispatch(
            'checkout_onepage_controller_success_action',
            [
                'order_ids' => [$this->_getOrder()->getId()],
                'order' => $this->_getOrder()
            ]
        );
    }
}