<?php
namespace Alvor\SalesRuleConfirmation\Model\Confirmation;

use Magento\Framework\Phrase;

class AbstractMessageResolver
{
    private $messageList;
    private $defaultMessage;

    public function __construct(
        array  $messageList,
        string $defaultMessage
    )
    {
        $this->messageList = $messageList;
        $this->defaultMessage = $defaultMessage;
    }

    public function getSuccessMessageByConfirmationType(string $confirmationType): Phrase
    {
        foreach ($this->messageList as $msg) {
            if ($msg['type'] === $confirmationType) {
                return __($msg['text']);
            }
        }

        return __($this->defaultMessage);
    }
}
