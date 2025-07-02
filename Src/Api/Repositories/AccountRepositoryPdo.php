<?php

namespace Src\Api\Repositories;

use PDO;
use PDOException;
use Src\Api\Db\PdoConnection;
use Src\Api\Interfaces\AccountRespositoryInterface;
use Src\Api\Models\Account;
use Ulid\Ulid;

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
            $id = Ulid::generate();
            $stmt = $this->db->prepare('
                INSERT INTO accounts (id, balance, user_id)
                VALUES (:id, :balance, :user_id)
            ');

            $stmt->execute([
                ':id' => $id,
                ':balance' => $account->balance,
                ':user_id' => $account->userId,
            ]);

            $account->id = $id;

            return $account;
        } catch (PDOException $e) {
            echo 'Error creating account: '.$e->getMessage();

            return null;
        }
    }

    public function updateBalance(string $id, Account $account): ?Account
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

            return $account;
        } catch (PDOException $e) {
            echo 'Error updating account: '.$e->getMessage();

            return null;
        }
    }

    public function findById(string $id): ?Account
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM accounts WHERE id = :id');
            $stmt->execute([':id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $data) {
                return null;
            }

            $account = new Account;
            $account->id = $data['id'];
            $account->balance = $data['balance'];
            $account->userId = $data['user_id'];

            return $account;
        } catch (PDOException $e) {
            echo 'Error finding account: '.$e->getMessage();

            return null;
        }
    }

    public function findByUserId(string $id): ?Account
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM accounts WHERE user_id = :user_id');
            $stmt->execute([':user_id' => $id]);

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (! $data) {
                return null;
            }

            $account = new Account;
            $account->id = $data['id'];
            $account->balance = $data['balance'];
            $account->userId = $data['user_id'];

            return $account;
        } catch (PDOException $e) {
            echo 'Error finding account: '.$e->getMessage();

            return null;
        }
    }
}
