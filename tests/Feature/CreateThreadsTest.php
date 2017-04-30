<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    function guests_may_not_create_threads()
    {
        $this->withExceptionHandling();

        $this->get(route('threads.create'))
            ->assertRedirect('/login');

        $this->post(route('threads.store'), [])
            ->assertRedirect('/login');

    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = create('App\Thread');
        $this->post('/threads', $thread->toArray());
        $thread = Thread::all()->last();
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
