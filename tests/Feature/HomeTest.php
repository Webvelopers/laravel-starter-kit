<?php

declare(strict_types=1);

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

it('loads the homepage successfully', function (): void {
    $response = get('/');

    $response->assertOk();
    $response->assertSee(__('frontend.welcome.title'));
});

it('redirects the legacy home path to the dashboard path', function (): void {
    get('/home')->assertRedirect('/dashboard');
});

it('renders the landing page in english when the session locale is english', function (): void {
    withSession(['locale' => 'en'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Laravel Starter Kit')
        ->assertSee('Read documentation');
});
