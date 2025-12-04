<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArtRequestResource\Pages;
use App\Filament\Resources\ArtRequestResource\RelationManagers;
use App\Models\ArtRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArtRequestResource extends Resource
{
    protected static ?string $model = ArtRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Solicitud de Arte';
    protected static ?string $pluralModelLabel = 'Solicitudes de Arte';
    protected static ?string $navigationLabel = 'Solicitudes de Arte';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'code')
                    ->label('Orden')
                    ->required(),
                Forms\Components\Select::make('designer_id')
                    ->relationship('designer', 'name')
                    ->label('Diseñador'),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'PENDING' => 'Pendiente',
                        'IN_PROGRESS' => 'En Progreso',
                        'APPROVED' => 'Aprobado',
                        'OBSERVED' => 'Observado',
                    ])
                    ->required()
                    ->default('PENDING'),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Iniciado'),
                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Aprobado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.code')
                    ->label('Orden')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('designer.name')
                    ->label('Diseñador')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'PENDING' => 'Pendiente',
                        'IN_PROGRESS' => 'En Progreso',
                        'APPROVED' => 'Aprobado',
                        'OBSERVED' => 'Observado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'gray',
                        'IN_PROGRESS' => 'warning',
                        'APPROVED' => 'success',
                        'OBSERVED' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Iniciado')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Aprobado')
                    ->dateTime()
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
            'index' => Pages\ListArtRequests::route('/'),
            'create' => Pages\CreateArtRequest::route('/create'),
            'edit' => Pages\EditArtRequest::route('/{record}/edit'),
        ];
    }
}
