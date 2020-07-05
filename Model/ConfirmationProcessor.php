<?php
namespace Alvor\SalesRuleConfirmation\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\SalesRule\Model\Rule as SalesRule;
use Magento\SalesRule\Model\RuleRepository;
use Magento\Framework\App\RequestInterface;
use Magento\SalesRule\Model\Converter\ToModel;

use Alvor\SalesRuleConfirmation\Model\Confirmation\AbstractMessageResolver;
use Alvor\SalesRuleConfirmation\Model\Confirmation\EmailNotification;
use Alvor\SalesRuleConfirmation\Model\Handlers\HandlerInterface;
use Alvor\SalesRuleConfirmation\Model\ActivationProcessor;


class ConfirmationProcessor
{
    private $initialConfirmationPoint;
    private $activationProcessor;
    protected $confirmationRepository;
    protected $ruleRepository;
    protected $toModelConverter;
    protected $emailNotification;
    protected $successMessageResolver;

    public function __construct(
        HandlerInterface $initialHandler,
        ConfirmationRepository $confirmationRepository,
        RuleRepository $ruleRepository,
        ToModel $toModel,
        ActivationProcessor $activationProcessor,
        EmailNotification $emailNotification,
        AbstractMessageResolver $successMessageResolver
    )
    {
        $this->emailNotification = $emailNotification;
        $this->initialConfirmationPoint = $initialHandler;
        $this->confirmationRepository = $confirmationRepository;
        $this->ruleRepository = $ruleRepository;
        $this->toModelConverter = $toModel;
        $this->activationProcessor = $activationProcessor;
        $this->successMessageResolver = $successMessageResolver;
    }

    public function processSalesRule(SalesRule $salesRule)
    {
        $this->initialConfirmationPoint->handleRule($salesRule);
    }

    public function processConfirmation(RequestInterface $request): Phrase
    {
        $code = $request->getParam('code');
        $type = $request->getParam('type');

        try {
            $confirmation = $this->confirmationRepository->getConfirmationByParams($type, $code);
            $confirmation->confirm();
            $this->confirmationRepository->save($confirmation);
            $this->processNextHandler($confirmation->getRuleId(), $type);
            return $this->successMessageResolver->getSuccessMessageByConfirmationType($type);
        } catch (NoSuchEntityException $exception) {
            return __('Confirmation not exist or was rejected by someone');
        } catch (LocalizedException $exception) {
            $msg = $exception->getMessage();
            return __('Something went wrong with confirmation. Error Message: %1', $msg);
        }
    }

    public function removeAllConfirmationForRule(SalesRule $salesRule)
    {
        if (!$salesRule->getId()) {
            return;
        }
        $this->confirmationRepository->deleteAllConfirmationByRuleId($salesRule->getId());
    }

    /**
     * Process next handler
     *
     * @param int    $ruleId      Rule ID
     * @param string $handlerCode handler Code
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function processNextHandler($ruleId, string $handlerCode)
    {
        $salesRuleDataModel = $this->ruleRepository->getById($ruleId);
        if ($this->activationProcessor->canActivateRule($ruleId)) {
            $salesRuleDataModel->setIsActive(1);
            $this->ruleRepository->save($salesRuleDataModel);
            $this->emailNotification->sendNotification($this->toModelConverter->toModel($salesRuleDataModel), $handlerCode);
        } else {
            $convertedSalesRuleModel = $this->toModelConverter->toModel($salesRuleDataModel);
            $this->initialConfirmationPoint->handleRule($convertedSalesRuleModel);
        }
    }
}
