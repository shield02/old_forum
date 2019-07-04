<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guest_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('replies/1/favorites')
            ->assertRedirect('login');
    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply()
    {
        // /replies/id/favorites
        $this->signIn();

        $reply = create('App\Reply'); // This created a thread also

        // If i post to a "favorite" endpoint
        $this->post('replies/'. $reply->id . '/favorites');

        // dd(\App\Favorite::all());

        // It should be recorded in the database
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $this->post('replies/'. $reply->id . '/favorites');
            $this->post('replies/'. $reply->id . '/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record twice.');
        }

        $this->assertCount(1, $reply->favorites);

    }
}