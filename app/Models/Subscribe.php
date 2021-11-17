<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Subscribe extends Pivot
{
    protected $table = 'subscribes';

    protected $fillable = [
    'settlement_date',
    'end_date',
    'continue',
    'user_id',
    'menu_id',
    'shop_id',
    ];

//    protected $with = ['shops','menus'];

    protected $attributes = [
        'continue' => true,
    ];


    public function shop(){
        return $this->belongsTo('App\Models\Shop','shop_id','id');
    }

    public function menu(){
        return $this->belongsTo('App\Models\Menu','menu_id','id');
    }

}
