<?php

use Laravel\Dusk\Browser;

test('visit Times of India and navigate to login page', function () {
    $this->browse(function (Browser $browser) {
        // Step 1: Visit Times of India homepage
        $browser->visit('https://timesofindia.indiatimes.com/')
                ->pause(5000);

        // Step 2: Navigate to the login page
        $browser->visit('https://timesofindia.indiatimes.com/login')
                ->pause(3000)
                ->assertUrlContains('login');
    });
});
