<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
uses(RefreshDatabase::class);

test('un usuario no puede eliminarse a sí mismo', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $this->actingAs($user, 'web');
    $response = $this->delete(route('admin.users.destroy', $user));
    $response->assertStatus(403);
    $this->assertDatabaseHas('users', ['id' => $user->id]);
});
