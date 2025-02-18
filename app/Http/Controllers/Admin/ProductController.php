<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\ProductImage;
use App\Models\ProductInventory;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Redirect;
Use Alert;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_products = Product::with('product_images', 'getCategory', 'product_inventory', 'product_attribute')->get();

        //dd($all_products);
        return view('admin.products.index', compact('all_products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $common_model = new Common();
        
        $all_categories = $common_model->allCategories();
        $all_attributes = $common_model->allAttributes();

        return view('admin.products.create', compact('all_categories', 'all_attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);

        if($request->isMethod('post')) {

            $validated = $request->validate([
                'product_name' => 'required',
                'parent_id' => 'required',
                'product_sku' => 'required'
            ]);

            $product_db = new Product();
            $product_db->product_title = $request->product_name;
            $product_db->category_id = $request->parent_id;
            $product_db->short_description = $request->short_description ? $request->short_description : null;
            $product_db->long_description = $request->long_description ? $request->long_description : null;
            $product_db->brand_id = $request->brand_id ? $request->brand_id : null;
            $product_db->product_tags = $request->product_tags ? $request->product_tags : null;
            $product_db->product_model = $request->product_model ? $request->product_model : null;
            $product_db->product_sku = $request->product_sku;
            $product_db->product_price = $request->product_price;
            $product_db->product_unit = $request->product_unit;

            $product_db->is_featured = $request->is_featured ? 1 : 0;
            $product_db->top_selling = $request->top_selling ? 1 : 0;
            $product_db->is_refundable = $request->is_refundable ? 1 : 0;
            $product_db->created_by = Auth::guard('admin')->user()->id;

            $product_db->save();
            $pid = $product_db->product_id;

            //upload feature & gallery images
            if($pid && isset($request->feature_image)){
                $feature_image   = $request->file('feature_image');
                $filename        = time().'_'.$feature_image->getClientOriginalName();
                $product_image = new ProductImage();
                $product_image->product_id      = $pid;
                $product_image->feature_image   = $filename;

                $feature_image->move(public_path('uploads/products/').$pid.'/original/',$filename);
                $image_resize = Image::read(public_path('uploads/products/').$pid.'/original/'.$filename);
                $image_resize->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $save_path = public_path('uploads/products/').$pid.'/thumbnail/';
                if (!file_exists($save_path)) {
                    mkdir($save_path, 777, true);
                }
                $image_resize->save(public_path('uploads/products/').$pid.'/thumbnail/'.$filename);

                //upload gallery images
                if(isset($request->gallery_images)){
                    $gallery_images = $request->gallery_images;
                    //dd($gallery_images);
                    $galleryimg = array();
                    foreach ($gallery_images as $gdata) {
                        $gfilename         = time().'_'.$gdata->getClientOriginalName();
                        $galleryimg[]      = $gfilename;
                        $gdata->move(public_path('uploads/products/').$pid.'/gallery_images/',$gfilename);
                    }
                    $product_image->gallery_images = json_encode($galleryimg);
                }
                $product_image->save();
            }

            if($pid && isset($request->attr_price)){
                $total_quantity = 0;
                $attribute_price = $request->attr_price;
                $attribute_quantity = $request->attr_quantity;
                foreach ($attribute_price as $attribute_title => $value) {
                    $total_quantity += $attribute_quantity[$attribute_title];
                    $product_attribute = new ProductAttribute();
                    $product_attribute->product_id = $pid;
                    $product_attribute->attribute_title = $attribute_title;
                    $product_attribute->attribute_price = $value;
                    $product_attribute->attribute_quantity = $attribute_quantity[$attribute_title];
                    $product_attribute->save();
                }

                //insert/update stock quantity
                $product_inventory = new ProductInventory();
                $product_inventory->product_id = $pid;
                $product_inventory->stock_amount = $total_quantity;
                $product_inventory->save();
            }

            Alert::success('Product Added Successfully!', 'success');    
            return redirect()->route('products.index');

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function getProductDetails(Request $request){
        $pid = $request->pid;
        $product_details = Product::with('product_images', 'product_inventory', 'product_attribute', 'getCategory')->where('product_id', $pid)->first();
        $feature_image_url = asset('/uploads/products/'.$pid.'/original/'.$product_details->product_images->feature_image);

        $product_attributes = $product_details->product_attribute;
        //dd($product_attributes);
        $size_array = array();
        $size_numeric_array = array();
        $color_array = array();
        $other_array = array();
        $main_array = array();

        $all_attributes = Attribute::all(); 
       
        foreach ($all_attributes as $key => $value) {
          if($value->attribute_name == 'Size'){
            $main_array['size'] = json_decode($value->attribute_value, true);
          } elseif($value->attribute_name == 'Color'){
            $main_array['color'] = json_decode($value->attribute_value, true);
          } elseif($value->attribute_name == 'Size (Numeric)'){
            $main_array['size_numeric'] = json_decode($value->attribute_value, true);
          } else {
            $main_array['other'] = json_decode($value->attribute_value, true);
          }
        }

        //dd($product_attributes);

        foreach ($product_attributes as $pakey => $pavalue) {
            $parray = explode("+", $pavalue['attribute_title']);
            foreach ($parray as $key => $value) {
              //echo $value.'<br>';
              if(in_array(trim($value), $main_array['size'])){
                $size_array[] = trim($value); 
              } elseif(in_array(trim($value), $main_array['color'])){
                $color_array[] = trim($value);
              } elseif(in_array(trim($value), $main_array['size_numeric'])){
                $size_numeric_array[] = trim($value);
              } else {
                $other_array[] = trim($value);
              }
            }
            
        }
        $size_data = array_unique($size_array);
        $color_data = array_unique($color_array);
        $size_numeric_data = array_unique($size_numeric_array);

        //dd($size_numeric_data);

        $gallery_images = json_decode($product_details->product_images->gallery_images, true);
        //dd($gallery_images);
        $gallery_html = '';
        foreach ($gallery_images as $key => $value) {
            $gallery_image_url = asset('/uploads/products/'.$pid.'/gallery_images/'.$value);
            $gallery_html .= '<div class="product-image-thumb"><img src="'.$gallery_image_url.'" alt="Product Image"></div>';
        }

        $color_html = '';
        foreach ($color_data as $ckey => $cvalue) {
          $class_name = 'text-'.strtolower($cvalue);
          $color_html .= '<label class="btn btn-default text-center active">
                  <input type="radio" name="color_option" id="color_option_a'.$ckey.'" autocomplete="off" checked>
                  '.$cvalue.'
                  <br>
                  <i class="fas fa-circle fa-2x '.$class_name.'"></i>
                </label>';
        }

        $size_html = '';
        foreach ($size_data as $skey => $svalue) {
          $size_html .= '<label class="btn btn-default text-center">
                  <input type="radio" name="color_option" id="color_option_'.$skey.'" autocomplete="off">
                  <span class="text-xl">'.$svalue.'</span>
                  <br>
                </label>';
        }

        $size_numeric_html = '';
        foreach ($size_numeric_data as $skey => $svalue) {
          $size_numeric_html .= '<label class="btn btn-default text-center">
                  <input type="radio" name="color_option" id="color_option_'.$skey.'" autocomplete="off">
                  <span class="text-xl">'.$svalue.'</span>
                  <br>
                </label>';
        }

        $size_enable = (isset($size_html) && ($size_html != '')) ? $size_html : $size_numeric_html;

        $html ='<section class="content">
                    <div class="card card-solid">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                            <h3 class="d-inline-block d-sm-none">'.$product_details->product_title.'</h3>
                            <div class="col-12">
                                <img src="'.$feature_image_url.'" class="product-image" alt="Product Image">
                            </div>
                            <div class="col-12 product-image-thumbs">
                                <div class="product-image-thumb active"><img src="'.$feature_image_url.'" alt="Product Image"></div>
                                '.$gallery_html.'
                            </div>
                            </div>
                            <div class="col-12 col-sm-6">
                            <h3 class="my-3">'.$product_details->product_title.'</h3>
                            <p>'.$product_details->short_description.'</p>

                            <hr>
                            <h4>Available Colors</h4>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                '.$color_html.'
                            </div>

                            <h4 class="mt-3">Available Sizes</h4>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                '.$size_enable.'
                            </div>

                            <div class="bg-gray py-2 px-3 mt-4">
                                <h2 class="mb-0">
                                $'.$product_details->product_price.'
                                </h2>
                                <h4 class="mt-0">
                                <small>Ex Tax: $00.00 </small>
                                </h4>
                            </div>

                            </div>

                            </div>
                        </div>
                        <div class="row mt-4">
                            <nav class="w-100">
                            <div class="nav nav-tabs" id="product-tab" role="tablist">
                                <a class="nav-item nav-link active" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Description</a>
                                <a class="nav-item nav-link" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Comments</a>
                                <a class="nav-item nav-link" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">Rating</a>
                            </div>
                            </nav>
                            <div class="tab-content p-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab"> '.$product_details->long_description.'</div>
                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab"> Vivamus rhoncus nisl sed venenatis luctus. Sed condimentum risus ut tortor feugiat laoreet. Suspendisse potenti. Donec et finibus sem, ut commodo lectus. Cras eget neque dignissim, placerat orci interdum, venenatis odio. Nulla turpis elit, consequat eu eros ac, consectetur fringilla urna. Duis gravida ex pulvinar mauris ornare, eget porttitor enim vulputate. Mauris hendrerit, massa nec aliquam cursus, ex elit euismod lorem, vehicula rhoncus nisl dui sit amet eros. Nulla turpis lorem, dignissim a sapien eget, ultrices venenatis dolor. Curabitur vel turpis at magna elementum hendrerit vel id dui. Curabitur a ex ullamcorper, ornare velit vel, tincidunt ipsum. </div>
                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab"> Cras ut ipsum ornare, aliquam ipsum non, posuere elit. In hac habitasse platea dictumst. Aenean elementum leo augue, id fermentum risus efficitur vel. Nulla iaculis malesuada scelerisque. Praesent vel ipsum felis. Ut molestie, purus aliquam placerat sollicitudin, mi ligula euismod neque, non bibendum nibh neque et erat. Etiam dignissim aliquam ligula, aliquet feugiat nibh rhoncus ut. Aliquam efficitur lacinia lacinia. Morbi ac molestie lectus, vitae hendrerit nisl. Nullam metus odio, malesuada in vehicula at, consectetur nec justo. Quisque suscipit odio velit, at accumsan urna vestibulum a. Proin dictum, urna ut varius consectetur, sapien justo porta lectus, at mollis nisi orci et nulla. Donec pellentesque tortor vel nisl commodo ullamcorper. Donec varius massa at semper posuere. Integer finibus orci vitae vehicula placerat. </div>
                            </div>
                        </div>
                        </div>
                    </div>

                </section>';

        return $html;
    }

}
