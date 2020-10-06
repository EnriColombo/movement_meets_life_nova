<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasFactory;
    use HasSlug;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * Returns the category of the event.
     */
    public function category() {
        return $this->belongsTo(EventCategory::class);
    }

    /**
     * Returns the venue of the event.
     */
    public function venue() {
        return $this->belongsTo(EventVenue::class);
    }

    /**
     * Get the repeat type of the event.
     */
    public function repeat_type() {
        return $this->belongsTo(EventRepeatType::class);
    }
}
