<?php

namespace Tests\Feature\Admin;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin User
        $this->adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);
    }

    /**
     * Test admin can access driver index page.
     */
    public function test_admin_can_access_driver_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.drivers.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Driver');
    }

    /**
     * Test admin can create a new driver.
     */
    public function test_admin_can_create_driver(): void
    {
        $driverData = [
            'action_type' => 'create',
            'nama_driver' => 'Joni Setiawan',
            'email' => 'joni@singgalang.com',
            'password' => 'driver12345',
            'no_hp' => '081234567800',
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 4321 XY',
            'kapasitas_mobil' => 5,
            'status_driver' => 'aktif',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.drivers.store'), $driverData);

        $response->assertRedirect(route('admin.drivers.index'));

        // Check if user and driver records are created
        $this->assertDatabaseHas('users', [
            'name' => 'Joni Setiawan',
            'email' => 'joni@singgalang.com',
            'role' => 'driver',
        ]);

        $user = User::where('email', 'joni@singgalang.com')->first();

        $this->assertDatabaseHas('drivers', [
            'user_id' => $user->id,
            'nama_driver' => 'Joni Setiawan',
            'no_hp' => '081234567800',
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 4321 XY',
            'kapasitas_mobil' => 5,
            'status_driver' => 'aktif',
        ]);
    }

    /**
     * Test admin can update a driver.
     */
    public function test_admin_can_update_driver(): void
    {
        // Create user and driver first
        $driverUser = User::create([
            'name' => 'Lama Driver',
            'email' => 'lama@singgalang.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'nama_driver' => 'Lama Driver',
            'no_hp' => '081234567809',
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas_mobil' => 5,
            'status_driver' => 'aktif',
        ]);

        $updateData = [
            'action_type' => 'edit',
            'nama_driver' => 'Baru Driver',
            'email' => 'baru@singgalang.com',
            'no_hp' => '081234567899',
            'nama_mobil' => 'Suzuki Ertiga',
            'nomor_plat' => 'BA 9999 ZZ',
            'kapasitas_mobil' => 6,
            'status_driver' => 'nonaktif',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.drivers.update', $driver->id), $updateData);

        $response->assertRedirect(route('admin.drivers.index', ['selected_id' => $driver->id]));

        $this->assertDatabaseHas('users', [
            'id' => $driverUser->id,
            'name' => 'Baru Driver',
            'email' => 'baru@singgalang.com',
        ]);

        $this->assertDatabaseHas('drivers', [
            'id' => $driver->id,
            'nama_driver' => 'Baru Driver',
            'no_hp' => '081234567899',
            'nama_mobil' => 'Suzuki Ertiga',
            'nomor_plat' => 'BA 9999 ZZ',
            'kapasitas_mobil' => 6,
            'status_driver' => 'nonaktif',
        ]);
    }

    /**
     * Test admin can delete a driver.
     */
    public function test_admin_can_delete_driver(): void
    {
        $driverUser = User::create([
            'name' => 'Hapus Driver',
            'email' => 'hapus@singgalang.com',
            'password' => bcrypt('password123'),
            'role' => 'driver',
        ]);

        $driver = Driver::create([
            'user_id' => $driverUser->id,
            'nama_driver' => 'Hapus Driver',
            'no_hp' => '081234567811',
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 8888 XY',
            'kapasitas_mobil' => 5,
            'status_driver' => 'aktif',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.drivers.destroy', $driver->id));

        $response->assertRedirect(route('admin.drivers.index'));

        // Check cascade deletion
        $this->assertDatabaseMissing('users', ['id' => $driverUser->id]);
        $this->assertDatabaseMissing('drivers', ['id' => $driver->id]);
    }
}
