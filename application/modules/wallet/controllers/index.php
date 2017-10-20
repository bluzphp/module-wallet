<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 17:44:10
 */
namespace Application;

use Application\Wallets\Table as WalletsTable;
use Application\Transactions\Table as TransactionsTable;
use Bluz\Controller\Controller;
use Bluz\Proxy\Acl;

/**
 * @privilege ViewTransactions
 *
 * @param int $id
 *
 * @return \closure
 */
return function (int $id = null) {
    /**
     * @var Controller $this
     */
    if (!Acl::isAllowed('wallet', 'Management') || !$id) {
        // if you don't have permissions you can see transactions only you
        $id = $this->user()->id;
    }

    $transactions = TransactionsTable::select()->where('userId = ?', $id)->limit(3)->execute();

    $this->assign('wallet', WalletsTable::getWallet($id));
    $this->assign('transactions', $transactions);
};
