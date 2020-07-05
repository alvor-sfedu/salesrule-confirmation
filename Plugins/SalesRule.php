<?php
namespace Alvor\SalesRuleConfirmation\Plugins;

use Magento\SalesRule\Model\Rule;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\State;
use Alvor\SalesRuleConfirmation\Helper\Data;
use Alvor\SalesRuleConfirmation\Model\ConfirmationProcessor;

class SalesRule
{
    const ACCEPTABLE_MODULE_NAME = 'sales_rule';

    private $confirmationProcessor;
    private $request;
    private $messageManager;
    private $state;

    /** @var Data */
    private $config;

    public function __construct(
        Data $config,
        ConfirmationProcessor $confirmationProcessor,
        RequestInterface $request,
        ManagerInterface $messageManager,
        State $state
    ) {
        $this->confirmationProcessor = $confirmationProcessor;
        $this->request = $request;
        $this->messageManager = $messageManager;
        $this->state = $state;
        $this->config = $config;
    }

    public function aroundSave(Rule $subject, \Closure $proceed)
    {
        if (!$this->checkModule()) {
            return $proceed($subject);
        }

        if ($this->request->getParam('skip_activation') && $this->config->canSkipActivation()) {
            return $proceed($subject);
        }

        $this->confirmationProcessor->removeAllConfirmationForRule($subject);
        $subject->setIsActive(0);
        $proceed($subject);
        $this->confirmationProcessor->processSalesRule($subject);
        $this->messageManager->addNoticeMessage(__('Activation process started. Rule will be active after confirmation'));

        return $subject;
    }

    protected function checkModule(): bool
    {
        return $this->request->getModuleName() == self::ACCEPTABLE_MODULE_NAME;
    }
}
