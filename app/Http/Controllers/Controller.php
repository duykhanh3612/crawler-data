<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Weidner\Goutte\GoutteFacade;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function crawlData()
    {
        $crawler = GoutteFacade::request('GET', 'https://tiki.vn/dien-thoai-may-tinh-bang/c1789?src=c.1789.hamburger_menu_fly_out_banner');

        $crawler->filter('.product-item')->each(function ($node) {
            $productName = $node->filter('.name')->text();
            $productPrice = $node->filter('.price-discount__price')->text();

            // Lưu hoặc xử lý dữ liệu tại đây
            dd($productName . ': ' . $productPrice) ;
        });
    }
    public function getProductIds()
    {
        $totalPages = 12;
        $productIds = [];

        $baseURL = 'https://tiki.vn/api/personalish/v1/blocks/listings';

        // Các tham số cố định
        $params = [
            'limit' => 40,
            'include' => 'advertisement',
            'aggregations' => 2,
            'version' => 'home-persionalized',
            'trackity_id' => '2f98d25e-5105-1509-29d3-9b04511767c1',
            'category' => 1789,
            'urlKey' => 'dien-thoai-may-tinh-bang',
        ];

        for ($page = 1; $page <= $totalPages; $page++) {
            // Thêm tham số trang vào mảng tham số
            $params['page'] = $page;

            // Xây dựng lại URL với các tham số
            $url = $baseURL . '?' . http_build_query($params);

            // Thực hiện yêu cầu HTTP
            $response = Http::get($url);

            // Kiểm tra xem yêu cầu có thành công không (200 OK)
            if ($response->successful()) {
                // Chuyển đổi phản hồi thành dạng mảng
                $data = $response->json();

                // Trích xuất các ID sản phẩm từ phản hồi và thêm vào mảng
                foreach ($data['data']['items'] as $item) {
                    $productIds[] = $item['product_id'];
                }
            } else {
                // Xử lý lỗi nếu cần thiết
                // Ví dụ: Log::error('Error fetching data from Tiki.vn: ' . $response->status());
            }
        }
}
}