<?php
/**
 * @copyright Bluz PHP Team
 * @link      https://github.com/bluzphp/skeleton
 */
namespace Application\Wallets;

use Application\Transactions\Table as TransactionsTable;
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
class Service extends \Bluz\Db\Table
{
    /**
     * Add Debit record
     *
     * @param int $userId
     * @param int $amount
     *
     * @return \Application\Transactions\Row|false
     */
    public static function addDebit(int $userId, int $amount)
    {
        return Db::transaction(static function () use ($userId, $amount) {
            $wallet = Table::getWallet($userId);

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
     * @return \Application\Transactions\Row|false
     */
    public static function addCredit(int $userId, int $amount)
    {
        return Db::transaction(static function () use ($userId, $amount) {

            $wallet = Table::getWallet($userId);
            if ($wallet->amount - $wallet->blocked < $amount) {
                throw new InsufficientFundsException;
            }

            $row = TransactionsTable::create(
                [
                    'userId' => $userId,
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
     * @return \Application\Transactions\Row|false
     */
    public static function addBlock(int $userId, int $amount)
    {
        return Db::transaction(static function () use ($userId, $amount) {
            $wallet = Table::getWallet($userId);
            if ($wallet->amount - $wallet->blocked < $amount) {
                throw new InsufficientFundsException;
            }

            $row = TransactionsTable::create(
                [
                    'userId' => $userId,
                    'amount' => $amount,
                    'type' => TransactionsTable::TYPE_BLOCK
                ]
            );
            $row->save();

            $wallet->blocked += $amount;
            return $row;
        });
    }

    /**
     * Add Credit record
     *
     * @param int $userId
     * @param int $amount
     *
     * @return \Application\Transactions\Row|false
     */
    public static function removeBlock(int $userId, int $amount)
    {
        return Db::transaction(static function () use ($userId, $amount) {
            $wallet = Table::getWallet($userId);

            $row = TransactionsTable::create(
                [
                    'userId' => $userId,
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
        });
    }

    /**
     * Send money from wallet to wallet
     *
     * @param int $fromUserId
     * @param int $toUserId
     * @param int $amount
     *
     * @return bool
     * @throws WalletsException
     */
    public static function send(int $fromUserId, int $toUserId, int $amount): bool
    {
        if ($amount < 0) {
            throw new WalletsException('Amount is lower than zero');
        }

        return Db::transaction(static function () use ($fromUserId, $toUserId, $amount) {
            $fromWallet = Table::getWallet($fromUserId);

            if ($fromWallet->amount - $fromWallet->blocked < $amount) {
                throw new InsufficientFundsException;
            }

            // Update wallets
            $fromWallet->amount -= $amount;
            $fromWallet->save();

            $toWallet = Table::getWallet($toUserId);
            $toWallet->amount += $amount;
            $toWallet->save();

            // Credit transaction
            $creditTransaction = TransactionsTable::create();
            $creditTransaction->userId = $fromUserId;
            $creditTransaction->chainUserId = $toUserId;
            $creditTransaction->amount = $amount;
            $creditTransaction->type = TransactionsTable::TYPE_CREDIT;
            $creditTransaction->save();

            // Debit transaction
            $debitTransaction = TransactionsTable::create();
            $debitTransaction->userId = $toUserId;
            $debitTransaction->chainUserId = $fromUserId;
            $debitTransaction->amount = $amount;
            $debitTransaction->type = TransactionsTable::TYPE_DEBIT;
            $debitTransaction->save();

            return true;
        });
    }
}
