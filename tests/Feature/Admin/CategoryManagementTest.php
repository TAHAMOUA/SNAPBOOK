<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->get('/admin/categories');

        $response->assertOk();
        $response->assertSee($category->category_name);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->post('/admin/categories', [
                'category_name' => 'Wedding Photography',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('categories', [
            'category_name' => 'Wedding Photography',
        ]);
    }

    public function test_duplicate_category_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Category::factory()->create(['category_name' => 'Wedding']);

        $response = $this
            ->actingAs($admin)
            ->post('/admin/categories', [
                'category_name' => 'Wedding',
            ]);

        $response->assertSessionHasErrors('category_name');
    }

    public function test_admin_can_update_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['category_name' => 'Wedding']);

        $response = $this
            ->actingAs($admin)
            ->put('/admin/categories/' . $category->id_category, [
                'category_name' => 'Wedding Photography',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $category->refresh();
        $this->assertEquals('Wedding Photography', $category->category_name);
    }

    public function test_admin_can_soft_delete_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($admin)
            ->delete('/admin/categories/' . $category->id_category);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertSoftDeleted('categories', [
            'id_category' => $category->id_category,
        ]);
    }

    public function test_client_cannot_access_category_management(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($client)
            ->get('/admin/categories');

        $response->assertForbidden();

        $response = $this
            ->actingAs($client)
            ->get('/admin/categories/create');

        $response->assertForbidden();

        $response = $this
            ->actingAs($client)
            ->post('/admin/categories', [
                'category_name' => 'Hacked',
            ]);

        $response->assertForbidden();

        $response = $this
            ->actingAs($client)
            ->delete('/admin/categories/' . $category->id_category);

        $response->assertForbidden();
    }
}