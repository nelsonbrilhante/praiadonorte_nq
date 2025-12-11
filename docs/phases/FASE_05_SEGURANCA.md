# Fase 5: Segurança Base

**Status**: ⏳ Pendente
**Dependências**: Fase 4
**Bloco**: 3 - Qualidade

> **Nota**: A arquitectura monolítica (Laravel + Blade) tem vantagens de segurança inerentes:
> - Sem API exposta publicamente
> - Autenticação baseada em sessions (mais segura que tokens)
> - Sem CORS para configurar

---

## Objetivos

- Implementar medidas de segurança Laravel
- Configurar security headers
- Escrever testes
- Preparar para produção

---

## Tarefas

### 5.1 Security Headers Middleware

**`app/Http/Middleware/SecurityHeaders.php`**:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // HSTS (apenas em produção)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content Security Policy
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.youtube.com https://www.google.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: https: blob:",
            "font-src 'self' https://fonts.gstatic.com data:",
            "frame-src 'self' https://www.youtube.com https://player.vimeo.com",
            "connect-src 'self' https://marine-api.open-meteo.com https://api.open-meteo.com",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions Policy
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
```

**Registar no `bootstrap/app.php`**:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SecurityHeaders::class,
    ]);
})
```

---

### 5.2 Rate Limiting

**`bootstrap/app.php`** (Laravel 12):

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

->withMiddleware(function (Middleware $middleware) {
    // Rate limiters
    RateLimiter::for('web', function (Request $request) {
        return Limit::perMinute(60)->by($request->ip());
    });

    RateLimiter::for('contact', function (Request $request) {
        return Limit::perMinute(5)->by($request->ip());
    });

    RateLimiter::for('auth', function (Request $request) {
        return Limit::perMinute(10)->by($request->ip());
    });
})
```

**Aplicar às rotas**:

```php
// Contact form com rate limiting
Route::post('/contacto', [ContactController::class, 'send'])
    ->name('contacto.send')
    ->middleware('throttle:contact');
```

---

### 5.3 Form Validation (Laravel Request)

**`app/Http/Requests/ContactRequest.php`**:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'subject' => ['required', 'string', 'min:5', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required', ['attribute' => 'nome']),
            'email.required' => __('validation.required', ['attribute' => 'email']),
            'email.email' => __('validation.email'),
            'subject.required' => __('validation.required', ['attribute' => 'assunto']),
            'message.required' => __('validation.required', ['attribute' => 'mensagem']),
        ];
    }
}
```

---

### 5.4 CSRF Protection

O Laravel inclui protecção CSRF automaticamente. Garantir que todos os formulários incluem o token:

```blade
<form method="POST" action="{{ route('contacto.send', ['locale' => $locale]) }}">
    @csrf
    {{-- campos do formulário --}}
</form>
```

---

### 5.5 XSS Prevention

O Blade escapa automaticamente output com `{{ }}`. Para HTML seguro usar:

```blade
{{-- SEGURO: escapa HTML --}}
{{ $user_input }}

{{-- CUIDADO: permite HTML (apenas para conteúdo confiável do CMS) --}}
{!! $trusted_html_from_cms !!}
```

**Sanitizar HTML do CMS** (`app/Helpers/HtmlPurifier.php`):

```php
<?php

namespace App\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    public static function clean(string $html): string
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,br,strong,em,ul,ol,li,a[href],h2,h3,h4,blockquote');

        $purifier = new HTMLPurifier($config);
        return $purifier->purify($html);
    }
}
```

---

### 5.6 Dependency Audit

```bash
# Verificar vulnerabilidades PHP
composer audit

# Verificar vulnerabilidades npm
npm audit

# Corrigir automaticamente
npm audit fix
```

---

### 5.7 Testes de Segurança

**`tests/Feature/SecurityTest.php`**:

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityTest extends TestCase
{
    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/pt');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    public function test_contact_form_has_csrf_protection(): void
    {
        $response = $this->post('/pt/contacto', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'Test message content',
        ]);

        // Without CSRF token, should fail
        $response->assertStatus(419);
    }

    public function test_contact_form_rate_limiting(): void
    {
        // Make 6 requests (limit is 5)
        for ($i = 0; $i < 6; $i++) {
            $response = $this->withSession(['_token' => 'test'])
                ->post('/pt/contacto', [
                    '_token' => 'test',
                    'name' => 'Test',
                    'email' => 'test@example.com',
                    'subject' => 'Test Subject',
                    'message' => 'Test message content',
                ]);
        }

        // 6th request should be rate limited
        $response->assertStatus(429);
    }

    public function test_admin_requires_authentication(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }
}
```

---

### 5.8 Checklist de Segurança

- [ ] HTTPS forçado em todas as rotas (Cloudflare SSL)
- [ ] Security headers implementados
- [ ] Rate limiting em endpoints sensíveis
- [ ] CSRF protection activo em todos os forms
- [ ] XSS prevenido (Blade auto-escaping)
- [ ] SQL injection prevenido (Eloquent ORM)
- [ ] Validação server-side de todos os inputs
- [ ] Admin panel protegido com autenticação
- [ ] composer audit sem vulnerabilidades críticas
- [ ] npm audit sem vulnerabilidades críticas

---

### 5.9 Vantagens de Segurança (Arquitectura Monolítica)

| Aspecto | Benefício |
|---------|-----------|
| **Sem API pública** | Menor superfície de ataque |
| **Sessions** | Mais seguro que JWT tokens |
| **Server-side rendering** | Menos lógica exposta no cliente |
| **CSRF nativo** | Protecção automática do Laravel |
| **Sem CORS** | Zero configuração de CORS para errar |
| **Single server** | Menos pontos de falha |

---

## Security Audit Targets

| Métrica | Target |
|---------|--------|
| Security Headers Grade | A |
| SSL Labs Rating | A+ |
| composer audit vulnerabilities | 0 críticas |
| npm audit vulnerabilities | 0 críticas |
| OWASP Top 10 | Mitigado |

---

## Entregáveis

- [ ] Security headers middleware implementado
- [ ] Rate limiting configurado
- [ ] Testes de segurança escritos
- [ ] Dependency audits limpos
- [ ] Documentação de segurança actualizada

---

## Critérios de Conclusão

1. securityheaders.com mostra grade A
2. SSL Labs mostra A+ (via Cloudflare)
3. Zero vulnerabilidades críticas em audits
4. Testes de segurança passam
5. Rate limiting bloqueia requests excessivos

---

## Próxima Fase

→ [Fase 6: E-commerce Setup](./FASE_06_ECOMMERCE.md)

---

*Actualizado: 11 Dezembro 2025 - Arquitectura monolítica*
