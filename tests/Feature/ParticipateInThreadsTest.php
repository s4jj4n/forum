<?php

namespace Tests\Feature;

use App\Activity;
use App\Favorite;
use App\Reply;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');

    }

     /** @test */
     function unauthorized_users_cannot_delete_replies()
     {
         $this->withExceptionHandling();

         $reply = create('App\Reply');

         $this->delete("/replies/{$reply->id}")
             ->assertRedirect('login');

         $this->signIn();

         $this->delete("/replies/{$reply->id}")
             ->assertStatus(403);
         
     }
     
     /** @test */
     function authorized_users_can_delete_replies()
     {
         $this->signIn();

         $reply = create(Reply::class, ['user_id' => auth()->id()]);
         $this->post(route('favorites.store', $reply->id));
         $this->delete("/replies/{$reply->id}")->assertStatus(302);

         $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
         $this->assertEquals(1, Activity::count());
     }
}