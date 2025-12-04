<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ArtRequestsRelationManager extends RelationManager
{
    protected static string $relationship = 'artRequests';

    protected static ?string $title = 'Solicitudes de Arte';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('designer_id')
                    ->label('Diseñador')
                    ->relationship('designer', 'name')
                    ->searchable()
                    ->preload(),
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('designer.name')
                    ->label('Diseñador'),
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
                    }),
                Tables\Columns\TextColumn::make('approved_at')
                    ->label('Fecha Aprobación')
                    ->dateTime(),
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
