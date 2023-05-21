<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'details','price' ,'size','amount', 'photo','photo_first','photo_second','active',
        'main_category_id','sub_category_id','vendor_id','created_at', 'updated_at'
    ];
    protected $hidden = ['created_at', 'updated_at'];


    ######  Scopes
    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function scopeSelection($query){
        return $query->select('id', 'name', 'details','price' ,'size','amount', 'photo','photo_first','photo_second', 'active','main_category_id','sub_category_id','vendor_id');
    }

    public function scopeSelectU($query){
        return $query->active()
                     ->whereHas('mainCategory')
                     ->whereHas('subCategory')
                     ->whereHas('vendor')
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

    public function mainCategory(){
        return $this->belongsTo('App\Models\MainCategory', 'main_category_id', 'id');
    }
    public function subCategory(){
        return $this->belongsTo('App\Models\SubCategory', 'sub_category_id', 'id');
    }
    public function vendor(){
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }








}
