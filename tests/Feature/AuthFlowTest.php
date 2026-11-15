<?php

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\RoleTypeSeeder;

// uses(RefreshDatabase::class); adalah cara Pest PHP untuk menyuntikkan trait RefreshDatabase ke dalam seluruh fungsi test di dalam satu file (sebagai pengganti sintaks use RefreshDatabase; yang biasanya ditulis di dalam blok class pada PHPUnit).
// Di balik layar, trait ini bekerja melalui dua tahap:
// Migrasi Awal: Sebelum pengujian pertama dimulai, Laravel memastikan struktur tabel terbaru sudah terpasang dengan menjalankan migrate:fresh satu kali.
// Transaction & Rollback: Setiap kali satu blok test(...) dieksekusi, Laravel membungkusnya di dalam transaksi database (BEGIN TRANSACTION). Begitu satu fungsi test selesai dijalankan, Laravel langsung melakukan ROLLBACK sebelum berpindah ke fungsi test berikutnya.
uses(RefreshDatabase::class);

beforeEach(function () {
    /** @var \Tests\TestCase $this */
    $this->seed([
        RoleTypeSeeder::class,
        PermissionSeeder::class,
        RoleSeeder::class,
        RolePermissionSeeder::class,
    ]);
});

//#1 LOGIN VALID
test('user can login with valid email & password', function() {
    $adminRole = Role::where('name', 'admin')->first();

    $email = 'gmail@gmail.com';
    $password = 'password@123';
    $user = User::factory()->create([
        'email' => $email,
        'password' => Hash::make($password),
        'must_change_password' => false,
    ]);

    $user->roles()->sync([
        $adminRole->id => [
            'is_active' => true,
            'start_date' => now(),
            'end_date' => null,
        ]
    ]);
    /** @var \Tests\TestCase $this */
    $response = $this->postJson('/api/login', [
        'email' => $email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email', 'roles'],
            'access_token',
            'token_type',
            'permissions'
        ]);
});

//#2 Login Invalid
test('user cant login invalid email & password', function() {
    $adminRole = Role::where('name', 'admin')->first();

    $email = 'gmail@gmail.com';
    $password = 'password@123';
    $user = User::factory()->create([
        'email' => $email,
        'password' => Hash::make($password),
        'must_change_password' => false,
    ]);

    $user->roles()->sync([
        $adminRole->id => [
            'is_active' => true,
            'start_date' => now(),
            'end_date' => null,
        ]
    ]);
    /** @var \Tests\TestCase $this */
    $response = $this->postJson('/api/login', [
        'email' => $email,
        'password' => $password . '2',
    ]);

    $response->assertStatus(401)
        ->assertJsonStructure([
            'message',
        ]);
});
