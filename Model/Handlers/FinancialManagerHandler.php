<?php
namespace Alvor\SalesRuleConfirmation\Model\Handlers;


class FinancialManagerHandler extends AbstractHandler
{
    const HANDLER_CODE = 'fin_manager';

    public function getConfirmationPersonDescription(): string
    {
        return __('Financial Manager');
    }

    public function getRecipients(): array
    {
        return $this->config->getFinancialManagersEmails();
    }

    public static function getHandlerCode(): string
    {
        return self::HANDLER_CODE;
    }
}
