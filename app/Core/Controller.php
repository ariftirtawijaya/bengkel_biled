<?php
declare(strict_types=1);

class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'app'): void
    {
        View::render($view, $data, $layout);
    }
}