# Fase 10: Autenticação e Área de Cliente

**Duração Estimada**: 1 semana
**Dependências**: Fase 9
**Bloco**: 4 - E-commerce

---

## Objetivos

- Implementar autenticação com Laravel Sanctum
- Criar área de cliente
- Histórico de encomendas

---

## Tarefas

### 7.1 Configuração Laravel Sanctum

**`config/sanctum.php`**:

```php
return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'praiadonortenazare.pt,www.praiadonortenazare.pt')),

    'guard' => ['web'],

    'expiration' => null,

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
];
```

### 7.2 Auth Controller

**`app/Http/Controllers/Api/AuthController.php`**:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
```

### 7.3 Rotas de Autenticação

**`routes/api.php`**:

```php
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});
```

### 7.4 Frontend Auth Context

**`src/lib/auth.ts`**:

```typescript
const API_URL = process.env.NEXT_PUBLIC_API_URL

export async function login(email: string, password: string) {
  const res = await fetch(`${API_URL}/api/auth/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password }),
  })

  if (!res.ok) throw new Error('Login failed')
  return res.json()
}

export async function register(data: {
  name: string
  email: string
  password: string
}) {
  const res = await fetch(`${API_URL}/api/auth/register`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  })

  if (!res.ok) throw new Error('Registration failed')
  return res.json()
}

export async function logout(token: string) {
  await fetch(`${API_URL}/api/auth/logout`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
    },
  })
}
```

### 7.5 Área de Cliente

**`src/app/[locale]/(praia-do-norte)/conta/page.tsx`**:

```typescript
import { getOrders } from '@/lib/api/orders'

export default async function AccountPage() {
  const orders = await getOrders()

  return (
    <main className="container py-8">
      <h1 className="font-display text-3xl font-bold mb-8">Minha Conta</h1>

      <div className="grid md:grid-cols-3 gap-8">
        <aside className="space-y-2">
          <a href="/conta" className="block p-2 bg-muted rounded">Resumo</a>
          <a href="/conta/encomendas" className="block p-2">Encomendas</a>
          <a href="/conta/moradas" className="block p-2">Moradas</a>
          <a href="/conta/dados" className="block p-2">Dados Pessoais</a>
        </aside>

        <div className="md:col-span-2">
          <h2 className="text-xl font-semibold mb-4">Últimas Encomendas</h2>
          {orders.map((order) => (
            <OrderCard key={order.id} order={order} />
          ))}
        </div>
      </div>
    </main>
  )
}
```

---

## Entregáveis

- [ ] Registo de utilizadores
- [ ] Login/Logout
- [ ] Recuperação de password
- [ ] Área "Minha Conta"
- [ ] Histórico de encomendas
- [ ] Gestão de moradas

---

## Critérios de Conclusão

1. Registo cria utilizador e retorna token
2. Login funciona com credenciais válidas
3. Token é guardado e usado em requests
4. Área de cliente mostra dados do utilizador
5. Histórico mostra encomendas passadas
6. Password reset envia email funcional
