<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\SelectionProcess;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class CandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        $processes = SelectionProcess::all();

        if ($processes->isEmpty()) {
            $this->command->warn('Nenhum processo seletivo encontrado. Execute o SelectionProcessesSeeder primeiro.');
            return;
        }

        $statuses = ['pending', 'approved', 'rejected'];
        $fakeResumes = [
            "Experiência em desenvolvimento web com PHP e Laravel. Formação em Ciência da Computação.",
            "5 anos de experiência como analista de dados. Domínio de Python, SQL e Power BI.",
            "Designer com 3 anos de experiência em UX/UI. Portfólio disponível em meu website.",
            "Gerente de projetos certificado PMP com experiência em metodologias ágeis.",
            "Estudante de Marketing com conhecimentos em SEO e mídias sociais."
        ];

        foreach ($processes as $process) {
            $candidatesCount = rand(5, 15); // Entre 5 e 15 candidatos por processo

            for ($i = 0; $i < $candidatesCount; $i++) {
                $status = $statuses[rand(0, 2)];
                
                // Para processos encerrados, não deixar como 'pending'
                if ($process->end_date < now() && $status === 'pending') {
                    $status = $statuses[rand(1, 2)]; // Apenas approved ou rejected
                }

                Candidate::create([
                    'process_id' => $process->id,
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'phone' => rand(0, 1) ? $faker->phoneNumber : null, // 50% chance de ter telefone
                    'resume' => $fakeResumes[array_rand($fakeResumes)],
                    'status' => $status,
                ]);
            }
        }

        $this->command->info('Candidates seeded successfully!');
    }
}