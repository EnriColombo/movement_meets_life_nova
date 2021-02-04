<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsightSearchRequest;
use App\Http\Requests\InsightStoreRequest;
use App\Models\Insight;
use App\Services\InsightService;
use App\Services\TagService;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class InsightController extends Controller
{
    use CheckPermission;

    private InsightService $insightService;
    private TagService $tagService;

    /**
     * InsightController constructor.
     * @param  InsightService  $insightService
     * @param  TagService  $tagService
     */
    public function __construct(
        InsightService $insightService,
        TagService $tagService
    )
    {
        $this->insightService = $insightService;
        $this->tagService = $tagService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InsightSearchRequest $request)
    {
        $this->checkPermission('insights.view');

        $searchParameters = $this->insightService->getSearchParameters($request);
        $insights = $this->insightService->getInsights(20, $searchParameters);
        $statuses = Insight::PUBLISHING_STATUS;
        return view('insights.index', [
            'insights' => $insights,
            'searchParameters' => $searchParameters,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->checkPermission('insights.create');

        $countriesAvailableForTranslations = LaravelLocalization::getSupportedLocales();
        $tags = $this->tagService->getTags();

        return view('insights.create', [
            'countriesAvailableForTranslations' => $countriesAvailableForTranslations,
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InsightStoreRequest $request)
    {
        $this->checkPermission('insights.create');

        $this->insightService->createInsight($request);

        return redirect()->route('insights.index')
            ->with('success', 'Insight updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($insightId)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $insightId
     * @return \Illuminate\Http\Response
     */
    public function edit($insightId)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $insightId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $insightId)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $insightId
     * @return \Illuminate\Http\Response
     */
    public function destroy($insightId)
    {
        //
    }
}
