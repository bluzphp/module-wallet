<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
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
