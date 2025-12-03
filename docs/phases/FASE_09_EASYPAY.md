# Fase 9: Integração Easypay

**Duração Estimada**: 1-2 semanas
**Dependências**: Fase 8
**Bloco**: 4 - E-commerce

---

## Objetivos

- Integrar gateway de pagamento Easypay
- Implementar múltiplos métodos de pagamento
- Configurar webhooks

---

## Tarefas

### 6.1 Serviço Easypay (Laravel)

**`app/Services/EasypayService.php`**:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EasypayService
{
    private string $baseUrl;
    private string $accountId;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.easypay.base_url');
        $this->accountId = config('services.easypay.account_id');
        $this->apiKey = config('services.easypay.api_key');
    }

    public function createPayment(array $data): array
    {
        $response = Http::withHeaders([
            'AccountId' => $this->accountId,
            'ApiKey' => $this->apiKey,
        ])->post("{$this->baseUrl}/single", [
            'type' => 'sale',
            'key' => Str::uuid(),
            'value' => $data['amount'],
            'currency' => 'EUR',
            'method' => $data['method'], // 'cc', 'mbw', 'mb'
            'customer' => [
                'name' => $data['customer_name'],
                'email' => $data['customer_email'],
                'phone' => $data['customer_phone'],
            ],
        ]);

        return $response->json();
    }

    public function validateWebhook(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha256', $payload, config('services.easypay.webhook_secret'));
        return hash_equals($expected, $signature);
    }
}
```

### 6.2 Controller de Checkout

**`app/Http/Controllers/Api/CheckoutController.php`**:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\EasypayService;
use Aimeos\MShop;

class CheckoutController extends Controller
{
    public function __construct(
        private EasypayService $easypay
    ) {}

    public function process(CheckoutRequest $request)
    {
        $validated = $request->validated();

        // 1. Validar stock e preços no servidor (NUNCA confiar no cliente)
        $context = app('aimeos.context')->get();
        $manager = MShop::create($context, 'product');

        $total = 0;
        foreach ($validated['items'] as $item) {
            $product = $manager->get($item['id']);

            // Verificar stock
            if ($product->getStockLevel() < $item['quantity']) {
                return response()->json([
                    'error' => "Stock insuficiente para {$product->getLabel()}"
                ], 400);
            }

            // Usar preço do servidor (não do cliente!)
            $total += $product->getPrice()->getValue() * $item['quantity'];
        }

        // 2. Criar encomenda no Aimeos
        $orderManager = MShop::create($context, 'order');
        $order = $orderManager->create();
        // ... configurar encomenda

        // 3. Criar pagamento no Easypay
        $payment = $this->easypay->createPayment([
            'amount' => $total,
            'method' => $validated['payment_method'],
            'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'customer_email' => $validated['email'],
            'customer_phone' => $validated['phone'],
        ]);

        return response()->json([
            'order_id' => $order->getId(),
            'payment_url' => $payment['method']['url'] ?? null,
            'reference' => $payment['method']['reference'] ?? null,
        ]);
    }
}
```

### 6.3 Webhook Handler

**`app/Http/Controllers/Api/WebhookController.php`**:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EasypayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function easypay(Request $request, EasypayService $easypay)
    {
        // Validar assinatura
        $signature = $request->header('X-Easypay-Signature');
        if (!$easypay->validateWebhook($request->getContent(), $signature)) {
            Log::warning('Invalid Easypay webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $payload = $request->all();

        // Processar evento
        switch ($payload['type']) {
            case 'payment.success':
                $this->handlePaymentSuccess($payload);
                break;
            case 'payment.failed':
                $this->handlePaymentFailed($payload);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentSuccess(array $payload): void
    {
        // Atualizar estado da encomenda
        // Enviar email de confirmação
        // Atualizar stock
    }

    private function handlePaymentFailed(array $payload): void
    {
        // Marcar encomenda como falhada
        // Notificar cliente
    }
}
```

### 6.4 Configuração de Serviços

**`config/services.php`**:

```php
return [
    // ... outros serviços

    'easypay' => [
        'account_id' => env('EASYPAY_ACCOUNT_ID'),
        'api_key' => env('EASYPAY_API_KEY'),
        'base_url' => env('EASYPAY_BASE_URL', 'https://api.prod.easypay.pt/2.0'),
        'webhook_secret' => env('EASYPAY_WEBHOOK_SECRET'),
    ],
];
```

### 6.5 Middleware de Validação de Webhook

**`app/Http/Middleware/ValidateEasypayWebhook.php`**:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateEasypayWebhook
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar IP da Easypay (whitelist)
        $allowedIps = ['212.55.154.0/24']; // IPs da Easypay

        if (!$this->isIpAllowed($request->ip(), $allowedIps)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }

    private function isIpAllowed(string $ip, array $ranges): bool
    {
        // Implementar verificação de IP
        return true; // Simplificado
    }
}
```

---

## Métodos de Pagamento Suportados

| Método | Código | Descrição |
|--------|--------|-----------|
| Cartão | `cc` | Visa, Mastercard, etc. |
| MB WAY | `mbw` | Pagamento via app MB WAY |
| Multibanco | `mb` | Referência para ATM |
| Débito Direto | `dd` | SEPA Direct Debit |

---

## Entregáveis

- [ ] Integração Easypay completa
- [ ] Suporte para Cartão, MB WAY, Multibanco
- [ ] Webhook handler com validação HMAC
- [ ] Página de confirmação de pagamento
- [ ] Emails transacionais (confirmação, fatura)
- [ ] Testes em modo sandbox

---

## Critérios de Conclusão

1. Pagamento com cartão funciona em sandbox
2. MB WAY envia notificação push
3. Multibanco gera referência válida
4. Webhooks atualizam estado da encomenda
5. Emails de confirmação são enviados
6. Logs de transação são guardados
