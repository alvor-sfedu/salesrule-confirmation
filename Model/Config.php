<?php
namespace Alvor\SalesRuleConfirmation\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getEcommerceManagersEmails(): array
    {
        return explode(',', $this->scopeConfig->getValue('confirmation/confirmation/ecomm_emails'));
    }

    public function getFinancialManagersEmails(): array
    {
        return explode(',', $this->scopeConfig->getValue('confirmation/confirmation/fin_emails'));
    }

    public function getBrandDirectorsEmails(): array
    {
        return explode(',', $this->scopeConfig->getValue('confirmation/notification/marketing_emails'));
    }

    public function getSenderEmail(): string
    {
        return $this->scopeConfig->getValue('trans_email/ident_sales/email');
    }

    public function getSenderName(): string
    {
        return $this->scopeConfig->getValue('trans_email/ident_sales/name');
    }
}
