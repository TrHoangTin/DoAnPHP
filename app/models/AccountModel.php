<?php
class AccountModel {
    private $conn;
    private $table = 'account';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add these methods to your existing AccountModel class

public function getUserCount() {
    $query = "SELECT COUNT(*) as count FROM {$this->table}";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetch()->count;
}

public function getAllUsers() {
    $query = "SELECT * FROM account ORDER BY created_at DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getRecentUsers($limit = 5) {
    $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

public function updateUserRole($id, $role, $status) {
    $query = "UPDATE {$this->table} 
             SET role = ?, status = ?, updated_at = NOW()
             WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$role, $status, $id]);
}

    public function getAccountByUsername($username) {
        $query = "SELECT * FROM {$this->table} WHERE username = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // public function getAccountById($id) {
    //     $query = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute([$id]);
    //     return $stmt->fetch();
    // }

    public function getUserById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateUser($id, $data) {
        $query = "UPDATE {$this->table} 
                 SET username = ?, fullname = ?, email = ?, phone = ?, 
                     role = ?, status = ?, updated_at = NOW()
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['username'],
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['role'],
            $data['status'],
            $id
        ]);
    }

    // public function createAccount($username, $password, $fullname, $email = null, $phone = null, $address = null) {
    //     $query = "INSERT INTO {$this->table} 
    //              (username, password, fullname, email, phone, address) 
    //              VALUES (?, ?, ?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($query);
        
    //     $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
    //     return $stmt->execute([
    //         $username,
    //         $hashed_password,
    //         $fullname,
    //         $email,
    //         $phone,
    //         $address
    //     ]);
    // }
    public function getAccountByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function createAccount($username, $password, $fullname, $email = null, $phone = null, $address = null, $provider = null, $provider_id = null) {
        $query = "INSERT INTO {$this->table} 
                 (username, password, fullname, email, phone, address, provider, provider_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        
        return $stmt->execute([
            $username,
            $hashed_password,
            $fullname,
            $email,
            $phone,
            $address,
            $provider,
            $provider_id
        ]);
    }
    public function updateAccount($id, $data) {
        $query = "UPDATE {$this->table} 
                 SET fullname = ?, email = ?, phone = ?, address = ?, updated_at = NOW()
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $id
        ]);
    }

    public function authenticate($username, $password) {
        $account = $this->getAccountByUsername($username);
        if ($account && password_verify($password, $account->password)) {
            return $account;
        }
        return false;
    }

    public function changePassword($id, $new_password) {
        $query = "UPDATE {$this->table} 
                 SET password = ?, updated_at = NOW()
                 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        
        return $stmt->execute([$hashed_password, $id]);
    }

    public function getAccountById($id) {
        return $this->getUserById($id);
    }

    // Thêm vào AccountModel.php
// public function getAccountByEmail($email) {
//     $query = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
//     $stmt = $this->conn->prepare($query);
//     $stmt->execute([$email]);
//     return $stmt->fetch();
// }



public function createPasswordResetToken($email, $token, $expiry) {
    $query = "UPDATE {$this->table} 
             SET reset_token = ?, reset_token_expiry = ?
             WHERE email = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$token, $expiry, $email]);
}

public function getAccountByResetToken($token) {
    $query = "SELECT * FROM {$this->table} 
             WHERE reset_token = ? AND reset_token_expiry > NOW() LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->execute([$token]);
    return $stmt->fetch();
}

public function resetPassword($token, $new_password) {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
    
    $query = "UPDATE {$this->table} 
             SET password = ?, reset_token = NULL, reset_token_expiry = NULL
             WHERE reset_token = ?";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$hashed_password, $token]);
}

}