<?php

// Trong UpdateProductInfoBatch Job

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductInfoBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productIds;

    public function __construct(array $productIds)
    {
        $this->productIds = $productIds;
    }

    public function handle()
    {
        foreach ($this->productIds as $productId) {
            // Kiểm tra xem đã có thông tin chi tiết sản phẩm chưa
            $detail = ProductDetail::where('product_id', $productId)->first();

            // Nếu chi tiết sản phẩm không tồn tại, dispatch công việc
            if (!$detail) {
                UpdateProductInfoJob::dispatch($productId)->onQueue('update_product_info');
            }
        }
    }
}
