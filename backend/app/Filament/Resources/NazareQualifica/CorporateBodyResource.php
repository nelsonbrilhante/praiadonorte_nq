<?php

namespace App\Filament\Resources\NazareQualifica;

use App\Filament\Resources\NazareQualifica\CorporateBodyResource\Pages;
use App\Models\CorporateBody;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

class CorporateBodyResource extends Resource
{
    protected static ?string $model = CorporateBody::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|\UnitEnum|null $navigationGroup = 'Nazaré Qualifica';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Corpos Sociais';

    protected static ?string $modelLabel = 'Membro';

    protected static ?string $pluralModelLabel = 'Corpos Sociais';

    protected static ?string $slug = 'nazare-qualifica/corpos-sociais';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informações')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        Grid::make(2)->schema([
                            TextInput::make('role.pt')
                                ->label('Cargo (PT)')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('role.en')
                                ->label('Role (EN)')
                                ->maxLength(255),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('section')
                                ->label('Secção')
                                ->options([
                                    'conselho_gerencia' => 'Conselho de Gerência',
                                    'assembleia_geral' => 'Assembleia Geral',
                                    'fiscal_unico' => 'Fiscal Único',
                                ])
                                ->required(),
                            TextInput::make('order')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0),
                        ]),
                    ]),
                Section::make('Ficheiros')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Fotografia')
                            ->image()
                            ->disk('public')
                            ->directory('corporate-bodies')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:4')
                            ->imageResizeTargetWidth('600')
                            ->imageResizeTargetHeight('800'),
                        FileUpload::make('cv_file')
                            ->label('Nota Curricular (PDF)')
                            ->acceptedFileTypes(['application/pdf'])
                            ->disk('public')
                            ->directory('corporate-bodies/cvs')
                            ->maxSize(10240)
                            ->openable()
                            ->downloadable(),
                    ]),
                Section::make('Publicação')
                    ->schema([
                        Toggle::make('published')
                            ->label('Publicado')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn () => 'data:image/svg+xml,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%231e3a5f"><circle cx="12" cy="8" r="4"/><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/></svg>')),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role.pt')
                    ->label('Cargo')
                    ->sortable(),
                Tables\Columns\TextColumn::make('section')
                    ->label('Secção')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'conselho_gerencia' => 'Conselho de Gerência',
                        'assembleia_geral' => 'Assembleia Geral',
                        'fiscal_unico' => 'Fiscal Único',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'conselho_gerencia' => 'primary',
                        'assembleia_geral' => 'success',
                        'fiscal_unico' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('published')
                    ->label('Publicado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Ordem')
                    ->sortable(),
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
            'index' => Pages\ListCorporateBodies::route('/'),
            'create' => Pages\CreateCorporateBody::route('/create'),
            'edit' => Pages\EditCorporateBody::route('/{record}/edit'),
        ];
    }
}
