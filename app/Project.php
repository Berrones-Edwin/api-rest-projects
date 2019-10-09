<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// multi-idioma
use Astrotomic\Translatable\Translatable;
// Slug
use Cviebrock\EloquentSluggable\Sluggable;

class Project extends Model 
{
    //

    use Translatable;
    use Sluggable;
    
    public $translatedAttributes = ['title', 'description'];
    protected $fillable = ["slug","user_id","image","thumbnail"];

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
