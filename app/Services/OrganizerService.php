<?php
namespace App\Services;

use App\Helpers\ImageHelpers;
use App\Http\Requests\OrganizerSearchRequest;
use App\Http\Requests\OrganizerStoreRequest;
use App\Models\Organizer;
use App\Repositories\OrganizerRepository;

class OrganizerService
{
    private OrganizerRepository $organizerRepository;

    /**
     * OrganizerService constructor.
     *
     * @param \App\Repositories\OrganizerRepository $organizerRepository
     */
    public function __construct(
        OrganizerRepository $organizerRepository
    ) {
        $this->organizerRepository = $organizerRepository;
    }

    /**
     * Create a organizer
     *
     * @param \App\Http\Requests\OrganizerStoreRequest $request
     *
     * @return \App\Models\Organizer
     * @throws \Spatie\ModelStatus\Exceptions\InvalidStatus
     */
    public function createOrganizer(OrganizerStoreRequest $request): Organizer
    {
        $organizer = $this->organizerRepository->store($request->all());
        ImageHelpers::storeImages($organizer, $request, 'profile_picture');

        $organizer->setStatus('published');

        return $organizer;
    }

    /**
     * Update the Organizer
     *
     * @param \App\Http\Requests\OrganizerStoreRequest $request
     * @param int $organizerId
     *
     * @return \App\Models\Organizer
     */
    public function updateOrganizer(OrganizerStoreRequest $request, int $organizerId): Organizer
    {
        $organizer = $this->organizerRepository->update($request->all(), $organizerId);

        ImageHelpers::storeImages($organizer, $request, 'profile_picture');
        ImageHelpers::deleteImages($organizer, $request, 'profile_picture');

        return $organizer;
    }

    /**
     * Return the organizer from the database
     *
     * @param int $organizerId
     *
     * @return \App\Models\Organizer
     */
    public function getById(int $organizerId): Organizer
    {
        return $this->organizerRepository->getById($organizerId);
    }

    /**
     * Get all the Organizers.
     *
     * @param int|null $recordsPerPage
     * @param array|null $searchParameters
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrganizers(int $recordsPerPage = null, array $searchParameters = null)
    {
        return $this->organizerRepository->getAll($recordsPerPage, $searchParameters);
    }

    /**
     * Delete the organizer from the database
     *
     * @param int $organizerId
     */
    public function deleteOrganizer(int $organizerId): void
    {
        $this->organizerRepository->delete($organizerId);
    }

    /**
     * Get the number of organizer created in the last 30 days.
     *
     * @return int
     */
    public function getNumberOrganizersCreatedLastThirtyDays(): int
    {
        return Organizer::whereDate('created_at', '>', date('Y-m-d', strtotime('-30 days')))->count();
    }
}
