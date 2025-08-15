<?php

use App\Models\Plant;
use App\Models\PlantContribution;
use App\Models\User;

test('regular users can create plant contributions', function () {
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    $contributionData = [
        'plant_id' => $plant->id,
        'field_name' => 'description',
        'current_value' => $plant->description,
        'proposed_value' => 'Updated description with more details',
        'reason' => 'Adding more comprehensive information',
    ];

    $contribution = PlantContribution::create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'description',
        'current_value' => $plant->description,
        'proposed_value' => 'Updated description with more details',
        'reason' => 'Adding more comprehensive information',
        'status' => 'pending',
    ]);

    expect($contribution->status)->toBe('pending');
    expect($contribution->user_id)->toBe($user->id);
    expect($contribution->plant_id)->toBe($plant->id);
});

test('admin can approve plant contribution and update plant', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create([
        'description' => 'Original description',
    ]);

    $contribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'description',
        'current_value' => 'Original description',
        'proposed_value' => 'Updated description',
        'status' => 'pending',
    ]);

    $this->actingAs($admin);
    $contribution->approve($admin, 'Good improvement');

    expect($contribution->fresh()->status)->toBe('approved');
    expect($contribution->fresh()->reviewed_by)->toBe($admin->id);
    expect($contribution->fresh()->admin_notes)->toBe('Good improvement');

    // Apply the contribution to the plant
    $contribution->applyToPlant();

    expect($plant->fresh()->description)->toBe('Updated description');
});

test('admin can reject plant contribution', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    $contribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'status' => 'pending',
    ]);

    $this->actingAs($admin);
    $contribution->reject($admin, 'Information not accurate');

    expect($contribution->fresh()->status)->toBe('rejected');
    expect($contribution->fresh()->reviewed_by)->toBe($admin->id);
    expect($contribution->fresh()->admin_notes)->toBe('Information not accurate');
});

test('user can only view their own contributions', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $plant = Plant::factory()->create();

    $contribution1 = PlantContribution::factory()->create([
        'user_id' => $user1->id,
        'plant_id' => $plant->id,
    ]);

    $contribution2 = PlantContribution::factory()->create([
        'user_id' => $user2->id,
        'plant_id' => $plant->id,
    ]);

    $this->assertTrue($user1->can('view', $contribution1));
    $this->assertFalse($user1->can('view', $contribution2));
});

test('admin can view all contributions', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    $contribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
    ]);

    $this->assertTrue($admin->can('viewAny', PlantContribution::class));
    $this->assertTrue($admin->can('view', $contribution));
});

test('contribution shows in admin dashboard', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create(['name' => 'Test Plant']);

    $pendingContribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'description',
        'proposed_value' => 'New description',
        'status' => 'pending',
    ]);

    $approvedContribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'category',
        'proposed_value' => 'herb',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertSee('Test Plant')
        ->assertSee('description')
        ->assertSee('New description');
});

test('contribution validation prevents invalid field names', function () {
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    $this->expectException(\InvalidArgumentException::class);

    PlantContribution::create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'invalid_field',
        'current_value' => 'current',
        'proposed_value' => 'proposed',
        'reason' => 'test',
        'status' => 'pending',
    ]);
});

test('contribution belongs to plant and user relationships work', function () {
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    $contribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
    ]);

    expect($contribution->user)->toBeInstanceOf(User::class);
    expect($contribution->plant)->toBeInstanceOf(Plant::class);
    expect($contribution->user->id)->toBe($user->id);
    expect($contribution->plant->id)->toBe($plant->id);
});

test('plant contribution can handle json field updates', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create([
        'leaf_characteristics' => ['shape' => 'oval', 'texture' => 'smooth'],
    ]);

    $contribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'leaf_characteristics',
        'current_value' => json_encode(['shape' => 'oval', 'texture' => 'smooth']),
        'proposed_value' => json_encode(['shape' => 'oval', 'texture' => 'smooth', 'color' => 'green']),
        'status' => 'pending',
    ]);

    $this->actingAs($admin);
    $contribution->approve($admin, 'Good addition');
    $contribution->applyToPlant();

    $updatedPlant = $plant->fresh();
    expect($updatedPlant->leaf_characteristics)->toBe(['shape' => 'oval', 'texture' => 'smooth', 'color' => 'green']);
});
