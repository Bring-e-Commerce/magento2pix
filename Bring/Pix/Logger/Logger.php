<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Logger;
/**
 * Pix custom logger allows name changing to differentiate log call origin
 * Class Logger
 *
 * @package Bring\Pix\Logger
 */
class Logger extends \Monolog\Logger
{
    /**
     * Set logger name
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}