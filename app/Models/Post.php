<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $attributes = [
        'user_id' => false,
    ];

    protected $with = ['user','category','photos','tags'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function photos(){
        return $this->hasMany(Photo::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function getTitleAttribute($a){
        return Str::words($a,5);
    }

    public function getShowCreatedAtAttribute(){
        return '<p class="mb-0 small">
                    <i class="fas fa-calendar fa-fw"></i>
                     '.$this->created_at->format('d / m / Y').'
                </p>
                <p class="mb-0 small">
                    <i class="fas fa-clock fa-fw"></i>
                     '.$this->created_at->format("h:i a").'
                </p>';
    }

    public function setSlugAttribute($value){
        return $this->attributes['slug'] = Str::slug($value);
    }

    //query scope, local scope
    public function scopeSearch($query){
        if(isset(request()->search)){
            $keyword = request()->search;
            $query->orWhere('title','like','%'.$keyword.'%')->orWhere('description','like',"%$keyword%");
        }
    }

    protected static function booted()
    {
//        static::created(function(){
//            DB::listen(function ($query){
//                Log::info($query);
//            });
//        });
    }
}
