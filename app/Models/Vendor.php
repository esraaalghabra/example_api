<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;

    protected $table = 'vendors';
    protected $fillable = ['name', 'address','details', 'mobile', 'email','password', 'photo', 'active',
        'photo_first','photo_second', 'main_category_id','sub_category_id', 'created_at', 'updated_at'
    ];
    protected $hidden = [ 'created_at', 'updated_at'];


    ######  Scopes
    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function scopeSelection($query){
        return $query->select('id', 'name', 'address','details', 'mobile', 'email', 'photo'
        ,'photo_first','photo_second' , 'active', 'main_category_id','sub_category_id');
    }

    public function scopeSelectU($query){
        return $query->active()
                     ->whereHas('mainCategory')
                     ->whereHas('subCategory')
                     ->whereHas('products')
                     ->select('id', 'name', 'address','details', 'mobile', 'photo','photo_first',
                         'photo_second', 'active', 'main_category_id','sub_category_id');
    }

    ######  Getters and Setters
    public function getPhotoAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function getPhotoFirstAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }
    public function getPhotoSecondAttribute($val){
        return ($val !== null) ? asset('assets/' . $val) : "";
    }

    public function setPasswordAttribute($password){
        if (!empty($password))
            $this->attributes['password'] = bcrypt($password);
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
        public function products(){
        return $this->hasMany('App\Models\Product', 'vendor_id', 'id');
    }

}
