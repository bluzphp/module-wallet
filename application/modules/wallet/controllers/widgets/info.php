<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 17:44:10
 */
namespace Application;

use Application\Wallets\Table as WalletsTable;
use Bluz\Controller\Controller;

/**
 * @privilege View
 */
return function () {
    /**
     * @var Controller $this
     */
    $id = $this->user()->getId();
    $wallet = WalletsTable::getWallet($id);
    $this->assign('wallet', $wallet);
};
