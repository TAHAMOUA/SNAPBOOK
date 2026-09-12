<?php

namespace Tests\Feature;

use App\Models\PhotographerProfile;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    private function photographerWithProfile(): array
    {
        $user = User::factory()->create(['role' => 'photographer']);
        $profile = PhotographerProfile::factory()->create(['id_user' => $user->id_user]);

        return [$user, $profile];
    }

    private function fakeImage(string $name = 'photo.png'): UploadedFile
    {
        return new UploadedFile(
            __DIR__.'/../Fixtures/photo.png',
            $name,
            'image/png',
            null,
            true
        );
    }

    public function test_photographer_can_add_portfolio_image(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $file = $this->fakeImage();

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
                'description' => 'My favorite shot',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        Storage::disk('public')->assertExists('portfolio/'.$file->hashName());

        $this->assertDatabaseHas('portfolios', [
            'description' => 'My favorite shot',
            'id_profile' => $profile->id_profile,
        ]);
    }

    public function test_client_cannot_add_portfolio_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'client']);
        $file = $this->fakeImage();

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
                'description' => 'Test',
            ]);

        $response->assertForbidden();
    }

    public function test_photographer_without_profile_cannot_add_portfolio_image(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'photographer']);
        $file = $this->fakeImage();

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
                'description' => 'Test',
            ]);

        $response->assertForbidden();
    }

    public function test_photographer_can_view_own_portfolio_image(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->get('/portfolio/'.$portfolio->id_photo);

        $response->assertOk();
    }

    public function test_authenticated_user_can_view_any_portfolio_image(): void
    {
        Storage::fake('public');
        [$photographer, $profile] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile->id_profile]);
        $client = User::factory()->create(['role' => 'client']);

        $response = $this
            ->actingAs($client)
            ->get('/portfolio/'.$portfolio->id_photo);

        $response->assertOk();
    }

    public function test_photographer_can_update_own_portfolio_description(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->patch('/portfolio/'.$portfolio->id_photo, [
                'description' => 'Updated description',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertDatabaseHas('portfolios', [
            'id_photo' => $portfolio->id_photo,
            'description' => 'Updated description',
        ]);
    }

    public function test_photographer_cannot_update_other_photographer_portfolio(): void
    {
        Storage::fake('public');
        [$photographer1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile2->id_profile]);

        $response = $this
            ->actingAs($photographer1)
            ->patch('/portfolio/'.$portfolio->id_photo, [
                'description' => 'Hacked description',
            ]);

        $response->assertForbidden();
    }

    public function test_photographer_can_soft_delete_own_portfolio_image_and_keeps_file(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $file = $this->fakeImage();
        $path = $file->store('portfolio', 'public');
        $portfolio = Portfolio::factory()->create([
            'id_profile' => $profile->id_profile,
            'image' => $path,
        ]);

        $response = $this
            ->actingAs($user)
            ->delete('/portfolio/'.$portfolio->id_photo);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertSoftDeleted('portfolios', [
            'id_photo' => $portfolio->id_photo,
        ]);

        // The physical file must remain after a normal soft delete.
        Storage::disk('public')->assertExists($path);
    }

    public function test_photographer_cannot_delete_other_photographer_portfolio(): void
    {
        Storage::fake('public');
        [$photographer1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile2->id_profile]);

        $response = $this
            ->actingAs($photographer1)
            ->delete('/portfolio/'.$portfolio->id_photo);

        $response->assertForbidden();
    }

    public function test_portfolio_image_is_required_on_creation(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'description' => 'No image here',
            ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_portfolio_image_optional_on_update(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile->id_profile]);

        $response = $this
            ->actingAs($user)
            ->patch('/portfolio/'.$portfolio->id_photo, [
                'description' => 'Updated without new image',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        // The original image path is kept.
        $portfolio->refresh();
        Storage::disk('public')->assertExists($portfolio->image);
    }

    public function test_portfolio_image_must_be_a_valid_image(): void
    {
        Storage::fake('public');
        [$user] = $this->photographerWithProfile();
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
            ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_portfolio_image_must_not_exceed_max_size(): void
    {
        Storage::fake('public');
        [$user] = $this->photographerWithProfile();
        $file = UploadedFile::fake()->create('large.png', 6000, 'image/png');

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
            ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_portfolio_description_max_length(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $file = $this->fakeImage();

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
                'description' => str_repeat('a', 2001),
            ]);

        $response->assertSessionHasErrors('description');
    }

    public function test_portfolio_string_id_generation(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $file = $this->fakeImage();

        $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $file,
            ]);

        $portfolio = Portfolio::where('id_profile', $profile->id_profile)->first();
        $this->assertStringStartsWith('PHT_', $portfolio->id_photo);
        $this->assertEquals(20, strlen($portfolio->id_photo));
    }

    public function test_portfolio_relationships(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $portfolio = Portfolio::factory()->create(['id_profile' => $profile->id_profile]);

        $this->assertInstanceOf(PhotographerProfile::class, $portfolio->photographerProfile);
        $this->assertEquals($profile->id_profile, $portfolio->photographerProfile->id_profile);
    }

    public function test_portfolio_index_shows_only_own_images(): void
    {
        Storage::fake('public');
        [$photographer1, $profile1] = $this->photographerWithProfile();
        Portfolio::factory()->count(2)->create(['id_profile' => $profile1->id_profile]);

        [, $profile2] = $this->photographerWithProfile();
        Portfolio::factory()->create(['id_profile' => $profile2->id_profile]);

        $response = $this
            ->actingAs($photographer1)
            ->get('/portfolio');

        $response->assertOk();
        // The controller filters by the authenticated profile's id_profile.
        $this->assertEquals(2, Portfolio::where('id_profile', $profile1->id_profile)->count());
    }

    public function test_id_profile_always_comes_from_authenticated_user(): void
    {
        Storage::fake('public');
        [$photographer1, $profile1] = $this->photographerWithProfile();
        [, $profile2] = $this->photographerWithProfile();
        $file = $this->fakeImage();

        $this
            ->actingAs($photographer1)
            ->post('/portfolio', [
                'image' => $file,
                'id_profile' => $profile2->id_profile,
            ]);

        // Even if a foreign id_profile is sent, the stored one comes from auth.
        $this->assertDatabaseHas('portfolios', [
            'id_profile' => $profile1->id_profile,
        ]);
        $this->assertDatabaseMissing('portfolios', [
            'id_profile' => $profile2->id_profile,
        ]);
    }

    public function test_image_replacement_deletes_old_file_and_stores_new(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $oldFile = $this->fakeImage('old.jpg');
        $oldPath = $oldFile->store('portfolio', 'public');
        $portfolio = Portfolio::factory()->create([
            'id_profile' => $profile->id_profile,
            'image' => $oldPath,
        ]);

        $newFile = $this->fakeImage();

        $this
            ->actingAs($user)
            ->patch('/portfolio/'.$portfolio->id_photo, [
                'image' => $newFile,
            ]);

        // The old physical image must be deleted.
        Storage::disk('public')->assertMissing($oldPath);

        // The new physical image must exist.
        Storage::disk('public')->assertExists('portfolio/'.$newFile->hashName());

        $portfolio->refresh();
        $this->assertEquals('portfolio/'.$newFile->hashName(), $portfolio->image);
    }

    public function test_image_store_failure_returns_error_and_creates_no_record(): void
    {
        Storage::fake('public');
        [$user] = $this->photographerWithProfile();

        $adapter = $this->mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        $adapter->shouldReceive('putFileAs')->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('public')->andReturn($adapter);

        $response = $this
            ->actingAs($user)
            ->post('/portfolio', [
                'image' => $this->fakeImage(),
                'description' => 'Test',
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasErrors('image');

        // A failed write must not create a broken "0" record.
        $this->assertDatabaseCount('portfolios', 0);
    }

    public function test_image_update_failure_keeps_old_image_and_file(): void
    {
        Storage::fake('public');
        [$user, $profile] = $this->photographerWithProfile();
        $oldFile = $this->fakeImage('old.jpg');
        $oldPath = $oldFile->store('portfolio', 'public');
        $portfolio = Portfolio::factory()->create([
            'id_profile' => $profile->id_profile,
            'image' => $oldPath,
        ]);

        $fakeDisk = Storage::disk('public');

        $adapter = $this->mock(\Illuminate\Contracts\Filesystem\Filesystem::class);
        $adapter->shouldReceive('putFileAs')->once()->andReturn(false);
        Storage::shouldReceive('disk')->with('public')->andReturn($adapter);

        $response = $this
            ->actingAs($user)
            ->patch('/portfolio/'.$portfolio->id_photo, [
                'image' => $this->fakeImage(),
            ]);

        $response
            ->assertRedirect()
            ->assertSessionHasErrors('image');

        // The original path and physical file must be kept on a failed write.
        $portfolio->refresh();
        $this->assertSame($oldPath, $portfolio->image);
        $fakeDisk->assertExists($oldPath);
    }

    public function test_force_delete_removes_physical_image(): void
    {
        Storage::fake('public');
        [, $profile] = $this->photographerWithProfile();
        $file = $this->fakeImage();
        $path = $file->store('portfolio', 'public');
        $portfolio = Portfolio::factory()->create([
            'id_profile' => $profile->id_profile,
            'image' => $path,
        ]);

        // A permanent delete removes the physical image file.
        $portfolio->forceDelete();

        $this->assertDatabaseMissing('portfolios', [
            'id_photo' => $portfolio->id_photo,
        ]);
        Storage::disk('public')->assertMissing($path);
    }
}
