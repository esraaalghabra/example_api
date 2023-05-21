<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = ['name', 'details','main_category_id', 'photo',
        'photo_first','photo_second', 'active', 'created_at', 'updated_at'
    ];
    protected $hidden = ['created_at', 'updated_at'];


    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function scopeSelection($query){
        return $query->select('id','main_category_id', 'name', 'details', 'photo','photo_first','photo_second', 'active');
    }

    public function scopeSelectU($query){
        return $query->active()
                     ->whereHas('mainCategory')
                     ->whereHas('vendors')
                     ->whereHas('products')
                     ->selection();
    }

    ######  Getter
    public function getPhotoAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function getPhotoFirstAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function getPhotoSecondAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }

    public function getActive(){
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    ######  Relations
    public  function mainCategory(){
        return $this -> belongsTo('App\Models\MainCategory','main_category_id','id');
    }

    public function vendors(){
        return $this ->hasMany('App\Models\Vendor','sub_category_id','id');
    }

    public function products(){
        return $this -> hasMany('App\Models\Product', 'sub_category_id', 'id');
    }





}
