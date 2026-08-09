<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'phone' => '082121212121',
            'password' => Hash::make('admin'),
            'role' => Role::ADMIN->status(),
        ]);

        $staffUsers = [
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@pemda.go.id', 'phone' => '081234567001'],
            ['name' => 'Siti Rahayu', 'email' => 'siti.rahayu@pemda.go.id', 'phone' => '081234567002'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@pemda.go.id', 'phone' => '081234567003'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi.lestari@pemda.go.id', 'phone' => '081234567004'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@pemda.go.id', 'phone' => '081234567005'],
            ['name' => 'Fitriani Wulandari', 'email' => 'fitriani.wulandari@pemda.go.id', 'phone' => '081234567006'],
            ['name' => 'Gunawan Saputra', 'email' => 'gunawan.saputra@pemda.go.id', 'phone' => '081234567007'],
            ['name' => 'Hesti Permata', 'email' => 'hesti.permata@pemda.go.id', 'phone' => '081234567008'],
            ['name' => 'Irwan Hidayat', 'email' => 'irwan.hidayat@pemda.go.id', 'phone' => '081234567009'],
            ['name' => 'Juliana Putri', 'email' => 'juliana.putri@pemda.go.id', 'phone' => '081234567010'],
        ];

        foreach ($staffUsers as $staff) {
            User::factory()->create([
                'name' => $staff['name'],
                'email' => $staff['email'],
                'phone' => $staff['phone'],
                'password' => Hash::make('password'),
                'role' => Role::STAFF->status(),
                'is_active' => true,
            ]);
        }
    }
}
