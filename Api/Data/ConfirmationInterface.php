<?php
namespace Alvor\SalesRuleConfirmation\Api\Data;


interface ConfirmationInterface
{
    const
        ID                = 'entity_id',
        RULE_ID           = 'rule_id',
        CONFIRMATION_TYPE = 'confirmation_type',
        CONFIRMATION_KEY  = 'confirmation_key',
        IS_CONFIRMED      = 'is_confirmed'
    ;

    public function getConfirmationId(): string;

    public function setConfirmationId(string $ruleId);

    public function getRuleId(): string;

    public function setRuleId(string $ruleId);

    public function getConfirmationType(): string;

    public function setConfirmationType(string $confirmationType);

    public function setConfirmationKey(string $confirmationKey);

    public function getConfirmationKey(): string;

    public function setIsConfirmed(bool $isConfirmed);

    public function getIsConfirmed(): bool;
}
