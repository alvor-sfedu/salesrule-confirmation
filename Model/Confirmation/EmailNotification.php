<?php
namespace Alvor\SalesRuleConfirmation\Model\Confirmation;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Phrase;
use Magento\SalesRule\Model\Rule;
use Magento\Framework\Mail\Template\TransportBuilder;
use Alvor\SalesRuleConfirmation\Model\Config;
use Alvor\SalesRuleConfirmation\Helper\Data as DataHelper;
use Alvor\SalesRuleConfirmation\Model\Confirmation\EmailVariableProvider\EmailVariableProviderInterface;

class EmailNotification
{
    private $scopeConfig;
    private $recipientsConfigPath;
    private $emailTitle;
    private $emailTemplateId;
    private $emailSubjectMainPart;

    private $transportBuilder;
    private $config;
    private $dataHelper;

    private $additionTemplateVarsProvider;

    public function __construct(
        string $emailTitle,
        string $recipientsConfigPath,
        string $emailTemplateId,
        string $emailSubjectMainPart,
        Config $config,
        DataHelper $dataHelper,
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        EmailVariableProviderInterface $additionTemplateVarsProvider = null
    )
    {
        $this->emailSubjectMainPart = $emailSubjectMainPart;
        $this->recipientsConfigPath = $recipientsConfigPath;
        $this->scopeConfig = $scopeConfig;
        $this->emailTitle = $emailTitle;
        $this->transportBuilder = $transportBuilder;
        $this->emailTemplateId = $emailTemplateId;
        $this->config = $config;
        $this->dataHelper = $dataHelper;
        $this->additionTemplateVarsProvider = $additionTemplateVarsProvider;
    }

    public function sendNotification(Rule $rule, string $handlerCode)
    {
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($this->emailTemplateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                ]
            )
            ->setTemplateVars($this->getEmailVars($rule, $handlerCode))
            ->setFrom([
                'name' => $this->config->getSenderName(),
                'email' => $this->config->getSenderEmail()
            ])
            ->addTo($this->getRecipientsEmail())
            ->getTransport();
        $transport->sendMessage();
    }

    /**
     * @return array
     */
    private function getRecipientsEmail(): array
    {
        return explode(',', $this->scopeConfig->getValue($this->recipientsConfigPath));
    }

    private function getEmailVars(Rule $rule, string $handlerCode): array
    {
        return array_merge([
            'rule'                => $rule,
            'rule_name'           => $rule->getName(),
            'can_accept'          => false,
            'description'         => '',
            'title'               => __($this->emailTitle),
            'subject'             => $this->dataHelper->prepareLetterSubject(__($this->emailSubjectMainPart), $rule->getName()),
            'subject_brand_alias' => $this->dataHelper->getSubjectAlias()
        ], $this->additionTemplateVarsProvider !== null ?
            $this->additionTemplateVarsProvider->getTemplateVars($handlerCode) : []);
    }

    protected function getCancelPhrase(string $whoCancel): Phrase
    {
        switch ($whoCancel) {
            case \Alvor\SalesRuleConfirmation\Model\Handlers\EcommerceManagerHandler::HANDLER_CODE:
                return __('Promo was canceled by E-commerce manager');
            case \Alvor\SalesRuleConfirmation\Model\Handlers\FinancialManagerHandler::HANDLER_CODE:
                return __('Promo was canceled by Financial manager');
            default:
                return __('');
        }
    }
}
