<?php

namespace App;

use Baum;

class Category extends Baum\Node
{
    protected $fillable = ['name', 'parent_id'];

    protected $parentColumn = 'parent_id';

    public function products ()
    {
        return $this->hasMany(Product::class);
    }
}
