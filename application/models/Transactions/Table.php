<?php
/**
 * @namespace
 */
namespace Application\Transactions;

use \Application\Transactions\Row;
use Application\Wallets\Table as WalletsTable;
use Bluz\Proxy\Db;

/**
 * Class Table for `transactions`
 *
 * @package  Application\Transactions
 *
 * @author   dev
 * @created  2017-10-19 15:21:59
 */
class Table extends \Bluz\Db\Table
{
    const TYPE_DEBIT = 'debit';
    const TYPE_CREDIT = 'credit';

    /**
     * @var string
     */
    protected $name = 'transactions';

    protected $rowClass = Row::class;

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = ['id'];

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $this->linkTo('userId', 'Users', 'id');
    }

    /**
     * Add Debit record
     *
     * @return bool|Row
     */
    public static function addDebit($userId, int $amount)
    {
        return Db::transaction(function() use ($userId, $amount) {
            $row = self::create(['userId' => $userId, 'amount' => $amount, 'type' => self::TYPE_DEBIT]);
            $row->save();

            $wallet = WalletsTable::getWallet($userId);
            $wallet->amount += $amount;
            $wallet->save();

            return $row;
        });
    }

    /**
     * Add Credit record
     *
     * @return bool|Row
     */
    public static function addCredit($userId, int $amount)
    {
        return Db::transaction(function() use ($userId, $amount) {
            $row = self::create(['userId' => $userId, 'amount' => $amount, 'type' => self::TYPE_CREDIT]);
            $row->save();

            $wallet = WalletsTable::getWallet($userId);
            $wallet->amount -= $amount;
            $wallet->save();

            return $row;
        });
    }
}
