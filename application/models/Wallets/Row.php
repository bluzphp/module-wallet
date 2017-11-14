<?php
/**
 * @namespace
 */
namespace Application\Wallets;

/**
 * Class Row for `wallets`
 *
 * @package  Application\Wallets
 *
 * @property integer $userId
 * @property integer $amount
 * @property integer $blocked
 * @property string $created
 * @property string $updated
 *
 * @author   Anton Shevchuk
 * @created  2017-10-19 15:21:27
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @return void
     */
    public function beforeInsert() : void
    {
    }

    /**
     * @return void
     */
    public function beforeUpdate() : void
    {
    }
}
