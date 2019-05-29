<?php
/**
 * @namespace
 */
namespace Application\Wallets;

/**
 * Class Table for `wallets`
 *
 * @package  Application\Wallets
 *
 * @method   static Row findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 *
 * @author   Anton Shevchuk
 * @created  2017-10-19 15:21:27
 */
class Table extends \Bluz\Db\Table
{
    /**
     * @var string
     */
    protected $name = 'wallets';

    protected $rowClass = Row::class;

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = ['userId'];

    /**
     * init
     *
     * @return void
     */
    public function init() : void
    {
        $this->linkTo('userId', 'Users', 'id');
    }

    /**
     * getWallet
     *
     * @param int $userId
     *
     * @return \Application\Wallets\Row
     * @throws \Bluz\Db\Exception\DbException
     */
    public static function getWallet(int $userId)
    {
        $wallet = self::findRowWhere(['userId' => $userId]);
        if (!$wallet) {
            $wallet = self::create(['userId' => $userId, 'amount' => 0, 'blocked' => 0]);
            $wallet->save();

            // workaround for https://github.com/bluzphp/framework/issues/447
            $wallet->userId = $userId;
        }
        return $wallet;
    }
}
