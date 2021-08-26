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
 * @author   Anton Shevchuk
 * @created  2017-10-19 15:21:59
 */
class Row extends \Bluz\Db\Row
{
    /**
     * @return void
     */
    public function beforeInsert(): void
    {
    }

    /**
     * @return void
     */
    public function beforeUpdate(): void
    {
    }

    /**
     * getUser
     *
     * @return \Application\Users\Row|null
     * @throws \Bluz\Db\Exception\RelationNotFoundException
     * @throws \Bluz\Db\Exception\TableNotFoundException
     */
    public function getUser(): ?\Application\Users\Row
    {
        return $this->getRelation('Users');
    }

    /**
     * getAmount
     *
     * @return string
     */
    public function getAmount()
    {
        // switch statement for $transaction->type
        switch ($this->type) {
            case Table::TYPE_CREDIT:
                $prefix = '-';
                break;
            case Table::TYPE_DEBIT:
                $prefix = '+';
                break;
            case Table::TYPE_BLOCK:
                $prefix = '<<';
                break;
            case Table::TYPE_UNBLOCK:
                $prefix = '>>';
                break;
            default:
                $prefix = '';
                break;
        }

        return $prefix . $this->amount;
    }
}
