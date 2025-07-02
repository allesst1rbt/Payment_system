<?php

namespace Src\Api\Repositories;

use PDO;
use PDOException;
use Src\Api\Db\PdoConnection;
use Src\Api\Models\Transaction;

class TransactionRepositoryPdo
{
    private PDO $db;

    public function __construct()
    {
        $this->db = PdoConnection::getInstance();
    }

    public function create(Transaction $transaction): ?Transaction
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO transactions (amount, payee_id, payer_id)
                VALUES (:amount, :payee_id, :payer_id)
            ');

            $stmt->execute([
                ':amount' => $transaction->amount,
                ':payee_id' => $transaction->payeeId,
                ':payer_id' => $transaction->payerId,
            ]);

            $transaction->id = (int) $this->db->lastInsertId();

            return $transaction;
        } catch (PDOException $e) {
            echo 'Error creating transaction: '.$e->getMessage();

            return null;
        }
    }
}
