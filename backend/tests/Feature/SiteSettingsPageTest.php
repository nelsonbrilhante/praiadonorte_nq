<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Filament\Pages\SiteSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SiteSettingsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => Role::Admin]));
    }

    public function test_it_persists_valid_carsurf_recipients(): void
    {
        Livewire::test(SiteSettings::class)
            ->set('carsurf_reservas_recipients', 'reservas@carsurf.nazare.pt, direcao@carsurf.nazare.pt')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame(
            ['reservas@carsurf.nazare.pt', 'direcao@carsurf.nazare.pt'],
            SiteSetting::carsurfReservasRecipients()
        );
    }

    public function test_it_rejects_an_invalid_address_without_persisting(): void
    {
        SiteSetting::set('carsurf_reservas_recipients', 'original@carsurf.nazare.pt');

        Livewire::test(SiteSettings::class)
            ->set('carsurf_reservas_recipients', 'reservas@carsurf.nazare.pt, isto-nao-e-um-email')
            ->call('save')
            ->assertHasErrors('carsurf_reservas_recipients');

        $this->assertSame(['original@carsurf.nazare.pt'], SiteSetting::carsurfReservasRecipients());
    }

    public function test_it_rejects_an_empty_value_without_persisting(): void
    {
        SiteSetting::set('carsurf_reservas_recipients', 'original@carsurf.nazare.pt');

        Livewire::test(SiteSettings::class)
            ->set('carsurf_reservas_recipients', '')
            ->call('save')
            ->assertHasErrors('carsurf_reservas_recipients');

        $this->assertSame(['original@carsurf.nazare.pt'], SiteSetting::carsurfReservasRecipients());
    }

    public function test_the_field_is_prefilled_with_the_fallback_when_unset(): void
    {
        Livewire::test(SiteSettings::class)
            ->assertSet('carsurf_reservas_recipients', SiteSetting::CARSURF_RESERVAS_FALLBACK);
    }

    /**
     * The save() method gained a validation call; this guards the pre-existing
     * maintenance-mode settings against regressing because of it.
     */
    public function test_it_still_saves_maintenance_mode_settings(): void
    {
        Livewire::test(SiteSettings::class)
            ->set('maintenance_mode', true)
            ->set('maintenance_message_pt', 'Voltamos em breve.')
            ->set('maintenance_message_en', 'Back shortly.')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue(SiteSetting::isMaintenanceMode());
        $this->assertSame(
            ['pt' => 'Voltamos em breve.', 'en' => 'Back shortly.'],
            SiteSetting::getMaintenanceMessage()
        );
    }

    public function test_it_still_saves_weekly_statistics_settings(): void
    {
        Livewire::test(SiteSettings::class)
            ->set('stats_weekly_enabled', true)
            ->set('stats_weekly_recipients', 'presidente@cm-nazare.pt')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('1', SiteSetting::get('stats_weekly_enabled'));
        $this->assertSame('presidente@cm-nazare.pt', SiteSetting::get('stats_weekly_recipients'));
    }
}
