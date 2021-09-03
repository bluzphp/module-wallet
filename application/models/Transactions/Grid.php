<?php

/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */

namespace Application\Transactions;

use Bluz\Grid\Source\SelectSource;

/**
 * Grid based on Table
 *
 * @package  Application\Transactions
 *
 * @author   Anton Shevchuk
 * @created  2017-10-19 15:29:44
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'transactions';

    /**
     * @return void
     * @throws \Bluz\Grid\GridException
     */
    public function init(): void
    {
        // Build select
        $select = Table::select()
            ->addSelect('users.login AS login')
            ->addSelect('usersChain.login AS chainLogin')
            ->leftJoin('transactions', 'users', 'users', 'users.id = transactions.userId')
            ->leftJoin('transactions', 'users', 'usersChain', 'usersChain.id = transactions.chainUserId')
        ;

        // Current table as source of grid
        $adapter = new SelectSource();
        $adapter->setSource($select);

        $this->addAlias('users.id', 'user');
        $this->addAlias('users.login', 'login');

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowFilters(['users.id', 'type']);
        $this->setAllowOrders(['type', 'amount', 'created', 'users.login']);
    }
}
