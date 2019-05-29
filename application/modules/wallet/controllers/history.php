<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 17:44:10
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Grid\Grid;
use Bluz\Proxy\Layout;

/**
 * @privilege View
 *
 * @throws \Bluz\Grid\GridException
 * @throws \Bluz\Common\Exception\CommonException
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::breadCrumbs(
        [
            Layout::ahref('Wallet', ['wallet', 'index']),
            __('Transactions history')
        ]
    );
    $grid = new Transactions\Grid();
    $grid->addFilter('users.id', Grid::FILTER_EQ, $this->user()->getId());

    $this->assign('grid', $grid);
};
