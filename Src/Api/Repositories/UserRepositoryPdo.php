<?php

namespace Src\Api\Repositories;

use PDO;
use PDOException;
use Src\Api\Db\PdoConnection;
use Src\Api\Interfaces\UserRepositoryInterface;
use Src\Api\Models\User;

class UserRepositoryPdo implements UserRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = PdoConnection::getInstance();
    }

    public function create(User $user): ?User
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO users (name, email, password, document, type)
                VALUES (:name, :email, :password, :document, :type)
            ');

            $success = $stmt->execute([
                ':name' => $user->name,
                ':email' => $user->email,
                ':password' => password_hash($user->password, PASSWORD_BCRYPT),
                ':document' => $user->document,
                ':type' => $user->type,
            ]);

            if ($success) {
                $user->id = (int) $this->db->lastInsertId();

                return $user;
            }

            return null;

        } catch (PDOException $e) {
            echo 'Error creating user: '.$e->getMessage();

            return null;
        }
    }

    public function findByUserId(int $id): ?User
    {
        try {
            $stmt = $this->db->prepare('SELECT id, name, email, password, document FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $user = new User;
                $user->id = (int) $data['id'];
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->document = $data['document'];
                $user->type = $data['type'];

                return $user;
            }

            return null;
        } catch (PDOException $e) {
            echo 'Error finding user: '.$e->getMessage();

            return null;
        }
    }

    public function findByUserEmail(string $email): ?User
    {
        try {
            $stmt = $this->db->prepare('SELECT id, name, email, password, document, type FROM users WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $user = new User;
                $user->id = (int) $data['id'];
                $user->email = $data['email'];
                $user->password = $data['password'];
                $user->type = $data['type'];

                return $user;
            }

            return null;
        } catch (PDOException $e) {
            echo 'Error finding user: '.$e->getMessage();

            return null;
        }
    }
}
