<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Details')->schema([
                    Forms\Components\FileUpload::make('photo')
                        ->image()
                        ->directory('user-photos')
                        ->maxSize(1024)
                        ->label('Photo')
                        ->nullable()
                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Hidden::make('role')
                        ->default('ADMIN'),
                    // Forms\Components\DateTimePicker::make('email_verified_at'),
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null) // Only hash if there's a value
                        ->dehydrated(fn($state) => !empty($state)) // Ignore the field if it's empty
                        ->visible(fn($livewire) => $livewire instanceof CreateUser) // Show only on create
                        ->rule(Password::default())
                        ->maxLength(255),
                    TextInput::make('role')
                        ->default('ADMIN')
                        ->password()
                        ->visible(false),
                ]),

                Section::make('User New Password')->schema([
                    TextInput::make('new_password')
                        ->nullable()
                        ->password()
                        ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : null) // Only hash if there's a value
                        ->dehydrated(fn($state) => !empty($state)), // Ignore the field if it's empty

                    TextInput::make('new_password_confirmation')
                        ->password()
                        ->same('new_password')
                        ->requiredWith('new_password'),
                ])->visible(fn($livewire) => $livewire instanceof EditUser)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->searchable(),
                // Tables\Columns\TextColumn::make('email_verified_at')
                //     ->dateTime()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
