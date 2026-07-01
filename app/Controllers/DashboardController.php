<?php

class DashboardController
{
    public function resumo(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'indicadores' => [
                'total_pessoas'      => 0,
                'total_tipos'        => 0,
                'total_atendimentos' => 0
            ],
            'atendimentos_recentes' => []
        ]);
    }
}