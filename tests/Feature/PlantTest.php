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
            ->assertSee('Herb')
            ->assertSee('Perennial');
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
            'family' => 'Testaceae',
            'genus' => 'Testus',
            'species' => 'plantus',
            'height_min_cm' => 50,
            'height_max_cm' => 100,
        ]);

        expect($plant->height_range)->toBe('50-100 cm');
        expect($plant->full_latin_name)->toBe('Testus plantus');
    });
});

describe('Plant Creation by Admin', function () {

    it('can save new plant with complete botanical data', function () {
        $admin = User::factory()->admin()->create();

        $plantData = [
            'name' => 'Basilikum',
            'latin_name' => 'Ocimum basilicum',
            'family' => 'Lamiaceae',
            'genus' => 'Ocimum',
            'species' => 'basilicum',
            'description' => 'Aromatisches Kraut',
            'botanical_description' => 'Einjährige Pflanze mit ovalen Blättern',
            'category' => 'herb',
            'plant_type' => 'annual',
            'growth_habit' => 'upright',
            'native_region' => 'Asia',
            'height_min_cm' => 20,
            'height_max_cm' => 60,
            'bloom_time' => 'Sommer',
            'flower_color' => 'weiß',
            'foliage_type' => 'deciduous',
            'is_edible' => true,
            'is_toxic' => false,
        ];

        $plant = Plant::create($plantData);

        expect($plant)->toBeInstanceOf(Plant::class);
        expect($plant->name)->toBe('Basilikum');
        expect($plant->latin_name)->toBe('Ocimum basilicum');
        expect($plant->family)->toBe('Lamiaceae');
        expect($plant->genus)->toBe('Ocimum');
        expect($plant->species)->toBe('basilicum');
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
