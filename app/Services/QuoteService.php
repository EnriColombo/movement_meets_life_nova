<?php


namespace App\Services;

use App\Http\Requests\QuoteSearchRequest;
use App\Models\Quote;
use App\Repositories\QuoteRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QuoteService
{
    private QuoteRepository $quoteRepository;

    /**
     * QuoteService constructor.
     *
     * @param \App\Repositories\QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Return the team from the database
     *
     * @param $quoteId
     *
     * @return \App\Models\Quote
     */
    public function getById($quoteId)
    {
        return Quote::findById($quoteId);
    }

    /**
     * Get all the quotes
     *
     * @param int|null $recordsPerPage
     * @param array|null $searchParameters
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getQuotes(int $recordsPerPage = null, array $searchParameters = null)
    {
        return $this->quoteRepository->getAll($recordsPerPage, $searchParameters);
    }

    /**
     * Delete the quote from the database
     *
     * @param int $quoteId
     */
    public function deleteQuote(int $quoteId): void
    {
        $this->quoteRepository->delete($quoteId);
    }

    /**
     * Get the quote search parameters
     *
     * @param \App\Http\Requests\QuoteSearchRequest $request
     *
     * @return array
     */
    public function getSearchParameters(QuoteSearchRequest $request): array
    {
        $searchParameters = [];
        $searchParameters['author'] = $request->author ?? null;
        $searchParameters['description'] = $request->description ?? null;

        return $searchParameters;
    }

    /**
     *  Return a quote.
     *  And set the quote to shown, so it will not be show it again in the
     *  next days until all the others has been shown.
     *
     * @return \App\Models\Quote
     */
    public function getRandomQuote(): ?Quote
    {
        $today = Carbon::today();

        $quote = Quote::where('shown', 0)
            ->orWhere('shown_on', $today)
            ->first();

        $quote->shown = 1;
        $quote->shown_on = $today;
        $quote->save();

        // Reset the quotes shown when all the quotes has already been shown
        if ($quote == null) {
            Quote::update(['shown' => 0]);
            $quote = Quote::where('shown', 0)->first();
        }

        return $quote;
    }
}
