<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Helpers\Session;
use App\Helpers\CSRF;
use App\Middleware\AuthMiddleware;

class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        \App\Middleware\RoleMiddleware::requirePermission('profile.view');
        $user = $this->userModel->findById(Session::get('user_id'));
        $this->view('profile.index', ['user' => $user]);
    }

    public function update() {
        \App\Middleware\RoleMiddleware::requirePermission('profile.edit');
        
        $name = htmlspecialchars(strip_tags($_POST['name'] ?? ''));
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!CSRF::verify($csrf_token)) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/profile');
        }

        $userId = Session::get('user_id');
        if ($this->userModel->update($userId, ['name' => $name, 'email' => $email])) {
            Session::set('user_name', $name);
            Session::flash('success', 'Profile updated successfully.');
        } else {
            Session::flash('error', 'Failed to update profile.');
        }
        $this->redirect('/profile');
    }

    public function updateAvatar() {
        \App\Middleware\RoleMiddleware::requirePermission('profile.edit');
        
        $csrf_token = $_POST['csrf_token'] ?? '';
        if (!CSRF::verify($csrf_token)) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/profile');
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['avatar']['tmp_name'];
            $fileName = $_FILES['avatar']['name'];
            $fileSize = $_FILES['avatar']['size'];
            $fileType = $_FILES['avatar']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedExtensions = ['jpg', 'gif', 'png', 'jpeg'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadFileDir = __DIR__ . '/../../public/uploads/avatars/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $userId = Session::get('user_id');
                    $this->userModel->updateAvatar($userId, 'uploads/avatars/' . $newFileName);
                    Session::flash('success', 'Avatar updated successfully.');
                } else {
                    Session::flash('error', 'Error moving the uploaded file.');
                }
            } else {
                Session::flash('error', 'Upload failed. Allowed types: ' . implode(',', $allowedExtensions));
            }
        }
        $this->redirect('/profile');
    }

    public function updatePassword() {
        \App\Middleware\RoleMiddleware::requirePermission('profile.edit');
        
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $csrf_token = $_POST['csrf_token'] ?? '';

        if (!CSRF::verify($csrf_token)) {
            Session::flash('error', 'Invalid CSRF token.');
            $this->redirect('/profile');
        }

        $userId = Session::get('user_id');
        $user = $this->userModel->findById($userId);

        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $this->userModel->updatePassword($userId, $new_password);
                Session::flash('success', 'Password updated successfully.');
            } else {
                Session::flash('error', 'New passwords do not match.');
            }
        } else {
            Session::flash('error', 'Current password is incorrect.');
        }
        $this->redirect('/profile');
    }
}
