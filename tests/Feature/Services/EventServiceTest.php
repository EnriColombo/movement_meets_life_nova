<?php

namespace Tests\Feature\Services;

use App\Http\Requests\EventStoreRequest;
use App\Models\Event;
use App\Models\EventRepetition;
use App\Models\Organizer;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Venue;
use App\Models\EventCategory;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class EventServiceTest extends TestCase
{
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
            'email' => 'admin@gmail.com',
        ]);

        $this->teachers = Teacher::factory()->count(3)->create();
        $this->organizers = Organizer::factory()->count(3)->create();
        $this->venues = Venue::factory()->count(3)->create();

        $this->event1 = Event::factory()->create()->setStatus('published');
        $this->event2 = Event::factory()->create()->setStatus('published');
        $this->event3 = Event::factory()->create()->setStatus('published');
    }

    /** @test */
    public function it_should_create_an_event()
    {
        $user = $this->authenticateAsUser();

        $request = new EventStoreRequest();
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
            'startDate' => '1/1/2020',
            'endDate' => '3/1/2020',
            'timeStartHours' => '06',
            'timeStartMinutes' => '00',
            'timeStartAmpm' => 'pm',
            'timeEndHours' => '08',
            'timeEndMinutes' => '00',
            'timeEndAmpm' => 'pm',
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
            'startDate' => '1/1/2020',
            'endDate' => '3/1/2020',
            'timeStartHours' => '06',
            'timeStartMinutes' => '00',
            'timeStartAmpm' => 'pm',
            'timeEndHours' => '08',
            'timeEndMinutes' => '00',
            'timeEndAmpm' => 'pm',
        ];
        $request->merge($data);

        $this->eventService->updateEvent($request, $this->event1->id);

        $this->assertDatabaseHas('events', ['title' => 'test title updated']);
    }

    /** @test */
    public function it_should_create_a_weekly_event()
    {
        $user = $this->authenticateAsUser();

        $request = new EventStoreRequest();
        $data = [
            'title' => 'weekly event title',
            'description' => 'test description',
            'contact_email' => 'test@newemail.com',
            'website_event_link' => 'www.link.com',
            'facebook_event_link' => 'www.facebookevent.com',
            'venue_id' => 1,
            'event_category_id' => 1,
            'repeat_type' => 2, // Weekly
            'user_id' => 1,
            'startDate' => '11/01/2021',
            'endDate' => '11/01/2021',
            'timeStartHours' => '06',
            'timeStartMinutes' => '00',
            'timeStartAmpm' => 'pm',
            'timeEndHours' => '08',
            'timeEndMinutes' => '00',
            'timeEndAmpm' => 'pm',
            "repeat_weekly_on" => [
                2 => "on",
                5 => "on",
            ],
            'repeat_until' => '20/01/2021',
        ];
        $request->merge($data);

        $event = $this->eventService->createEvent($request);

        $this->assertDatabaseHas('events', [
            'title' => 'weekly event title',
            'repeat_weekly_on' => '2,5', // Tuesday, Friday
        ]);

        $this->assertDatabaseHas('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-01-12 18:00:00',
            'end_repeat' => '2021-01-12 20:00:00',
        ]);

        $this->assertDatabaseHas('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-01-15 18:00:00',
            'end_repeat' => '2021-01-15 20:00:00',
        ]);

        $this->assertDatabaseHas('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-01-19 18:00:00',
            'end_repeat' => '2021-01-19 20:00:00',
        ]);

        $this->assertDatabaseMissing('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-01-22 18:00:00',
            'end_repeat' => '2021-01-22 20:00:00',
        ]);
    }

    /** @test */
    public function it_should_create_a_monthly_event()
    {
        $user = $this->authenticateAsUser();

        $request = new EventStoreRequest();
        $data = [
            'title' => 'monthly event title',
            'description' => 'test description',
            'contact_email' => 'test@newemail.com',
            'website_event_link' => 'www.link.com',
            'facebook_event_link' => 'www.facebookevent.com',
            'venue_id' => 1,
            'event_category_id' => 1,
            'repeat_type' => 3, // Monthly
            'user_id' => 1,
            'startDate' => '1/01/2021',
            'endDate' => '1/01/2021',
            'timeStartHours' => '06',
            'timeStartMinutes' => '00',
            'timeStartAmpm' => 'pm',
            'timeEndHours' => '08',
            'timeEndMinutes' => '00',
            'timeEndAmpm' => 'pm',
            "on_monthly_kind" => "1|1|5", // First Friday of the month
            'repeat_until' => '20/3/2021',
        ];
        $request->merge($data);

        $event = $this->eventService->createEvent($request);

       $this->assertDatabaseHas('events', [
            'title' => 'monthly event title',
            'on_monthly_kind' => '1|1|5',
            'repeat_type' => '3',
        ]);

        $this->assertDatabaseHas('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-01-01 18:00:00',
            'end_repeat' => '2021-01-01 20:00:00',
        ]);

        $this->assertDatabaseHas('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-02-05 18:00:00',
            'end_repeat' => '2021-02-05 20:00:00',
        ]);

        $this->assertDatabaseHas('event_repetitions', [
            'event_id' => $event->id,
            'start_repeat' => '2021-03-05 18:00:00',
            'end_repeat' => '2021-03-05 20:00:00',
        ]);
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

    /** @test */
    public function it_should_return_event_date_time_parameters()
    {
        $event = $this->event1;
        $eventFirstRepetition = EventRepetition::first();

        $eventDateTimeParameters = $this->eventService->getEventDateTimeParameters($event, $eventFirstRepetition);

        $this->assertArrayHasKey('dateStart', $eventDateTimeParameters);
        $this->assertArrayHasKey('dateEnd', $eventDateTimeParameters);

        //$this->assertArrayHasKey('timeStart', $eventDateTimeParameters);
        //$this->assertArrayHasKey('timeEnd', $eventDateTimeParameters);

        $this->assertArrayHasKey('timeStartHours', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeStartMinutes', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeStartAmpm', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeEndHours', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeEndMinutes', $eventDateTimeParameters);
        $this->assertArrayHasKey('timeEndAmpm', $eventDateTimeParameters);

        $this->assertArrayHasKey('repeatUntil', $eventDateTimeParameters);
        $this->assertArrayHasKey('multipleDates', $eventDateTimeParameters);
    }

    /** @test */
    public function it_should_return_event_monthly_select_options()
    {
        $date = '16/11/2020';
        $options = $this->eventService->getMonthlySelectOptions($date);

        $this->assertStringContainsString("<option value='0|16'>the 16th day of the month</option>", $options);
        $this->assertStringContainsString("<option value='1|3|1'>the 3rd Monday of the month</option>", $options);
        $this->assertStringContainsString("<option value='2|14'>the 15th to last day of the month</option>", $options);
        $this->assertStringContainsString("<option value='3|2|1'>the 3rd to last Monday of the month</option>", $options);

        $this->assertStringContainsString("<select name='on_monthly_kind' id='on_monthly_kind' class='selectpicker' title='Select start date first'><option value='0|16'>the 16th day of the month</option><option value='1|3|1'>the 3rd Monday of the month</option><option value='2|14'>the 15th to last day of the month</option><option value='3|2|1'>the 3rd to last Monday of the month</option></select>", $options);

    }

    /** @test */
    public function it_should_return_event_repetition_string_empty_for_one_time_event()
    {
        $event = Event::factory()->create([
            'repeat_type' => 1,
        ]);
        $eventRepetition = EventRepetition::factory()->create([
            'event_id' => $event->id,
        ]);

        $repetitionTextString = $this->eventService->getRepetitionTextString($event, $eventRepetition);

        $this->assertEquals("", $repetitionTextString);
    }

    /** @test */
    public function it_should_return_event_repetition_weekly_string_for_weekly_repeat_event()
    {
        $event = Event::factory()->create([
            'repeat_type' => 2,
            'repeat_weekly_on' => '1,3',
            'repeat_until' => Carbon::createFromFormat('d/m/Y', "16/12/2025"),
        ]);

        $eventRepetition = EventRepetition::factory()->create([
            'event_id' => $event->id,
        ]);

        $repetitionTextString = $this->eventService->getRepetitionTextString($event, $eventRepetition);

        $this->assertEquals("The event happens every Monday and Wednesday until 16/12/2025", $repetitionTextString);
    }

    /** @test */
    public function it_should_return_event_repetition_monthly_string_for_monthly_repeat_event()
    {
        $event = Event::factory()->create([
            'repeat_type' => 3,
            'on_monthly_kind' => '1|4|1',
            'repeat_until' => Carbon::createFromFormat('d/m/Y', "16/12/2025"),
        ]);

        $eventRepetition = EventRepetition::factory()->create([
            'event_id' => $event->id,
        ]);

        $repetitionTextString = $this->eventService->getRepetitionTextString($event, $eventRepetition);

        $this->assertEquals("The event happens the 4th Monday of the month until 16/12/2025", $repetitionTextString);
    }

    /** @test */
    public function it_should_return_event_repetition_multiple_dates_string_for_multiple_dates_repeat_event()
    {
        $event = Event::factory()->create([
            'repeat_type' => 4,
            'multiple_dates' => '1/3/2020,15/5/2020,7/6/2020',
        ]);

        $eventRepetition = EventRepetition::factory()->create([
            'event_id' => $event->id,
            'start_repeat' => Carbon::createFromFormat('d/m/Y', "14/1/2020"),
        ]);

        $repetitionTextString = $this->eventService->getRepetitionTextString($event, $eventRepetition);

        $this->assertEquals("The event happens on this dates: 14/01/2020, 1/3/2020, 15/5/2020, 7/6/2020", $repetitionTextString);
    }

    /** @test */
    public function it_should_return_report_misuse_reason_description()
    {
        $reportMisuseReasonDescription = $this->eventService->getReportMisuseReasonDescription(1);

        $this->assertEquals("Not about Contact Improvisation", $reportMisuseReasonDescription);
    }

    /** @test */
    public function it_should_return_on_mothly_kind_day_of_the_month_string()
    {
        $onMonthlyKindCode = '0|7';
        $onMonthlyKind = $this->eventService->decodeOnMonthlyKind($onMonthlyKindCode);

        $this->assertEquals("the 7th day of the month", $onMonthlyKind);
    }

    /** @test */
    public function it_should_return_on_mothly_kind_weekday_of_the_month_from_month_beginning_string()
    {
        $onMonthlyKindCode = '1|2|4';
        $onMonthlyKind = $this->eventService->decodeOnMonthlyKind($onMonthlyKindCode);

        $this->assertEquals("the 2nd Thursday of the month", $onMonthlyKind);
    }

    /** @test */
    public function it_should_return_on_mothly_kind_day_of_the_month_from_month_end_string()
    {
        $onMonthlyKindCode = '2|20';
        $onMonthlyKind = $this->eventService->decodeOnMonthlyKind($onMonthlyKindCode);

        $this->assertEquals("the 21st to last day of the month", $onMonthlyKind);
    }

    /** @test */
    public function it_should_return_on_mothly_kind_weekday_of_the_month_from_month_end_string()
    {
        $onMonthlyKindCode = '3|3|4';
        $onMonthlyKind = $this->eventService->decodeOnMonthlyKind($onMonthlyKindCode);

        $this->assertEquals("the 4th to last Thursday of the month", $onMonthlyKind);
    }

    /** @test */
    public function itShouldDeleteAnEvent()
    {
        $this->eventService->deleteEvent($this->event1->id);
        $this->assertDatabaseMissing('events', ['id' => $this->event1->id]);
    }

    /** @test */
    public function itShouldGetNumberTeachersCreatedLastThirtyDays()
    {
        $numberEventsCreatedLastThirtyDays = $this->eventService->getNumberEventsCreatedLastThirtyDays();
        $this->assertEquals($numberEventsCreatedLastThirtyDays, 3);
    }







}