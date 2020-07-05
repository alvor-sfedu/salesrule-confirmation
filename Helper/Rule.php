<?php
namespace Alvor\SalesRuleConfirmation\Helper;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroupCollection;
use Magento\SalesRule\Api\Data\RuleInterface;
use Magento\SalesRule\Model\Rule\Action\Product as ActionProduct;

class Rule
{
    const
        NO_COUPON = 1,
        SPECIFIED_COUPON = 2,
        AUTO_GENERATION = 3
    ;
    private $customerGroupCollectionFactory;
    private $productAction;

    public function __construct(
        CollectionFactory $collectionFactory,
        ActionProduct $productAction
    )
    {
        $this->customerGroupCollectionFactory = $collectionFactory;
        $this->productAction = $productAction;
    }

    public function getCouponDescription(int $couponType)
    {
        switch ($couponType) {
            case self::NO_COUPON:
                return RuleInterface::COUPON_TYPE_NO_COUPON;
            case self::SPECIFIED_COUPON:
                return RuleInterface::COUPON_TYPE_SPECIFIC_COUPON;
            case self::AUTO_GENERATION:
                return RuleInterface::COUPON_TYPE_AUTO;
            default:
                return '';
        }
    }

    public function getApplyToDescription(string $applyTo): string
    {
        if (\Magento\SalesRule\Api\Data\RuleInterface::DISCOUNT_ACTION_FIXED_AMOUNT_FOR_CART == $applyTo) {
            return __('Fixed amount discount for whole cart');
        }

        $options = $this->productAction->loadOperatorOptions()->getOperatorOption();

        return $options[$applyTo] ?? '';
    }

    public function getCustomerGroupsAsString(array $customerGroupIds): string
    {
        /** @var CustomerGroupCollection $customerGroupCollection */
        $customerGroupCollection = $this->customerGroupCollectionFactory->create();

        $customerGroupCollection
            ->addFieldToFilter('customer_group_id', ['in' => $customerGroupIds])
            ->addFieldToSelect('customer_group_code')
        ;

        $customerGroupNames = [];
        foreach ($customerGroupCollection as $customerGroup) {
            $customerGroupNames[] = $customerGroup->getData('customer_group_code');
        }

        return implode(',', $customerGroupNames);
    }
}
