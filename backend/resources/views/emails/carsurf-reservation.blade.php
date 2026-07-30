{{--
 | Usa <x-mail::layout> em vez de <x-mail::message> para definir cabeçalho e
 | rodapé próprios. O <x-mail::message> padrão herda config('app.name') e
 | config('app.url'), que são globais da plataforma ("Praia do Norte" e, em
 | produção, o domínio antigo) — errados para um email do Carsurf.
 --}}
<x-mail::layout>

{{-- Cabeçalho: marca Carsurf, a apontar para o domínio Carsurf --}}
<x-slot:header>
<x-mail::header url="https://carsurf.nazare.pt">
CARSURF
<div class="header-rule">&nbsp;</div>
</x-mail::header>
</x-slot:header>

{{-- Corpo --}}
# Novo Pedido de Reserva

Foi recebido um novo pedido de reserva através do website.

---

**Nome:** {{ $senderName }}

**Email:** {{ $senderEmail }}

@if($senderPhone)
**Telefone:** {{ $senderPhone }}
@endif

**Mensagem:**

{{ $senderMessage }}

---

<x-mail::button :url="'mailto:' . $senderEmail">
Responder
</x-mail::button>

{{-- Rodapé fino: enviado automaticamente --}}
<x-slot:subcopy>
<x-mail::subcopy>
Este email foi enviado automaticamente pelo formulário de reservas em
[carsurf.nazare.pt](https://carsurf.nazare.pt). Ao responder, a resposta segue
diretamente para quem submeteu o pedido.
</x-mail::subcopy>
</x-slot:subcopy>

{{-- Rodapé: em português, com atribuição correta --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} Carsurf — Nazaré Qualifica, E.M. Todos os direitos reservados.
</x-mail::footer>
</x-slot:footer>

</x-mail::layout>
