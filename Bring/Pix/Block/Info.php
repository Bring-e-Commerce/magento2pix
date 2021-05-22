<?php  /** @author contato@bring.com.br */
namespace Bring\Pix\Block;

class Info extends \Magento\Payment\Block\Info
{
	protected $_checkoutSession;
    protected $_orderFactory;
    protected $_scopeConfig;

    protected $_helper;
    protected $_pix;

    protected $_template = 'Bring_Pix::info.phtml';

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Bring\Pix\Helper\Data $helper,
        \Bring\Pix\Lib\Pix $pix,       
        array $data = []
    ) {
		parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_orderFactory = $orderFactory;   
        $this->_helper = $helper;  
        $this->_pix = $pix;  
    }


    // Use this method to get ID    
    public function getRealOrderId()
    {
        if ($this->_checkoutSession->getLastRealOrderId()) {
			$lastorderId = $this->_checkoutSession->getLastRealOrderId();
		}
        else if ($order = $this->getInfo()->getOrder()) {
			$lastorderId = $order->getIncrementId();
		}
        return $lastorderId;
    }

	public function getOrder()
    {
        if ($this->_checkoutSession->getLastRealOrderId()) {
			//$order = $this->_orderFactory->create()->loadByIncrementId($orderIncrementId);
            return $this->_checkoutSession->getLastRealOrder();
        }
        else if ($order = $this->getInfo()->getOrder()) {
            return $order;
        }

        return false;
    }
	

    /**
     * @return \Magento\Sales\Model\Order\Payment
     */
    public function getPayment()
    {
        $order = $this->getOrder();
        $payment = $order->getPayment();

        return $payment;
	}
		
    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPaymentMethod()
    {
		$payment = $this->getPayment();

		$payment_method = $payment->getMethod();

		if (!$payment_method) {
			$payment_method = $payment->getMethodInstance()->getCode();
		}

		return $payment_method;
	}
	
	public function getAdditionalInformation() {
		$payment = $this->getPayment();
		if ($payment) {
			return $payment->getAdditionalInformation();
		}
		else return(null);
	}
	
	public function getTransactionId() {
		$payment = $this->getPayment();
		
		$ret = $payment->getTransactionId();
		
		if (!$ret) {
			$ret = $payment->getAdditionalInformation('transaction_id');
		}
		
		if (!$ret) {
			$ret = $this->getInfo()->getAdditionalInformation('transaction_id');
		}
		
		return($ret);
	}
	
	public function getStatusMessage($payment) {
		$additionInfo = $payment->getAdditionalInformation();
		return($additionInfo['status_message']);		
	}
	
	public function getPaymentInfo() {
        $order = $this->getOrder();
        $payment = $order->getPayment();
		$instrucoes = $this->_helper->getInstructions();

        $configValues = $this->_helper->getAllConfig();

        $chaveRecebedor = $configValues[\Bring\Pix\Helper\ConfigData::PATH_PUBLIC_KEY];
        $nomeRecebedor = $configValues[\Bring\Pix\Helper\ConfigData::PATH_DEST_NAME];
        $cidadeRecebedor = $configValues[\Bring\Pix\Helper\ConfigData::PATH_DEST_CITY];

        $descricao = $configValues[\Bring\Pix\Helper\ConfigData::PATH_DESCRIPTION];
        $valor = $order->getGrandTotal();
        $identificador = $this->getRealOrderId();
        $descricao = $configValues[\Bring\Pix\Helper\ConfigData::PATH_DESCRIPTION] . ' - ' . $identificador;
        
        $pix = $this->_pix->getPix($chaveRecebedor, $descricao, $valor, $nomeRecebedor, $cidadeRecebedor, $identificador);
        $qrcode = $this->_pix->getQrcode($pix);

        $ret['pix'] = $pix;
        $ret['qrcode'] = $qrcode;
        $ret['instrucoes'] = $instrucoes;
		$ret['additional_information'] = $payment->getAdditionalInformation();

        return $ret;
    }	
}