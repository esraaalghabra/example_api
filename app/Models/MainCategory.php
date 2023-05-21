<?php

namespace App\Models;

use App\Observers\MainCategoryObserver;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table = 'main_categories';
    protected $fillable = ['name', 'details', 'photo','photo_first','photo_second',
         'active','created_at', 'updated_at',
    ];
    protected $hidden = ['created_at', 'updated_at'];


    ######  Scopes
    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function scopeSelection($query){
        return $query->select('id','name', 'details', 'photo','photo_first','photo_second', 'active');
    }

    public function scopeSelectU($query){
        return $query->active()
                     ->whereHas('subCategories')
                     ->whereHas('vendors')
                     ->whereHas('products')
                     ->selection();
    }

    ######  Getters
    public function getPhotoAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function getPhotoFirstAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function getPhotoSecondAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }

    ######  Methods
    public function getActive(){
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    ######  Relations
    public  function subCategories(){
        return $this -> hasMany('App\Models\SubCategory','main_category_id','id');
    }

    public function vendors(){
        return $this ->hasMany('App\Models\Vendor','main_category_id','id');
    }

    public function products(){
        return $this -> hasMany('App\Models\Product', 'main_category_id', 'id');
    }


}
