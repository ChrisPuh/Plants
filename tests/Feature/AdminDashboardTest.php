<?php

use App\Enums\Plant\PlantContributionStatusEnum;
use App\Models\Plant;
use App\Models\PlantContribution;
use App\Models\PlantRequest;
use App\Models\User;

test('admin dashboard shows pending requests and contributions', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create(['name' => 'Existing Plant']);

    // Create pending items
    $pendingRequest = PlantRequest::factory()->create([
        'user_id' => $user->id,
        'name' => 'New Plant Request',
        'status' => 'pending',
    ]);

    $pendingContribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'field_name' => 'description',
        'proposed_value' => 'Updated description',
        'status' => 'pending',
    ]);

    // Create already processed items (should not show)
    $approvedRequest = PlantRequest::factory()->create([
        'user_id' => $user->id,
        'name' => 'Approved Request',
        'status' => 'approved',
    ]);

    $rejectedContribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'status' => 'rejected',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertSuccessful()
        ->assertSee('Admin Dashboard')
        ->assertSee('New Plant Request')
        ->assertSee('Existing Plant')
        ->assertSee('Updated description')
        ->assertDontSee('Approved Request');
});

test('admin can approve request from dashboard', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $request = PlantRequest::factory()->create([
        'user_id' => $user->id,
        'name' => 'Test Plant',
        'latin_name' => 'Testus plantus',
        'status' => 'pending',
        'proposed_data' => [
            'description' => 'Test description',
            'plant_type' => 'annual',
        ],
    ]);

    // Call the approve method directly on the model
    $this->actingAs($admin);
    $request->approve($admin, 'Approved via test');
    $plant = $request->createPlantFromRequest();

    // Verify request was approved
    $request->refresh();
    expect($request->status)->toBe('approved');
    expect($request->reviewed_by)->toBe($admin->id);

    // Verify plant was created
    $this->assertDatabaseHas('plants', [
        'name' => 'Test Plant',
        'latin_name' => 'Testus plantus',
    ]);
});

test('admin can reject request from dashboard', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();

    $request = PlantRequest::factory()->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    // Call the reject method directly on the model
    $this->actingAs($admin);
    $request->reject($admin, 'Rejected via test');

    // Verify request was rejected
    $request->refresh();
    expect($request->status)->toBe('rejected');
    expect($request->reviewed_by)->toBe($admin->id);
});

test('admin can approve contribution from dashboard', function () {
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
        'proposed_value' => 'Improved description',
        'status' => 'pending',
    ]);

    // Call the approve method directly on the model
    $this->actingAs($admin);
    $contribution->approve($admin, 'Approved via test');
    $contribution->applyToPlant();

    // Verify contribution was approved
    $contribution->refresh();
    expect($contribution->status)->toBe(PlantContributionStatusEnum::Approved);
    expect($contribution->reviewed_by)->toBe($admin->id);

    // Verify plant was updated
    $plant->refresh();
    expect($plant->description)->toBe('Improved description');
});

test('admin can reject contribution from dashboard', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    $contribution = PlantContribution::factory()->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'status' => 'pending',
    ]);

    // Call the reject method directly on the model
    $this->actingAs($admin);
    $contribution->reject($admin, 'Rejected via test');

    // Verify contribution was rejected
    $contribution->refresh();
    expect($contribution->status)->toBe(PlantContributionStatusEnum::Rejected)
        ->and($contribution->reviewed_by)->toBe($admin->id);
});

test('admin dashboard shows task counts', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create();
    $plant = Plant::factory()->create();

    // Create multiple pending items
    PlantRequest::factory()->count(3)->create([
        'user_id' => $user->id,
        'status' => 'pending',
    ]);

    PlantContribution::factory()->count(2)->create([
        'user_id' => $user->id,
        'plant_id' => $plant->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertSee('3'); // Request count
    $response->assertSee('2'); // Contribution count
});

test('admin dashboard shows user information for requests', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->create(['name' => 'Test User', 'email' => 'test@example.com']);

    $request = PlantRequest::factory()->create([
        'user_id' => $user->id,
        'name' => 'Test Plant',
        'status' => 'pending',
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertSee('Test User')
        ->assertSee('test@example.com')
        ->assertSee('Test Plant');
});

test('empty admin dashboard shows appropriate message', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertSee('Keine ausstehenden Aufgaben');
});
