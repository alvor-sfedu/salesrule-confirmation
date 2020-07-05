<?php
namespace Alvor\SalesRuleConfirmation\Api;

use Alvor\SalesRuleConfirmation\Model\Confirmation;

interface ConfirmationRepositoryInterface
{
    public function save(Confirmation $confirmation);
}
