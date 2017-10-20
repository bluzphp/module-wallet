<?php
/**
 * @namespace
 */
namespace Application\Wallets;

use \Application\Wallets\Row;

/**
 * Class Table for `wallets`
 *
 * @package  Application\Wallets
 *
 * @author   dev
 * @created  2017-10-19 15:21:27
 */
class Table extends \Bluz\Db\Table
{
    /**
     * @var string
     */
    protected $name = 'wallets';

    protected $rowClass = Row::class;

    /**
     * Primary key(s)
     * @var array
     */
    protected $primary = ['userId'];

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $this->linkTo('userId', 'Users', 'id');
    }

    /**
     * getWallet
     *
     * @return Row
     */
    public static function getWallet(int $userId)
    {
        $wallet = self::findRow($userId);
        if (!$wallet) {
            $wallet = self::create(['userId' => $userId, 'amount' => 0]);
            $wallet->save();
        }
        return $wallet;
    }
}
