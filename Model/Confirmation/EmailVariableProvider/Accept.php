<?php


namespace Alvor\SalesRuleConfirmation\Model\Confirmation\EmailVariableProvider;


class Accept implements EmailVariableProviderInterface
{
    public function getTemplateVars(string $confirmationPersonCode = null, array $additionalData = null): array
    {
        return ['description' => __('Rule was Accepted')];
    }
}
