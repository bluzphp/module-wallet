<?php

/**
 * @namespace
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
            ->addSelect('p.id AS paymentId', 'p.amount AS money', 'p.currency')
            ->addSelect('users.login AS login')
            ->leftJoin('transactions', 'payments', 'p', 'p.transactionId = transactions.id')
            ->leftJoin('transactions', 'users', 'users', 'users.id = transactions.userId')
        ;

        // Current table as source of grid
        $adapter = new SelectSource();
        $adapter->setSource($select);

        $this->addAlias('transactions.id', 'id');
        $this->addAlias('users.id', 'user');
        $this->addAlias('users.login', 'login');

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowFilters(['users.id', 'type']);
        $this->setAllowOrders(['transactions.id', 'type', 'amount', 'created', 'users.login']);
        $this->setDefaultOrder('transactions.id', self::ORDER_DESC);
    }
}
