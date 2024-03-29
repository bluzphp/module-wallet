<?php

/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 15:32:53
 */

namespace Application;

use Application\Wallets\Service;
use Bluz\Controller\Controller;

/**
 * @param int $id
 * @param int $amount
 *
 * @throws Exception
 * @privilege Management
 */
return function (int $id, int $amount) {
    /**
     * @var Controller $this
     */
    if (!Service::addCredit($id, $amount)) {
        throw new Exception('System can\'t added credit');
    }
};
