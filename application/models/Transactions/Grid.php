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
     * @return self
     */
    public function init()
    {
        // Current table as source of grid
        $adapter = new SelectSource();
        $adapter->setSource(Table::select());

        $this->setAdapter($adapter);
        $this->setDefaultLimit(25);
        $this->setAllowFilters(['userId', 'type']);
        $this->setAllowOrders(['id', 'type', 'amount', 'created']);

        return $this;
    }
}
