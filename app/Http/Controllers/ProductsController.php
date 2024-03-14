<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        // Số sản phẩm trên mỗi trang
        $perPage = $request->input('per_page', 12);
    
        // Trang hiện tại
        $currentPage = $request->input('page', 1);

        // Tìm kiếm theo từ khóa
        $searchQuery = $request->input('search');
    
        // Lấy danh sách sản phẩm với phân trang và thông tin từ cả hai bảng
        $query = Product::join('product_details', 'products.id', '=', 'product_details.product_id')
            ->orderBy('products.id', 'desc')
            ->select('products.*', 'product_details.category as detail_category', 'product_details.price', 'product_details.rate', 'product_details.quantity_sold', 'product_details.categories');

        // Thêm điều kiện tìm kiếm nếu có từ khóa
        $this->addSearchCondition($query, $searchQuery);

        $products = $query->paginate($perPage, ['*'], 'page', $currentPage);
    
        return response()->json($products);
    }

    /**
     * Add search conditions to the query.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @param  string  $searchQuery
     * @return void
     */
    private function addSearchCondition($query, $searchQuery)
    {
        if ($searchQuery) {
            $query->where('products.name', 'like', "%$searchQuery%")
                  ->orWhere('product_details.categories', 'like', "%$searchQuery%");
        }
    }
    
}
