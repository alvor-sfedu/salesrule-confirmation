<?php
namespace Alvor\SalesRuleConfirmation\Model\Handlers;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\SalesRule\Model\Rule as SalesRule;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Url;
use Magento\Framework\Message\ManagerInterface;
use Alvor\SalesRuleConfirmation\Api\ConfirmationRepositoryInterface;
use Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation\CollectionFactory;
use Alvor\SalesRuleConfirmation\Model\Handlers\Exception\SalesRuleMissed;
use Alvor\SalesRuleConfirmation\Model\Confirmation;
use Alvor\SalesRuleConfirmation\Model\ConfirmationFactory;
use Alvor\SalesRuleConfirmation\Helper\Hash as HashHelper;
use Alvor\SalesRuleConfirmation\Helper\Data as DataHelper;
use Alvor\SalesRuleConfirmation\Model\Config;

abstract class AbstractHandler implements HandlerInterface
{
    protected $confirmationRepository;
    protected $nextHandler;
    protected $confirmationPersonCode;
    protected $confirmationCollectionFactory;
    protected $confirmationFactory;
    protected $hashHelper;
    protected $dataHelper;
    protected $transportBuilder;
    protected $url;
    protected $handlerCode;
    protected $config;
    protected $messageManager;

    protected $salesRule;
    protected $confirmation;

    public function __construct(
        ConfirmationRepositoryInterface $confirmationRepository,
        CollectionFactory $collectionFactory,
        ConfirmationFactory $confirmationFactory,
        HashHelper $hashHelper,
        TransportBuilder $transportBuilder,
        Url $url,
        Config $config,
        ManagerInterface $messageManager,
        DataHelper $dataHelper,
        HandlerInterface $nextHandler = null,
        string $confirmationPersonCode = ''
    )
    {
        $this->confirmationRepository = $confirmationRepository;
        $this->nextHandler = $nextHandler;
        $this->confirmationPersonCode = $confirmationPersonCode;
        $this->confirmationCollectionFactory = $collectionFactory;
        $this->confirmationFactory = $confirmationFactory;
        $this->hashHelper = $hashHelper;
        $this->transportBuilder = $transportBuilder;
        $this->url = $url;
        $this->config = $config;
        $this->messageManager = $messageManager;
        $this->dataHelper = $dataHelper;
    }

    public function handleRule(SalesRule $rule)
    {
        $this->salesRule = $rule;
        if ($this->isAlreadyConfirmed()) {
            if ($this->nextHandler) {
                $this->nextHandler->handleRule($rule);
            }
            return;
        }

        if($this->handle()) {
            $this->onSuccess();
        }
    }

    protected function isAlreadyConfirmed(): bool
    {
        if (!$this->salesRule) {
            throw new SalesRuleMissed(__('Sales rule missed'));
        }

        /** @var \Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation\Collection $collection */
        $collection = $this->confirmationCollectionFactory->create();
        $collection
            ->addFieldToFilter('rule_id', $this->salesRule->getId())
            ->addFieldToFilter('confirmation_type', $this->getHandlerCode())
            ->addFieldToFilter('is_confirmed', Confirmation::CONFIRMED);

        $collectionSize = $collection->getSize();

        return (bool) $collectionSize;
    }

    protected function createNewConfirmation()
    {
        /** @var \Alvor\SalesRuleConfirmation\Api\Data\ConfirmationInterface $newConfirmation */
        $newConfirmation = $this->confirmationFactory->create();
        $newConfirmation->setIsConfirmed(Confirmation::NOT_CONFIRMED);
        $newConfirmation->setConfirmationKey(
            $this->hashHelper->generateConfirmationHash(
                $this->salesRule,
                $this->getHandlerCode()
            )
        );
        $newConfirmation->setConfirmationType($this->getHandlerCode());
        $newConfirmation->setRuleId($this->salesRule->getRuleId());
        $this->confirmationRepository->save($newConfirmation);
        $this->confirmation = $newConfirmation;
    }


    /**
     * Do handle stuff
     *
     * @return bool
     */
    protected function handle(): bool
    {
        try {
            $this->createNewConfirmation();
            $this->sendConfirmationLetter();
            return true;
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
            return false;
        }
    }

    protected function sendConfirmationLetter()
    {
        $this->sendEmail(
            $this->getHandlerEmailData(),
            $this->getRecipients()
        );
    }

    protected function sendEmail(array $templateVars, array $recipients, string $templateId = 'salesrule_confirmation_confirm')
    {
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom([
                'name' => $this->config->getSenderName(),
                'email' => $this->config->getSenderEmail()
            ])
            ->addTo($recipients)
            ->getTransport();
        $transport->sendMessage();
    }

    protected function getHandlerEmailData(): array
    {
        return [
            'rule'                => $this->salesRule,
            'rule_name'           => $this->salesRule->getName(),
            'handler'             => $this,
            'subject'             => $this->dataHelper->prepareLetterSubject(__('Rule Activation'), $this->salesRule->getName()),
            'can_accept'          => true,
            'subject_brand_alias' => $this->dataHelper->getSubjectAlias(),
            'decline'             => false
        ];
    }

    public function getConfirmationUrl()
    {
        return $this->getUrlByType('confirm');
    }

    public function getDeclineUrl()
    {
        return $this->getUrlByType('decline');
    }

    private function getUrlByType(string $routerCode): string
    {
        return $this->url->getUrl(
            "ruleconfirmation/salesrule/$routerCode",
            [
                'code'              => $this->confirmation->getConfirmationKey(),
                'type'              => $this->getHandlerCode()
            ]
        );
    }

    protected function onSuccess()
    {
    }

    abstract public static function getHandlerCode(): string;

    abstract protected function getRecipients(): array;

    abstract public function getConfirmationPersonDescription(): string;
}
