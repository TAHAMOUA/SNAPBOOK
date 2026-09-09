<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\PhotographerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AvailabilityTest extends TestCase
{
    use RefreshDatabase;

    private function photographerWithProfile(): array
    {
        $user = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        return [$user, $profile];
    }

    private function futureDate(): string
    {
        return now()->addDays(2)->format('Y-m-d');
    }

    private function pastDate(): string
    {
        return now()->subDay()->format('Y-m-d');
    }

    private function validPayload(string $startTime = '10:00', string $endTime = '12:00'): array
    {
        return [
            'available_date' => $this->futureDate(),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }

    public function test_photographer_with_profile_can_create_availability(): void
    {
        [$user, $profile] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload());

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('availabilities', [
            'available_date' => $this->futureDate(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'id_profile' => $profile->id_profile,
        ]);
    }

    public function test_client_cannot_create_availability(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload());

        $response->assertForbidden();
    }

    public function test_photographer_without_profile_cannot_create_availability(): void
    {
        $user = User::factory()->create(['role' => 'photographer']);

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload());

        $response->assertForbidden();
    }

    public function test_photographer_can_update_own_availability(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->patch('/availabilities/' . $availability->id_availability, $this->validPayload('14:00', '16:00'));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $availability->refresh();
        $this->assertEquals($this->futureDate(), $availability->available_date->format('Y-m-d'));
        $this->assertEquals('14:00', $availability->start_time);
        $this->assertEquals('16:00', $availability->end_time);
    }

    public function test_photographer_cannot_update_other_photographer_availability(): void
    {
        [$photographer1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile2->id_profile]);

        $response = $this
            ->actingAs($photographer1)
            ->patch('/availabilities/' . $availability->id_availability, $this->validPayload('14:00', '16:00'));

        $response->assertForbidden();
    }

    public function test_photographer_can_delete_own_availability(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->delete('/availabilities/' . $availability->id_availability);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSoftDeleted('availabilities', [
            'id_availability' => $availability->id_availability,
        ]);
    }

    public function test_photographer_cannot_delete_other_photographer_availability(): void
    {
        [$photographer1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile2->id_profile]);

        $response = $this
            ->actingAs($photographer1)
            ->delete('/availabilities/' . $availability->id_availability);

        $response->assertForbidden();

        $this->assertDatabaseHas('availabilities', [
            'id_availability' => $availability->id_availability,
        ]);
    }

    public function test_available_date_is_required(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'start_time' => '10:00',
                'end_time' => '12:00',
            ]);

        $response->assertSessionHasErrors('available_date');
    }

    public function test_invalid_available_date_is_rejected(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'available_date' => 'not-a-date',
                'start_time' => '10:00',
                'end_time' => '12:00',
            ]);

        $response->assertSessionHasErrors('available_date');
    }

    public function test_past_date_is_rejected(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'available_date' => $this->pastDate(),
                'start_time' => '10:00',
                'end_time' => '12:00',
            ]);

        $response->assertSessionHasErrors('available_date');
    }

    public function test_start_time_is_required(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'available_date' => $this->futureDate(),
                'end_time' => '12:00',
            ]);

        $response->assertSessionHasErrors('start_time');
    }

    public function test_invalid_start_time_is_rejected(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'available_date' => $this->futureDate(),
                'start_time' => '25:00',
                'end_time' => '12:00',
            ]);

        $response->assertSessionHasErrors('start_time');
    }

    public function test_end_time_is_required(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'available_date' => $this->futureDate(),
                'start_time' => '10:00',
            ]);

        $response->assertSessionHasErrors('end_time');
    }

    public function test_invalid_end_time_is_rejected(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', [
                'available_date' => $this->futureDate(),
                'start_time' => '10:00',
                'end_time' => '25:00',
            ]);

        $response->assertSessionHasErrors('end_time');
    }

    public function test_end_time_must_be_after_start_time(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload('12:00', '10:00'));

        $response->assertSessionHasErrors('end_time');
    }

    public function test_equal_start_and_end_time_is_rejected(): void
    {
        [$user] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload('10:00', '10:00'));

        $response->assertSessionHasErrors('end_time');
    }

    public function test_exact_duplicate_availability_is_rejected(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        Availability::factory()->create([
            'id_profile' => $profile->id_profile,
            'available_date' => $this->futureDate(),
            'start_time' => '10:00',
            'end_time' => '12:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload('10:00', '12:00'));

        $response->assertSessionHasErrors('start_time');
    }

    public function test_overlapping_availability_is_rejected(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        Availability::factory()->create([
            'id_profile' => $profile->id_profile,
            'available_date' => $this->futureDate(),
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload('10:00', '13:00'));

        $response->assertSessionHasErrors('start_time');
    }

    public function test_adjacent_availability_is_allowed(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        Availability::factory()->create([
            'id_profile' => $profile->id_profile,
            'available_date' => $this->futureDate(),
            'start_time' => '09:00',
            'end_time' => '12:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload('12:00', '15:00'));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('availabilities', [
            'id_profile' => $profile->id_profile,
            'available_date' => $this->futureDate(),
            'start_time' => '12:00',
            'end_time' => '15:00',
        ]);
    }

    public function test_availability_can_be_created(): void
    {
        [$user, $profile] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload());

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('availabilities', [
            'available_date' => $this->futureDate(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'id_profile' => $profile->id_profile,
        ]);
    }

    public function test_availability_can_be_updated(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->patch('/availabilities/' . $availability->id_availability, $this->validPayload('14:00', '16:00'));

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('availabilities', [
            'id_availability' => $availability->id_availability,
            'start_time' => '14:00',
            'end_time' => '16:00',
            'id_profile' => $profile->id_profile,
        ]);
    }

    public function test_availability_can_be_deleted(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->delete('/availabilities/' . $availability->id_availability);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSoftDeleted('availabilities', [
            'id_availability' => $availability->id_availability,
        ]);
    }

    public function test_availability_index_shows_only_own_availability(): void
    {
        [$photographer1, $profile1] = $this->photographerWithProfile();
        Availability::factory()->count(2)->create(['id_profile' => $profile1->id_profile]);

        [, $profile2] = $this->photographerWithProfile();
        Availability::factory()->create(['id_profile' => $profile2->id_profile]);

        $response = $this
            ->actingAs($photographer1)
            ->get('/availabilities');

        $response->assertOk();
        $this->assertEquals(2, Availability::where('id_profile', $profile1->id_profile)->count());
    }

    public function test_submitted_id_profile_is_ignored(): void
    {
        [$photographer1, $profile1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();

        $this
            ->actingAs($photographer1)
            ->post('/availabilities', $this->validPayload() + ['id_profile' => $profile2->id_profile]);

        $this->assertDatabaseHas('availabilities', [
            'available_date' => $this->futureDate(),
            'start_time' => '10:00',
            'end_time' => '12:00',
            'id_profile' => $profile1->id_profile,
        ]);

        $this->assertDatabaseMissing('availabilities', [
            'id_profile' => $profile2->id_profile,
        ]);
    }

    public function test_photographer_cannot_create_availability_for_another_profile(): void
    {
        [$photographer1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();

        $this
            ->actingAs($photographer1)
            ->post('/availabilities', $this->validPayload() + ['id_profile' => $profile2->id_profile]);

        $this->assertDatabaseMissing('availabilities', [
            'id_profile' => $profile2->id_profile,
        ]);
    }

    public function test_availability_string_id_generation(): void
    {
        [$user, $profile] = $this->photographerWithProfile();

        $this
            ->actingAs($user)
            ->post('/availabilities', $this->validPayload());

        $availability = Availability::where('id_profile', $profile->id_profile)->first();

        $this->assertStringStartsWith('AVL_', $availability->id_availability);
        $this->assertEquals(20, strlen($availability->id_availability));
        $this->assertFalse($availability->getIncrementing());
        $this->assertEquals('string', $availability->getKeyType());
    }

    public function test_availability_relationships(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $this->assertInstanceOf(PhotographerProfile::class, $availability->photographerProfile);
        $this->assertEquals($profile->id_profile, $availability->photographerProfile->id_profile);

        $this->assertInstanceOf(Availability::class, $profile->availabilities->first());
        $this->assertEquals(1, $profile->availabilities()->count());
    }

    public function test_normal_delete_soft_deletes_the_record(): void
    {
        [$user, $profile] = $this->photographerWithProfile();
        $availability = Availability::factory()->create(['id_profile' => $profile->id_profile]);

        $this
            ->actingAs($user)
            ->delete('/availabilities/' . $availability->id_availability);

        $this->assertSoftDeleted('availabilities', [
            'id_availability' => $availability->id_availability,
        ]);

        $this->assertNull(Availability::find($availability->id_availability));
        $this->assertNotNull(Availability::withTrashed()->find($availability->id_availability));
    }
}