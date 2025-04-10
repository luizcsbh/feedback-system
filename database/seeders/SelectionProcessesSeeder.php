<?php

namespace Database\Seeders;

use App\Models\SelectionProcess;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SelectionProcessesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $processes = [
            [
                'title' => 'Desenvolvedor Backend PHP Pleno',
                'description' => 'Processo seletivo para vaga de desenvolvedor backend PHP com experiência em Laravel.',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(15),
                'is_active' => true,
            ],
            [
                'title' => 'Analista de Dados Sênior',
                'description' => 'Seleção para analista de dados com conhecimento em Python, SQL e ferramentas de BI.',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'is_active' => true,
            ],
            [
                'title' => 'Designer UX/UI',
                'description' => 'Processo para designer de experiência do usuário com portfólio comprovado.',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(10),
                'is_active' => true,
            ],
            [
                'title' => 'Gerente de Projetos',
                'description' => 'Seleção para gerente de projetos com certificação PMP e experiência em metodologias ágeis.',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(30),
                'is_active' => false,
            ],
            [
                'title' => 'Estágio em Marketing Digital',
                'description' => 'Processo seletivo para estágio na área de marketing digital.',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'is_active' => true,
            ],
        ];

        foreach ($processes as $process) {
            SelectionProcess::create($process);
        }

        $this->command->info('Selection processes seeded successfully!');
    }
}