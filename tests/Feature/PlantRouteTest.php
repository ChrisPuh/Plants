<?php

declare(strict_types=1);

use App\Enums\User\UserRoleEnum;
use App\Models\Plant;
use App\Models\PlantContribution;
use App\Models\PlantRequest;
use App\Models\User;

describe('Plant Routes', function () {
    describe('plants.index', function () {
        it('can be accessed by authenticated users', function () {
            $user = User::factory()->create();

            $this->actingAs($user)
                ->get(route('plants.index'))
                ->assertSuccessful()
                ->assertSeeLivewire('pages.plants.index');
        });

        it('redirects guests to login', function () {
            $this->get(route('plants.index'))
                ->assertRedirect(route('login'));
        });

        it('displays existing plants', function () {
            $user = User::factory()->create();
            $plant = Plant::factory()->create(['name' => 'Test Plant']);

            $this->actingAs($user)
                ->get(route('plants.index'))
                ->assertSee('Test Plant');
        });
    });

    describe('plants.show', function () {
        it('can be accessed by authenticated users', function () {
            $user = User::factory()->create();
            $plant = Plant::factory()->create();

            $this->actingAs($user)
                ->get(route('plants.show', $plant))
                ->assertSuccessful()
                ->assertSeeLivewire('pages.plants.show');
        });

        it('redirects guests to login', function () {
            $plant = Plant::factory()->create();

            $this->get(route('plants.show', $plant))
                ->assertRedirect(route('login'));
        });

        it('displays plant details', function () {
            $user = User::factory()->create();
            $plant = Plant::factory()->create([
                'name' => 'Test Plant',
                'latin_name' => 'Testus plantus',
                'description' => 'A test plant description',
            ]);

            $this->actingAs($user)
                ->get(route('plants.show', $plant))
                ->assertSee('Test Plant')
                ->assertSee('Testus plantus')
                ->assertSee('A test plant description');
        });

        it('shows edit button only for admins', function () {
            $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);
            $user = User::factory()->create(['role' => UserRoleEnum::User]);
            $plant = Plant::factory()->create();

            $this->actingAs($admin)
                ->get(route('plants.show', $plant))
                ->assertSee('Bearbeiten');

            $this->actingAs($user)
                ->get(route('plants.show', $plant))
                ->assertDontSee('Bearbeiten');
        });
    });

    describe('plants.create', function () {
        it('can be accessed by admin users only', function () {
            $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);

            $this->actingAs($admin)
                ->get(route('plants.create'))
                ->assertSuccessful()
                ->assertSeeLivewire('pages.plants.create');
        });

        it('is forbidden for regular users', function () {
            $user = User::factory()->create(['role' => UserRoleEnum::User]);

            $this->actingAs($user)
                ->get(route('plants.create'))
                ->assertForbidden();
        });

        it('redirects guests to login', function () {
            $this->get(route('plants.create'))
                ->assertRedirect(route('login'));
        });
    });

    describe('plants.edit', function () {
        it('can be accessed by admin users only', function () {
            $admin = User::factory()->admin()->create();
            $plant = Plant::factory()->create();

            $this->actingAs($admin)
                ->get(route('plants.edit', $plant))
                ->assertSuccessful()
                ->assertSeeLivewire('pages.plants.edit');
        });

        it('is forbidden for regular users', function () {
            $user = User::factory()->create(['role' => UserRoleEnum::User]);
            $plant = Plant::factory()->create();

            $this->actingAs($user)
                ->get(route('plants.edit', $plant))
                ->assertForbidden();
        });

        it('redirects guests to login', function () {
            $plant = Plant::factory()->create();

            $this->get(route('plants.edit', $plant))
                ->assertRedirect(route('login'));
        });
    });

    describe('plants.request', function () {
        it('can be accessed by regular users', function () {
            $user = User::factory()->create(['role' => UserRoleEnum::User]);

            $this->actingAs($user)
                ->get(route('plants.request'))
                ->assertSuccessful()
                ->assertSeeLivewire('pages.plants.request');
        });

        it('is forbidden for admin users (they create plants directly)', function () {
            $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);

            $this->actingAs($admin)
                ->get(route('plants.request'))
                ->assertForbidden();
        });

        it('redirects guests to login', function () {
            $this->get(route('plants.request'))
                ->assertRedirect(route('login'));
        });
    });
});

describe('Admin Dashboard Routes', function () {
    describe('admin.dashboard', function () {
        it('can be accessed by admin users only', function () {
            $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);

            $this->actingAs($admin)
                ->get(route('admin.dashboard'))
                ->assertSuccessful()
                ->assertSeeLivewire('pages.admin.dashboard');
        });

        it('is forbidden for regular users', function () {
            $user = User::factory()->create(['role' => UserRoleEnum::User]);

            $this->actingAs($user)
                ->get(route('admin.dashboard'))
                ->assertForbidden();
        });

        it('redirects guests to login', function () {
            $this->get(route('admin.dashboard'))
                ->assertRedirect(route('login'));
        });

        it('displays pending requests and contributions', function () {
            $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);
            $user = User::factory()->create(['role' => UserRoleEnum::User]);

            // Create test data
            $plantRequest = PlantRequest::factory()->create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);

            $plant = Plant::factory()->create();
            $contribution = PlantContribution::factory()->create([
                'user_id' => $user->id,
                'plant_id' => $plant->id,
                'status' => 'pending',
            ]);

            $this->actingAs($admin)
                ->get(route('admin.dashboard'))
                ->assertSee('Pending Plant Requests')
                ->assertSee('Pending Contributions');
        });
    });
});

describe('Route Authorization', function () {
    it('enforces plant policy for create routes', function () {
        $user = User::factory()->create(['role' => UserRoleEnum::User]);
        $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);

        // User should be denied
        $this->actingAs($user)
            ->get(route('plants.create'))
            ->assertForbidden();

        // Admin should be allowed
        $this->actingAs($admin)
            ->get(route('plants.create'))
            ->assertSuccessful();
    });

    it('enforces plant policy for edit routes', function () {
        $user = User::factory()->create(['role' => UserRoleEnum::User]);
        $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);
        $plant = Plant::factory()->create();

        // User should be denied
        $this->actingAs($user)
            ->get(route('plants.edit', $plant))
            ->assertForbidden();

        // Admin should be allowed
        $this->actingAs($admin)
            ->get(route('plants.edit', $plant))
            ->assertSuccessful();
    });

    it('enforces admin middleware on admin routes', function () {
        $user = User::factory()->create(['role' => UserRoleEnum::User]);
        $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);

        // User should be denied
        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        // Admin should be allowed
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertSuccessful();
    });

    it('allows all authenticated users to view plants', function () {
        $user = User::factory()->create(['role' => UserRoleEnum::User]);
        $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);
        $plant = Plant::factory()->create();

        // Both user types should be allowed
        $this->actingAs($user)
            ->get(route('plants.show', $plant))
            ->assertSuccessful();

        $this->actingAs($admin)
            ->get(route('plants.show', $plant))
            ->assertSuccessful();
    });

    it('allows only regular users to request plants (admins create directly)', function () {
        $user = User::factory()->create(['role' => UserRoleEnum::User]);
        $admin = User::factory()->create(['role' => UserRoleEnum::Admin]);

        // Regular users should be allowed
        $this->actingAs($user)
            ->get(route('plants.request'))
            ->assertSuccessful();

        // Admins should be forbidden (they create plants directly)
        $this->actingAs($admin)
            ->get(route('plants.request'))
            ->assertForbidden();
    });
});
