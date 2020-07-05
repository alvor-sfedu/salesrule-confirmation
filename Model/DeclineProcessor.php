<?php
namespace Alvor\SalesRuleConfirmation\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\SalesRule\Model\Converter\ToModel;
use Magento\SalesRule\Model\RuleRepository;
use Alvor\SalesRuleConfirmation\Model\Confirmation\EmailNotification;
use Alvor\SalesRuleConfirmation\Model\Handlers\HandlerInterface;

class DeclineProcessor extends ConfirmationProcessor
{
    public function processConfirmation(RequestInterface $request): Phrase
    {
        $code = $request->getParam('code');
        $type = $request->getParam('type');

        try {
            $confirmation = $this->confirmationRepository->getConfirmationByParams($type, $code);
            $salesRuleDataModel = $this->ruleRepository->getById($confirmation->getRuleId());
            $this->emailNotification->sendNotification(
                $this->toModelConverter->toModel($salesRuleDataModel),
                $confirmation->getConfirmationType()
            );
            $this->confirmationRepository->delete($confirmation);
            return __('Confirmation successfully declined');
        } catch (NoSuchEntityException $exception) {
            return __('Confirmation not exist or was rejected by someone');
        } catch (LocalizedException $exception) {
            $msg = $exception->getMessage();
            return __('Something went wrong with confirmation. Error Message: %1', $msg);
        }
    }
}
