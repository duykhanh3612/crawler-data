<?php

namespace App\Http\Controllers;
use App\Jobs\FetchProductIdsJob;

use App\Jobs\UpdateDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Jobs\UpdateProductInfoJob;

class TikiController extends Controller
{

    public function fetchProductIds()
    {
        $payload = [
            'limit' => 40,
            'include' => 'advertisement',
            'aggregations' => 2,
            'version' => 'home-persionalized',
            'trackity_id' => '2f98d25e-5105-1509-29d3-9b04511767c1',
            'category' => 1789,
            'page' => 1,
            'urlKey' => 'dien-thoai-may-tinh-bang',
        ];

        dispatch(new FetchProductIdsJob($payload));
    }


    

    // public function crawlerproduct()
    // {
    //     $productIds = Product::pluck('id')->toArray();
    //     $chunkedProductIds = array_chunk($productIds, 10); // Điều chỉnh kích thước lô nếu cần

    //     foreach ($chunkedProductIds as $batchProductIds) {
    //         dispatch(new FetchProductDetailsJob($batchProductIds));
    //     }
    // }
    public function updateAllProductInfo()
    {
        $products = Product::whereNull('name')->get();
    
        foreach ($products as $product) {
            // Check if the product exists and dispatch the job
            if ($product->exists()) {
                UpdateProductInfoJob::dispatch($product->id);
            } else {
                // Log or handle the case where the product is not found
                \Log::warning("Product with ID {$product->id} not found.");
            }
        }
    
        return response()->json(['message' => 'Updating product information for products with null name.']);
    }
    
    
    public function updatedetails()
    {
        $productIds = ProductDetail::pluck('Product_id');
    
        foreach ($productIds as $productId) {
            $product = ProductDetail::where('product_id',$productId)->first();
    
            // Check if the product exists
            if ($product) {
                $categories = $product->categories;
    
                // Check if price is null or empty
                if ($categories === null || $categories === "") {
                    UpdateDetail::dispatch($productId);
                }
            } else {
                // Log or handle the case where the product is not found
                \Log::warning("Product with ID {$productId} not found.");
            }
        }
    
        return response()->json(['message' => 'Updating product information for all products.']);
    }
    
}

    