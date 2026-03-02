<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['pt' => 'Contratações In-House', 'en' => 'In-House Hiring'],
            ['pt' => 'Contratos-Programa', 'en' => 'Program Contracts'],
            ['pt' => 'Estatutos da Empresa', 'en' => 'Company Bylaws'],
            ['pt' => 'Estrutura do Capital Social', 'en' => 'Capital Structure'],
            ['pt' => 'Execuções Orçamentais', 'en' => 'Budget Executions'],
            ['pt' => 'Montantes Auferidos Pelos Membros Remunerados dos Órgãos Sociais', 'en' => 'Board Member Compensation'],
            ['pt' => 'Número de Trabalhadores por Modalidade de Vinculação', 'en' => 'Employee Count by Contract Type'],
            ['pt' => 'Orçamentos e Plano de Atividades', 'en' => 'Budgets & Activity Plans'],
            ['pt' => 'Outros', 'en' => 'Other'],
            ['pt' => 'Pareceres (al. a) a c) do n.º 6 do art. 25.º)', 'en' => 'Required Opinions (Art. 25.6)'],
            ['pt' => 'Plano de Prevenção de Riscos de Corrupção', 'en' => 'Corruption Risk Prevention Plan'],
            ['pt' => 'Prestação de Contas Anuais', 'en' => 'Annual Financial Statements'],
            ['pt' => 'Prestação de Contas Semestrais', 'en' => 'Semi-Annual Financial Statements'],
        ];

        foreach ($categories as $index => $category) {
            DocumentCategory::updateOrCreate(
                ['slug' => Str::slug($category['pt'])],
                [
                    'name' => $category,
                    'order' => $index + 1,
                ]
            );
        }
    }
}
