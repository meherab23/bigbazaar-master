<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Common;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\Request;
use DB;
Use Alert;
use Illuminate\Support\Facades\Redirect;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $common_model = new Common();      
        $data['all_records'] = $common_model->allCategories();
        return view('admin.category.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $common_model = new Common();      
        $data['all_records'] = $common_model->allCategories();
        //dd($data);
        return view('admin.category.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);
        $category_model = new Category();
        $category_model->category_name = $request->category_name;  
        $category_model->parent_id = $request->parent_id;
        $category_model->level = 0;

        if($category_model->parent_id) {
            $parent_cat_info =   DB::table('categories')->where('category_row_id', $category_model->parent_id)->first();
            $category_model->level = $parent_cat_info->level + 1;
        }


        if($request->category_image){
            $category_image   = $request->file('category_image');
            $filename         = time().'_'.$category_image->getClientOriginalName();
            //echo $filename; exit();
            $category_image->move(public_path('uploads/category').'/original/',$filename);
            
            $image_resize = Image::read(public_path('uploads/category').'/original/'.$filename);
            $image_resize->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('uploads/category').'/thumbnail/'.$filename);
            $category_model->category_image = $filename;

        } else {
            $category_model->category_image = null;    
        }

        $category_model->category_description = $request->long_desc;
        $category_model->is_featured = $request->is_featured ? 1 : 0;
        

        $category_model->save();

        if($category_model->parent_id) {
            if($parent_cat_info->has_child != 1) { 
                 DB::table('categories')
                  ->where('category_row_id', $request->parent_id)
                  ->update([
                    'has_child'=> 1
                  ]);
            }
        }

        $details = [
    
            'title' => '!!!! Category Creation Alert !!!!',
            'body' => 'A new category "'.$request->category_name.'" has been created'
    
        ];
        \Mail::to('romeoasif@gmail.com')->send(new \App\Mail\CategoryEmail($details));

        //Alert::success('Category Created Successfully!', 'success');    
        return Redirect::to('/admin/category');

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
        $common_model = new Common();       
        $data['all_records'] = $common_model->allCategories();
        $data['single_info'] = DB::table('categories')->where('category_row_id', $id)->first();
        //dd($data['single_info']);
        return view('admin.category.edit', ['data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category_model = new Category();

        $category_model = $category_model->find($request->category_row_id); // edit operation.
        $category_model->category_name = $request->category_name;  
        $category_model->parent_id = $request->parent_id;

        // parent changed ? 
        $parent_id_changed = 0;
        $prev_parent_id = DB::table('categories')->where('category_row_id', $request->category_row_id)->first()->parent_id;
        if($request->parent_id != $prev_parent_id) {
            $parent_id_changed = 1; // just status to understand parent id has been changed
        }

        // get level,  level = parent level + 1.
        $category_model->level = 0;
        if($category_model->parent_id) {
          // fetching modified parent id main category information
          $parent_cat_info =   DB::table('categories')->where('category_row_id',$category_model->parent_id)->first(); 
          $category_model->level = $parent_cat_info->level + 1;
        }

        if(isset($request->category_image)){
            $category_image   = $request->file('category_image');
            $filename         = time().'_'.$category_image->getClientOriginalName();

            $category_image->move(public_path('uploads/category').'/original/',$filename);
            $image_resize = Image::make(public_path('uploads/category').'/original/'.$filename);
            $image_resize->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $image_resize->save(public_path('uploads/category').'/thumbnail/'.$filename);
            $category_model->category_image = $filename;
        } else {
            $prev_category_image = DB::table('categories')->where('category_row_id', $request->category_row_id)->first()->category_image;
            if($prev_category_image != NULL){
                $category_model->category_image = $prev_category_image;    
            } else {
                $category_model->category_image = null;
            }
            
        }

        $category_model->save();

        // update has_child status of present parent         
        if($category_model->parent_id)
        {
           if($parent_cat_info->has_child != 1)
           { 
               DB::table('categories')->where('category_row_id', $request->parent_id)
                ->update([
                  'has_child'=> 1
                ]);
           }
        }

        // update  has_child status of previous parent 
        if($parent_id_changed){            
           $total_child_count = DB::table('categories')->where('parent_id', $prev_parent_id)->count();
           if($total_child_count == 0)
           {
                DB::table('categories')->where('category_row_id', $prev_parent_id)
                ->update([
                  'has_child'=> 0
                ]);
           }      
        }

        Alert::success('Category Updated Successfully!', 'success');
        return redirect()->route('category.index'); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
