<?php

namespace Database\Seeders;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'customer_name' => 'Juan Dela Cruz',
                'status' => Order::STATUS_PENDING,
                'created_at' => Carbon::now()->subMinutes(25),
                'updated_at' => Carbon::now()->subMinutes(25),
            ],
            [
                'customer_name' => 'Maria Santos',
                'status' => Order::STATUS_PREPARING,
                'created_at' => Carbon::now()->subMinutes(18),
                'updated_at' => Carbon::now()->subMinutes(12),
            ],
            [
                'customer_name' => 'Angela Reyes',
                'status' => Order::STATUS_READY,
                'created_at' => Carbon::now()->subMinutes(15),
                'updated_at' => Carbon::now()->subMinutes(3),
            ],
            [
                'customer_name' => 'Mark Villanueva',
                'status' => Order::STATUS_COMPLETED,
                'created_at' => Carbon::now()->subMinutes(40),
                'updated_at' => Carbon::now()->subMinutes(20),
            ],
            [
                'customer_name' => 'Sofia Mendoza',
                'status' => Order::STATUS_PENDING,
                'created_at' => Carbon::now()->subMinutes(8),
                'updated_at' => Carbon::now()->subMinutes(8),
            ],
        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
