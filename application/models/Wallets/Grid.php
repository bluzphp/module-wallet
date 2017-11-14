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
     */
    public function init() : void
    {
        // Current table as source of grid
        $adapter = new SelectSource();
        $adapter->setSource(Table::select());

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowFilters(['amount']);
        $this->setAllowOrders(['amount', 'created']);
    }
}
