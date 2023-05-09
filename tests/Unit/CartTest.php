<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_cart()
    {
        $response = $this->post(route('api.user.cart.get'));
        $response->assertStatus(200);
    }

    /** @test */
    public function add_cart()
    {
        $user = User::factory()->create();
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response->assertStatus(200);
    }

    /** @test */
    public function add_multi_products()
    {
        $user = User::factory()->create();
        $this->withoutExceptionHandling();
        \Auth::guard('api')->login($user);

        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();
        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);

        $response = $this->post(route('api.user.cart.get'));
        $this->assertCount(3, $response['data']['products']);
    }

    /** @test */

    public function addCoupon()
    {
        $user = User::factory()->create();
        $this->withoutExceptionHandling();
        \Auth::guard('api')->login($user);

        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();
        $address = Address::factory()->create();

        $coupon = Coupon::factory()->create([
            'type' => 'Order',
            'minimum_shopping' => '10',
            'discount_type' => 'Amount'
        ]);

        $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);

//dump($coupon->discount, $coupon->discount_type);
        $this->call('post', route('api.user.cart.coupon.add'), ['code' => $coupon->code]);

        $response = $this->post(route('api.user.cart.get'));
dump($product->price);

        $this->assertDatabaseHas('carts', [
            'product_id'=>$product->id,
            'quantity' => 1,
            'price' => $product->price,
        ]);
    }
}
