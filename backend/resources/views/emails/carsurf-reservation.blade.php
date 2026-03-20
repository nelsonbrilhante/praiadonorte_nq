<x-mail::message>
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

<small>Este email foi enviado automaticamente pelo website Carsurf.</small>
</x-mail::message>
