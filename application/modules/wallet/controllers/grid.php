<?php
/**
 * Grid controller for Wallets model
 *
 * @author   dev
 * @created  2017-10-20 09:56:24
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
            __('Wallets')
        ]
    );
    $grid = new Wallets\Grid();
    $this->assign('grid', $grid);
};
