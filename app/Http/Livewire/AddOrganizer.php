<?php

namespace App\Http\Livewire;

use App\Helpers\ImageHelpers;
use App\Models\Organizer;
use App\Repositories\OrganizerRepository;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Livewire\Component;

class AddOrganizer extends Component
{
    public $organizers;
    public $selected;
    public $showModal = false;
    public $newOrganizer;
    public $profilePicture;

    protected $rules = [
        'newOrganizer.name' => ['required', 'string', 'max:255'],
        'newOrganizer.surname' => ['required', 'string', 'max:255'],
        'newOrganizer.email' => ['required', 'email', 'max:255'],
        'newOrganizer.phone' => ['nullable', 'string', 'max:255'],
        'newOrganizer.website' => ['nullable', 'url'],
        'newOrganizer.description' => ['required', 'string'],
        'profilePicture' => ['nullable'], // 5MB Max - , 'image','mimes:jpg,jpeg,png','max:5120'
    ];

    protected $listeners = [
        'fileUpload' => 'handleFileUpload',
    ];

    public function handleFileUpload($imageData)
    {
        $this->profilePicture = $imageData;
    }

    /**
     * The component constructor
     */
    public function mount($organizers, $selected)
    {
        $this->organizers = $organizers;
        $this->selected = $selected;
    }

    /**
     * Default component render method
     */
    public function render()
    {
        return view('livewire.add-organizer');
    }

    /**
     * Open the modal
     */
    public function openModal(): void
    {
        $this->organizer = new Organizer();
        $this->showModal = true;
    }

    /**
     * Close the modal
     */
    public function close(): void
    {
        $this->showModal = false;
    }

    /**
     * Store a newly created organizer in storage.
     */
    public function saveOrganizer()
    {
        $organizerRepository = App::make(OrganizerRepository::class);

        $this->validate();

        $organizer = $organizerRepository->store($this->newOrganizer);
        ImageHelpers::storeImageFromLivewireComponent($organizer, $this->profilePicture);

        $this->selected[] = $organizer->id;
        //$this->organizers[] = $organizer;

        $this->organizers = $organizerRepository->getAll();

        $this->emit('refreshOrganizersDropdown', ['organizer' => $organizer]);

        session()->flash('message', 'Organizer added successfully 😁'); //todo - replace this flash message with a variable to set true
        $this->showModal = false;

        $this->newOrganizer = [];
    }
}
