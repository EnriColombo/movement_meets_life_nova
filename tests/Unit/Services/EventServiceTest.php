<?php

namespace Tests\Unit\Services;

use App\Http\Requests\EventStoreRequest;
use App\Models\Event;
use App\Models\EventRepetition;
use App\Models\Organizer;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Venue;
use App\Models\EventCategory;
use App\Services\EventService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class EventServiceTest extends TestCase{

    use WithFaker;
    use RefreshDatabase; // empty the test DB

    private EventService $eventService;

    private User $user1;
    private Collection $teachers;
    private Collection $organizers;
    private Collection $venues;
    private Event $event1;
    private Event $event2;
    private Event $event3;

    /**
     * Populate test DB with dummy data.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Write to log file
        file_put_contents(storage_path('logs/laravel.log'), "");

        // Seeders - /database/seeds
        $this->seed();

        $this->eventService = $this->app->make('App\Services\EventService');

        $this->user1 = User::factory()->create([
            'name' => 'Paolo',
            'email' => 'admin@gmail.com',
        ]);

        $this->teachers = Teacher::factory()->count(3)->create();
        $this->organizers = Organizer::factory()->count(3)->create();
        $this->venues = Venue::factory()->count(3)->create();

        $this->event1 = Event::factory()->create();
        $this->event2 = Event::factory()->create();
        $this->event3 = Event::factory()->create();
    }

    /** @test */
    public function it_should_create_an_event()
    {
        $user = $this->authenticateAsUser();

        $request = new EventStoreRequest();


        //dump(EventCategory::all());

        $data = [
            'title' => 'test title',
            'description' => 'test description',
            'contact_email' => 'test@newemail.com',
            'website_event_link' => 'www.link.com',
            'facebook_event_link' => 'www.facebookevent.com',
            'venue_id' => 1,
            'event_category_id' => 1,
            'repeat_type' => 1,
            'user_id' => 1,
        ];
        $request->merge($data);

        $this->eventService->createEvent($request);

        $this->assertDatabaseHas('events', ['title' => 'test title']);
    }

    /** @test */
    public function it_should_update_an_event()
    {
        $request = new EventStoreRequest();

        $data = [
            'title' => 'test title updated',
            'description' => 'test description',
            'contact_email' => 'test@newemail.com',
            'website_event_link' => 'www.link.com',
            'facebook_event_link' => 'www.facebookevent.com',
            'venue_id' => 1,
            'event_category_id' => 1,
            'repeat_type' => 1,
            'user_id' => 1,
        ];
        $request->merge($data);

        $this->eventService->updateEvent($request, $this->event1->id);

        $this->assertDatabaseHas('events', ['title' => 'test title updated']);
    }

    /** @test */
    public function it_should_return_event_by_id()
    {
        $event = $this->eventService->getById($this->event1->id);

        $this->assertEquals($this->event1->id, $event->id);
    }

    /** @test */
   public function it_should_return_all_events()
    {
        $events = $this->eventService->getEvents(20);
        $this->assertCount(3, $events);
    }

    /** @test */
    /*public function it_should_search_members_by_email()
    {
        $searchParameters = ['email' => 'info@aaa.com'];
        $users = $this->memberService->getMembers(20, $searchParameters);
        $this->assertCount(1, $users);
    }*/


    /** @test */
    /*public function it_should_search_members_by_region()
    {
        $searchParameters = ['regionId' => 5];
        $users = $this->memberService->getMembers(20, $searchParameters);
        $this->assertCount(1, $users);
    }*/

    /** @test */
    /*public function it_should_return_number_of_pending_members()
    {
        $numberPendingMembers = $this->memberService->countAllPendingMembers();

        $this->assertEquals(2, $numberPendingMembers);
    }*/

    public function it_should_return_event_date_time_parameters()
    {
        $event = $this->event1;
        $eventFirstRepetition = EventRepetition::first();

        $eventDateTimeParameters = $this->eventService->getEventDateTimeParameters($event, $eventFirstRepetition);

        $this->assertArrayHasKey('dateStart', $eventDateTimeParameters);
        $this->assertArrayHasKey('dateEnd', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeStart', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeEnd', $eventDateTimeParameters);
        $this->assertArrayHasKey('repeatUntil', $eventDateTimeParameters);
        $this->assertArrayHasKey('multipleDates', $eventDateTimeParameters);
    }










}