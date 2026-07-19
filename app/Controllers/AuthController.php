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
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_role', $user['role']);
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
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
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

        if ($this->userModel->create(['name' => $name, 'email' => $email, 'password' => $password])) {
            Session::flash('success', 'Registration successful. Please login.');
            $this->redirect('/login');
        } else {
            Session::flash('error', 'Registration failed.');
            $this->redirect('/register');
        }
    }

    public function logout() {
        Session::destroy();
        header("Location: " . ($_ENV['APP_URL'] ?? '') . "/login");
        exit;
    }
}
