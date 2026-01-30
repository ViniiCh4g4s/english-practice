<div class="min-h-screen bg-zinc-50">

    {{-- Top Bar --}}
    <header class="border-b border-zinc-200 bg-white">
        <div class="max-w-3xl mx-auto px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold tracking-tight" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <span class="sm:hidden text-zinc-900">EP</span>
                    <span class="hidden sm:inline"><span class="text-zinc-900">English</span> <span class="text-zinc-400">Practice</span></span>
                </h1>
            </div>
            <div class="flex items-center gap-6 text-sm">
                <div class="flex items-center gap-1.5 text-zinc-500">
                    <span class="font-medium text-zinc-900">{{ $progress->total_xp }}</span> XP
                </div>
                <div class="h-4 w-px bg-zinc-200"></div>
                <div class="flex items-center gap-1.5 text-zinc-500">
                    <span class="font-medium text-zinc-900">{{ $progress->current_streak }}</span> streak
                </div>
                <div class="h-4 w-px bg-zinc-200"></div>
                <div class="flex items-center gap-1.5 text-zinc-500">
                    <span class="font-medium text-zinc-900">{{ $progress->accuracy }}%</span> acc
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-6 py-8 space-y-6">

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white border border-zinc-200 rounded-lg px-4 py-3">
                <p class="text-xs text-zinc-500 mb-0.5">Level</p>
                <p class="text-lg font-semibold text-zinc-900">{{ $progress->level }}</p>
            </div>
            <div class="bg-white border border-zinc-200 rounded-lg px-4 py-3">
                <p class="text-xs text-zinc-500 mb-0.5">Exercises</p>
                <p class="text-lg font-semibold text-zinc-900">{{ $progress->total_exercises }}</p>
            </div>
            <div class="bg-white border border-zinc-200 rounded-lg px-4 py-3">
                <p class="text-xs text-zinc-500 mb-0.5">Correct</p>
                <p class="text-lg font-semibold text-zinc-900">{{ $progress->correct_exercises }}</p>
            </div>
            <div class="bg-white border border-zinc-200 rounded-lg px-4 py-3">
                <p class="text-xs text-zinc-500 mb-0.5">Best Streak</p>
                <p class="text-lg font-semibold text-zinc-900">{{ $progress->best_streak }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="flex items-center gap-3">
            <select wire:model="selectedLevel" wire:change="loadNewExercise" class="text-sm bg-white border border-zinc-200 rounded-lg px-3 py-2 text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-1">
                <option value="all">All levels</option>
                <option value="A1">A1 - Beginner</option>
                <option value="A2">A2 - Elementary</option>
                <option value="B1">B1 - Intermediate</option>
                <option value="B2">B2 - Upper Inter.</option>
                <option value="C1">C1 - Advanced</option>
            </select>
            <select wire:model="selectedTopic" wire:change="loadNewExercise" class="text-sm bg-white border border-zinc-200 rounded-lg px-3 py-2 text-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-1">
                <option value="all">All topics</option>
                <option value="food">Food</option>
                <option value="family">Family</option>
                <option value="work">Work</option>
                <option value="travel">Travel</option>
                <option value="education">Education</option>
                <option value="routine">Routine</option>
                <option value="entertainment">Entertainment</option>
            </select>
        </div>

        @if($currentSentence)
        {{-- Exercise Card --}}
        <div class="bg-white border border-zinc-200 rounded-lg shadow-sm">

            {{-- Card Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-100">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-zinc-100 text-zinc-600">
                    {{ $currentSentence->level }} &middot; {{ $currentSentence->topic }}
                </span>
                <button wire:click="toggleFavorite" class="text-sm {{ $isFavorite ? 'text-rose-500' : 'text-zinc-300 hover:text-zinc-400' }} transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M9.653 16.915l-.005-.003-.019-.01a20.759 20.759 0 01-1.162-.682 22.045 22.045 0 01-2.765-2.033C3.747 12.458 2 10.28 2 7.5A4.5 4.5 0 016.5 3c1.274 0 2.456.558 3.297 1.472L10 4.7l.203-.228A4.488 4.488 0 0113.5 3 4.5 4.5 0 0118 7.5c0 2.78-1.747 4.958-3.702 6.687a22.045 22.045 0 01-3.927 2.715l-.019.01-.005.003L10 17.2l-.347-.285z"/>
                    </svg>
                </button>
            </div>

            {{-- Sentence to translate --}}
            <div class="px-6 pt-6 pb-4">
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-3">Translate to English</p>
                <p class="text-xl text-zinc-900 leading-relaxed">{{ $currentSentence->text_pt }}</p>
            </div>

            {{-- Hints --}}
            @if($hintsUsed > 0)
            <div class="mx-6 mb-4 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-xs font-medium text-amber-700 mb-1">Hints</p>
                @foreach(array_slice($availableHints, 0, $hintsUsed) as $hint)
                <p class="text-sm text-amber-600">{{ $hint }}</p>
                @endforeach
            </div>
            @endif

            @if(!$isReviewing)
            {{-- Answer Form --}}
            <form wire:submit.prevent="submitAnswer" class="px-6 pb-6">
                <div class="mb-4">
                    <textarea
                        wire:model="userAnswer"
                        rows="2"
                        class="w-full rounded-lg border border-zinc-200 px-4 py-3 text-zinc-900 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-1 resize-none"
                        placeholder="Type your translation here..."
                    ></textarea>
                    @error('userAnswer')
                    <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    @if($hintsUsed < count($availableHints))
                    <button
                        type="button"
                        wire:click="showHint"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors"
                    >
                        Hint
                        <span class="text-zinc-400">({{ $hintsUsed }}/{{ count($availableHints) }})</span>
                    </button>
                    @endif

                    <button
                        type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-zinc-900 rounded-lg hover:bg-zinc-800 transition-colors disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="submitAnswer"
                    >
                        <span wire:loading.remove wire:target="submitAnswer">Check Translation</span>
                        <span wire:loading wire:target="submitAnswer" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Checking...
                        </span>
                    </button>
                </div>
            </form>
            @endif

            {{-- AI Feedback --}}
            @if($isReviewing && $aiFeedback)
            <div class="border-t border-zinc-200">

                {{-- Score Banner --}}
                <div class="px-6 py-5 {{ $aiFeedback['is_correct'] ? 'bg-emerald-50' : 'bg-amber-50' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold {{ $aiFeedback['is_correct'] ? 'text-emerald-800' : 'text-amber-800' }}">
                                {{ $aiFeedback['is_correct'] ? 'Correct!' : 'Almost there' }}
                            </p>
                            <p class="text-sm {{ $aiFeedback['is_correct'] ? 'text-emerald-700' : 'text-amber-700' }} mt-0.5">
                                {{ $aiFeedback['overall_comment'] }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold {{ $aiFeedback['is_correct'] ? 'text-emerald-700' : 'text-amber-700' }}">
                                {{ $aiFeedback['score'] }}
                            </p>
                            <p class="text-xs {{ $aiFeedback['is_correct'] ? 'text-emerald-600' : 'text-amber-600' }}">/ 100</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 space-y-5">

                    {{-- Your Answer --}}
                    <div>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1.5">Your answer</p>
                        <p class="text-zinc-700">{{ $userAnswer }}</p>
                    </div>

                    {{-- Corrected Version --}}
                    <div>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1.5">Corrected version</p>
                        <p class="text-zinc-900 font-medium">{{ $aiFeedback['corrected_sentence'] }}</p>
                    </div>

                    {{-- Mistakes --}}
                    @if(!empty($aiFeedback['mistakes']))
                    <div>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-3">Issues found</p>
                        <div class="space-y-3">
                            @foreach($aiFeedback['mistakes'] as $mistake)
                            <div class="border border-zinc-200 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        {{ $mistake['type'] }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-2 text-sm">
                                    <div>
                                        <p class="text-zinc-400 text-xs mb-0.5">You wrote</p>
                                        <p class="text-rose-600 line-through">{{ $mistake['original'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-zinc-400 text-xs mb-0.5">Correction</p>
                                        <p class="text-emerald-700 font-medium">{{ $mistake['suggestion'] }}</p>
                                    </div>
                                </div>
                                <p class="text-sm text-zinc-600 mt-2">{{ $mistake['explanation_pt'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Natural Alternatives --}}
                    @if(!empty($aiFeedback['natural_alternatives']))
                    <div>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-2">Alternative ways to say it</p>
                        <ul class="space-y-1">
                            @foreach($aiFeedback['natural_alternatives'] as $alternative)
                            <li class="text-sm text-zinc-700 pl-3 border-l-2 border-zinc-200 py-0.5">{{ $alternative }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Positive Points --}}
                    @if(!empty($aiFeedback['positive_points']))
                    <div>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-2">What you got right</p>
                        <ul class="space-y-1">
                            @foreach($aiFeedback['positive_points'] as $point)
                            <li class="text-sm text-emerald-700 pl-3 border-l-2 border-emerald-300 py-0.5">{{ $point }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Reference --}}
                    @if($showReference)
                    <div>
                        <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider mb-1.5">Reference translation</p>
                        <p class="text-zinc-700 font-medium">{{ $currentSentence->text_en_reference }}</p>
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="px-6 py-4 border-t border-zinc-100 flex items-center gap-3">
                    @if(!$showReference)
                    <button
                        wire:click="$set('showReference', true)"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-50 transition-colors"
                    >
                        Show reference
                    </button>
                    @endif

                    <button
                        wire:click="loadNewExercise"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-zinc-900 rounded-lg hover:bg-zinc-800 transition-colors"
                    >
                        Next sentence
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M2 8a.75.75 0 01.75-.75h8.69L8.22 4.03a.75.75 0 011.06-1.06l4.5 4.5a.75.75 0 010 1.06l-4.5 4.5a.75.75 0 01-1.06-1.06l3.22-3.22H2.75A.75.75 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            {{-- Error flash --}}
            @if(session()->has('error'))
            <div class="px-6 pb-4">
                <div class="px-4 py-3 bg-rose-50 border border-rose-200 rounded-lg text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            </div>
            @endif
        </div>
        @else
        {{-- Empty State --}}
        <div class="bg-white border border-zinc-200 rounded-lg shadow-sm px-6 py-12 text-center">
            <p class="text-zinc-500">No sentences found for the selected filters.</p>
            <button wire:click="$set('selectedLevel', 'all')" class="mt-3 text-sm font-medium text-zinc-900 underline underline-offset-4 hover:text-zinc-700">
                Reset filters
            </button>
        </div>
        @endif

    </main>
</div>
