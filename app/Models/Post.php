<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'created_by', 'slug', 'category_id', 'meta_description', 'meta_keywords', 'seo_title', 'image', 'status', 'featured', 'introimage_src', 'introimage_alt', 'before_content', 'after_content', 'extra_field_1', 'extra_field_2', 'extra_field_3', 'extra_field_trans_1', 'extra_field_trans_2',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // The saving / saved events will fire when a model is created or updated.
        static::saving(function ($model) {

            // Intro image picture upload
            /*if ($model->file('introimage')) {
                $introImagePictureFile = $model->file('introimage');
                $imageName = $introImagePictureFile->hashName();
                $model->introimage = $imageName;
            }*/

            // Remove all malicious code (XSS) - http://htmlpurifier.org/  - https://github.com/mewebstudio/Purifier
            //$model->body = clean($model->get('body'));  //package mews/purifier was causing errors with strange characters in the body.. I have uninstalled it
            $model->created_by = Auth::id();
        });
    }

    // https://stackoverflow.com/questions/48264084/how-to-get-unique-slug-to-same-post-title-for-other-time-too

    /**
     * Generates a unique slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Returns the user that owns the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Returns the roles for the user.
     */
    public function post_category() {
        return $this->belongsTo(PostCategory::class, 'category_id');
    }


}
