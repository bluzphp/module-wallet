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
 * @author   Anton Shevchuk
 * @created  2017-10-19 15:21:59
 */
class Table extends \Bluz\Db\Table
{
    const TYPE_DEBIT = 'debit';
    const TYPE_CREDIT = 'credit';
    const TYPE_BLOCK = 'block';
    const TYPE_UNBLOCK = 'unblock';

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
}
