<?php

namespace App\Livewire;

use App\Models\Sentence;
use App\Models\UserAnswer;
use App\Models\UserFavorite;
use App\Models\UserProgress;
use App\Services\AiCorrectionService;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ExercisePractice extends Component
{
    // Estado do exercício
    public ?Sentence $currentSentence = null;
    public string $userAnswer = '';
    public bool $isReviewing = false;
    public bool $showReference = false;
    public bool $isFavorite = false;

    // Feedback da IA
    public ?array $aiFeedback = null;

    // Filtros
    public string $selectedLevel = 'all';
    public string $selectedTopic = 'all';

    // Progresso
    public ?UserProgress $progress = null;
    public int $hintsUsed = 0;
    public array $availableHints = [];

    protected $rules = [
        'userAnswer' => 'required|string|min:3',
    ];

    public function mount()
    {
        $this->loadProgress();
        $this->loadNewExercise();
    }

    public function loadProgress()
    {
        $identifier = auth()->id() ?? Session::getId();
        $column = auth()->check() ? 'user_id' : 'session_id';

        $this->progress = UserProgress::firstOrCreate(
            [$column => $identifier],
            [
                'total_exercises' => 0,
                'correct_exercises' => 0,
                'current_streak' => 0,
                'best_streak' => 0,
                'total_xp' => 0,
                'level' => 1,
            ]
        );
    }

    public function loadNewExercise()
    {
        $this->reset(['userAnswer', 'isReviewing', 'showReference', 'aiFeedback', 'hintsUsed', 'availableHints']);

        $query = Sentence::where('active', true);

        if ($this->selectedLevel !== 'all') {
            $query->where('level', $this->selectedLevel);
        }

        if ($this->selectedTopic !== 'all') {
            $query->where('topic', $this->selectedTopic);
        }

        // Spaced repetition: priorizar frases que o usuário errou
        $identifier = auth()->id() ?? Session::getId();
        $column = auth()->check() ? 'user_id' : 'session_id';

        $wrongAnswers = UserAnswer::where($column, $identifier)
            ->where('is_correct', false)
            ->pluck('sentence_id')
            ->toArray();

        if (!empty($wrongAnswers) && rand(1, 100) <= 40) {
            $this->currentSentence = $query->whereIn('id', $wrongAnswers)->inRandomOrder()->first();
        }

        if (!$this->currentSentence) {
            $this->currentSentence = $query->inRandomOrder()->first();
        }

        if ($this->currentSentence) {
            $this->checkIfFavorite();
            $this->prepareHints();
        }
    }

    public function prepareHints()
    {
        if (!$this->currentSentence) return;

        $reference = $this->currentSentence->text_en_reference;

        $wordCount = str_word_count($reference);
        $this->availableHints[] = "A frase em inglês tem {$wordCount} palavras";

        $words = explode(' ', $reference);
        $this->availableHints[] = "A frase começa com: \"{$words[0]}\"";

        $this->availableHints[] = "Preste atenção no tempo verbal";
    }

    public function showHint()
    {
        if ($this->hintsUsed < count($this->availableHints)) {
            $this->hintsUsed++;
        }
    }

    public function submitAnswer()
    {
        $this->validate();

        $this->isReviewing = true;

        try {
            $aiService = app(AiCorrectionService::class);

            $this->aiFeedback = $aiService->correctTranslation(
                textPt: $this->currentSentence->text_pt,
                textEnReference: $this->currentSentence->text_en_reference,
                userTextEn: $this->userAnswer
            );

            $this->saveAnswer();
            $this->updateProgress();

        } catch (\Exception $e) {
            $this->isReviewing = false;
            session()->flash('error', 'Erro ao processar resposta. Tente novamente.');
        }
    }

    protected function saveAnswer()
    {
        $identifier = auth()->id() ?? Session::getId();
        $column = auth()->check() ? 'user_id' : 'session_id';

        UserAnswer::create([
            $column => $identifier,
            'sentence_id' => $this->currentSentence->id,
            'user_text_en' => $this->userAnswer,
            'ai_feedback' => $this->aiFeedback,
            'is_correct' => $this->aiFeedback['is_correct'],
            'score' => $this->aiFeedback['score'] ?? 0,
            'reviewed_at' => now(),
        ]);
    }

    protected function updateProgress()
    {
        $this->progress->total_exercises++;

        if ($this->aiFeedback['is_correct']) {
            $this->progress->correct_exercises++;
            $this->progress->current_streak++;

            if ($this->progress->current_streak > $this->progress->best_streak) {
                $this->progress->best_streak = $this->progress->current_streak;
            }

            $xp = 10;
            $xp += min($this->progress->current_streak, 10);
            $xp -= $this->hintsUsed * 2;
            $xp = max($xp, 5);

            $this->progress->total_xp += $xp;

        } else {
            $this->progress->current_streak = 0;
        }

        $this->progress->last_practice_date = today();
        $this->progress->level = floor($this->progress->total_xp / 100) + 1;

        $this->progress->save();
    }

    public function toggleFavorite()
    {
        $identifier = auth()->id() ?? Session::getId();
        $column = auth()->check() ? 'user_id' : 'session_id';

        $favorite = UserFavorite::where($column, $identifier)
            ->where('sentence_id', $this->currentSentence->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $this->isFavorite = false;
        } else {
            UserFavorite::create([
                $column => $identifier,
                'sentence_id' => $this->currentSentence->id,
            ]);
            $this->isFavorite = true;
        }
    }

    protected function checkIfFavorite()
    {
        $identifier = auth()->id() ?? Session::getId();
        $column = auth()->check() ? 'user_id' : 'session_id';

        $this->isFavorite = UserFavorite::where($column, $identifier)
            ->where('sentence_id', $this->currentSentence->id)
            ->exists();
    }

    public function render()
    {
        return view('livewire.exercise-practice');
    }
}
