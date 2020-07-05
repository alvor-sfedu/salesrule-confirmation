<?php
namespace Alvor\SalesRuleConfirmation\Model\Confirmation\EmailVariableProvider;

class Decline implements EmailVariableProviderInterface
{
    public function getTemplateVars(string $confirmationPersonCode = null, array $additionalData = null): array
    {
        if (!$confirmationPersonCode) {
            return [];
        }

        switch ($confirmationPersonCode) {
            case \Alvor\SalesRuleConfirmation\Model\Handlers\EcommerceManagerHandler::HANDLER_CODE:
                return ['description' => __('Promo was canceled by E-commerce manager')];
            case \Alvor\SalesRuleConfirmation\Model\Handlers\FinancialManagerHandler::HANDLER_CODE:
                return ['description' => __('Promo was canceled by Financial manager')];
            default:
                return [];
        }
    }
}
