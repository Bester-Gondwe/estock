<?php
require_once __DIR__ . '/../connection.php';

class User extends Database
{
    private $usersTable = "users";
    public function __construct()
    {
        parent::__construct();
    }

    // create User
    public function addUser($first_name, $last_name, $email, $passwd)
    {
        $query = "INSERT INTO $this->usersTable (first_name,last_name,email,passwd) VALUES (:first_name,:last_name,:email,:passwd)";
        $hashPasswd = password_hash($passwd,PASSWORD_DEFAULT);
        $params = ['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'passwd' => $hashPasswd];
        $this->executeQuery($query, $params);
        return  $last_id = $this->conn->lastInsertId();
    }


    // get user  by id
    public function getUserById($user_id)
    {
        $query = "SELECT * FROM  $this->usersTable  WHERE user_id=:user_id LIMIT 1";
        $stmt = $this->executeQuery($query, ['user_id' => $user_id]);
        return $stmt->fetch();
    }


    // authenticate user
    public function login($email, $passwd)
    {
        $query = "SELECT * FROM $this->usersTable WHERE email = :email LIMIT 1";
        $stmt = $this->executeQuery($query, ['email' => $email]);
        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch();
            if (password_verify($passwd, $row['passwd'])) {
                return $row; // Return user data if login is successful
            }
            throw new Exception("Wrong email or password");
        }
    }


    // check if email already exists
    public function emailExists($email)
    {
        $query = "SELECT COUNT(*) FROM $this->usersTable WHERE email = :email";
        $stmt = $this->executeQuery($query, ['email' => $email]);
        return $stmt->fetchColumn() > 0;
    }

    public function updateUser($user_id, $first_name, $last_name, $email)
    {
        $query = "UPDATE $this->usersTable 
              SET first_name = :first_name, last_name = :last_name, email = :email 
              WHERE user_id = :user_id";
        $params = [
            'user_id' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email
        ];
        return $this->executeQuery($query, $params);
    }

    public function changePassword($user_id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $query = "UPDATE $this->usersTable SET passwd = :passwd WHERE user_id = :user_id";
        $params = [
            'user_id' => $user_id,
            'passwd' => $hashedPassword
        ];
        return $this->executeQuery($query, $params);
    }

    public function searchUsers($keyword)
    {
        $query = "SELECT * FROM $this->usersTable 
              WHERE first_name LIKE :keyword 
                 OR last_name LIKE :keyword 
                 OR email LIKE :keyword";
        $params = ['keyword' => '%' . $keyword . '%'];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchAll();
    }

    public function assignRoleToUser($user_id, $role_id)
    {
        $query = "INSERT INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)";
        $params = ['user_id' => $user_id, 'role_id' => $role_id];
        return $this->executeQuery($query, $params);
    }

    public function getUserRoles($user_id)
    {
        $query = "SELECT r.role_id, r.role_name 
              FROM roles r
              INNER JOIN user_roles ur ON r.role_id = ur.role_id
              WHERE ur.user_id = :user_id";
        $stmt = $this->executeQuery($query, ['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function userHasRole($user_id, $role_name)
    {
        $query = "SELECT COUNT(*) 
              FROM roles r
              INNER JOIN user_roles ur ON r.role_id = ur.role_id
              WHERE ur.user_id = :user_id AND r.role_name = :role_name";
        $params = ['user_id' => $user_id, 'role_name' => $role_name];
        $stmt = $this->executeQuery($query, $params);
        return $stmt->fetchColumn() > 0;
    }

    public function getRoleByName($role_name){
        $query = "SELECT role_id FROM roles WHERE role_name=:role_name LIMIT 1";
        $stmt = $this->executeQuery($query,['role_name' => $role_name]);
        return $stmt->fetchColumn();
    }

    public function getUsersByRole($role_name)
    {
        $query = "SELECT u.user_id, u.first_name, u.last_name, u.email 
              FROM users u
              INNER JOIN user_roles ur ON u.user_id = ur.user_id
              INNER JOIN roles r ON r.role_id = ur.role_id
              WHERE r.role_name = :role_name";
        $stmt = $this->executeQuery($query, ['role_name' => $role_name]);
        return $stmt->fetchAll();
    }

    public function createRole($role_name)
    {
        $query = "INSERT INTO roles (role_name) VALUES (:role_name)";
        $params = ['role_name' => $role_name];
        return $this->executeQuery($query, $params);
    }

    public function deleteRole($role_id)
    {
        $query = "DELETE FROM roles WHERE role_id = :role_id";
        $params = ['role_id' => $role_id];
        return $this->executeQuery($query, $params);
    }
}

