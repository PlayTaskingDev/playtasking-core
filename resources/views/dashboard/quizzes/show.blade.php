<x-app-layout>
    <x-slot name="title">
        {{ $quiz->title }}
    </x-slot>
    <x-slot name="description">
        {{ $quiz->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class=" overflow-hidden">
                <div class="game-card rounded-lg shadow p-3">

                    <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $quiz->campaign->slug])" :active="'games'" />
                           
                        @if (!is_null($quiz->game_banner_video))
                            <div class="aspect-w-16 aspect-h-9 mb-6">
                                <iframe src="{{$quiz->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        @endif

                        @if(is_null($quiz->game_banner_video) && !is_null($quiz->game_banner))
                            @if ($quiz->game_banner_url)
                                <a href="{{ $quiz->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{$quiz->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                </a>
                            @else
                                <img src="{{$quiz->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            @endif
                            
                        @endif
                    
                        <form id="quiz_{{ $quiz->id }}" class="quiz-container" method="POST"
                        action="{{ route('quiz.evaluate', ['tenant' => tenant('id')]) }}">
                        @csrf
                        <input type="hidden" name="quid" value="{{ $quiz->id }}">

                        @if (!is_null($quiz->brief_image))
                        <img src="{{ $quiz->brief_image }}" alt="{{ $quiz->title }}" class="mx-auto w-full max-w-lg px-1">
                        @else
                            <h2
                                class="font-semibold text-2xl leading-tight pb-5 pt-5 uppercase game-heading">
                                {{ __('Quiz') }}
                            </h2>
                            <p class="font-bold mb-5 mt-5 game-description">
                                {{ $quiz->description }}
                            </p>
                        @endif

                        @foreach ($quiz->questions as $question)
                       
                            <div id="question_{{ $loop->iteration }}"
                               style="{{ !$loop->first ? 'display:none;' : '' }}"
                                class="section-question grid gap-4 sm:grid-cols-1 {{ $loop->first ? '' : 'opacity-0' }}">
                                <p class="font-bold mb-5 text-xl question-title">
                                    {{$question->title}}
                                </p>
                                 <img src="{{$question->featured_image}}" alt="" class="w-full rounded mb-5">
                                <div>
                                    <ul class="w-auto grid grid-cols-2 gap-4 text-sm font-medium quiz-answers">
                                        @foreach ($question->answers as $answer)
                                            <li class="w-full py-3 rounded-3xl bg-gray-100 text-black ">
                                                <div
                                                    class="{{ !is_null($answer->featured_image) ? 'checkbox-group' : '' }} px-4 py-0 w-full flex items-center">
                                                    <input id="answer_{{ $answer->id }}" type="radio"
                                                        value="{{ $answer->id }}"
                                                        name="answers[{{ $question->id }}][answer]"
                                                        class="w-4 h-4 my-3 mr-5 bg-gray-100 active:bg-black checked:ring-black checked:bg-black border-gray-300 ring-black focus:ring-2 focus:ring-black focus:bg-black"
                                                        required>
                                                    <label for="answer_{{ $answer->id }}"
                                                        class="w-full py-3 ml-2 text-sm font-medium ">{{ $answer->title }}
                                                    </label>
                                                </div>
                                                @if (!is_null($answer->featured_image))
                                                    <img src="{{ $answer->featured_image }}"
                                                        alt="{{ $answer->title }}" class="mx-auto w-full rounded -mt-14">
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="grid {{ !$loop->first ? 'grid-cols-2' : '' }} gap-4 my-4">
                                        @if (!$loop->first)
                                            <x-primary-button type="button" class="trigger_btn previous_btn"
                                                data-hidequestion="question_{{ $loop->iteration }}"
                                                data-showquestion="question_{{ $loop->iteration - 1 }}">
                                                {{ __('Previous') }}
                                            </x-primary-button>
                                        @endif
                                        @if (!$loop->last)
                                            <x-primary-button type="button" class="trigger_btn next_btn"
                                                data-hidequestion="question_{{ $loop->iteration }}"
                                                data-showquestion="question_{{ $loop->iteration + 1 }}"
                                                data-validateanswer="answers[{{ $question->id }}][answer]">
                                                {{ __('Next') }}
                                            </x-primary-button>
                                        @endif
                                        @if ($loop->last)
                                            <x-primary-button class="w-full">
                                                {{ __('Send response') }}
                                            </x-primary-button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            const triggerButtons = document.querySelectorAll('button.trigger_btn');

            triggerButtons.forEach(element => {
                element.addEventListener("click", () => {
                    showQuestion(element);
                });
            });

            function showQuestion(element) {
                // Prevent move forward on empty response
                if (element.classList.contains('next_btn')) {
                    var validateDataProperty = element.dataset.validateanswer;
                    var getSelectedValue = document.querySelector('input[name="' + validateDataProperty + '"]:checked');
                    if (getSelectedValue === null) {
                        let modalTitle = document.getElementById("modal-title");
                        let modalContent = document.getElementById("modal-content");
                        let modalIcon = document.getElementById("modal-icon");
                        modalContent.innerHTML = '';

                        modalTitle.innerHTML = '{{ __('Error') }}';
                        modalIcon.src = '{{ Vite::asset('resources/images/movie-error.png') }}';
                        let paragraph = document.createElement("p");
                        paragraph.innerText = '{{ __('You must select a response') }}';

                        modalContent.appendChild(paragraph);
                        modalWindow.show();
                        return false;
                    }
                }
                //Show and hide questions
                var showQuestionDataProperty = element.dataset.showquestion;
                var showQuestion = document.getElementById(showQuestionDataProperty);

                var hideQuestionDataProperty = element.dataset.hidequestion;
                var hideQuestion = document.getElementById(hideQuestionDataProperty);

                hideQuestion.style.display = 'none';
                //hideQuestion.classList.add('hidden');
                hideQuestion.classList.remove('opacity-100');
                hideQuestion.classList.add('opacity-0');

                
                showQuestion.style.display = 'block';
                //showQuestion.classList.remove('hidden');
                showQuestion.classList.add('opacity-100');
                showQuestion.classList.remove('opacity-0');
            }
        </script>
    @endsection
</x-app-layout>
