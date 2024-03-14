<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Http;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\ProductDetail;
use App\Models\Product;
class UpdateDetail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    public function handle()
    {
        $url = "https://tiki.vn/api/v2/products/{$this->productId}?platform=web&spid=217500643&version=3";
    
        try {
            // Gửi yêu cầu GET đến API chi tiết sản phẩm không đồng bộ
            $response = Http::get($url)->throw();
    
            // Lấy dữ liệu từ response
            $productData = $response->json();
    
            // Kiểm tra xem có dữ liệu hay không
            if (!empty($productData)) {
                // Fetch the corresponding Product model
               
    
                // Cập nhật thông tin chi tiết của sản phẩm trong cơ sở dữ liệu
                $productDetails = [
                    'price' => data_get($productData, 'price'),
                    'rate' => data_get($productData, 'rating_average'),
                    'quantity_sold' => data_get($productData, 'quantity_sold.value'),
                    'categories' => data_get($productData, 'categories.name')
                ];
            
                ProductDetail::where('product_id', $this->productId)->update($productDetails);
    
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