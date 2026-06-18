<?php

namespace Tests\Feature;

use App\Models\Armada;
use App\Models\Driver;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_customer_profile_uses_public_customer_navbar(): void
    {
        $user = User::factory()->create(['role' => 'pelanggan']);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Booking Saya')
            ->assertSee('Profil Saya')
            ->assertSee('Nomor Telepon')
            ->assertDontSee('Profile Information')
            ->assertDontSee('Dashboard')
            ->assertDontSee('Login');
    }

    public function test_admin_profile_uses_admin_layout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Admin Control')
            ->assertSee('Profil Admin')
            ->assertDontSee('Profile Information');
    }

    public function test_driver_profile_uses_driver_layout(): void
    {
        $user = User::factory()->create(['role' => 'driver']);
        $armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 1234 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        Driver::create([
            'user_id' => $user->id,
            'armada_id' => $armada->id,
            'nama_driver' => 'Joni Driver',
            'no_hp' => '081234567890',
            'status_driver' => 'aktif',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response
            ->assertOk()
            ->assertSee('Driver Portal')
            ->assertSee('Nomor Telepon')
            ->assertSee('Toyota Avanza')
            ->assertDontSee('Profile Information');
    }

    public function test_customer_phone_number_can_be_updated_from_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'pelanggan',
            'name' => 'Budi Lama',
            'email' => 'budi-lama@example.com',
        ]);

        Pelanggan::create([
            'user_id' => $user->id,
            'nama' => 'Budi Lama',
            'no_hp' => '081200000000',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Budi Baru',
                'email' => 'budi-baru@example.com',
                'no_hp' => '081299998888',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Budi Baru', $user->name);
        $this->assertSame('budi-baru@example.com', $user->email);
        $this->assertDatabaseHas('pelanggan', [
            'user_id' => $user->id,
            'nama' => 'Budi Baru',
            'no_hp' => '081299998888',
        ]);
    }

    public function test_driver_phone_number_can_be_updated_from_profile(): void
    {
        $user = User::factory()->create([
            'role' => 'driver',
            'name' => 'Driver Lama',
            'email' => 'driver-lama@example.com',
        ]);
        $armada = Armada::create([
            'nama_mobil' => 'Toyota Avanza',
            'nomor_plat' => 'BA 5678 XY',
            'kapasitas' => 5,
            'status_armada' => 'aktif',
        ]);

        Driver::create([
            'user_id' => $user->id,
            'armada_id' => $armada->id,
            'nama_driver' => 'Driver Lama',
            'no_hp' => '081200000001',
            'status_driver' => 'aktif',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Driver Baru',
                'email' => 'driver-baru@example.com',
                'no_hp' => '081233344455',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Driver Baru', $user->name);
        $this->assertSame('driver-baru@example.com', $user->email);
        $this->assertDatabaseHas('drivers', [
            'user_id' => $user->id,
            'nama_driver' => 'Driver Baru',
            'no_hp' => '081233344455',
        ]);
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
