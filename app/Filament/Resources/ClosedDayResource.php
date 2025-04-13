<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClosedDayResource\Pages;
use App\Filament\Resources\ClosedDayResource\RelationManagers;
use App\Models\ClosedDay;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClosedDayResource extends Resource
{
    protected static ?string $model = ClosedDay::class;
    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Specific Closed Date')
                    ->helperText('Optional: Leave blank if using Repeat Day'),

                Forms\Components\Select::make('repeat_day')
                    ->label('Repeat Weekly')
                    ->options([
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                        'Sunday' => 'Sunday',
                    ])
                    ->helperText('Optional: Leave blank if using specific date'),

                Forms\Components\TextInput::make('reason')
                    ->label('Reason')
                    ->maxLength(255),

                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('repeat_day')
                    ->label('Repeat Day')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->reason),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
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
            'index' => Pages\ListClosedDays::route('/'),
            'create' => Pages\CreateClosedDay::route('/create'),
            'edit' => Pages\EditClosedDay::route('/{record}/edit'),
        ];
    }
}
