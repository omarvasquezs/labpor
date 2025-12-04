<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionLogResource\Pages;
use App\Filament\Resources\ProductionLogResource\RelationManagers;
use App\Models\ProductionLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionLogResource extends Resource
{
    protected static ?string $model = ProductionLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Registro de Producción';
    protected static ?string $pluralModelLabel = 'Registros de Producción';
    protected static ?string $navigationLabel = 'Producción';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('production_stage_id')
                    ->label('Etapa de Producción')
                    ->relationship('productionStage', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('operator_id')
                    ->label('Operador')
                    ->relationship('operator', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('machine_name')
                    ->label('Máquina')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('good_quantity')
                    ->label('Cantidad Buena')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('waste_quantity')
                    ->label('Merma')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('productionStage.name')
                    ->label('Etapa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('operator.name')
                    ->label('Operador')
                    ->sortable(),
                Tables\Columns\TextColumn::make('machine_name')
                    ->label('Máquina')
                    ->searchable(),
                Tables\Columns\TextColumn::make('good_quantity')
                    ->label('Buenos')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waste_quantity')
                    ->label('Merma')
                    ->numeric()
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
            'index' => Pages\ListProductionLogs::route('/'),
            'create' => Pages\CreateProductionLog::route('/create'),
            'edit' => Pages\EditProductionLog::route('/{record}/edit'),
        ];
    }
}
