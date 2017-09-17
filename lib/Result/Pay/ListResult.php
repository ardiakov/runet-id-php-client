<?php

namespace RunetId\ApiClient\Result\Pay;

use RunetId\ApiClient\Result\AbstractResult;

/**
 * @property OrderAwareItemResult[] $Items
 * @property OrderResult[]          $Orders
 */
class ListResult extends AbstractResult
{
    /**
     * {@inheritdoc}
     */
    protected function getMap()
    {
        return [
            'Items' => 'RunetId\ApiClient\Result\Pay\OrderAwareItemResult[]',
            'Orders' => 'RunetId\ApiClient\Result\Pay\OrderResult[]',
        ];
    }
}
