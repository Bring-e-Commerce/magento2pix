<?php  /** @author contato@bring.com.br */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Bring_Pix', __DIR__);

//ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Bring_Pix', isset($file) ? dirname($file) : __DIR__);
