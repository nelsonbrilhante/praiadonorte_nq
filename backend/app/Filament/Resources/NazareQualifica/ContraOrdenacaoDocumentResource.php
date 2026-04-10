<?php

namespace App\Filament\Resources\NazareQualifica;

use App\Filament\Resources\NazareQualifica\ContraOrdenacaoDocumentResource\Pages;
use App\Models\ContraOrdenacaoDocument;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ContraOrdenacaoDocumentResource extends Resource
{
    public static function canAccess(): bool
    {
        return auth()->user()->canAccessEntity('nazare-qualifica');
    }

    protected static ?string $model = ContraOrdenacaoDocument::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static string|\UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Contra-Ordenações';

    protected static ?string $modelLabel = 'Documento de Contra-Ordenação';

    protected static ?string $pluralModelLabel = 'Documentos de Contra-Ordenação';

    protected static ?string $slug = 'nazare-qualifica/contra-ordenacoes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('title.pt')
                                ->label('Título (PT)')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('title.en')
                                ->label('Title (EN)')
                                ->maxLength(255),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('description.pt')
                                ->label('Descrição (PT)')
                                ->rows(3),
                            Textarea::make('description.en')
                                ->label('Description (EN)')
                                ->rows(3),
                        ]),
                        FileUpload::make('file')
                            ->label('Ficheiro PDF')
                            ->required()
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('documentos/contra-ordenacoes')
                            ->visibility('public')
                            ->maxSize(20480)
                            ->openable()
                            ->downloadable(),
                        Grid::make(3)->schema([
                            Select::make('icon')
                                ->label('Ícone')
                                ->options([
                                    'document' => 'Documento',
                                    'shield' => 'Escudo (Defesa)',
                                    'chat' => 'Mensagem (Reclamação)',
                                    'table' => 'Tabela (Taxas)',
                                    'stamp' => 'Carimbo (Despacho)',
                                ])
                                ->default('document'),
                            TextInput::make('order')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0),
                            DatePicker::make('published_at')
                                ->label('Data de Publicação')
                                ->native(false)
                                ->displayFormat('d/m/Y'),
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
                Tables\Columns\TextColumn::make('icon')
                    ->label('Ícone')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
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
            'index' => Pages\ListContraOrdenacaoDocuments::route('/'),
            'create' => Pages\CreateContraOrdenacaoDocument::route('/create'),
            'edit' => Pages\EditContraOrdenacaoDocument::route('/{record}/edit'),
        ];
    }
}
