<?php

namespace Tests\Feature\Admin;

use App\Models\Armada;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArmadaTest extends TestCase
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
     * Test admin can access armada index page.
     */
    public function test_admin_can_access_armada_index(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.armada.index'));

        $response->assertStatus(200);
        $response->assertSee('Manajemen Armada');
    }

    /**
     * Test admin can create a new armada.
     */
    public function test_admin_can_create_armada(): void
    {
        $armadaData = [
            'nama_mobil' => 'Toyota Innova',
            'nomor_plat' => 'BA 5555 ZZZ',
            'kapasitas' => 7,
            'status_armada' => 'aktif',
        ];

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.armada.store'), $armadaData);

        $armada = Armada::where('nomor_plat', 'BA 5555 ZZZ')->first();

        $response->assertRedirect(route('admin.armada.index', ['selected_id' => $armada->id]));

        $this->assertDatabaseHas('armada', [
            'nama_mobil' => 'Toyota Innova',
            'nomor_plat' => 'BA 5555 ZZZ',
            'kapasitas' => 7,
            'status_armada' => 'aktif',
        ]);
    }

    /**
     * Test admin can update an armada.
     */
    public function test_admin_can_update_armada(): void
    {
        $armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        $updateData = [
            'nama_mobil' => 'Suzuki Ertiga',
            'nomor_plat' => 'BA 9999 ZZ',
            'kapasitas' => 6,
            'status_armada' => 'nonaktif',
        ];

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.armada.update', $armada->id), $updateData);

        $response->assertRedirect(route('admin.armada.index', ['selected_id' => $armada->id]));

        $this->assertDatabaseHas('armada', [
            'id' => $armada->id,
            'nama_mobil' => 'Suzuki Ertiga',
            'nomor_plat' => 'BA 9999 ZZ',
            'kapasitas' => 6,
            'status_armada' => 'nonaktif',
        ]);
    }

    /**
     * Test admin can delete an armada.
     */
    public function test_admin_can_delete_armada(): void
    {
        $armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 8888 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.armada.destroy', $armada->id));

        $response->assertRedirect(route('admin.armada.index'));

        $this->assertDatabaseMissing('armada', ['id' => $armada->id]);
    }
}
