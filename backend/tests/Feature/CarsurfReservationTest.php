<?php

namespace Tests\Feature;

use App\Mail\CarsurfReservation;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

class CarsurfReservationTest extends TestCase
{
    use RefreshDatabase;

    private array $validPayload = [
        'name' => 'Maria Silva',
        'email' => 'maria.silva@example.com',
        'phone' => '+351 912 345 678',
        'message' => 'Gostaria de reservar uma sessão para dois adultos.',
    ];

    private const URI = '/carsurf/reservas';

    /**
     * The localization package resolves the locale prefix from the incoming URL
     * at route-registration time, so under test the routes register unprefixed
     * and the redirect filters bounce every request to /pt. Skipping those two
     * middlewares lets these tests exercise the controller, which is the point.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            LocaleSessionRedirect::class,
            LaravelLocalizationRedirectFilter::class,
        ]);
    }

    public function test_reservation_is_sent_to_the_configured_recipients(): void
    {
        Mail::fake();
        SiteSetting::set('carsurf_reservas_recipients', 'reservas@carsurf.nazare.pt, direcao@carsurf.nazare.pt');

        $this->post(self::URI, $this->validPayload)
            ->assertRedirect()
            ->assertSessionHas('success');

        Mail::assertQueued(CarsurfReservation::class, function (CarsurfReservation $mail) {
            return $mail->hasTo('reservas@carsurf.nazare.pt')
                && $mail->hasTo('direcao@carsurf.nazare.pt');
        });
    }

    public function test_reservation_falls_back_to_the_default_recipient_when_setting_is_empty(): void
    {
        Mail::fake();
        SiteSetting::set('carsurf_reservas_recipients', '');

        $this->post(self::URI, $this->validPayload)->assertRedirect();

        Mail::assertQueued(CarsurfReservation::class, function (CarsurfReservation $mail) {
            return $mail->hasTo(SiteSetting::CARSURF_RESERVAS_FALLBACK);
        });
    }

    public function test_invalid_addresses_in_the_setting_are_discarded(): void
    {
        Mail::fake();
        SiteSetting::set('carsurf_reservas_recipients', 'nao-e-um-email, valido@carsurf.nazare.pt');

        $this->post(self::URI, $this->validPayload)->assertRedirect();

        Mail::assertQueued(CarsurfReservation::class, function (CarsurfReservation $mail) {
            return $mail->hasTo('valido@carsurf.nazare.pt')
                && ! $mail->hasTo('nao-e-um-email');
        });
    }

    public function test_reservation_is_persisted_even_when_it_is_the_only_record_of_it(): void
    {
        Mail::fake();

        $this->post(self::URI, $this->validPayload);

        $this->assertDatabaseHas('contact_messages', [
            'entity' => 'carsurf',
            'type' => 'reserva',
            'email' => 'maria.silva@example.com',
        ]);
    }

    public function test_the_page_displays_the_first_configured_recipient(): void
    {
        SiteSetting::set('carsurf_reservas_recipients', 'reservas@carsurf.nazare.pt, direcao@carsurf.nazare.pt');

        $this->get(self::URI)
            ->assertOk()
            ->assertSee('mailto:reservas@carsurf.nazare.pt', escape: false)
            ->assertDontSee('direcao@carsurf.nazare.pt');
    }

    public function test_invalid_submissions_are_rejected_without_sending_mail(): void
    {
        Mail::fake();

        $this->post(self::URI, ['name' => '', 'email' => 'nao-e-email', 'message' => ''])
            ->assertSessionHasErrors(['name', 'email', 'message']);

        Mail::assertNothingQueued();
        $this->assertSame(0, ContactMessage::count());
    }
}
