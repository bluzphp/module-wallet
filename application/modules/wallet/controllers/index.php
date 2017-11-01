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
 */
return function (int $id = null) {
    /**
     * @var Controller $this
     */
    if (!Acl::isAllowed('wallet', 'Management') || !$id) {
        // if you don't have permissions you can see transactions only you
        $id = $this->user()->id;
    }

    $wallet = WalletsTable::getWallet($id);
    $transactions = TransactionsTable::select()
        ->where('userId = ?', $id)
        ->orderBy('created', 'DESC')
        ->limit(3)
        ->execute();

    $this->assign('wallet', $wallet);
    $this->assign('transactions', $transactions);
};
