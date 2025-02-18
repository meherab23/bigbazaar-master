<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Common;
use App\Models\Attribute;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderProduct;

class ApiController extends Controller
{
    public function getCategories()
    {
        $common_model = new Common();
        $all_categories = $common_model->allCategories();

        $category_array = array();
        foreach ($all_categories as $category_data) {
            $cid = $category_data->category_row_id;

            if ($category_data->parent_id == 0) {
                $category_array[$cid]['category_name'] = $category_data->category_name;
                $category_array[$cid]['category_image'] = $category_data->category_image;
            } else {
                $pcount = Product::where('category_id', $cid)->count();

                $category_array[$category_data->parent_id]['subcategory'][$cid]['category_name'] = $category_data->category_name;
                $category_array[$category_data->parent_id]['subcategory'][$cid]['category_image'] = $category_data->category_image;
                $category_array[$category_data->parent_id]['subcategory'][$cid]['product_count'] = $pcount;
            }
        }

        if (isset($category_array) && count($category_array) > 0) {
            return response()->json($category_array);
        } else {
            return response()->json(['error' => 'No Categories Found'], 404);
        }
    }

    public function getProductsByCategoryId($cid)
    {
        if (is_numeric($cid) && $cid > 0) {
            $products = Product::with('product_images', 'product_inventory', 'product_attribute', 'getCategory')->where('category_id', $cid)->get();

            if ($products->isEmpty()) {
                return response()->json(['error' => 'No products found for this category'], 404);
            } else {
                return response()->json($products);
            }
        } else {
            return response()->json(['error' => 'Invalid Category ID provided'], 400);
        }
    }

    public function getProductsById($pid)
    {
        if (is_numeric($pid) && $pid > 0) {
            $product_details = Product::with('product_images', 'product_inventory', 'product_attribute', 'getCategory')->where('product_id', $pid)->first();

            if (!$product_details) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $product_full_details = [
                'product_details' => $product_details,
                'size_data' => $this->getProductAttributes($product_details, 'Size'),
                'color_data' => $this->getProductAttributes($product_details, 'Color'),
                'size_numeric_data' => $this->getProductAttributes($product_details, 'Size (Numeric)'),
                'other_data' => $this->getProductAttributes($product_details, 'Other'),
            ];

            return response()->json($product_full_details);
        } else {
            return response()->json(['error' => 'Invalid Product ID provided'], 400);
        }
    }

    private function getProductAttributes($product, $attributeName)
    {
        $attribute_data = [];
        $attribute = Attribute::where('attribute_name', $attributeName)->first();

        if ($attribute) {
            $attribute_values = json_decode($attribute->attribute_value, true);
            foreach ($product->product_attribute as $product_attribute) {
                $attribute_titles = explode("+", $product_attribute->attribute_title);
                foreach ($attribute_titles as $title) {
                    if (in_array(trim($title), $attribute_values)) {
                        $attribute_data[] = trim($title);
                    }
                }
            }
        }

        return array_unique($attribute_data);
    }

    public function getAllFeaturedCategory()
    {
        $featured_category = Category::where('is_featured', 1)->withCount('total_products')->get();

        if ($featured_category->isEmpty()) {
            return response()->json(['error' => 'No featured categories found'], 404);
        } else {
            return response()->json($featured_category);
        }
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1'
        ]);

        $query = $request->input('query');

        $products = Product::where('product_title', 'like', "%$query%")
                           ->orWhere('short_description', 'like', "%$query%")
                           ->get();

        if ($products->isEmpty()) {
            return response()->json(['error' => 'No products found for the search query'], 404);
        } else {
            return response()->json($products);
        }
    }

    public function submitOrderDetails(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email',
            'mobileNumber' => 'required|string',
            'cartTotal' => 'required|numeric',
            'cartItems' => 'required|array',
        ]);

        $name = $request->firstname . ' ' . $request->lastname;
        $username = strtolower($request->firstname) . strtolower($request->lastname);
        $email = $request->email;
        $mobile_number = $request->mobileNumber;
        $cartTotal = $request->cartTotal;
        $cartItems = $request->cartItems;

        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => bcrypt('12345678'),
            'phone' => $mobile_number,
            'role' => 'site-user'
        ]);

        $order_db = new Order();
        $order_db->order_number = 'ORD-' . date('ymdhi') . mt_rand(1000, 9999);
        $order_db->total_amount = $cartTotal;
        $order_db->status = 'pending';
        $order_db->description = '';
        $order_db->user_id = $user->id;
        $order_db->save();

        foreach ($cartItems as $data) {
            $orderproduct_db = new OrderProduct();
            $orderproduct_db->order_id = $order_db->id;
            $orderproduct_db->product_id = $data['product_id'];
            $orderproduct_db->quantity = $data['quantity'];
            $orderproduct_db->price = $data['product_price'] * $data['quantity'];
            $orderproduct_db->save();
        }

        return response()->json([
            'user_data' => $user,
            'order_data' => $order_db,
        ]);
    }
}
