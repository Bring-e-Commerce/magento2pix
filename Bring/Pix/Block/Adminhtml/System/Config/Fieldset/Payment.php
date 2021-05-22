<?php  /** @author contato@bring.com.br */

namespace Bring\Pix\Block\Adminhtml\System\Config\Fieldset;
/**
 * Config form FieldSet renderer
 */
class Payment
    extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     *
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $configResource;

    /**
     *
     * @var \Magento\Backend\Block\Store\Switcher
     */
    protected $switcher;

    /**
     * @param \Magento\Backend\Block\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\View\Helper\Js $jsHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Config\Model\ResourceModel\Config $configResource
     * @param \Magento\Backend\Block\Store\Switcher $switcher
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Config\Model\ResourceModel\Config $configResource,
        \Magento\Backend\Block\Store\Switcher $switcher,
        array $data = []
    )
    {
        parent::__construct($context, $authSession, $jsHelper, $data);
        $this->scopeConfig = $scopeConfig;
        $this->configResource = $configResource;
        $this->switcher = $switcher;
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     *
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return parent::render($element);
    }
}
