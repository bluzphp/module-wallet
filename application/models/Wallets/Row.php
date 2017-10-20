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
 * @property string $created
 * @property string $updated
 *
 * @author   dev
 * @created  2017-10-19 15:21:27
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @return void
     */
    public function beforeInsert()
    {
    }

    /**
     * @return void
     */
    public function beforeUpdate()
    {
    }
}
