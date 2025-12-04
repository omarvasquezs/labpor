<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\ArtRequest;
use App\Models\ProductionStage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@labpor.com',
            'password' => Hash::make('password'),
        ]);

        // Designers
        $designer = User::factory()->create([
            'name' => 'Ivet Designer',
            'email' => 'ivet@labpor.com',
            'password' => Hash::make('password'),
        ]);

        // Operators
        $operator = User::factory()->create([
            'name' => 'Juan Operator',
            'email' => 'juan@labpor.com',
            'password' => Hash::make('password'),
        ]);

        // Sample Order
        $order = Order::create([
            'code' => '182291',
            'client_name' => 'CAJ BENEXAM',
            'type' => 'CAJA',
            'quantity' => 15000,
            'status' => 'IN_PROGRESS',
            'due_date' => now()->addDays(5),
        ]);

        // Art Request
        ArtRequest::create([
            'order_id' => $order->id,
            'designer_id' => $designer->id,
            'status' => 'PENDING',
            'started_at' => now(),
        ]);

        // Production Stages
        $stages = ['CORTE', 'IMPRESION', 'TROQUEL', 'PEGADO'];
        foreach ($stages as $stage) {
            ProductionStage::create([
                'order_id' => $order->id,
                'name' => $stage,
                'status' => 'PENDING',
            ]);
        }
    }
}
