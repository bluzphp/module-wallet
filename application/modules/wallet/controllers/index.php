<?php
/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 17:44:10
 */
namespace Application;

use Application\Wallets\Table;
use Bluz\Controller\Controller;
use Bluz\Http\Exception\NotFoundException;
use Bluz\Proxy\Layout;

/**
 * @privilege View
 *
 * @param int $id
 */
return function (int $id = null) {
    /**
     * @var Controller $this
     */
    Layout::title(__('Wallet'));

    // try to load profile of current user
    if (!$id && $this->user()) {
        $id = $this->user()->getId();
    }

    /**
     * @var Users\Row $user
     */
    $user = Users\Table::findRow($id);

    if (!$user) {
        throw new NotFoundException('User not found');
    }
    $this->assign('user', $user);
    $this->assign('wallet', Table::getWallet($id));
};
