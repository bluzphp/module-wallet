<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 15:32:57
 */
namespace Application;

use Application\Transactions\Table;
use Bluz\Controller\Controller;

/**
 * @param int $id
 * @param int $amount
 *
 * @privilege Management
 */
return function (int $id, int $amount) {
    /**
     * @var Controller $this
     */
    Table::addDebit($id, $amount);
};
