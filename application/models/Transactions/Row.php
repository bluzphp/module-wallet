<?php
/**
 * @namespace
 */
namespace Application\Transactions;

/**
 * Class Row for `transactions`
 *
 * @package  Application\Transactions
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $amount
 * @property string $type
 * @property string $created
 * @property string $updated
 *
 * @author   dev
 * @created  2017-10-19 15:21:59
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
