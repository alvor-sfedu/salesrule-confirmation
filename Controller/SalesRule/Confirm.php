<?php
namespace Alvor\SalesRuleConfirmation\Controller\SalesRule;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Alvor\SalesRuleConfirmation\Model\ConfirmationProcessor;

class Confirm extends Action
{
    private $confirmationProcessor;


    public function __construct(
        Context $context,
        ConfirmationProcessor $confirmationProcessor
    )
    {
        $this->confirmationProcessor = $confirmationProcessor;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $message = $this->confirmationProcessor->processConfirmation($this->getRequest());
        $this->_view->getLayout()
                    ->getBlock('confirmation')
                    ->setMessage($message);
        $this->_view->renderLayout();
    }
}
