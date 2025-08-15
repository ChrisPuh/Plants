<?php

use App\Models\Plant;
use App\Models\PlantContribution;
use App\Models\PlantRequest;
use App\Models\User;
use App\UserRole;

test('user roles are correctly assigned', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    expect($admin->role)->toBe(UserRole::Admin);
    expect($user->role)->toBe(UserRole::User);

    expect($admin->isAdmin())->toBeTrue();
    expect($admin->isUser())->toBeFalse();

    expect($user->isAdmin())->toBeFalse();
    expect($user->isUser())->toBeTrue();
});

test('plant authorization works correctly', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    // Admin can do everything
    expect($admin->can('viewAny', Plant::class))->toBeTrue();
    expect($admin->can('view', $plant))->toBeTrue();
    expect($admin->can('create', Plant::class))->toBeTrue();
    expect($admin->can('update', $plant))->toBeTrue();
    expect($admin->can('delete', $plant))->toBeTrue();

    // User can only view
    expect($user->can('viewAny', Plant::class))->toBeTrue();
    expect($user->can('view', $plant))->toBeTrue();
    expect($user->can('create', Plant::class))->toBeFalse();
    expect($user->can('update', $plant))->toBeFalse();
    expect($user->can('delete', $plant))->toBeFalse();
});

test('plant request authorization works correctly', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $userRequest = PlantRequest::factory()->create(['user_id' => $user->id]);
    $otherRequest = PlantRequest::factory()->create();

    // Admin can view all and approve/reject
    expect($admin->can('viewAny', PlantRequest::class))->toBeTrue();
    expect($admin->can('view', $userRequest))->toBeTrue();
    expect($admin->can('view', $otherRequest))->toBeTrue();
    expect($admin->can('create', PlantRequest::class))->toBeFalse(); // Admins create plants directly
    expect($admin->can('update', $userRequest))->toBeTrue(); // Can approve/reject
    expect($admin->can('delete', $userRequest))->toBeTrue();

    // User can create and view their own
    expect($user->can('viewAny', PlantRequest::class))->toBeFalse();
    expect($user->can('view', $userRequest))->toBeTrue();
    expect($user->can('view', $otherRequest))->toBeFalse();
    expect($user->can('create', PlantRequest::class))->toBeTrue();
    expect($user->can('update', $userRequest))->toBeFalse(); // Cannot approve their own
    expect($user->can('delete', $userRequest))->toBeTrue(); // Can delete their own
});

test('plant contribution authorization works correctly', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create();
    $userContribution = PlantContribution::factory()->create(['user_id' => $user->id, 'plant_id' => $plant->id]);
    $otherContribution = PlantContribution::factory()->create(['plant_id' => $plant->id]);

    // Admin can view all and approve/reject
    expect($admin->can('viewAny', PlantContribution::class))->toBeTrue();
    expect($admin->can('view', $userContribution))->toBeTrue();
    expect($admin->can('view', $otherContribution))->toBeTrue();
    expect($admin->can('create', PlantContribution::class))->toBeTrue();
    expect($admin->can('update', $userContribution))->toBeTrue(); // Can approve/reject
    expect($admin->can('delete', $userContribution))->toBeTrue();

    // User can create and view their own
    expect($user->can('viewAny', PlantContribution::class))->toBeFalse();
    expect($user->can('view', $userContribution))->toBeTrue();
    expect($user->can('view', $otherContribution))->toBeFalse();
    expect($user->can('create', PlantContribution::class))->toBeTrue();
    expect($user->can('update', $userContribution))->toBeFalse(); // Cannot approve their own
    expect($user->can('delete', $userContribution))->toBeTrue(); // Can delete their own
});

test('unauthorized users cannot access protected routes', function () {
    // Test without authentication
    $this->get('/plants')->assertRedirect('/login');
    $this->get('/plants/create')->assertRedirect('/login');
    $this->get('/admin/dashboard')->assertRedirect('/login');
});

test('admin navigation shows admin links', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/plants');
    $response->assertSee('Admin'); // Admin link in navigation
});

test('regular user navigation does not show admin links', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/plants');
    $response->assertDontSee('Admin'); // No admin link in navigation
});

test('route authorization prevents unauthorized access', function () {
    $user = User::factory()->create();

    // Regular user cannot access plant creation
    $this->actingAs($user)
        ->get('/plants/create')
        ->assertForbidden();

    // Regular user cannot access admin dashboard
    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden();
});

test('authorization middleware protects sensitive actions', function () {
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    // User cannot edit plants
    $this->actingAs($user)
        ->get("/plants/{$plant->id}/edit")
        ->assertForbidden();
});
