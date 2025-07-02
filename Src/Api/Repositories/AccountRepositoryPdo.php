<?php

namespace Src\Api\Repositories;

use PDO;
use PDOException;
use Src\Api\Db\PdoConnection;
use Src\Api\Interfaces\AccountRespositoryInterface;
use Src\Api\Models\Account;

class AccountRepositoryPdo implements AccountRespositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = PdoConnection::getInstance();
    }

    public function create(Account $account): ?Account
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO accounts (balance, user_id)
                VALUES (:balance, :user_id)
            ');

            $stmt->execute([
                ':balance' => $account->balance,
                ':user_id' => $account->userId,
            ]);

            $account->id = (int) $this->db->lastInsertId();

            return $account;
        } catch (PDOException $e) {
            echo 'Error creating account: '.$e->getMessage();

            return null;
        }
    }

    public function updateBalance(int $id, Account $account): ?Account
    {
        try {
            $stmt = $this->db->prepare('
                UPDATE accounts
                SET balance = :balance
                WHERE id = :id
            ');

            $stmt->execute([
                ':id' => $id,
                ':balance' => $account->balance,
            ]);

            $account->id = $id;

            return $account;
        } catch (PDOException $e) {
            echo 'Error updating account: '.$e->getMessage();

            return null;
        }
    }

    public function findById(int $id): ?Account
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM accounts WHERE id = :id');
            $stmt->execute([':id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $data) {
                return null;
            }

            $account = new Account;
            $account->id = (int) $data['id'];
            $account->balance = $data['balance'];
            $account->userId = $data['user_id'];

            return $account;
        } catch (PDOException $e) {
            echo 'Error finding account: '.$e->getMessage();

            return null;
        }
    }

    public function findByUserId(int $id): ?Account
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM accounts WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $data) {
                return null;
            }

            $account = new Account;
            $account->id = (int) $data['id'];
            $account->balance = $data['balance'];
            $account->userId = $data['user_id'];

            return $account;
        } catch (PDOException $e) {
            echo 'Error finding account: '.$e->getMessage();

            return null;
        }
    }

    public function delete(int $id): void
    {
        try {
            $stmt = $this->db->prepare('DELETE FROM accounts WHERE id = :id');
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            echo 'Error deleting account: '.$e->getMessage();
        }
    }
}
