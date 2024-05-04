<?php

namespace App\Controllers;
use App\Models\User_model;
use CodeIgniter\Controller;

class Auth extends BaseController {

    protected $user_model;
    protected $session;
    protected $validation;
    protected $request;

    public function __construct()
    {
        $this->user_model = new User_model();
        $this->validation = \Config\Services::validation();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
    }

    public function register() {
        // Set validation rules for registration form
        if($this->user_model->load_database()){
            
            $validationRules = [
                'nickname' => 'required|alpha_numeric',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required',
                'confirm_password' => 'required|matches[password]'
            ];
        
            
            if ($this->validation->setRules($validationRules)->withRequest($this->request)->run()===false) {
            // If validation fail, reload the registration form with validation errors
            $errors = $this->validation->getErrors();
            $errorString = '<ul>';
            foreach ($errors as $field => $error) {
                if (strpos($error, "The email field must contain a unique value.") !== false) {
                    $error =  "Email already registered in the system.";
                }
                $errorString .= '<li>' . $error . '</li>';
            }
            $errorString .= '</ul>';
            $this->session->setFlashdata('error', $errorString);
            return redirect()->to('register')->withInput();
        } else {
            // Retrieve input data

            $nickname = $this->request->getPost('nickname');
            $email = $this->request->getPost('email');
            $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
    
            $data = [
                'nickname' => $nickname,
                'email' => $email,
                'password' => $password
            ];
    
            // Attempt registration
            

            $result = $this->user_model->register($data);
            if ($result) {

                // Registration successful, redirect to login page
                $this->session->setFlashdata('success', 'Registration successful!');
                return redirect()->to('login')->withInput();

            } else {

                // Registration failed due to database error, display error message
                $this->session->setFlashdata('error', 'Error: Registration failed due to a database error.');
                return redirect()->to('register')->withInput();

            }

            }
        }
        else{
            $this->session->setFlashdata('error', 'Unable to communicate with Database...');
            return redirect()->to('register')->withInput();
        }
    }

    public function login() {

        $validationRules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if ($this->validation->setRules($validationRules)->withRequest($this->request)->run()===false) {
            // If validation fails, reload the login view
            $errors = $this->validation->getErrors();
            $errorString = '<ul>';
            foreach ($errors as $field => $error) {
                $errorString .= '<li>' . $error . '</li>';
            }
            $errorString .= '</ul>';
            $this->session->setFlashdata('validation_errors', $errorString);
            return redirect()->to('login')->withInput();

        } else {
            // Retrieve input data
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

           
            // Attempt login
            if($this->user_model->load_database()){
                try {
                    $user = $this->user_model->login($email, $password);
                    if ($user) {
                        // Set user session and redirect to dashboard
                        $this->session->set('user_id', $user['id']);
                        $this->session->set('user_nickname', $user['nickname']);
                        $this->session->setFlashdata('success', 'Login successful!');
                        return redirect()->to('dashboard')->withInput();
                    } else {
                        // Display error message
                        $this->session->setFlashdata('login_errors', 'Invalid email or password. Please try again.');
                        return redirect()->to('login')->withInput();
                    }
                    }catch (Exception $e) {
                        // Handle database connection errors
                        $this->session->setFlashdata('db_errors', 'Unable to communicate with Database...');
                        return redirect()->to('login')->withInput();
                    }
            } else{
                $this->session->setFlashdata('db_errors', 'Unable to communicate with Database...');
                return redirect()->to('login')->withInput();
            }
           
        }
    }

    public function logout() {
        // Destroy session and redirect to login page
        $this->session->remove('user_id');
        $this->session->setFlashdata('success', 'Logout Successfully!');
        return redirect()->to('login')->withInput();
    }
}