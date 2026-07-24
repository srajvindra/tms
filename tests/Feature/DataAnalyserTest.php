<?php

use App\Models\User;
use Modules\DataAnalyser\Database\Seeders\DataAnalyserDatabaseSeeder;

beforeEach(function () {
    $this->seed(DataAnalyserDatabaseSeeder::class);
});

test('guests are redirected to the login page', function () {
    $this->get('/data-analyser')->assertRedirect('/login');
});

test('authenticated users can view the classifier form', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/data-analyser')
        ->assertSuccessful()
        ->assertSee('Data Analyser');
});

test('pasted bookmark lines are classified', function () {
    $this->actingAs(User::factory()->create());

    $content = "https://laravel.com/docs/12.x\tLaravel Documentation\n"
        ."https://chatgpt.com/c/1\tSome chat";

    $this->post('/data-analyser/classify', ['content' => $content])
        ->assertSuccessful()
        ->assertSee('Development')
        ->assertSee('https://laravel.com/docs/12.x');
});

test('submitting neither text nor file fails validation', function () {
    $this->actingAs(User::factory()->create());

    $this->from('/data-analyser')
        ->post('/data-analyser/classify', [])
        ->assertRedirect('/data-analyser')
        ->assertSessionHasErrors(['content', 'file']);
});
