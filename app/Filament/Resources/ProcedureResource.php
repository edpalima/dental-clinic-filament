<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcedureResource\Pages;
use App\Filament\Resources\ProcedureResource\RelationManagers;
use App\Models\Procedure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProcedureResource extends Resource
{
    protected static ?string $model = Procedure::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('short_description')
                    ->required()
                    ->maxLength(30),

                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('cost')
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('cant_combine')
                    ->label('Can\'t Combine With')
                    ->options(fn() => Procedure::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->placeholder('Select')
                    ->reactive(),

                Forms\Components\Select::make('duration')
                    ->required()
                    ->options([
                        1 => '1 hour',
                        2 => '2 hours',
                        3 => '3 hours',
                    ])
                    ->placeholder('Select procedure time'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->extraAttributes([
                        'style' => 'max-width: 350px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;',
                    ])
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state . ' hour' . ($state > 1 ? 's' : ''))
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->formatStateUsing(fn($state) => number_format($state, 2))
                    ->sortable(),
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
                // Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListProcedures::route('/'),
            // 'create' => Pages\CreateProcedure::route('/create'),
            // 'view' => Pages\ViewProcedure::route('/{record}'),
            // 'edit' => Pages\EditProcedure::route('/{record}/edit'),
        ];
    }
}
