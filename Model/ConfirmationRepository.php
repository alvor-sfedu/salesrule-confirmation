<?php
namespace Alvor\SalesRuleConfirmation\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Alvor\SalesRuleConfirmation\Api\ConfirmationRepositoryInterface;
use Alvor\SalesRuleConfirmation\Api\Data\ConfirmationInterface;
use Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation as ConfirmationResource;
use Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation\CollectionFactory as ConfirmationCollectionFactory;
use Alvor\SalesRuleConfirmation\Model\Confirmation;

class ConfirmationRepository implements ConfirmationRepositoryInterface
{
    private $confirmationResource;
    private $confirmationCollectionFactory;

    public function __construct(
        ConfirmationResource $confirmationResource,
        ConfirmationCollectionFactory $confirmationCollectionFactory
    )
    {
        $this->confirmationResource = $confirmationResource;
        $this->confirmationCollectionFactory = $confirmationCollectionFactory;
    }

    public function save(Confirmation $confirmation)
    {
        $this->confirmationResource->save($confirmation);
    }

    public function getConfirmationByParams(string $type, string $key): Confirmation
    {
        /** @var \Alvor\SalesRuleConfirmation\Model\ResourceModel\Confirmation\Collection $collection */
        $collection = $this->confirmationCollectionFactory->create();
        $collection->addFieldToFilter('confirmation_type', $type);
        $collection->addFieldToFilter('confirmation_key', $key);

        $confirmationObject = $collection->getFirstItem();
        if ($confirmationObject->isObjectNew()) {
            throw new NoSuchEntityException();
        }

        return $confirmationObject;
    }

    public function deleteAllConfirmationByRuleId($ruleId)
    {
        $this->confirmationResource->deleteAllConfirmationByRuleId($ruleId);
    }

    public function delete(Confirmation $confirmation)
    {
        $this->confirmationResource->delete($confirmation);
    }
}
