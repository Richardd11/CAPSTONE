<?php

namespace App\Core;

class View
{
    private $viewsPath;
    private $data = [];

    public function __construct($viewsPath = null)
    {
        $this->viewsPath = $viewsPath ?: __DIR__ . '/../Views/';
    }

    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function render($view, $data = [])
    {
        $data = array_merge($this->data, $data);
        extract($data);
        ob_start();
        $viewFile = $this->viewsPath . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new \Exception("View file not found: {$viewFile}");
        }
        $content = ob_get_clean();
        if (isset($layout)) {
            return $this->renderWithLayout($layout, $content, $data);
        }
        return $content;
    }

    private function renderWithLayout($layout, $content, $data = [])
    {
        $data['content'] = $content;
        extract($data);
        ob_start();
        $layoutFile = $this->viewsPath . 'layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            throw new \Exception("Layout file not found: {$layoutFile}");
        }
        return ob_get_clean();
    }

    public static function make($view, $data = [])
    {
        $instance = new static();
        return $instance->render($view, $data);
    }

    public function display($view, $data = [])
    {
        $content = $this->render($view, $data);
        if (isset($data['layout']) || (isset($GLOBALS['layout']) && $GLOBALS['layout'])) {
            $layout = $data['layout'] ?? $GLOBALS['layout'];
            $data['content'] = $content;
            echo $this->renderWithLayout($layout, $content, $data);
        } else {
            echo $content;
        }
    }

    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public static function redirect($url, $statusCode = 302)
    {
        http_response_code($statusCode);
        header("Location: {$url}");
        exit;
    }
}

