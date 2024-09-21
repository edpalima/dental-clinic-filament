<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Filament\Resources\AnnouncementResource\RelationManagers;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use Illuminate\Support\Facades\Auth;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationIcon = 'heroicon-o-check';
    protected static ?string $slug = 'announcements/active';
    protected static ?string $navigationLabel = 'Active';
    protected static ?string $navigationGroup = 'Announcements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),
                Forms\Components\DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->required(),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->maxSize(1024) // Limit size to 1MB
                    ->directory('announcements')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Is Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('description')
                    ->sortable()
                    ->searchable()
                    ->limit(50),
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')
                    ->square()
                    ->size(50),
                Tables\Columns\TextColumn::make('start_date')
                    ->sortable()
                    ->searchable()
                    ->label('Start Date')
                    ->dateTime('F j, Y, g:i a') // Display date in a human-readable format
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->sortable()
                    ->searchable()
                    ->label('End Date')
                    ->dateTime('F j, Y, g:i a') // Display date in a human-readable format
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->boolean(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === User::ROLE_ADMIN;
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $currentDateTime = Carbon::now();

        return parent::getEloquentQuery()
            ->where('start_date', '<=', $currentDateTime)
            ->where('end_date', '>=', $currentDateTime)
            ->where('is_active', true);
    }
}
