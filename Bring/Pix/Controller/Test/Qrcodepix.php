<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Controller\Test;

use Exception;
use Magento\Framework\App\Action\Context;

/**
 * Class Qrcodepix
 * @package Bring\Pix\Controller
 */
class Qrcodepix extends \Magento\Framework\App\Action\Action {
    /**
     * @var \Bring\Pix\Helper\Data
     */
    protected $_helperData;

    protected $_pix;

    /**
     * Page constructor.
     * @param Context $context
     */

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Bring\Pix\Helper\Data $helperData,
        \Bring\Pix\Lib\Pix $pix
    )
    {
        $this->_pix = $pix;
        $this->_helperData = $helperData;

        parent::__construct($context);
    }

    /**
     * Controller action
     */
    public function execute()
    {
        $chaveRecebedor = '27417422861';
        $descricao = 'Pedido feito em mag2.isoft - 00000000420000000042000000';
        $valor = 2.00;
        $nomeRecebedor = 'José da Silva';
        $cidadeRecebedor = 'São Paulo';
        $identificador = '0000000042000000000000000000000';

        $pix = $this->_pix->getPix($chaveRecebedor, $descricao, $valor, $nomeRecebedor, $cidadeRecebedor, $identificador);
        $qrcode = $this->_pix->getQrcode($pix);

        echo '<center>Pix (copia e cola)</center><br />';
        echo '<center><pre>' . $pix . '</pre></center><br />';

        echo '<center><h1>QRCode</h1></center>';
        //echo '<center><img src="data:image/png;base64,' . $qrcode . '"></center>';
        echo '<center><img src="' . $qrcode . '"></center>';

        exit;
    }
}