<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The root URL has no content of its own — it redirects to the default
     * locale, so a 302 to /pt is the correct response here.
     */
    public function test_the_root_url_redirects_to_the_default_locale(): void
    {
        $this->get('/')->assertRedirect('/pt');
    }
}
