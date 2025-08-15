<?php

use App\Models\Plant;
use App\Models\User;

describe('Plant Index Page', function () {
    it('allows admin to view plants index', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/plants')
            ->assertSuccessful()
            ->assertSee('Pflanzen-Datenbank');
    });

    it('allows users to view plants index', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/plants')
            ->assertSuccessful()
            ->assertSee('Pflanzen-Datenbank');
    });

    it('shows plant information correctly in index', function () {
        $user = User::factory()->create();

        $plant = Plant::factory()->create([
            'name' => 'Test Plant',
            'latin_name' => 'Testus plantus',
            'category' => 'herb',
            'plant_type' => 'perennial',
        ]);

        $response = $this->actingAs($user)->get('/plants');
        $response->assertSee('Test Plant')
            ->assertSee('Testus plantus')
            ->assertSee('Herb');
    });
});

describe('Plant Creation Authorization', function () {
    it('prevents regular users from creating plants directly', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/plants/create')
            ->assertForbidden();
    });

    it('allows admin to access plant creation', function () {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/plants/create')
            ->assertSuccessful()
            ->assertSee('Neue Pflanze hinzufügen');
    });
});

describe('Plant Model Accessors', function () {
    it('shows botanical information correctly', function () {
        $plant = Plant::factory()->create([
            'name' => 'Test Plant',
            'latin_name' => 'Testus plantus',
        ]);

        expect($plant->latin_name)->toBe('Testus plantus');
    });
});

describe('Plant Creation by Admin', function () {

    it('can save new plant with complete botanical data', function () {
        $admin = User::factory()->admin()->create();

        $plantData = [
            'name' => 'Basilikum',
            'latin_name' => 'Ocimum basilicum',
            'description' => 'Aromatisches Kraut',
            'category' => 'herb',
            'plant_type' => 'annual',
        ];

        $plant = Plant::create($plantData);

        expect($plant)->toBeInstanceOf(Plant::class)
            ->and($plant->name)->toBe('Basilikum')
            ->and($plant->latin_name)->toBe('Ocimum basilicum');

    });
});

describe('Plant Search Functionality', function () {
    it('works correctly with plant names', function () {
        $admin = User::factory()->admin()->create();

        $plant1 = Plant::factory()->create([
            'name' => 'Basilikum',
            'latin_name' => 'Ocimum basilicum',
        ]);

        $plant2 = Plant::factory()->create([
            'name' => 'Rosmarin',
            'latin_name' => 'Rosmarinus officinalis',
        ]);

        // Test search by common name
        $response = $this->actingAs($admin)->get('/plants?search=Basilikum');
        $response->assertSee('Basilikum')
            ->assertDontSee('Rosmarin');

        // Test search by latin name
        $response = $this->actingAs($admin)->get('/plants?search=Rosmarinus');
        $response->assertSee('Rosmarin')
            ->assertDontSee('Basilikum');
    });
});
