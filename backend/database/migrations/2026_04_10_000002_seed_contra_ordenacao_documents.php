<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        $sourceDir = public_path('documents/nq');
        $targetDir = 'documentos/contra-ordenacoes';

        Storage::disk('public')->makeDirectory($targetDir);

        $documents = [
            [
                'title' => json_encode(['pt' => 'Requerimento', 'en' => 'Request Form']),
                'description' => json_encode(['pt' => 'Formulário geral para pedidos e requerimentos à Nazaré Qualifica.', 'en' => 'General form for requests and applications to Nazaré Qualifica.']),
                'source_file' => 'requerimento.pdf',
                'icon' => 'document',
                'order' => 1,
            ],
            [
                'title' => json_encode(['pt' => 'Formulário de Apresentação de Defesa', 'en' => 'Defense Submission Form']),
                'description' => json_encode(['pt' => 'Utilize este formulário para apresentar a sua defesa em processos de contraordenação.', 'en' => 'Use this form to submit your defense in traffic violation proceedings.']),
                'source_file' => 'formulario-apresentacao-defesa.pdf',
                'icon' => 'shield',
                'order' => 2,
            ],
            [
                'title' => json_encode(['pt' => 'Reclamação / Pedido de Esclarecimento', 'en' => 'Complaint / Clarification Request']),
                'description' => json_encode(['pt' => 'Formulário para reclamações ou pedidos de esclarecimento sobre contraordenações.', 'en' => 'Form for complaints or clarification requests regarding traffic violations.']),
                'source_file' => 'formulario-reclamacao.pdf',
                'icon' => 'chat',
                'order' => 3,
            ],
            [
                'title' => json_encode(['pt' => 'Tabela de Taxas I', 'en' => 'Fee Schedule I']),
                'description' => json_encode(['pt' => 'Tabela de taxas aplicáveis a contraordenações de estacionamento.', 'en' => 'Fee schedule applicable to parking violations.']),
                'source_file' => 'tabela-taxas-1.pdf',
                'icon' => 'table',
                'order' => 4,
            ],
            [
                'title' => json_encode(['pt' => 'Tabela de Taxas II', 'en' => 'Fee Schedule II']),
                'description' => json_encode(['pt' => 'Tabela de taxas aplicáveis a outras contraordenações municipais.', 'en' => 'Fee schedule applicable to other municipal violations.']),
                'source_file' => 'tabela-taxas-2.pdf',
                'icon' => 'table',
                'order' => 5,
            ],
            [
                'title' => json_encode(['pt' => 'Despacho de Subdelegação de Competências', 'en' => 'Competency Delegation Order']),
                'description' => json_encode(['pt' => 'Documento oficial de delegação de competências para processos de contraordenação.', 'en' => 'Official document delegating authority for traffic violation proceedings.']),
                'source_file' => 'despacho-subdelegacao.pdf',
                'icon' => 'stamp',
                'order' => 6,
            ],
        ];

        $now = now();

        foreach ($documents as $doc) {
            $sourcePath = $sourceDir . '/' . $doc['source_file'];
            $storagePath = $targetDir . '/' . $doc['source_file'];

            if (File::exists($sourcePath)) {
                Storage::disk('public')->put($storagePath, File::get($sourcePath));
            }

            DB::table('contra_ordenacao_documents')->insert([
                'title' => $doc['title'],
                'description' => $doc['description'],
                'file' => $storagePath,
                'icon' => $doc['icon'],
                'order' => $doc['order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('contra_ordenacao_documents')->truncate();
        Storage::disk('public')->deleteDirectory('documentos/contra-ordenacoes');
    }
};
