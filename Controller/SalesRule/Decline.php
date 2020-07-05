<?php
namespace Alvor\SalesRuleConfirmation\Controller\SalesRule;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Alvor\SalesRuleConfirmation\Model\DeclineProcessor;

class Decline extends Action
{
    private $declineProcessor;


    public function __construct(
        Context $context,
        DeclineProcessor $confirmationProcessor
    )
    {
        $this->declineProcessor = $confirmationProcessor;
        parent::__construct($context);
    }

    public function execute()
    {
        $this->_view->loadLayout();
        $message = $this->declineProcessor->processConfirmation($this->getRequest());
        $this->_view->getLayout()
                    ->getBlock('confirmation')
                    ->setMessage($message);
        $this->_view->renderLayout();
    }
}
