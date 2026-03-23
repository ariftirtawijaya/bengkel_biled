<?php
declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => 'Dashboard',
            'summary' => [
                'produk' => 0,
                'customer' => 0,
                'work_order' => 0,
                'omzet' => 0,
            ]
        ];

        $this->view('dashboard/index', $data);
    }
}