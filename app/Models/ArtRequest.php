<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtRequest extends Model
{
    protected $fillable = [
        'order_id',
        'designer_id',
        'status',
        'started_at',
        'approved_at',
        'attachments',
        'rejection_reason',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function designer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    protected static function booted(): void
    {
        static::updated(function (ArtRequest $artRequest) {
            if ($artRequest->isDirty('designer_id') && $artRequest->designer_id) {
                \Filament\Notifications\Notification::make()
                    ->title('Nueva Solicitud Asignada')
                    ->body("Se te ha asignado la solicitud de la orden #{$artRequest->order->code}")
                    ->icon('heroicon-o-pencil-square')
                    ->iconColor('success')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->label('Ver Solicitud')
                            ->url(\App\Filament\Resources\ArtRequestResource::getUrl('edit', ['record' => $artRequest])),
                    ])
                    ->sendToDatabase(User::find($artRequest->designer_id));
            }
        });

        static::created(function (ArtRequest $artRequest) {
             if ($artRequest->designer_id) {
                \Filament\Notifications\Notification::make()
                    ->title('Nueva Solicitud Asignada')
                    ->body("Se te ha asignado la solicitud de la orden #{$artRequest->order->code}")
                     ->icon('heroicon-o-pencil-square')
                    ->iconColor('success')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->label('Ver Solicitud')
                            ->url(\App\Filament\Resources\ArtRequestResource::getUrl('edit', ['record' => $artRequest])),
                    ])
                    ->sendToDatabase(User::find($artRequest->designer_id));
            }
        });
    }
}
