<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Redis;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp() :void
    {
        parent::setUp();

        $this->thread = factory('App\Thread')->create();

    }

    /** @test */
    function a_thread_has_a_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path()
        );
    }
    
    /** @test */
    function a_thread_has_a_creator() 
    {
        $thread = factory('App\Thread')->create();

        $this->assertInstanceOf('App\User', $this->thread->creator);
    }


    /** @test */
    function a_thread_has_replies()
    {

        $this->assertInstanceOf(
            'Illuminate\Database\Eloquent\Collection', $this->thread->replies
        );

    }

    /** @test */
    function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body'      => 'Foobar',
            'user_id'   => 1,
        ]);
        
        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body'      => 'Foobar',
                'user_id'   => 999,
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);

    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1, 
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /** @test */
    function a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);
    
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    // /** @test */
    // function a_thread_records_each_visit()
    // {
    //     $thread = make('App\Thread', ['id' => 1]);

    //     $thread->visits()->reset();

    //     $this->assertSame(0, $thread->visits()->count());
        
    //     $thread->visits()->record();

    //     $this->assertEquals(1, $thread->visits()->count());

    //     // $thread->recordVisits();
    //     // $this->assertEquals(2, $thread->visits());
    // }
}
