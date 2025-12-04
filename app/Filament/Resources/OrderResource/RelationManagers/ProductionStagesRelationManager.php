<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductionStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'productionStages';

    protected static ?string $title = 'Etapas de Producción';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'PENDING' => 'Pendiente',
                        'IN_PROGRESS' => 'En Progreso',
                        'COMPLETED' => 'Completado',
                    ])
                    ->required()
                    ->default('PENDING')
                    ->rules([
                        fn (\Filament\Forms\Get $get, $record) => function (string $attribute, $value, \Closure $fail) use ($get, $record) {
                            if ($value === 'IN_PROGRESS' && $record && $record->name === 'IMPRESION') {
                                $order = $record->order;
                                $artRequest = $order->artRequests()->latest()->first();
                                if (!$artRequest || $artRequest->status !== 'APPROVED') {
                                    $fail("No se puede iniciar IMPRESION porque el Arte no está APROBADO.");
                                }
                            }
                        },
                    ]),
                Forms\Components\Toggle::make('is_blocked')
                    ->label('Bloqueado')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Etapa'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'PENDING' => 'Pendiente',
                        'IN_PROGRESS' => 'En Progreso',
                        'COMPLETED' => 'Completado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'PENDING' => 'gray',
                        'IN_PROGRESS' => 'warning',
                        'COMPLETED' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Bloqueado')
                    ->boolean(),
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
