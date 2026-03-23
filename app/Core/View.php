<?php
declare(strict_types=1);

class View
{
    public static function render(string $view, array $data = [], string $layout = 'app'): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            die("View '{$view}' tidak ditemukan.");
        }

        if (!file_exists($layoutFile)) {
            die("Layout '{$layout}' tidak ditemukan.");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }
}