<?php


namespace App\Repositories;

use App\Models\Country;
use Illuminate\Support\Collection;


class CountryRepository implements CountryRepositoryInterface {

    /**
     * Get all PostCategories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll(): Collection
    {
        return Country::orderBy('name')->get();
    }

    /**
     * Get Country by id
     *
     * @param int $id
     * @return Country
     */
    public function getById(int $id)
    {
        return Country::findOrFail($id);
    }

    /**
     * Store Country
     *
     * @param $data
     * @return Country
     */
    public function store($data): Country
    {
        $country = new Country();
        $country->name = $data['name'];
        $country->code = $data['code'];
        $country->continent_id = $data['continent_id'];

        $country->save();

        return $country->fresh();
    }

    /**
     * Update Country
     *
     * @param $data
     * @param int $id
     * @return Country
     */
    public function update($data, int $id): Country
    {
        $country = $this->getById($id);
        $country->name = $data['name'];
        $country->code = $data['code'];
        $country->continent_id = $data['continent_id'];

        $country->update();

        return $country;
    }

    /**
     * Delete Country
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        Country::destroy($id);
    }
}