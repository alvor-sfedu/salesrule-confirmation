<?php
namespace Alvor\SalesRuleConfirmation\Model\Handlers;

use Magento\Framework\Exception\LocalizedException;

class EcommerceManagerHandler extends AbstractHandler
{
    const HANDLER_CODE = 'ecomm_manager';

    protected function onSuccess()
    {
        try {
            $this->sendNotificationToBrandDirectors();
        } catch (LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
    }

    public function getConfirmationPersonDescription(): string
    {
        return __('E-commerce Manager');
    }

    public function getRecipients(): array
    {
        return $this->config->getEcommerceManagersEmails();
    }

    protected function sendNotificationToBrandDirectors()
    {
        $notificationHandlerData = $this->getHandlerEmailData();
        $notificationHandlerData['can_accept'] = false;
        $notificationHandlerData['description'] = __('New Promo action was created');
        $this->sendEmail($notificationHandlerData, $this->config->getBrandDirectorsEmails());
    }

    public static function getHandlerCode(): string
    {
        return self::HANDLER_CODE;
    }
}
