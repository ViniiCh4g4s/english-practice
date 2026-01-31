<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Practice - Entrar</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen glass-mesh-bg antialiased">
    <div class="flex min-h-screen items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">

            {{-- Header / Boas-vindas --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold tracking-tight mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <span class="text-zinc-900">English</span> <span class="text-zinc-400">Practice</span>
                </h1>
                <p class="text-sm text-zinc-500 leading-relaxed">
                    Pratique traduções do português para o inglês<br>
                    com correções inteligentes por IA.
                </p>
            </div>

            {{-- Status da sessão --}}
            @if (session('status'))
            <div class="mb-4 px-4 py-3 glass-success rounded-xl text-sm text-emerald-700 text-center">
                {{ session('status') }}
            </div>
            @endif

            {{-- Card de Login --}}
            <div class="glass rounded-2xl p-6">
                <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                    @csrf

                    {{-- E-mail --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">E-mail</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="seu@email.com"
                            class="w-full rounded-xl glass-input px-4 py-2.5 text-zinc-900 placeholder-zinc-400 text-sm"
                        >
                        @error('email')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-zinc-700">Senha</label>
                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-zinc-400 hover:text-zinc-600 transition-colors">
                                Esqueceu a senha?
                            </a>
                            @endif
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="current-password"
                            placeholder="Sua senha"
                            class="w-full rounded-xl glass-input px-4 py-2.5 text-zinc-900 placeholder-zinc-400 text-sm"
                        >
                        @error('password')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Lembrar de mim --}}
                    <div class="flex items-center gap-2">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            {{ old('remember') ? 'checked' : '' }}
                            class="rounded border-zinc-300/50 text-zinc-900 focus:ring-zinc-900"
                        >
                        <label for="remember" class="text-sm text-zinc-600">Lembrar de mim</label>
                    </div>

                    {{-- Botão --}}
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium glass-button rounded-xl"
                    >
                        Entrar
                    </button>
                </form>
            </div>

            {{-- Link para registro --}}
            @if (Route::has('register'))
            <p class="mt-6 text-center text-sm text-zinc-500">
                Não tem uma conta?
                <a href="{{ route('register') }}" class="font-medium text-zinc-900 underline underline-offset-4 hover:text-zinc-700">Criar conta</a>
            </p>
            @endif

        </div>
    </div>
</body>
</html>
