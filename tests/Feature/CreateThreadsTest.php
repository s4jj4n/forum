<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->expectException(AuthenticationException::class);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
    }

    /** @test */
    function guest_can_not_see_the_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get(route('threads.create'))
            ->assertRedirect('/login');

    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
