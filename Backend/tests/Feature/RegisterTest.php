<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_contains_a_post_form(): void
    {
        $response = $this->get('/register');

        $response->assertOk()
            ->assertSee('action="'.route('register.store').'"', false)
            ->assertSee('method="POST"', false)
            ->assertSee('name="no_hp"', false);
    }

    public function test_valid_registration_creates_and_logs_in_a_peminjam(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Test Peminjam',
            'email' => 'peminjam@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Bandung',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'peminjam@example.com')->firstOrFail();

        $response->assertRedirect(route('peminjam.katalog'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('peminjam', $user->role);
        $this->assertSame('081234567890', $user->no_hp);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_registration_rejects_mismatched_password_confirmation(): void
    {
        $response = $this->from('/register')->post(route('register.store'), [
            'name' => 'Test Peminjam',
            'email' => 'peminjam@example.com',
            'no_hp' => '081234567890',
            'alamat' => 'Bandung',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertRedirect('/register')
            ->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'peminjam@example.com']);
    }
}
