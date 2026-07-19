<?php

namespace Core;

abstract class Controller {
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../app/Views/" . str_replace('.', '/', $view) . ".php";
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View $view not found.");
        }
    }

    protected function redirect($url) {
        header("Location: " . ($_ENV['APP_URL'] ?? '') . $url);
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
