<?php

namespace Database\Seeders;

use App\Models\CorporateBody;
use Illuminate\Database\Seeder;

class CorporateBodySeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'Álvaro Festas',
                'role' => ['pt' => 'Presidente', 'en' => 'President'],
                'section' => 'conselho_gerencia',
                'photo' => 'corporate-bodies/alvaro_festas.jpg',
                'cv_file' => 'corporate-bodies/cvs/nota-curricular-presidente-do-cg.pdf',
                'order' => 1,
            ],
            [
                'name' => 'Marco Carreira',
                'role' => ['pt' => '1º Vogal', 'en' => '1st Member'],
                'section' => 'conselho_gerencia',
                'photo' => 'corporate-bodies/marco_carreira.jpg',
                'cv_file' => 'corporate-bodies/cvs/nota-curricular-1-vogal-cg.pdf',
                'order' => 2,
            ],
            [
                'name' => 'Fátima Lourenço',
                'role' => ['pt' => '2º Vogal', 'en' => '2nd Member'],
                'section' => 'conselho_gerencia',
                'photo' => 'corporate-bodies/fatima_lourenco.jpg',
                'cv_file' => 'corporate-bodies/cvs/nota-curricular-2-vogal-cg.pdf',
                'order' => 3,
            ],
            [
                'name' => 'Joaquim Paulo',
                'role' => ['pt' => 'Presidente', 'en' => 'President'],
                'section' => 'assembleia_geral',
                'photo' => 'corporate-bodies/joaquim_paulo.jpg',
                'cv_file' => 'corporate-bodies/cvs/nota-curricular-jp.pdf',
                'order' => 4,
            ],
            [
                'name' => 'Mazars',
                'role' => [
                    'pt' => 'Sociedade Mazars & Associados - Sociedade de Revisores Oficiais de Contas, S.A - Henrique Oliveira',
                    'en' => 'Sociedade Mazars & Associados - Sociedade de Revisores Oficiais de Contas, S.A - Henrique Oliveira',
                ],
                'section' => 'fiscal_unico',
                'photo' => 'corporate-bodies/mazars.jpg',
                'cv_file' => null,
                'order' => 5,
            ],
        ];

        foreach ($members as $member) {
            CorporateBody::updateOrCreate(
                ['name' => $member['name'], 'section' => $member['section']],
                $member
            );
        }

        $this->command->info('CorporateBodySeeder: ' . count($members) . ' members seeded.');
    }
}
