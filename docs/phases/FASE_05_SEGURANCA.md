# Fase 5: Segurança Base

**Duração Estimada**: 1 semana
**Dependências**: Fase 4
**Bloco**: 3 - Qualidade

> **Nota**: Esta fase implementa segurança para o site institucional. Segurança adicional para e-commerce será adicionada nas Fases 6-10.

---

## Objetivos

- Implementar medidas de segurança
- Escrever testes
- Preparar para produção

---

## Tarefas

### 10.1 Security Headers (Next.js)

**`next.config.js`**:

```javascript
const securityHeaders = [
  {
    key: 'X-DNS-Prefetch-Control',
    value: 'on'
  },
  {
    key: 'Strict-Transport-Security',
    value: 'max-age=63072000; includeSubDomains; preload'
  },
  {
    key: 'X-Content-Type-Options',
    value: 'nosniff'
  },
  {
    key: 'X-Frame-Options',
    value: 'DENY'
  },
  {
    key: 'X-XSS-Protection',
    value: '1; mode=block'
  },
  {
    key: 'Referrer-Policy',
    value: 'strict-origin-when-cross-origin'
  },
  {
    key: 'Content-Security-Policy',
    value: "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self' https://api.praiadonortenazare.pt"
  }
]

module.exports = {
  async headers() {
    return [
      {
        source: '/:path*',
        headers: securityHeaders,
      },
    ]
  },
}
```

### 10.2 Rate Limiting (Laravel)

**`app/Providers/RouteServiceProvider.php`**:

```php
protected function configureRateLimiting(): void
{
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    RateLimiter::for('auth', function (Request $request) {
        return Limit::perMinute(10)->by($request->ip());
    });

    RateLimiter::for('checkout', function (Request $request) {
        return Limit::perMinute(30)->by($request->ip());
    });
}
```

### 10.3 CORS Configuration (Laravel)

**`config/cors.php`**:

```php
return [
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://praiadonortenazare.pt',
        'https://www.praiadonortenazare.pt',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
```

### 10.4 Input Sanitization

**Frontend (Zod já implementado):**

```typescript
// Todas as entradas devem passar por Zod antes de processar
import { z } from 'zod'

const userInput = z.string()
  .trim()
  .min(1)
  .max(1000)
  .refine(val => !/<script/i.test(val), 'Scripts not allowed')
```

**Backend (Laravel Form Requests):**

```php
class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'string', 'exists:mshop_product,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}
```

### 10.5 Dependency Audit

```bash
# Frontend
npm audit
npm audit fix

# Backend
composer audit
```

### 10.6 Checklist de Segurança

- [ ] HTTPS forçado em todas as rotas
- [ ] CORS configurado apenas para domínio autorizado
- [ ] Rate limiting em endpoints sensíveis
- [ ] Validação server-side de todos os preços
- [ ] Credenciais Easypay apenas no backend
- [ ] Webhooks com validação HMAC
- [ ] SQL injection prevenido (Eloquent ORM)
- [ ] XSS prevenido (sanitização de HTML)
- [ ] CSRF protection ativo
- [ ] npm audit / composer audit sem vulnerabilidades críticas

### 10.7 Testes Unitários (Laravel)

**`tests/Unit/EasypayServiceTest.php`**:

```php
class EasypayServiceTest extends TestCase
{
    public function test_webhook_signature_validation()
    {
        $service = new EasypayService();

        $payload = '{"type":"payment.success"}';
        $secret = config('services.easypay.webhook_secret');
        $validSignature = hash_hmac('sha256', $payload, $secret);

        $this->assertTrue($service->validateWebhook($payload, $validSignature));
        $this->assertFalse($service->validateWebhook($payload, 'invalid'));
    }
}
```

### 10.8 Testes E2E (Playwright)

**`e2e/checkout.spec.ts`**:

```typescript
import { test, expect } from '@playwright/test'

test('complete checkout flow', async ({ page }) => {
  // Adicionar produto ao carrinho
  await page.goto('/loja')
  await page.click('[data-testid="product-card"]:first-child button')

  // Ir para checkout
  await page.click('[data-testid="cart-icon"]')
  await page.click('text=Finalizar Compra')

  // Preencher formulário
  await page.fill('[name="firstName"]', 'João')
  await page.fill('[name="lastName"]', 'Silva')
  await page.fill('[name="email"]', 'joao@example.com')
  // ...

  // Submeter
  await page.click('button[type="submit"]')

  // Verificar redirecionamento para pagamento
  await expect(page).toHaveURL(/easypay/)
})
```

---

## Security Audit Targets

| Métrica | Target |
|---------|--------|
| Security Headers Grade | A |
| SSL Labs Rating | A+ |
| npm audit vulnerabilities | 0 críticas |
| composer audit vulnerabilities | 0 críticas |
| OWASP Top 10 | Mitigado |

---

## Entregáveis

- [ ] Security headers implementados
- [ ] Rate limiting configurado
- [ ] Testes unitários para funções críticas
- [ ] Testes de integração para checkout
- [ ] Testes E2E para fluxos principais
- [ ] Documentação de API atualizada
- [ ] Runbook de operações

---

## Critérios de Conclusão

1. securityheaders.com mostra grade A
2. SSL Labs mostra A+
3. Zero vulnerabilidades críticas em audits
4. Testes unitários com > 80% coverage em código crítico
5. Testes E2E passam para checkout completo
6. Rate limiting bloqueia requests excessivos
7. Logs de segurança funcionam corretamente
