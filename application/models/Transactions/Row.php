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
 * @property integer $userChainId
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
    public function beforeInsert() : void
    {
    }

    /**
     * @return void
     */
    public function beforeUpdate() : void
    {
    }

    /**
     * getUser
     *
     * @return \Application\Users\Row|false
     */
    public function getUser()
    {
        return $this->getRelation('Users');
    }

    /**
     * getChainUser
     *
     * @return \Application\Users\Row|false
     */
    public function getChainUser()
    {
        if (!$this->userChainId) {
            return false;
        }
        return \Application\Users\Table::findRow($this->userChainId);
    }
}
