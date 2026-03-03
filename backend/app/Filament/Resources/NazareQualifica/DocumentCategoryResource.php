<?php

namespace App\Filament\Resources\NazareQualifica;

use App\Filament\Resources\NazareQualifica\DocumentCategoryResource\Pages;
use App\Models\DocumentCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DocumentCategoryResource extends Resource
{
    protected static ?string $model = DocumentCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-folder';

    protected static string|\UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Categorias de Documentos';

    protected static ?string $modelLabel = 'Categoria';

    protected static ?string $pluralModelLabel = 'Categorias de Documentos';

    protected static ?string $slug = 'nazare-qualifica/categorias-documentos';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name.pt')
                                ->label('Nome (PT)')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) =>
                                    $set('slug', Str::slug($state))),
                            TextInput::make('name.en')
                                ->label('Name (EN)')
                                ->maxLength(255),
                        ]),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Grid::make(2)->schema([
                            Textarea::make('description.pt')
                                ->label('Descrição (PT)')
                                ->rows(3),
                            Textarea::make('description.en')
                                ->label('Description (EN)')
                                ->rows(3),
                        ]),
                        TextInput::make('order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name.pt')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('documents_count')
                    ->label('Documentos')
                    ->counts('documents')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->reorderRecordsTriggerAction(
                fn (\Filament\Actions\Action $action, bool $isReordering) => $action
                    ->label($isReordering ? 'Guardar' : 'Reordenar')
                    ->button()
            )
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DocumentCategory $record) {
                        if ($record->documents()->exists()) {
                            throw new \Exception('Não é possível eliminar uma categoria com documentos.');
                        }
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocumentCategories::route('/'),
            'create' => Pages\CreateDocumentCategory::route('/create'),
            'edit' => Pages\EditDocumentCategory::route('/{record}/edit'),
        ];
    }
}
