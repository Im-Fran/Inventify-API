<?php

namespace Tests\Feature\Business;

use App\Models\Business\Address;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Str;
use Tests\TestCase;

class DeleteBusinessTest extends TestCase {
    use RefreshDatabase;

    private array $fakeData = [
        'business' => [
            'name' => 'Mi Negocio',
        ],
        'address' => [
            'address_line_1' => 'Moneda',
            'street_reference' => 'S/N',
            'country' => 'Chile',
            'province' => 'Santiago',
            'city' => 'Santiago',
            'region' => 'Metropolitana',
            'postal_code' => '8320000',
            'latitude' => 33.44278,
            'longitude' => -70.65385,
        ],
    ];

    /* Prueba que un usuario puede eliminar su negocio */
    public function test_user_can_delete_business() {
        $user = User::factory()->create();
        $this->seed([PermissionsSeeder::class]);
        $address = Address::create($this->fakeData['address']);
        $business = $user->businesses()->create($this->fakeData['business'] + ['address_id' => $address->id]);
        $user->givePermissionTo('business.destroy');

        $response = $this->actingAs($user)->deleteJson(route('business.destroy', ['business' => $business->id]));

        $this->assertAuthenticated();
        $response->assertStatus(204);
    }

    /* Prueba que un usuario no puede eliminar un negocio que no le pertenece */
    public function test_user_cannot_delete_business_from_another_user() {
        $user = User::factory()->create();
        $this->seed([PermissionsSeeder::class]);
        $address = Address::create($this->fakeData['address']);
        $business = $user->businesses()->create($this->fakeData['business'] + ['address_id' => $address->id]);
        $anotherUser = User::factory()->create();
        $anotherUser->givePermissionTo('business.destroy');

        $response = $this->actingAs($anotherUser)->deleteJson(route('business.destroy', ['business' => $business->id]));

        $this->assertAuthenticated();
        $response->assertStatus(403);
    }

    /* Prueba que un usuario no puede eliminar un negocio si no tiene el permiso */
    public function test_user_cannot_delete_business_without_permission() {
        $user = User::factory()->create();
        $this->seed([PermissionsSeeder::class]);
        $address = Address::create($this->fakeData['address']);
        $business = $user->businesses()->create($this->fakeData['business'] + ['address_id' => $address->id]);

        $response = $this->actingAs($user)->deleteJson(route('business.destroy', ['business' => $business->id]));

        $this->assertAuthenticated();
        $response->assertStatus(403);
    }

    /* Prueba que un usuario no puede eliminar un negocio que no existe */
    public function test_user_cannot_delete_nonexistent_business() {
        $user = User::factory()->create();
        $this->seed([PermissionsSeeder::class]);
        $user->givePermissionTo('business.destroy');

        $response = $this->actingAs($user)->deleteJson(route('business.destroy', ['business' => Str::uuid()]));

        $this->assertAuthenticated();
        $response->assertStatus(404);
    }
}
