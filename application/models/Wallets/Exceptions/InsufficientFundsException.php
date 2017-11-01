<?php
/**
 * @namespace
 */

namespace Application\Wallets\Exceptions;

/**
 * InsufficientFundsException
 *
 * @package  Application\Wallets\Exceptions
 * @author   Anton Shevchuk
 */
class InsufficientFundsException extends WalletsException
{
    protected $message = 'Insufficient Funds';
}
