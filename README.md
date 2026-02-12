# English Practice

Aplicacao web para pratica de traducao portugues-ingles com correcao por IA.

O usuario recebe frases em portugues, escreve a traducao em ingles e recebe feedback detalhado da API do Google Gemini, incluindo correcoes, explicacoes e alternativas naturais.

## Stack

- PHP 8.2+ / Laravel 12
- Livewire 3
- Tailwind CSS 4
- SQLite
- Google Gemini API

## Funcionalidades

- 50 frases de pratica em 5 niveis (A1 a C1)
- Correcao por IA com score, erros detalhados e alternativas
- Sistema de progresso com XP, levels e streaks
- Filtros por nivel e topico
- Sistema de dicas
- Favoritos
- Spaced repetition (prioriza frases que o usuario errou)
- Funciona com ou sem autenticacao (sessao anonima)

## Instalacao

```bash
git clone <repo-url>
cd english-practice

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Adicione sua chave da API do Gemini no `.env`:

```
GEMINI_API_KEY=sua-chave-aqui
```

Configure o banco e popule os dados:

```bash
php artisan migrate
php artisan db:seed
```

## Rodando

```bash
# Terminal 1 - Vite (compilacao de assets)
npm run dev

# Terminal 2 - Servidor Laravel
php artisan serve
```

Acesse: http://localhost:8000

## Estrutura Alterada

```
app/
  Livewire/ExercisePractice.php    # Componente principal
  Models/
    Sentence.php                   # Frases para traducao
    UserAnswer.php                 # Respostas do usuario
    UserFavorite.php               # Favoritos
    UserProgress.php               # Progresso (XP, streak, level)
  Services/AiCorrectionService.php # Integracao com Gemini API

database/
  migrations/                      # 4 tabelas do sistema
  seeders/SentenceSeeder.php       # 50 frases em 5 niveis

resources/views/
  livewire/exercise-practice.blade.php  # Interface do exercicio
  welcome.blade.php                     # Pagina principal
```
