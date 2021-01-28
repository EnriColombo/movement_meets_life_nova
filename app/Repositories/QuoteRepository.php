<?php

namespace App\Repositories;

use App\Models\Quote;

class QuoteRepository implements QuoteRepositoryInterface
{

    /**
     * Get all Quote terms.
     *
     * @param int|null $recordsPerPage
     * @param array|null $searchParameters
     *
     * @return \Illuminate\Support\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(int $recordsPerPage = null, array $searchParameters = null)
    {
        $query = Quote::orderBy('created_at', 'desc');

        if (!is_null($searchParameters)) {
            if (!empty($searchParameters['author'])) {
                $query->where(
                    'author',
                    'like',
                    '%' . $searchParameters['author'] . '%'
                );
            }
            if (!empty($searchParameters['description'])) {
                $query->where(
                    'description',
                    'like',
                    '%' . $searchParameters['description'] . '%'
                );
            }
            if (!empty($searchParameters['status'])) {
                $query->currentStatus($searchParameters['status']);
            }
        }

        if ($recordsPerPage) {
            $results = $query->paginate($recordsPerPage);
        } else {
            $results = $query->get();
        }

        return $results;
    }

    /**
     * Get Quote term by id
     *
     * @param int $id
     * @return Quote
     */
    public function getById(int $id): Quote
    {
        return Quote::findOrFail($id);
    }

    /**
     * Store Quote term
     *
     * @param array $data
     * @return Quote
     */
    public function store(array $data): Quote
    {
        $quote = new Quote();
        $quote->author = $data['author'];
        $quote->description = $data['description'];

        $quote->save();

        return $quote->fresh();
    }

    /**
     * Update Quote term
     *
     * @param array $data
     * @param int $id
     * @return Quote
     */
    public function update(array $data, int $id): Quote
    {
        $quote = $this->getById($id);
        $quote->author = $data['author'];
        $quote->description = $data['description'];

        $quote->update();

        return $quote;
    }

    /**
     * Delete Quote term
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Quote::destroy($id);
    }
}
