<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductDetail;

class FetchProductIdsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function handle()
    {
        $allProductIds = collect();
        $page = 1;
        $category = 'dien-thoai-may-tinh-bang';

        do {
            $response = Http::withHeaders([
                'Accept' => 'application/json, text/plain, */*',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Accept-Language' => 'vi,vi-VN;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5',
                'User-Agent' => 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Mobile Safari/537.36',
                'Referer' => "https://tiki.vn/{$category}/c1789",
            ])->get('https://tiki.vn/api/personalish/v1/blocks/listings', [
                'limit' => 12,
                'include' => 'advertisement',
                'is_mweb' => 1,
                'aggregations' => 2,
                'version' => 'home-persionalized',
                '_v' => '',
                'trackity_id' => '5b3b923e-d067-3881-5c9e-8db329713e89',
                'urlKey' => $category,
                'categoryId' => 1789,
                'category' => 1789,
                'page' => $page,
            ]);

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['data'])) {
                    $data = $responseData['data'];
                    $productIds = collect(array_column($data, 'id'));

                    $newProductIds = $productIds->diff(Product::pluck('id'));
                    $allProductIds = $allProductIds->merge($newProductIds);
                }

                $page++;
            } else {
                break;
            }

        } while ($responseData['paging']['current_page'] < $responseData['paging']['last_page']);

        DB::transaction(function () use ($allProductIds) {
            $allProductIds->each(function ($productId) {
                $product = Product::firstOrCreate(['id' => $productId]);
                $product_detail = ProductDetail::firstOrCreate(['product_id' => $productId]);
                // UpdateProductInfoJob::dispatch($productId);
            });
        });
    }
}
