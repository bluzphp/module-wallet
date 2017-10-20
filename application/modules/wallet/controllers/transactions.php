<?php
/**
 * Grid controller for Transactions model
 *
 * @author   dev
 * @created  2017-10-19 15:29:44
 */

/**
 * @namespace
 */
namespace Application;

use Bluz\Controller\Controller;
use Bluz\Grid\Grid;
use Bluz\Proxy\Acl;

/**
 * @privilege ViewTransactions
 *
 * @return mixed
 */
return function () {
    /**
     * @var Controller $this
     */
    $grid = new Transactions\Grid();

    if (!Acl::isAllowed('wallet', 'Management')) {
        // if you don't have permissions you can see transactions only you
        $grid->addFilter('userId', Grid::FILTER_EQ, $this->user()->id);
    }

    $this->assign('grid', $grid);
};
