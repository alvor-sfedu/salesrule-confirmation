<?php
namespace Alvor\SalesRuleConfirmation\Component\Form\Field;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Form\Field;
use Alvor\SalesRuleConfirmation\Helper\Data;

class SkipActivation extends Field
{
    /** @var Data */
    private $config;

    public function __construct(
        Data $config,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepare()
    {
        if (!$this->config->canSkipActivation()) {
            $config = $this->getData('config');
            $config['visible'] = false;
            $this->setData('config', $config);
        }

        parent::prepare();
    }
}
