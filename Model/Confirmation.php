<?php

namespace Alvor\SalesRuleConfirmation\Model;
use Magento\Framework\Model\AbstractModel;
use Alvor\SalesRuleConfirmation\Api\Data\ConfirmationInterface;
use Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation as ConfirmationResource;

class Confirmation extends AbstractModel implements ConfirmationInterface
{
    const
        CONFIRMED = 1,
        NOT_CONFIRMED = 0
    ;

    public function _construct()
    {
        $this->_init(ConfirmationResource::class);
    }

    public function setConfirmationId(string $ruleId)
    {
        $this->setData(self::ID, $ruleId);
    }

    public function getConfirmationId(): string
    {
        return (string) $this->getData(self::ID);
    }

    public function setRuleId(string $ruleId)
    {
        $this->setData(self::RULE_ID, $ruleId);
    }

    public function getRuleId(): string
    {
        return $this->getData(self::RULE_ID);
    }

    public function setConfirmationKey(string $confirmationKey)
    {
        $this->setData(self::CONFIRMATION_KEY, $confirmationKey);
    }

    public function getConfirmationKey(): string
    {
        return (string)$this->getData(self::CONFIRMATION_KEY);
    }

    public function setConfirmationType(string $confirmationType)
    {
        $this->setData(self::CONFIRMATION_TYPE, $confirmationType);
    }

    public function getConfirmationType(): string
    {
        return $this->getData(self::CONFIRMATION_TYPE);
    }

    public function setIsConfirmed(bool $isConfirmed)
    {
        $this->setData(self::IS_CONFIRMED, $isConfirmed);
    }

    public function getIsConfirmed(): bool
    {
        return $this->getData(self::IS_CONFIRMED);
    }

    public function confirm()
    {
        $this->setIsConfirmed(self::CONFIRMED);
    }
}
