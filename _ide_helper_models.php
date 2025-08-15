<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $latin_name
 * @property string|null $family
 * @property string|null $genus
 * @property string|null $species
 * @property string|null $common_names
 * @property string|null $description
 * @property string|null $botanical_description
 * @property string|null $category
 * @property string|null $plant_type
 * @property string|null $growth_habit
 * @property string|null $native_region
 * @property int|null $height_min_cm
 * @property int|null $height_max_cm
 * @property int|null $width_min_cm
 * @property int|null $width_max_cm
 * @property string|null $bloom_time
 * @property string|null $flower_color
 * @property array<array-key, mixed>|null $leaf_characteristics
 * @property array<array-key, mixed>|null $flower_characteristics
 * @property array<array-key, mixed>|null $fruit_characteristics
 * @property string|null $bark_type
 * @property string|null $foliage_type
 * @property bool $is_edible
 * @property bool $is_toxic
 * @property array<array-key, mixed>|null $propagation_methods
 * @property string|null $image_url
 * @property array<array-key, mixed>|null $images
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read array $all_common_names
 * @property-read string $full_latin_name
 * @property-read string|null $height_range
 * @property-read string|null $width_range
 * @method static \Database\Factories\PlantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereBarkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereBloomTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereBotanicalDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereCommonNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereFlowerCharacteristics($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereFlowerColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereFoliageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereFruitCharacteristics($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereGenus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereGrowthHabit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereHeightMaxCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereHeightMinCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereIsEdible($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereIsToxic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereLatinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereLeafCharacteristics($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereNativeRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant wherePlantType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant wherePropagationMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereSpecies($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereWidthMaxCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Plant whereWidthMinCm($value)
 */
	class Plant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $user_id
 * @property int $id
 * @property int $plant_id
 * @property string $field_name
 * @property string|null $current_value
 * @property string $proposed_value
 * @property string|null $reason
 * @property string $status
 * @property string|null $admin_notes
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $field_display_name
 * @property-read \App\Models\Plant $plant
 * @property-read \App\Models\User|null $reviewedBy
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PlantContributionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereCurrentValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereFieldName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution wherePlantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereProposedValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantContribution whereUserId($value)
 */
	class PlantContribution extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $latin_name
 * @property string|null $family
 * @property string|null $description
 * @property string|null $reason
 * @property array<array-key, mixed>|null $proposed_data
 * @property string $status
 * @property string|null $admin_notes
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property int|null $created_plant_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Plant|null $createdPlant
 * @property-read \App\Models\User|null $reviewedBy
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\PlantRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereAdminNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereCreatedPlantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereFamily($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereLatinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereProposedData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlantRequest whereUserId($value)
 */
	class PlantRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $role
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

