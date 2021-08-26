<?php

/**
 * Generated controller
 *
 * @author   dev
 * @created  2017-10-19 15:32:53
 */

namespace Application;

use Application\Wallets\Table;
use Bluz\Controller\Controller;
use Bluz\Proxy\Messages;
use Bluz\Proxy\Request;
use Bluz\Proxy\Response;
use Bluz\Proxy\Router;

/**
 * @param int $id
 * @param int $amount
 *
 * @accept JSON
 * @method POST
 * @throws Exception
 * @privilege Management
 * @throws \Bluz\Http\Exception\RedirectException
 */
return function (int $id, int $amount) {
    /**
     * @var Controller $this
     */
    if (Table::send($this->user()->id, $id, $amount)) {
        Messages::addSuccess('%d added to balance', $amount);
        $referer = Request::getHeader('referer') ?? Router::getFullUrl('wallet', 'grid');
        Response::redirect($referer);
    } else {
        Messages::addError('Found some problems with your account');
    }
};
