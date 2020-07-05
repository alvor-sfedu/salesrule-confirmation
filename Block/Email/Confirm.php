<?php

namespace Alvor\SalesRuleConfirmation\Block\Email;

use Magento\Framework\View\Element\Template;
use Alvor\SalesRuleConfirmation\ViewModel\Email\Confirmation as ConfirmationViewModel;
use Alvor\CartRule\Model\Rule\GiftRuleFactory;


class Confirm extends Template
{
    private $viewModel;


    public function __construct(
        Template\Context $context,
        ConfirmationViewModel $viewModel,
        array $data = []
    )
    {
        $this->viewModel = $viewModel;
        $this->viewModel->init($data['rule']);
        parent::__construct($context, $data);
    }

    public function getViewModel(): ConfirmationViewModel
    {
        return $this->viewModel;
    }
}
