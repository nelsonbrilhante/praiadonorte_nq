<?php

namespace App\Filament\Resources\Configuracoes\Schemas;

use App\Models\ActivityLog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registo')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Data')
                            ->dateTime('d/m/Y H:i:s'),
                        TextEntry::make('description')
                            ->label('Descrição'),
                        TextEntry::make('causer.name')
                            ->label('Utilizador')
                            ->placeholder('Sistema'),
                        TextEntry::make('causer.email')
                            ->label('Email')
                            ->placeholder('—'),
                        TextEntry::make('log_name')
                            ->label('Categoria')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'auth' => 'Autenticação',
                                'deploy' => 'Deploy',
                                'default', null => 'Conteúdo',
                                default => ucfirst($state),
                            }),
                        TextEntry::make('event')
                            ->label('Evento')
                            ->badge(),
                        TextEntry::make('subject_type')
                            ->label('Tipo de Conteúdo')
                            ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '—'),
                        TextEntry::make('subject_id')
                            ->label('ID do Conteúdo')
                            ->placeholder('—'),
                        TextEntry::make('ip_address')
                            ->label('Endereço IP')
                            ->placeholder('—'),
                        TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->placeholder('—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Alterações')
                    ->description('Valores antigos vs novos.')
                    ->schema([
                        TextEntry::make('attribute_changes')
                            ->label('')
                            ->formatStateUsing(fn ($state, ActivityLog $record): string => self::renderChanges($record))
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (ActivityLog $record): bool => ! empty(self::extractChanges($record))),
            ]);
    }

    protected static function extractChanges(ActivityLog $record): array
    {
        $changes = [];

        if ($record->attribute_changes) {
            $raw = is_array($record->attribute_changes)
                ? $record->attribute_changes
                : $record->attribute_changes->toArray();
            $changes = array_merge($changes, $raw);
        }

        if ($record->properties && ! empty($record->properties->toArray())) {
            $changes['_properties'] = $record->properties->toArray();
        }

        return $changes;
    }

    protected static function renderChanges(ActivityLog $record): string
    {
        $changes = self::extractChanges($record);
        $old = $changes['old'] ?? [];
        $new = $changes['attributes'] ?? [];
        $customProps = $changes['_properties'] ?? [];

        $sections = [];

        // Model changes (old vs new)
        if (! empty($old) || ! empty($new)) {
            $sections[] = self::renderDiffTable($old, $new);
        }

        // Custom properties (auth, deploy, etc.)
        if (! empty($customProps)) {
            $sections[] = '<div class="mt-4"><h4 class="text-sm font-semibold mb-2">Propriedades adicionais</h4>'
                . '<pre class="text-xs bg-gray-100 dark:bg-gray-800 p-3 rounded overflow-auto">'
                . htmlspecialchars(json_encode($customProps, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8')
                . '</pre></div>';
        }

        if (empty($sections)) {
            return '<em>Sem detalhes.</em>';
        }

        return implode('', $sections);
    }

    protected static function renderDiffTable(array $old, array $new): string
    {

        $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
        sort($keys);

        $rows = [];
        foreach ($keys as $key) {
            $oldValue = self::formatValue($old[$key] ?? null);
            $newValue = self::formatValue($new[$key] ?? null);
            $rows[] = '<tr class="border-b border-gray-200 dark:border-gray-700">'
                . '<td class="px-3 py-2 align-top font-semibold text-xs text-gray-600 dark:text-gray-300 whitespace-nowrap">' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '</td>'
                . '<td class="px-3 py-2 align-top text-xs text-red-700 dark:text-red-400 break-all">' . $oldValue . '</td>'
                . '<td class="px-3 py-2 align-top text-xs text-green-700 dark:text-green-400 break-all">' . $newValue . '</td>'
                . '</tr>';
        }

        return '<table class="w-full text-left border-collapse">'
            . '<thead class="bg-gray-50 dark:bg-gray-800">'
            . '<tr>'
            . '<th class="px-3 py-2 text-xs font-bold uppercase text-gray-500">Campo</th>'
            . '<th class="px-3 py-2 text-xs font-bold uppercase text-red-600">Antes</th>'
            . '<th class="px-3 py-2 text-xs font-bold uppercase text-green-600">Depois</th>'
            . '</tr>'
            . '</thead>'
            . '<tbody>'
            . implode('', $rows)
            . '</tbody>'
            . '</table>';
    }

    protected static function formatValue(mixed $value): string
    {
        if ($value === null) {
            return '<em class="text-gray-400">—</em>';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return '<pre class="text-xs whitespace-pre-wrap">' . htmlspecialchars(
                json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ENT_QUOTES,
                'UTF-8'
            ) . '</pre>';
        }

        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
