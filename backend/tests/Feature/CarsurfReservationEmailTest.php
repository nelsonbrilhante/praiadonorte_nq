<?php

namespace Tests\Feature;

use App\Mail\CarsurfReservation;
use Tests\TestCase;

/**
 * Locks in the four presentation fixes made on 2026-07-30. Before them the
 * email inherited the platform-wide APP_NAME and APP_URL, so a Carsurf
 * reservation arrived branded "Praia do Norte" and linking to the old staging
 * domain, with an untranslated footer and the framework's default black button.
 */
class CarsurfReservationEmailTest extends TestCase
{
    private function render(): string
    {
        $this->app->setLocale('pt');

        return (new CarsurfReservation(
            senderName: 'Maria Silva',
            senderEmail: 'maria@example.com',
            senderPhone: '+351 912 345 678',
            senderMessage: 'Gostaria de reservar uma sessão.',
        ))->render();
    }

    /** Only the invisible <title> may still carry the platform-wide app name. */
    private function visibleHtml(): string
    {
        return preg_replace('#<title\b[^>]*>.*?</title>#is', '', $this->render());
    }

    public function test_the_header_carries_the_carsurf_brand(): void
    {
        $html = $this->visibleHtml();

        $this->assertStringContainsString('CARSURF', $html);
        $this->assertStringNotContainsString('Praia do Norte', $html);
    }

    public function test_the_header_links_to_the_carsurf_production_domain(): void
    {
        $html = $this->render();

        $this->assertStringContainsString('https://carsurf.nazare.pt', $html);
        $this->assertStringNotContainsString('nelsonbrilhante.com', $html);
    }

    public function test_the_footer_is_in_portuguese(): void
    {
        $html = $this->render();

        $this->assertStringContainsString('Todos os direitos reservados', $html);
        $this->assertStringNotContainsString('All rights reserved', $html);
    }

    public function test_the_button_uses_the_carsurf_blue(): void
    {
        $html = $this->render();

        // #127a99 is the logo blue (#18a2cc, hue 194°) darkened until white
        // text reaches 4.92:1. The exact logo blue only reaches 2.97:1.
        $this->assertStringContainsString('background-color: #127a99', $html);
        $this->assertStringNotContainsString('background-color: #18181b', $html);
    }

    public function test_the_exact_logo_blue_is_used_for_the_header_rule(): void
    {
        $this->assertStringContainsString('background-color: #18a2cc', $this->render());
    }

    public function test_the_reply_to_is_the_person_who_submitted(): void
    {
        $envelope = (new CarsurfReservation(
            senderName: 'Maria Silva',
            senderEmail: 'maria@example.com',
            senderPhone: null,
            senderMessage: 'Olá.',
        ))->envelope();

        $this->assertSame('maria@example.com', $envelope->replyTo[0]->address);
        $this->assertSame('Novo Pedido de Reserva — Carsurf', $envelope->subject);
    }

    public function test_the_phone_row_is_omitted_when_no_phone_is_given(): void
    {
        $this->app->setLocale('pt');

        $html = (new CarsurfReservation(
            senderName: 'Maria Silva',
            senderEmail: 'maria@example.com',
            senderPhone: null,
            senderMessage: 'Olá.',
        ))->render();

        $this->assertStringNotContainsString('Telefone', $html);
    }
}
