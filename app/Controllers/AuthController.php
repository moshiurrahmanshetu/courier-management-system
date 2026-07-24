<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\AuthMiddleware;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        AuthMiddleware::guest();
        $this->view('auth.login');
    }

    public function login() {
        AuthMiddleware::guest();
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!CSRF::verify($csrf_token)) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/login');
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                Session::flash('error', 'Your account is inactive.');
                $this->redirect('/login');
            }

            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role']);
            
            $this->userModel->updateLoginInfo($user['id'], $_SERVER['REMOTE_ADDR'] ?? null);
            logActivity('Login', 'User logged in successfully');

            if ($user['password_change_required']) {
                Session::flash('info', 'Please change your password before continuing.');
                $this->redirect('/profile');
            }

            $this->redirect('/dashboard');
        } else {
            Session::flash('error', 'Invalid email or password.');
            $this->redirect('/login');
        }
    }

    public function showRegister() {
        AuthMiddleware::guest();
        $this->view('auth.register');
    }

    public function register() {
        AuthMiddleware::guest();
        
        $name = htmlspecialchars(strip_tags($_POST['name'] ?? ''));
        $username = htmlspecialchars(strip_tags($_POST['username'] ?? ''));
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $mobile = htmlspecialchars(strip_tags($_POST['mobile'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!CSRF::verify($csrf_token)) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/register');
        }

        if ($password !== $confirm_password) {
            Session::flash('error', 'Passwords do not match.');
            $this->redirect('/register');
        }

        if ($this->userModel->findByEmail($email)) {
            Session::flash('error', 'Email already exists.');
            $this->redirect('/register');
        }
        
        if ($this->userModel->findByUsername($username)) {
            Session::flash('error', 'Username already exists.');
            $this->redirect('/register');
        }

        $userData = [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'mobile' => $mobile,
            'password' => $password,
            'role_slug' => 'general-user',
            'status' => 'active',
            'password_change_required' => 0
        ];

        if ($this->userModel->create($userData)) {
            logActivity('Registration', "New user registered: $username");
            Session::flash('success', 'Registration successful. Please login.');
            $this->redirect('/login');
        } else {
            Session::flash('error', 'Registration failed.');
            $this->redirect('/register');
        }
    }

    public function logout() {
        logActivity('Logout', 'User logged out');
        Session::destroy();
        header("Location: " . ($_ENV['APP_URL'] ?? '') . "/login");
        exit;
    }
}
