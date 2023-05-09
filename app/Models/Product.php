<?php

namespace App\Models;

use App\Mail\ProductAlertsMail;
use App\Mail\ThanksMail;
use App\Traits\NotificationTrait;
use App\Traits\ProductTrait;
use App\Traits\SerializeDateTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\Translatable\HasTranslations;
use stdClass;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory;
    use HasTranslations;
    use SoftDeletes;
    use SerializeDateTrait;
    use NotificationTrait;
    use LogsActivity;
    use ProductTrait;

    public static $none = 'none';
    public static $fixed = 'fixed';
    public static $percent = 'percent';
    public $translatable = ['title', 'short_title', 'faq', 'summary_name', 'description', 'meta_title', 'meta_description'];

    public $fillable = ['title',
        'short_title',
        'description',
        'description_meta',
        'title_meta',
        'slug',
        'priority',
        'category_id',
        'image',
        'price',
        'weight',
        'sku',
        'blocked_countries',
        'quantity',
        'status',
        'years'];
    protected $casts = [
        'blocked_countries' => 'array'
    ];


    public static function boot()
    {

        parent::boot();

        static::updated(function ($model) {
            if ($model->created_at < Carbon::now()->subMinutes(3)) {
                if ($model->quantity <= get_setting('low_product_quantity_alert') && $model->quantity > 0) {
                    $receivers['admins'] = Admin::query()->where('status', 1)->get();
                    $data = [
                        'title' => 'Low quantity',
                        'body' => $model->short_title . ' stock is low, There is only ' . $model->quantity . ' in the stock',
                    ];
                    if (get_setting('product_notifications'))
                        $model->sendNotification($receivers, 'low_quantity', $data, '/', Product::class, $model->id,);
                    $details['product'] = $model;
                    $details['title'] = 'Low quantity';
                    $details['content'] = $model->short_title . ' stock is low, There is only ' . $model->quantity . ' in the stock';
                    $details['button'] = 'show product';
//                Mail::to(json_decode(get_setting('product_notifications_receivers')))
//                    ->queue(new ProductAlertsMail('Low quantity', $details));

                } elseif ($model->quantity == 0) {
                    $receivers['admins'] = Admin::query()->where('status', 1)->get();
                    $data = [
                        'title' => 'Out of stock',
                        'body' => $model->short_title . ' is out of stock',
                    ];
                    if (get_setting('product_notifications'))
                        $model->sendNotification($receivers, 'out_of_stock', $data, '/', Product::class, $model->id,);
                    $details['product'] = $model;
                    $details['title'] = 'Out of stock';
                    $details['content'] = $model->short_title . ' is out of stock';
                    $details['button'] = 'show product';
//                Mail::to($receivers['admins'])
//                    ->bcc(json_decode(get_setting('product_notifications_receivers')))
//                    ->queue(new ProductAlertsMail('Out of stock', $details));
                }
            }

        });
    }

    public function orders()
    {
        $this->belongsToMany(Order::class, 'orders_products', 'product_id', 'order_id');
    }

    public function tickets(): MorphMany
    {
        return $this->morphMany(Ticket::class, 'model');
    }

    public function contactUs(): MorphMany
    {
        return $this->morphMany(ContactUs::class, 'model');
    }

    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function sub_attributes(): BelongsToMany
    {
        return $this->BelongsToMany(SubAttribute::class, 'products_attributes', 'product_id', 'sub_attribute_id')
            ->wherePivotNull('deleted_at');
    }

    public function brands(): HasMany
    {
        return $this->hasMany(ProductsBrand::class)->groupBy(['brand_id', 'brand_model_id'])->with(['brand', 'brand_year', 'brand_model']);
    }
//
//    public function brand()
//    {
//        return $this->belongsToMany(Brand::class, 'product_id','brand_id')->groupBy(['brand_id', 'brand_model_id'])->with(['brand', 'brand_year', 'brand_model']);
//    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function accessories()
    {
        return Product::query()->whereIn('id', json_decode($this->accessories, true))->pluck('slug');
    }

    public function bundled()
    {
        return Product::query()->whereIn('id', json_decode($this->bundled, true))->pluck('slug');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ProductsPackages::class);
    }

    public function serial_numbers(): HasMany
    {
        return $this->hasMany(ProductsSerialNumber::class);
    }


    public function attributes()
    {

        $ids = $this->sub_attributes()->where('status', 1)->groupBy('sub_attribute_id')->pluck('sub_attribute_id');
        $sub_attributes = SubAttribute::query()->where('status', 1)->whereIn('id', $ids)->pluck('attribute_id');
        $attributes = Attribute::query()->where('status', 1)->whereIn('id', $sub_attributes)->with('sub_attributes')->get();
        foreach ($attributes as $item) {
            $item->sub_attributes = $item->sub_attributes()->where('status', 1)->whereIn('id', $ids)->get();
        }
        return $attributes;
    }

    public function api_attributes()
    {
        $ids = $this->sub_attributes()->groupBy('sub_attribute_id')->pluck('sub_attribute_id');
        $sub_attributes = SubAttribute::query()->where('status', 1)->whereIn('id', $ids)->pluck('attribute_id');
        $attributes = Attribute::query()->where('status', 1)->whereIn('id', $sub_attributes)->with('sub_attributes')->get();
        $returned_attributes = [];
        foreach ($attributes as $item) {
            $returned_attributes[$item->name] = $item->sub_attributes()->whereNull('deleted_at')->whereIn('id', $ids)->pluck('value');
        }
        return $returned_attributes;

    }

    public function api_get_categories_parent()
    {
        $category = $this->category;
        $names = null;
        if (!empty($category)) {
            $names = $category->get_parents();
        }
        return $names;
    }

    public function api_accessories($currency)
    {
        $accessories = Product::query()->whereIn('id', json_decode($this->accessories, true))->get();
        $response_accessories = [];
        foreach ($accessories as $accessory) {
            $response_accessories[] = $accessory->api_shop_data($currency);
        }

        return $response_accessories;

    }

    public function api_bundleds($currency)
    {
        $bundleds = Product::query()->whereIn('id', json_decode($this->bundled, true))->get();

        $response_bundled = [];
        foreach ($bundleds as $bundled) {
            $response_bundled[] = $bundled->api_shop_data($currency);
        }

        return $response_bundled;

    }

    public function api_discount_form()
    {
        $discount = $this->discount_value == 0 ? new stdClass
            : [
                'type' => $this->discount_type,
                'value' => $this->discount_value,
            ];
        if ($this->discount_value == 0)
            return [];
        if ($this->end_date_discount != null && $this->end_date_discount > Carbon::now()) {
            $discount = $this->end_date_discount != null && is_array($discount) ? array_merge($discount, ['until' => Carbon::parse($this->end_date_discount)->endOfDay()->format('D M d Y H:i:s')]) : $discount;
        } else if (empty($this->end_date_discount)) {
            $discount = array_merge($discount, ['until' => '']);
        } else {
            return null;
        }
        return $discount;
    }

    public function api_shop_data($currency)
    {
        $categories_parent = $this->api_get_categories_parent();
        $offers = [];
        foreach ($this->offers as $key => $offer) {
            $offers[] = [
                'from' => $offer->from,
                'to' => $offer->to,
                'price' => api_currency($offer->price, $currency),
            ];
        }
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'short_title' => $this->short_title,
            'summary_name' => $this->summary_name,
            'slug' => $this->slug,
            'seo_description' => $this->meta_description,
            'sku' => $this->sku,
            'offers' => $offers,
            'stock' => $this->quantity,
            'price' => !$this->hide_price ? api_currency($this->price, $currency) :
                ["value" => '-', "currency" => $currency->symbol],
            'sale_price' => !$this->hide_price ? ($this->sale_price == 0 ? api_currency($this->price, $currency) : api_currency($this->sale_price, $currency)) : ["value" => '-', "currency" => $currency->symbol],
            'weight' => $this->weight,
            'attributes' => $this->api_attributes(),
            'categories' =>$categories_parent,
            'has_token' => isset($categories_parent[count($categories_parent) - 1]) && ($categories_parent[count($categories_parent) - 1]['slug'] == 'token' || $categories_parent[count($categories_parent) - 1]['slug'] == 'software'),
            'color' => $this->color ? ['name' => $this->color?->name, 'hex' => $this->color?->code] : new stdClass(),
            'gallery' => [get_multisized_image($this->image), get_multisized_image($this->secondary_image)],
            'is_best_seller' => $this->is_best_seller,
            'is_sale' => $this->sale_price == null ? 0 : 1,
            'saudi_branch' => $this->is_saudi_branch,
            'is_featured' => $this->is_featured,
            'is_free_shipping' => $this->is_free_shipping,
            'avg_rating' => Review::query()->where('status', 1)->where('product_id', $this->id)->average('rating'),
            'total_reviews' => Review::query()->where('status', 1)->where('product_id', $this->id)->count(),

            'hide_price' => $this->hide_price,
            'type' => trans('backend.category.' . $this->category?->type),
            'discount' => !$this->hide_price ? $this->api_discount_form() : null,
        ];
        return $data;
    }

    public function api_small_card_data($currency)
    {
        return [
            'sku' => $this->sku,
            'short_title' => $this->short_title,
            'slug' => $this->slug,
            'gallery' => [get_multisized_image($this->image), get_multisized_image($this->secondary_image)],
            'avg_rating' => number_format($this->avg_rating, 2),
            'total_reviews' => $this->total_reviews,
            'price' => api_currency($this->price, $currency),
            'sale_price' => api_currency($this->sale_price, $currency),
            'hide_price' => $this->hide_price,
            'type' => $this->category?->type,
            'stock' => $this->quantity,
            'discount' => $this->api_discount_form(),

        ];
    }

    public function last_visited(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'last_visited_products');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function outOfStock(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'out_of_stocks', 'product_id', 'user_id')->withPivot(['quantity'])->withTimestamps();
    }

    public function StockStatus(): HasMany
    {
        return $this->hasMany(ProductStockStatus::class);
    }

}
