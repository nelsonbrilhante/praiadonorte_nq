<?php

namespace App\Filament\Resources\Geral\Pages;

use App\Filament\Resources\Geral\HomepageResource;
use App\Models\HeroSlide;
use Filament\Resources\Pages\EditRecord;

class EditHomepage extends EditRecord
{
    protected static string $resource = HomepageResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        // Backup: ensure any orphaned slides are deleted on save
        $submittedSlideIds = collect($this->data['heroSlides'] ?? [])
            ->pluck('id')
            ->filter()
            ->toArray();

        HeroSlide::where('pagina_id', $this->record->id)
            ->whereNotIn('id', $submittedSlideIds)
            ->delete();
    }
}
