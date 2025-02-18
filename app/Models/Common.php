<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB; 

class Common extends Model
{
    use HasFactory;


    public  $output = array();

    public function allCategories(){
        $result = Category::all();
        $this->output = array();
        
        foreach($result as $category){

            if($category->parent_id == 0){
                if($category->has_child){

                    $this->output[] = $category;
                    $this->setChildren($result, $category->category_row_id);

                } else {
                    $this->output[] = $category;
                }
            } 
        }

        $output = $this->output;
        $this->output = array();
        return $output;
    }

    function setChildren($haystack, $parentCategoryId){  
        if( count($haystack)){  
            foreach($haystack as $category){
                if($category->parent_id && ($category->parent_id == $parentCategoryId)){
                  if($category->has_child){              
                        $this->output[] = $category;
                        $this->setChildren($haystack, $category->category_row_id);
                    } else {                  
                        $this->output[] = $category;
                    }
                }
            }
        }
    }

    public function allAttributes(){
        return $result =  Attribute::all();
    }
}
