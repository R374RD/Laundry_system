<?php

namespace Database\Seeders;

use App\Models\AddOnService;
use App\Models\Branch;
use App\Models\Pricing;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $main = Branch::firstOrCreate(['name' => 'Main Branch'], ['address' => 'Branch 1']);
        $north = Branch::firstOrCreate(['name' => 'North Branch'], ['address' => 'Branch 2']);
        $south = Branch::firstOrCreate(['name' => 'South Branch'], ['address' => 'Branch 3']);

        Pricing::firstOrCreate(['is_active' => true], [
            'price_per_load' => 55,
            'max_kilo_per_load' => 8,
        ]);

        foreach ([
            ['name' => 'Fabric Conditioner', 'price' => 20],
            ['name' => 'Rush Service', 'price' => 80],
            ['name' => 'Extra Detergent', 'price' => 15],
            ['name' => 'Fold and Pack', 'price' => 25],
        ] as $service) {
            AddOnService::firstOrCreate(['name' => $service['name']], $service + ['is_active' => true]);
        }

        User::firstOrCreate(['email' => 'admin@laundry.test'], [
            'branch_id' => null,
            'name' => 'Owner Admin',
            'password' => 'password',
            'role' => 'admin',
            'is_active' => true,
            'staff_signup_status' => 'approved',
            'approved_at' => now(),
        ]);

        foreach ([
            ['branch' => $main, 'name' => 'Main Staff', 'email' => 'main@laundry.test'],
            ['branch' => $north, 'name' => 'North Staff', 'email' => 'north@laundry.test'],
            ['branch' => $south, 'name' => 'South Staff', 'email' => 'south@laundry.test'],
        ] as $staff) {
            User::firstOrCreate(['email' => $staff['email']], [
                'branch_id' => $staff['branch']->id,
                'name' => $staff['name'],
                'password' => 'password',
                'role' => 'staff',
                'is_active' => true,
                'staff_signup_status' => 'approved',
                'approved_at' => now(),
            ]);
        }

        User::where('email', 'test@example.com')->update([
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::where('role', 'customer')->update([
            'role' => 'staff',
            'is_active' => false,
            'branch_id' => null,
        ]);
    }
}
