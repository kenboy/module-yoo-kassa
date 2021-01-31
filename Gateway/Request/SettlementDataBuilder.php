<?php
/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YooKassa\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Settlement Data Builder
 */
class SettlementDataBuilder implements BuilderInterface
{
    const SETTLEMENT = 'capture';

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            self::SETTLEMENT => true
        ];
    }
}
