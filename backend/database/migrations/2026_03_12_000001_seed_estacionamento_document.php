<?php

use App\Models\Pagina;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        // Copy existing PDF from public/ to storage/
        $source = public_path('documents/nq/regulamento-parque.pdf');
        $destination = 'documents/nq/regulamento-parque.pdf';

        if (file_exists($source)) {
            Storage::disk('public')->makeDirectory('documents/nq');
            Storage::disk('public')->put($destination, file_get_contents($source));
        }

        // Update the estacionamento page content with the document path
        $page = Pagina::where('entity', 'nazare-qualifica')
            ->where('slug', 'estacionamento')
            ->first();

        if ($page && Storage::disk('public')->exists($destination)) {
            $content = $page->content ?? [];
            $content['documents'] = ['regulamento' => $destination];
            $page->content = $content;
            $page->save();
        }
    }

    public function down(): void
    {
        $page = Pagina::where('entity', 'nazare-qualifica')
            ->where('slug', 'estacionamento')
            ->first();

        if ($page) {
            $content = $page->content ?? [];
            unset($content['documents']);
            $page->content = $content;
            $page->save();
        }
    }
};
