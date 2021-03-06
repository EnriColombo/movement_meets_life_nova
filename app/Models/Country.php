<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Returns the continent of the event.
     */
    public function continent()
    {
        return $this->belongsTo(Continent::class);
    }

    /**
     * Return the regions in this country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    /**
     * Return the teachers based in this country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    /**
     * Return the organizers based in this country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizers()
    {
        return $this->hasMany(Organizer::class);
    }

    /**
     * Return the venues in this country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    /**
     * Return all the events in this country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function events()
    {
        return $this->hasManyThrough('Event', 'Venue');
    }

    /**
     * Return all the events in this country
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

}
