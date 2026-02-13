<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkOrder;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class WorkOrderSeeder extends Seeder
{
    public function run()
    {
       DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        WorkOrder::truncate();
        DB::table('work_order_logs')->truncate(); 
        DB::table('activity_logs')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $faker = Faker::create();

        $admin = User::where('email', 'admin@shabakat.com')->first();
        $manager = User::where('email', 'manager@shabakat.com')->first();
        $operator = User::where('email', 'operator@shabakat.com')->first();

        if (!$admin || !$operator) {
            $this->command->info('Please run RBACSeeder first to create users!');
            return;
        }

        $userIds = [$admin->id, $manager->id];

        $workOrders = [
            [
                'title' => 'Server Room Overheating',
                'description' => 'The main server room AC is down, temperature is rising critical.',
                'priority' => 'high',
                'status' => 'in_progress',
                'assigned_to' => $operator->id,
                'due_date' => Carbon::now()->addDays(1),
            ],
            [
                'title' => 'Install New Router - 3rd Floor',
                'description' => 'Setup the new Cisco router for the marketing department.',
                'priority' => 'medium',
                'status' => 'pending',
                'assigned_to' => null, 
                'due_date' => Carbon::now()->addDays(3),
            ],
            [
                'title' => 'Fix Printer Network Issue',
                'description' => 'The HR printer is not connecting to the local network.',
                'priority' => 'low',
                'status' => 'completed',
                'assigned_to' => $operator->id,
                'due_date' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($workOrders as $order) {
            WorkOrder::create(array_merge($order, [
                'created_by' => $admin->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        for ($i = 0; $i < 25; $i++) {
            $status = $faker->randomElement(['pending', 'in_progress', 'completed',]);
            
            $assignee = ($status === 'pending') ? null : $operator->id;
            
            if ($status === 'pending' && rand(0, 1)) {
                $assignee = $operator->id;
            }

            WorkOrder::create([
                'title'       => $faker->sentence(4),
                'description' => $faker->paragraph(2), 
                'priority'    => $faker->randomElement(['low', 'medium', 'high']),
                'status'      => $status,
                'created_by'  => $userIds[array_rand($userIds)],
                'assigned_to' => $assignee,
                'due_date'    => $faker->dateTimeBetween('-1 week', '+1 month'),
                'created_at'  => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at'  => now(),
            ]);
        }
    }
}