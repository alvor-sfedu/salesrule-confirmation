<?php
namespace Alvor\SalesRuleConfirmation\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Phrase;

class Data extends AbstractHelper
{
    const
        XML_PATH_TO_STORE_NAME = 'general/store_information/name',
        XML_PATH_TO_SUBJECT_PROJECT_ALIAS = 'confirmation/general/email_alias'
    ;

    /** @var AuthorizationInterface */
    private $authorization;

    public function __construct(Context $context, AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
        parent::__construct($context);
    }

    /**
     * Get Store Name
     *
     * @return mixed
     */
    public function getStoreName()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TO_STORE_NAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Subject Alias
     *
     * @return string|null
     */
    public function getSubjectAlias()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_TO_SUBJECT_PROJECT_ALIAS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function prepareLetterSubject(Phrase $mainPart, string $ruleName): string
    {
        $subjectAlias = $this->getSubjectAlias();

        return "{$subjectAlias} \\\\ {$mainPart} «{$ruleName}» ";
    }

    public function canSkipActivation(): bool
    {
        return $this->authorization->isAllowed('Magento_SalesRule::skip_activation');
    }
}
