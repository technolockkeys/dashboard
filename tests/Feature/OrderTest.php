<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function add_cart()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response->assertStatus(200);
        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $this->assertEquals($product->price, $response['data']['order']['total']);
    }

    /** @test */
    public function create_order_with_quantity()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 3]);
        $response->assertStatus(200);
        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $this->assertEquals($product->price * 3, $response['data']['order']['total']);
    }

    /** @test */
    public function add_multi_product()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);
        $response->assertStatus(200);
        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $total_price = $product->price + $product_2->price + $product_3->price;
        $this->assertEquals($total_price, $response['data']['order']['total']);
    }

    /** @test */
    public function add_order_amount_coupon()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);
        $response->assertStatus(200);

        $coupon = Coupon::factory()->create([
            'type' => 'Order',
            'minimum_shopping' => '10',
            'discount_type' => 'Amount'
        ]);
        $this->call('post', route('api.user.cart.coupon.add'), ['code' => $coupon->code]);


        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $total_price = $product->price + $product_2->price + $product_3->price;
        $this->assertEquals($total_price - $coupon->discount, $response['data']['order']['total']);
    }

    /** @test */
    public function add_order_percentage_coupon()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);
        $response->assertStatus(200);

        $coupon = Coupon::factory()->create([
            'type' => 'Order',
            'minimum_shopping' => '10',
            'discount_type' => 'Percentage'
        ]);
        $this->call('post', route('api.user.cart.coupon.add'), ['code' => $coupon->code]);


        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $total_price = $product->price + $product_2->price + $product_3->price;
        $total_with_coupon = $total_price - (($coupon->discount / 100) * (float)$total_price);
        $this->assertEquals($total_with_coupon, $response['data']['order']['total']);
    }

    /** @test */
    public function add_product_percentage_coupon()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);
        $response->assertStatus(200);

        $coupon = Coupon::factory()->create([
            'type' => 'Product',
            'products_ids' => [$product->id, $product_2->id],
            'discount_type' => 'Percentage'
        ]);

        $this->call('post', route('api.user.cart.coupon.add'), ['code' => $coupon->code]);

        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);


        $total_coupon_applaible_products_price = $product->price + $product_2->price;

        $total_with_coupon = $total_coupon_applaible_products_price - (($coupon->discount / 100) * $total_coupon_applaible_products_price);
        $final_total = $total_with_coupon + $product_3->price;
        $this->assertEquals($final_total, $response['data']['order']['total']);
    }

    /** @test */
    public function add_product_amount_coupon()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);

        $coupon = Coupon::factory()->create([
            'type' => 'Product',
            'products_ids' => [$product->id, $product_2->id],
            'discount_type' => 'Amount',
        ]);


        $this->call('post', route('api.user.cart.coupon.add'), ['code' => $coupon->code]);

        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $total_with_coupon = $product->price + $product_2->price - ($coupon->discount * 2);

        $final_total = $total_with_coupon + $product_3->price;
        $this->assertEquals($final_total, $response['data']['order']['total']);
    }

    /** @test */
    public function stripe_payment_failed()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);


        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4000002500003155',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $this->assertEquals('failed', $response['data']['order']['payment_status']);

    }

    /** @test */
    public function repay_order()
    {
        $user = User::factory()->create(
            ['stripe_cust_id' => 'cus_Lzoh2TBX1653Dk']
        );
        $this->withoutExceptionHandling();
        \Auth::guard('web')->login($user);

        $this->actingAs($user, 'api');
        $product = Product::factory()->create();
        $product_2 = Product::factory()->create();
        $product_3 = Product::factory()->create();

        $address = Address::factory()->create();
        $this->assertNotNull($product);

        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_2->id, 'address' => $address->id, 'quantity' => 1]);
        $response = $this->call('post', route('api.user.cart.add'), ['product' => $product_3->id, 'address' => $address->id, 'quantity' => 1]);


        set_setting('strip_secret_test', 'sk_test_51LFyE6KzRX4XRAlDlqpW1lHXpXpvuqug6p62rsdPvF384Tj1t6gYZTto6gd7G0zRKsrJF74NiLw5UipFqVhAR7yD00XfzBeFqM');
        $response = $this->call('post', route('api.user.order.create'), [
            'address' => $address->id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4000002500003155',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $this->assertEquals('failed', $response['data']['order']['payment_status']);

        $order_id = $response['data']['order']['id'];

        $response = $this->call('post', route('api.user.order.repay'), [
            'order_id' => $order_id,
            'payment_method' => 'stripe',
            'card_id' => -1,
            'card_name' => 'ammar alrez ',
            'card_number' => '4242424242424242',
            'card_exp_month' => 1,
            'card_exp_year' => 2024,
            'card_cvc' => 555
        ]);

        $this->assertEquals('paid', $response['data']['order']['payment_status']);
    }

}
