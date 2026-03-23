<?php
declare(strict_types=1);

class DashboardController extends Controller
{
    public function index(): void
    {
        $model = new DashboardModel();

        $data = [
            'title' => 'Dashboard',
            'summary' => $model->getSummary(),
        ];

        $this->view('dashboard/index', $data);
    }
}