<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2) // Create a grid with 2 columns
                    ->schema([
                        // First column: Documentation
                        Forms\Components\Placeholder::make('documentation')
                            ->label('Chart')
                            ->content(new HtmlString('<img src="/assets/img/tooth-chart.png"/>'))
                            ->columnSpan(1), // Span only one column width

                        // Second column: Tooth Number and Procedure ID
                        Forms\Components\Grid::make(1) // Nested grid for the second column
                            ->schema([
                                Forms\Components\Select::make('tooth_number')
                                    ->label('Tooth Number')
                                    ->options(range(1, 32)) // Options from 1 to 32
                                    ->required()
                                    ->placeholder('Select a tooth number'),

                                Forms\Components\Select::make('procedure_id')
                                    ->relationship(name: 'procedure', titleAttribute: 'name')
                                    ->required()
                                    ->placeholder('Select a procedure'),
                            ])
                            ->columnSpan(1), // Span the remaining column width
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tooth_number')
            ->columns([
                Tables\Columns\TextColumn::make('tooth_number'),
                Tables\Columns\TextColumn::make('procedure.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
