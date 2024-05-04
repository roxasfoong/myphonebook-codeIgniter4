<?php

namespace App\Models;

use CodeIgniter\Model;

class User_model extends Model{

    protected $table = 'users';
    protected $allowedFields = ['nickname', 'email', 'password'];

    public function load_database() {

        // Load the database connection
        $db = db_connect();

        // Check if the connection is successful
        if ($db->connect_errno) {
            return false;
        } else {
            // Connection successful
            return true;
        }
    }
    

    public function register($data) {
        $builder = $this->db->table('users');
        return $builder->insert($data);
    }

    public function login($email, $password) {
        
        $user = $this->db->table('users')->where('email', $email)->get()->getRowArray();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
        
    }
}