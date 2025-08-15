<?php

use App\Models\Plant;
use App\Models\PlantRequest;
use App\Models\User;

describe('Plant Request Creation', function () {
    it('allows regular users to create plant requests', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/plants/request')
            ->assertSuccessful()
            ->assertSee('Neue Pflanze vorschlagen');
    });

    it('prevents admin from directly creating plant requests', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/plants/request')
            ->assertForbidden();
    });

    it('allows user to submit plant request with botanical data', function () {
        $user = User::factory()->create();

        $request = PlantRequest::create([
            'user_id' => $user->id,
            'name' => 'Neue Testpflanze',
            'latin_name' => 'Novus testus',
            'family' => 'Testaceae',
            'genus' => 'Novus',
            'species' => 'testus',
            'reason' => 'Diese Pflanze fehlt in der Datenbank',
            'proposed_data' => [
                'description' => 'Eine interessante neue Pflanze',
                'plant_type' => 'perennial',
                'native_region' => 'Europa',
            ],
            'status' => 'pending',
        ]);

        expect($request->name)->toBe('Neue Testpflanze');
        expect($request->latin_name)->toBe('Novus testus');
        expect($request->user_id)->toBe($user->id);
        expect($request->status)->toBe('pending');
    });
});

describe('Plant Request Approval Process', function () {
    it('allows admin to approve plant request and create plant', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $request = PlantRequest::factory()->create([
            'user_id' => $user->id,
            'name' => 'Testpflanze',
            'latin_name' => 'Testus plantus',
            'proposed_data' => [
                'description' => 'Test description',
                'plant_type' => 'annual',
                'family' => 'Testaceae',
            ],
            'status' => 'pending',
        ]);

        // Admin approves the request
        $this->actingAs($admin);
        $request->approve($admin, 'Genehmigt durch Admin');

        expect($request->fresh()->status)->toBe('approved');
        expect($request->fresh()->reviewed_by)->toBe($admin->id);
        expect($request->fresh()->admin_notes)->toBe('Genehmigt durch Admin');

        // Plant should be created
        $plant = $request->createPlantFromRequest();

        expect($plant)->toBeInstanceOf(Plant::class);
        expect($plant->name)->toBe('Testpflanze');
        expect($plant->latin_name)->toBe('Testus plantus');
    });

    it('allows admin to reject plant request', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $request = PlantRequest::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Request',
            'status' => 'pending',
        ]);

        $this->actingAs($admin);
        $request->reject($admin, 'Unzureichende Informationen');

        expect($request->fresh()->status)->toBe('rejected');
        expect($request->fresh()->reviewed_by)->toBe($admin->id);
        expect($request->fresh()->admin_notes)->toBe('Unzureichende Informationen');
    });
});

describe('Admin Dashboard Integration', function () {
    it('shows pending plant requests on admin dashboard', function () {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $pendingRequest = PlantRequest::factory()->create([
            'user_id' => $user->id,
            'name' => 'Pending Plant',
            'status' => 'pending',
        ]);

        $approvedRequest = PlantRequest::factory()->create([
            'user_id' => $user->id,
            'name' => 'Approved Plant',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        // Should see pending request
        $response->assertSee('Pending Plant');

        // Check pending count is 1
        expect(PlantRequest::where('status', 'pending')->count())->toBe(1);
        expect(PlantRequest::where('status', 'approved')->count())->toBe(1);
    });
});

describe('Authorization Tests', function () {
    it('prevents regular users from accessing admin dashboard', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    });

    it('ensures user can only view their own plant requests', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $request1 = PlantRequest::factory()->create([
            'user_id' => $user1->id,
            'name' => 'User 1 Request',
        ]);

        $request2 = PlantRequest::factory()->create([
            'user_id' => $user2->id,
            'name' => 'User 2 Request',
        ]);

        expect($user1->can('view', $request1))->toBeTrue();
        expect($user1->can('view', $request2))->toBeFalse();
    });
});

describe('Validation Tests', function () {
    it('validates plant request name is required', function () {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        PlantRequest::create([
            'user_id' => $user->id,
            'status' => 'pending',
            // name is missing - should fail
        ]);
    });
});
