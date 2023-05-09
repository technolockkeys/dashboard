<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Product;
use App\Traits\NotificationTrait;
use Google\Exception;
use Illuminate\Console\Command;
use Wa72\HtmlPageDom\HtmlPage;

class GetCompetitorsPrice extends Command
{
    use NotificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'competitors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command to get competitors prices';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       try{
           $products = Product::query()->whereNotNull('competitors_price')->get();

           $receivers['admins'] = Admin::query()->where('status', 1)->get();
           foreach ($products as $product){
               $competitors_url = [];
               foreach (json_decode($product->competitors_price) as $price){

                   $selector = $price->selector == 'id'? '#': '.';
                    try{
                        $page = new HtmlPage(file_get_contents($price->url));
                        $competitor_price =$page->filter($selector.$price->name)->getCombinedText();
                        $competitors_url[] = [
                            'url' => $price->url,
                            'selector' => $price->selector,
                            'name' => $price->name,
                            'html_tag' => $price->html_tag,
                            'price' => $competitor_price,
                        ];

                        if(intval( ltrim($competitor_price,'$')) < $product->price){
                            $data = [
                                'title' => $product->short_title,
                                'body' => trans('backend.product.competitors_have_lower_price')
                            ];
                            $this->sendNotification($receivers, 'competitors_price' , $data, '/',Product::class , $product->id );
                        }
                    }catch (\Exception $exception){

                    }
               }

               $product->competitors_price = json_encode($competitors_url);
               $product->save();



           }
           return 0;
       }catch (Exception $exception){

       }

    }
}
