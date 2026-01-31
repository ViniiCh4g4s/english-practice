<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>English Practice - Criar Conta</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen glass-mesh-bg antialiased">
    <div class="flex min-h-screen items-center justify-center px-6 py-12">
        <div class="w-full max-w-sm">

            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold tracking-tight mb-2" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <span class="text-zinc-900">English</span> <span class="text-zinc-400">Practice</span>
                </h1>
                <p class="text-sm text-zinc-500">Crie sua conta e comece a praticar.</p>
            </div>

            {{-- Card de Registro --}}
            <div class="glass rounded-2xl p-6">
                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf

                    {{-- Nome --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-zinc-700 mb-1.5">Nome</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Seu nome"
                            class="w-full rounded-xl glass-input px-4 py-2.5 text-zinc-900 placeholder-zinc-400 text-sm"
                        >
                        @error('name')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- E-mail --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">E-mail</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
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
                        <label for="password" class="block text-sm font-medium text-zinc-700 mb-1.5">Senha</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Crie uma senha"
                            class="w-full rounded-xl glass-input px-4 py-2.5 text-zinc-900 placeholder-zinc-400 text-sm"
                        >
                        @error('password')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmar Senha --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-1.5">Confirmar senha</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Confirme a senha"
                            class="w-full rounded-xl glass-input px-4 py-2.5 text-zinc-900 placeholder-zinc-400 text-sm"
                        >
                    </div>

                    {{-- Botão --}}
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium glass-button rounded-xl"
                    >
                        Criar conta
                    </button>
                </form>
            </div>

            {{-- Link para login --}}
            <p class="mt-6 text-center text-sm text-zinc-500">
                Já tem uma conta?
                <a href="{{ route('login') }}" class="font-medium text-zinc-900 underline underline-offset-4 hover:text-zinc-700">Entrar</a>
            </p>

        </div>
    </div>
</body>
</html>
