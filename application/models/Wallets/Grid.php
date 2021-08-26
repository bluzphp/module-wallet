<?php

/**
 * @namespace
 */

namespace Application\Wallets;

use Bluz\Grid\Source\SelectSource;

/**
 * Grid based on Table
 *
 * @package  Application\Wallets
 *
 * @author   Anton Shevchuk
 * @created  2017-10-20 09:56:24
 */
class Grid extends \Bluz\Grid\Grid
{
    /**
     * @var string
     */
    protected $uid = 'wallets';

    /**
     * @return void
     * @throws \Bluz\Grid\GridException
     */
    public function init(): void
    {
        // Current table as source of grid
        $adapter = new SelectSource();
        $adapter->setSource(
            Table::select()
                ->addSelect('users.login as login')
                ->join('wallets', 'users', 'users', 'users.id = wallets.userId')
        );

        $this->addAlias('users.id', 'user');
        $this->addAlias('users.login', 'login');
        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowFilters(['amount', 'blocked', 'users.id', 'users.login']);
        $this->setAllowOrders(['amount', 'blocked', 'users.id', 'users.login', 'created']);
    }
}
