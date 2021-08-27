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

/**
 * @privilege View
 *
 * @param int $id
 *
 * @throws \Bluz\Grid\GridException
 * @throws \Bluz\Common\Exception\CommonException
 */
return function (int $id = null) {
    /**
     * @var Controller $this
     */
    $grid = new Transactions\Grid();
    $grid->addFilter('users.id', Grid::FILTER_EQ, $this->user()->getId());

    $this->assign('grid', $grid);
    $this->assign('user', $this->user());
};
