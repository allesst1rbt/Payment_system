<?php

namespace Src\Api\Repositories;

use PDO;
use PDOException;
use Src\Api\Db\PdoConnection;
use Src\Api\Interfaces\TransactionRepositoryInterface;
use Src\Api\Models\Transaction;
use Ulid\Ulid;

class TransactionRepositoryPdo implements TransactionRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = PdoConnection::getInstance();
    }

    public function create(Transaction $transaction): ?Transaction
    {
        try {
            $id = Ulid::generate();
            $stmt = $this->db->prepare('
                INSERT INTO transactions (id, amount, payee_id, payer_id)
                VALUES (:id, :amount, :payee_id, :payer_id)
            ');

            $stmt->execute([
                ':id' => $id,
                ':amount' => $transaction->amount,
                ':payee_id' => $transaction->payeeId,
                ':payer_id' => $transaction->payerId,
            ]);

            $transaction->id = $id;

            return $transaction;
        } catch (PDOException $e) {
            echo 'Error creating transaction: '.$e->getMessage();

            return null;
        }
    }

    public function findById(string $id): ?Transaction
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM transactions WHERE id = :id');
            $stmt->execute([':id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $data) {
                return null;
            }

            $account = new Transaction;
            $account->id = $data['id'];
            $account->amount = $data['amount'];
            $account->payeeId = $data['payee_id'];
            $account->payerId = $data['payer_id'];

            return $account;
        } catch (PDOException $e) {
            echo 'Error finding transaction: '.$e->getMessage();

            return null;
        }
    }

    public function findUserTransactions(string $userId): ?array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM transactions WHERE payee_id = :user_id or payer_id = :user_id');
            $stmt->execute([':user_id' => $userId]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $data) {
                return null;
            }

            return $data;
        } catch (PDOException $e) {
            echo 'Error finding transactions for user: '.$e->getMessage();

            return null;
        }
    }
}
