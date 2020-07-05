<?php
namespace Alvor\SalesRuleConfirmation\Model\Confirmation;


class TypeManager
{
    const
        ECOMM_MANAGER = 'ecommerce_manager',
        FINANCIAL_MANAGER = 'financial_manager'
    ;

    public function getDescriptionByCode(string $code)
    {
        $descriptions = $this->getDescriptions();
        return $descriptions[$code] ?? '';
    }

    private function getDescriptions(): array
    {
        return [
            self::ECOMM_MANAGER     => __('E-commerce Manager'),
            self::FINANCIAL_MANAGER => __('Financial Manager')
        ];
    }
}
