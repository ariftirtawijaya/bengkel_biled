<?php
declare(strict_types=1);

class HomeController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => 'Home',
        ];

        $this->view('home/index', $data);
    }
}