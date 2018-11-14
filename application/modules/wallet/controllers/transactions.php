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
use Bluz\Proxy\Layout;

/**
 * @privilege Management
 */
return function () {
    /**
     * @var Controller $this
     */
    Layout::setTemplate('dashboard.phtml');
    Layout::breadCrumbs(
        [
            Layout::ahref('Dashboard', ['dashboard', 'index']),
            __('Transactions')
        ]
    );
    $grid = new Transactions\Grid();
    $this->assign('grid', $grid);
};
