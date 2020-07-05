<?php
namespace Alvor\SalesRuleConfirmation\Model\Confirmation\EmailVariableProvider;

interface EmailVariableProviderInterface
{
    public function getTemplateVars(string $confirmationPersonCode = null, array $additionalData = null): array;
}
