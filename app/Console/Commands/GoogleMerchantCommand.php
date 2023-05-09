<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use phpseclib3\Crypt\EC;
use Vitalybaev\GoogleMerchant\Feed;
use \Vitalybaev\GoogleMerchant\Product as ProductGoogle;
use Vitalybaev\GoogleMerchant\Product\Shipping;
use Vitalybaev\GoogleMerchant\Product\Availability\Availability;

class GoogleMerchantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:merchant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {            $products = Product::query()->whereNotNull('google_merchant')->get();
        $feed = new Feed(get_setting('title'), get_setting('app_url'), get_setting('meta_description'));

        foreach ($products as $product) {
            $data = json_decode($product->google_merchant, true);
            $item = new ProductGoogle();
            if (!empty($data['sku'])) $item->setId($data['sku']);
            if (!empty($data['title'])) $item->setTitle($data['title']);
            if (!empty($data['description'])) $item->setDescription($data['description']);
            if (!empty($data['category'])) $item->setGoogleCategory($data['category']);
            if (!empty($data['manufacturer'])) $item->setBrand($data['manufacturer']);
            if (!empty($data['link'])) $item->setLink($data['link']);
            if (!empty($data['price'])) $item->setPrice($data['price']);
            if (!empty($data['sale_price'])) $item->setSalePrice($data['sale_price']);
//            if (!empty($data['in_stock'])) $item->setCondition($data['in_stock']);
            if (!empty($data['weight'])) $item->setShippingWeight($data['weight']);
            if (!empty($data['mpn'])) $item->setMpn($data['mpn']);
            if (!empty($data['gtin'])) $item->setGtin($data['gtin']);

            //images
            if (!empty($data['image_url'])) $item->setImage($data['image_url']);
            if (!empty($data['gallery_url'])) {
                foreach ($data['gallery_url'] as $gallery_item) {
                    $item->setAdditionalImage($gallery_item);
                }
            }
            //attributes
            $attributes = [];
            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $attributes_item) {
                    if (!isset($attributes[$attributes_item['name']])) $attributes[$attributes_item['name']] = [];
                    $attributes[$attributes_item['name']] [] = $attributes_item['value'];
                }
            }

            foreach ($attributes as $key =>$attribute){
                $item->setAttribute($key, implode(',' ,$attribute));
            }
            /*
            //shipping
            if (!empty($data['shipping_costs'])) {
                foreach ($data['shipping_costs'] as $shipping_country) {
                    // shipping_method
                    #region dhl
                    $shipping_dhl = new Shipping();
                    $shipping_dhl->setCountry($shipping_country['name']);
                    $shipping_dhl->setPrice($shipping_country['dhl'] . ' USD');
                    $shipping_dhl->setService('DHL');
                    $item->setShipping($shipping_dhl);
                    #endregion
                    #region fedex
                    $shipping_fedex = new Shipping();
                    $shipping_fedex->setCountry($shipping_country['name']);
                    $shipping_fedex->setPrice($shipping_country['fedex'] . ' USD');
                    $shipping_fedex->setService('FEDEX');
                    $item->setShipping($shipping_fedex);
                    #endregion
                    #region aramex
                    $shipping_aramex = new Shipping();
                    $shipping_aramex->setCountry($shipping_country['name']);
                    $shipping_aramex->setPrice($shipping_country['aramex'] . ' USD');
                    $shipping_aramex->setService("aramex");
                    $item->setShipping($shipping_aramex);
                    #endregion
                    #region ups
                    $shipping_ups = new Shipping();
                    $shipping_ups->setCountry($shipping_country['name']);
                    $shipping_ups->setPrice($shipping_country['ups'] . ' USD');
                    $shipping_ups->setService('UPS Express');
                    $item->setShipping($shipping_ups);
                    #endregion
                }
            }
            */
            $feed->addProduct($item);
        }
        $feedXml = $feed->build();
        \Storage::disk('public')->put('xml/google_merchant.xml', $feedXml);


    }
}
