<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $this->get('/worldmaps')->assertRedirect('/login');
});

test('authenticated users can view the world map', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/worldmaps')
        ->assertSuccessful()
        ->assertSee('id="worldmap"', false)
        ->assertSee('worldmap-loading', false);
});
