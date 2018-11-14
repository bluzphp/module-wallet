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
 * @method   static Row create(array $data = [])
 *
 * @method   static Row findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 *
 * @author   Anton Shevchuk
 * @created  2017-10-19 15:21:59
 */
class Table extends \Bluz\Db\Table
{
    public const TYPE_DEBIT = 'debit';
    public const TYPE_CREDIT = 'credit';
    public const TYPE_BLOCK = 'block';
    public const TYPE_UNBLOCK = 'unblock';

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
    public function init() : void
    {
        $this->linkTo('userId', 'Users', 'id');
    }

    /**
     * getInfo
     *
     * @param integer $id
     *
     * @return Row
     */
    public static function getInfo($id) : ?Row
    {
        $select = self::select()
            ->addSelect('p.id AS paymentId', 'p.amount AS money', 'p.currency')
            ->leftJoin('transactions', 'payments', 'p', 'p.transactionId = transactions.id')
            ->where('transactions.id = ?', [$id]);

        return current($select->execute(Row::class));
    }
}
