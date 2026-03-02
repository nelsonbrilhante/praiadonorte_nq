<?php

namespace App\Filament\Resources\NazareQualifica;

use App\Filament\Resources\NazareQualifica\DocumentResource\Pages;
use App\Models\Document;
use App\Models\DocumentCategory;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-down';

    protected static string|\UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Documentos';

    protected static ?string $modelLabel = 'Documento';

    protected static ?string $pluralModelLabel = 'Documentos';

    protected static ?string $slug = 'nazare-qualifica/documentos';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações')
                    ->schema([
                        Select::make('document_category_id')
                            ->label('Categoria')
                            ->relationship('category', 'name->pt')
                            ->getOptionLabelFromRecordUsing(fn (DocumentCategory $record) => $record->name['pt'] ?? '')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Grid::make(2)->schema([
                            TextInput::make('title.pt')
                                ->label('Título (PT)')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('title.en')
                                ->label('Title (EN)')
                                ->maxLength(255),
                        ]),
                        FileUpload::make('file')
                            ->label('Ficheiro PDF')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('documentos')
                            ->maxSize(20480)
                            ->openable()
                            ->downloadable(),
                        Grid::make(2)->schema([
                            DatePicker::make('published_at')
                                ->label('Data de Publicação')
                                ->native(false)
                                ->displayFormat('d/m/Y'),
                            TextInput::make('order')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title.pt')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoria')
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state['pt'] ?? '') : $state)
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicação')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('document_category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name->pt')
                    ->getOptionLabelFromRecordUsing(fn (DocumentCategory $record) => $record->name['pt'] ?? '')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
