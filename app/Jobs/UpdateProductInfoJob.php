<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\ProductDetail;

class UpdateProductInfoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function handle()
    {
        $url = "https://tiki.vn/api/v2/products/{$this->productId}";
    
        try {
            // Gửi yêu cầu GET đến API chi tiết sản phẩm không đồng bộ
            $response = Http::get($url)->throw();
    
            // Lấy dữ liệu từ response
            $productData = $response->json();

            // Kiểm tra xem có dữ liệu hay không
            if ($productData) {
                // Cập nhật thông tin chi tiết của sản phẩm trong cơ sở dữ liệu
                $product = Product::updateOrCreate(
                    ['id' => $this->productId],
                    [
                        'name' => data_get($productData,'name'),
                        'description' => data_get($productData,'short_description'),
                        'urlImg' => data_get($productData,'images.0.base_url'),
                        'urlproduct' => data_get($productData,'short_url'),
                        'category' => data_get($productData,'breadcrumbs.0.name'),
                    ]
                );

                
                // Log thông báo hoặc thông tin cần thiết
                \Log::info("Updated details for product ID: {$this->productId}");
            } else {
                \Log::error("Failed to retrieve details for product ID: {$this->productId}");
            }
        } catch (\Exception $e) {
            \Log::error("Error updating product ID: {$this->productId}. Error: {$e->getMessage()}");
        }
    }
}
