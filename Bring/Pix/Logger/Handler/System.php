<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Logger\Handler;

use Monolog\Logger;

/**
 * Pix logger handler
 */
class System
    extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::DEBUG;

    /**
     * File name
     *
     * @var string
     */
    protected $fileName = '/var/log/pix.log';

}