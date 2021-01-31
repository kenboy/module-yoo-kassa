<?php
/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YooKassa\Gateway\Validator;

use YooKassa\Model\PaymentStatus;

/**
 * Response Validator
 */
class ResponseValidator extends GeneralResponseValidator
{
    /**
     * @return array
     */
    protected function getResponseValidators()
    {
        return array_merge(
            parent::getResponseValidators(),
            [
                function ($response) {
                    return [
                        in_array(
                            $response['status'],
                            [PaymentStatus::WAITING_FOR_CAPTURE, PaymentStatus::PENDING, PaymentStatus::SUCCEEDED]
                        ),
                        [__('Wrong transaction status')]
                    ];
                }
            ]
        );
    }
}
