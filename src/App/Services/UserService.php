<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Exceptions\ValidationException;
use Framework\DB;

class UserService
{
    public function __construct(private DB $db)
    {
    }

    public function isEmailExist(string $email)
    {
        $emailCount = $this->db->query(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [
                'email' => $email
            ]
        )->count();

        if ($emailCount > 0) {
            throw new ValidationException(['email' => ['Email taken']]);
        }
    }

    public function create(array $formData)
    {
        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $this->db->query(
            "INSERT INTO users(email,password,age,country,social_media_url) 
            VALUES (:email, :password, :age, :country, :url)",
            [
                'email' => $formData['email'],
                'password' => $password,
                'age' => $formData['age'],
                'country' => $formData['country'],
                'url' => $formData['social']
            ]
        );

        session_regenerate_id();

        $_SESSION['user'] = $this->db->id();
    }

    public function login(array $data)
    {
        $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            [
                'email' => $data['email']
            ]
        )->find();

        $passwordMatch = password_verify($data['password'], $user['password'] ?? '');

        if (!$user || !$passwordMatch) {
            throw new ValidationException(['password' => ['Invalid Credentials']]);
        }

        session_regenerate_id();

        $_SESSION['user'] = $user['id'];
    }

    public function logout()
    {
        session_destroy();

        $params = session_get_cookie_params();

        setcookie(
            'PHPSESSID',
            '',
            time() - 3600,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
}
