<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2018-01-16 12:07:07
 */
namespace Application;

use Bluz\Controller\Controller;

/**
 * @privilege Management
 *
 * @param int $id
 */
return function (int $id) {
    /**
     * @var Controller $this
     */
    $this->assign('transaction', Transactions\Table::getInfo($id));
};
