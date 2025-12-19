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

    public static function canCreate(): bool
    {
        return ! auth()->user()->isDesigner();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->orderBy('created_at', 'desc');

        // Client restriction removed per user request. They can see all but not edit.
        
        if (auth()->user()->isDesigner()) {
            return $query->where('designer_id', auth()->id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('id')
                    ->label('N° Solicitud')
                    ->content(fn ($record) => $record?->id ? '#' . $record->id : '-')
                    ->hidden(fn ($record) => $record === null),
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'code')
                    ->label('Orden')
                    ->required(),
                Forms\Components\Select::make('designer_id')
                    ->relationship('designer', 'name', fn (Builder $query) => $query->where('role', 'designer'))
                    ->label('Diseñador')
                    ->default(fn () => auth()->user()->isDesigner() ? auth()->id() : null),
                Forms\Components\Select::make('status')
                    ->label('Estado')
                    ->options([
                        'PENDING' => 'Pendiente',
                        'IN_PROGRESS' => 'En Progreso',
                        'APPROVED' => 'Aprobado',
                        'OBSERVED' => 'Observado',
                    ])
                    ->required()
                    ->default('PENDING')
                    ->hidden(fn (string $context) => auth()->user()->isClient() && $context === 'create'), // Use closure for check
                Forms\Components\Textarea::make('rejection_reason')
                    ->label('Observaciones / Razón de rechazo')
                    ->columnSpanFull()
                    ->visible(fn ($get) => $get('status') === 'OBSERVED') // Visible if observed
                    ->disabled(fn () => auth()->user()->isClient()) // Read-only for clients
                    ->required(fn ($get) => $get('status') === 'OBSERVED'),
                Forms\Components\DateTimePicker::make('started_at')
                    ->label('Iniciado')
                    ->hidden(fn (string $context) => auth()->user()->isClient() && $context === 'create'),
                Forms\Components\DateTimePicker::make('approved_at')
                    ->label('Aprobado')
                    ->hidden(fn (string $context) => auth()->user()->isClient() && $context === 'create'),
                Forms\Components\FileUpload::make('attachments')
                    ->label('Artes (Adjuntos)')
                    ->multiple()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->directory('art-attachments'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N° Solicitud')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('order.code')
                    ->label('Orden')
                    ->sortable(),
                Tables\Columns\TextColumn::make('designer.name')
                    ->label('Diseñador')
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
                Tables\Actions\EditAction::make()
                    ->hidden(fn () => auth()->user()->isClient()),
                Tables\Actions\Action::make('view_attachments')
                    ->label('Ver Artes')
                    ->icon('heroicon-o-photo')
                    ->modalContent(fn ($record) => view('art-requests.gallery', ['record' => $record]))
                    ->modalWidth('4xl')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(fn ($action) => $action->label('Cerrar')),
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
