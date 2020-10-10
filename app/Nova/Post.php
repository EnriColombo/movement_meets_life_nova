<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Files;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Spatie\NovaTranslatable\Translatable;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Post::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            Translatable::make([
                Text::make('Title')
                    ->sortable()
                    ->rules('required', 'max:255'),
                Trix::make('Intro Text'),
                Trix::make('Body')
                    ->rules('required'),
                Text::make('Introimage Alt')->hideFromIndex(),
            ]),
            Image::make('Introimage')->disk('public')->hideFromIndex(),
            DateTime::make('Publish At')->hideFromIndex(),
            DateTime::make('Publish Until')->hideFromIndex(),
            Boolean::make('Is Published'),
            Boolean::make('Featured'),
            Textarea::make('Before Content')->rows(3),
            Textarea::make('After Content')->rows(3),
            BelongsTo::make('User')->hideFromIndex(),
            BelongsTo::make('Post Category'),
            BelongsToMany::make('Tags'),
            Images::make('Gallery')
                ->customPropertiesFields([
                    Boolean::make('Active'),
                    Markdown::make('Description'),
                ])
                ->croppable(false)
                ->hideFromIndex(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}