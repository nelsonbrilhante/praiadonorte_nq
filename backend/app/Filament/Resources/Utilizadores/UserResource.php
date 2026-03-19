<?php

namespace App\Filament\Resources\Utilizadores;

use App\Enums\Role;
use App\Filament\Resources\Utilizadores\Pages\CreateUser;
use App\Filament\Resources\Utilizadores\Pages\EditUser;
use App\Filament\Resources\Utilizadores\Pages\ListUsers;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?int $navigationSort = 997;

    protected static ?string $modelLabel = 'Utilizador';

    protected static ?string $pluralModelLabel = 'Utilizadores';

    public static function canAccess(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Dados')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->minLength(8)
                            ->maxLength(255),
                    ]),

                Section::make('Permissões')
                    ->schema([
                        Select::make('role')
                            ->label('Perfil')
                            ->options(collect(Role::cases())->mapWithKeys(fn (Role $role) => [$role->value => $role->label()]))
                            ->required()
                            ->default('editor')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state !== Role::EntityEditor->value) {
                                    $set('entities', null);
                                }
                            }),
                        CheckboxList::make('entities')
                            ->label('Entidades')
                            ->options([
                                'praia-norte' => 'Praia do Norte',
                                'carsurf' => 'Carsurf',
                                'nazare-qualifica' => 'Nazaré Qualifica',
                            ])
                            ->visible(fn (callable $get): bool => $get('role') === Role::EntityEditor->value)
                            ->requiredIf('role', Role::EntityEditor->value)
                            ->helperText('Selecione as entidades a que este utilizador tem acesso.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label('Perfil')
                    ->badge()
                    ->formatStateUsing(fn (Role $state): string => $state->label())
                    ->color(fn (Role $state): string => $state->color())
                    ->sortable(),
                TextColumn::make('entities')
                    ->label('Entidades')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'praia-norte' => 'Praia do Norte',
                        'carsurf' => 'Carsurf',
                        'nazare-qualifica' => 'Nazaré Qualifica',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'praia-norte' => 'info',
                        'carsurf' => 'success',
                        'nazare-qualifica' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (User $record): bool => $record->id === auth()->id()),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
