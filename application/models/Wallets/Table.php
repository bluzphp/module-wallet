<?php

/**
 * @namespace
 */

namespace Application\Wallets;

use Application\Options\Table as OptionsTable;
use Application\Transactions\Table as TransactionsTable;
use Application\Users\Table as UsersTable;
use Application\Wallets\Exceptions\InsufficientFundsException;
use Application\Wallets\Exceptions\WalletsException;
use Bluz\Proxy\Db;

/**
 * Class Table for `wallets`
 *
 * @package  Application\Wallets
 *
 * @method   static Row findRow($primaryKey)
 * @see      \Bluz\Db\Table::findRow()
 * @method   static Row findRowWhere($whereList)
 * @see      \Bluz\Db\Table::findRowWhere()
 *
 * @author   Anton Shevchuk
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
    public function init(): void
    {
        $this->linkTo('userId', 'Users', 'id');
    }

    /**
     * getWallet
     *
     * @param int $userId
     *
     * @return \Application\Wallets\Row
     * @throws \Bluz\Db\Exception\DbException
     */
    public static function getWallet(int $userId)
    {
        $wallet = self::findRowWhere(['userId' => $userId]);
        if (!$wallet) {
            $wallet = self::create(['userId' => $userId, 'amount' => 0]);
            $wallet->save();

            // workaround for https://github.com/bluzphp/framework/issues/447
            $wallet->userId = $userId;
        }
        return $wallet;
    }

    /**
     * Add Debit record
     *
     * @param int $userId
     * @param int $amount
     *
     * @return \Application\Wallets\Row|bool
     */
    public static function addDebit(int $userId, int $amount)
    {
        return Db::transaction(function () use ($userId, $amount) {
            $wallet = self::getWallet($userId);

            $row = TransactionsTable::create(
                [
                    'userId' => $userId,
                    'amount' => $amount,
                    'type' => TransactionsTable::TYPE_DEBIT
                ]
            );
            $row->save();

            $wallet->amount += $amount;
            $wallet->save();

            return $row;
        });
    }

    /**
     * Add Credit record
     *
     * @param int $userId
     * @param int $amount
     *
     * @return \Application\Wallets\Row|bool
     */
    public static function addCredit(int $userId, int $amount)
    {
        return Db::transaction(function () use ($userId, $amount) {

            $wallet = self::getWallet($userId);
            if ($wallet->amount - $wallet->blocked < $amount) {
                throw new InsufficientFundsException();
            }

            $row = TransactionsTable::create(
                [
                    'walletId' => $wallet->id,
                    'amount' => $amount,
                    'type' => TransactionsTable::TYPE_CREDIT
                ]
            );
            $row->save();

            $wallet->amount -= $amount;
            $wallet->save();

            return $row;
        });
    }

    /**
     * Block some money
     *
     * @param int $userId
     * @param int $amount
     *
     * @return bool
     * @throws InsufficientFundsException
     * @throws \Bluz\Common\Exception\ConfigurationException
     * @throws \Bluz\Db\Exception\DbException
     * @throws \Bluz\Db\Exception\InvalidPrimaryKeyException
     * @throws \Bluz\Db\Exception\TableNotFoundException
     */
    public static function addBlock(int $userId, int $amount): bool
    {
        $wallet = self::getWallet($userId);
        if ($wallet->amount - $wallet->blocked < $amount) {
            throw new InsufficientFundsException();
        }

        $row = TransactionsTable::create(
            [
                'walletId' => $wallet->id,
                'amount' => $amount,
                'type' => TransactionsTable::TYPE_BLOCK
            ]
        );
        $row->save();

        $wallet->blocked += $amount;
        return $wallet->save();
    }

    /**
     * Add Credit record
     *
     * @param int $userId
     * @param int $amount
     *
     * @return bool
     * @throws \Bluz\Common\Exception\ConfigurationException
     * @throws \Bluz\Db\Exception\DbException
     * @throws \Bluz\Db\Exception\InvalidPrimaryKeyException
     * @throws \Bluz\Db\Exception\TableNotFoundException
     */
    public static function removeBlock(int $userId, int $amount): bool
    {
        $wallet = self::getWallet($userId);

        $row = TransactionsTable::create(
            [
                'walletId' => $wallet->id,
                'amount' => $amount,
                'type' => TransactionsTable::TYPE_UNBLOCK
            ]
        );
        $row->save();

        if ($wallet->blocked < $amount) {
            $wallet->blocked = 0;
        } else {
            $wallet->blocked -= $amount;
        }

        return $wallet->save();
    }

    /**
     * Send money from wallet to wallet
     *
     * @param int $fromUserId
     * @param int $toUserId
     * @param int $amount
     *
     * @return \Application\Wallets\Row|bool
     * @throws InsufficientFundsException
     * @throws WalletsException
     * @throws \Bluz\Db\Exception\DbException
     */
    public static function send(int $fromUserId, int $toUserId, int $amount)
    {
        if ($amount < 0) {
            throw new WalletsException('Amount is lower than zero');
        }

        $fromWallet = self::getWallet($fromUserId);

        if ($fromWallet->amount - $fromWallet->blocked < $amount) {
            throw new InsufficientFundsException();
        }

        return Db::transaction(function () use ($fromWallet, $toUserId, $amount) {
            // Credit
            $creditTransaction = TransactionsTable::create();
            $creditTransaction->userId = $fromWallet->userId;
            $creditTransaction->amount = $amount;
            $creditTransaction->type = TransactionsTable::TYPE_CREDIT;
            $creditTransaction->save();

            $fromWallet->amount -= $amount;
            $fromWallet->save();

            // System percent
            $systemAmount = ceil(OptionsTable::get('percent') / 100 * $amount);
            $ownerAmount = $amount - $systemAmount;

            // Debit to system
            $transaction = TransactionsTable::create();
            $transaction->userId = UsersTable::SYSTEM_USER;
            $transaction->amount = $systemAmount;
            $transaction->type = TransactionsTable::TYPE_DEBIT;
            $transaction->save();

            // Debit to owner
            $debitTransaction = TransactionsTable::create();
            $debitTransaction->userId = $toUserId;
            $debitTransaction->amount = $ownerAmount;
            $debitTransaction->type = TransactionsTable::TYPE_DEBIT;
            $debitTransaction->save();

            $toWallet = self::getWallet($toUserId);
            $toWallet->amount += $ownerAmount;
            $toWallet->save();
        });
    }
}
