<x-app-layout>
    <x-slot name="title">
        {{ $memory_quiz->title }}
    </x-slot>
    <x-slot name="description">
        {{ $memory_quiz->description }}
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $memory_quiz->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($memory_quiz->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$memory_quiz->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(is_null($memory_quiz->game_banner_video) && !is_null($memory_quiz->game_banner))
                        @if ($memory_quiz->game_banner_url)
                            <a href="{{ $memory_quiz->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{$memory_quiz->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            </a>
                        @else
                            <img src="{{$memory_quiz->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                        @endif
                    @endif
                        <h2
                            class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ __('Memory Quiz') }}
                        </h2>
                        
                        <p class="font-bold mb-5">
                            {{ $memory_quiz->description }}
                        </p>
                        <div id="timer" class="rounded p-3 mb-5 text-2xl text-center font-bold">
                            {{ __('Remaining')}} <span></span> {{ __('seconds')}}
                        </div>
                        <div class="dark:bg-gray-800 overflow-hidden rounded grid grid-cols-4 gap-1 sm:gap-2 memory-game">
                            @foreach ($memory_quiz->memory_cards as $memory_card)
                                <div class="memory-card" data-framework="{{ $memory_card->name }}">
                                    <img class="front-face" src="{{ $memory_card->featured_image }}" alt="{{ $memory_card->name }}" title="{{ $memory_card->name }}" />
                                    <img class="back-face" src="{{$memory_quiz->back_card_image}}" alt="{{ $memory_quiz->title }}" />
                                </div>
                                <div class="memory-card" data-framework="{{ $memory_card->name }}">
                                    <img class="front-face" src="{{ $memory_card->featured_image }}" alt="{{ $memory_card->name }}" title="{{ $memory_card->name }}" />
                                    <img class="back-face" src="{{$memory_quiz->back_card_image}}" alt="{{ $memory_quiz->title }}" />
                                </div>
                            @endforeach
                        </div>
                        <div id="try-again" class="hidden text-center rounded mx-3 mb-5">
                            <h2 class="text-3xl mb-3 font-bold">
                                {{__('Time is up!')}}
                            </h2>
                            <a href="{{route('memory_quiz.show', ['tenant' => tenant('id'), 'slug'=>$memory_quiz->slug])}}">
                                <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                            </a>
                            <img src="{{$memory_quiz->failed_image}}" alt="{{__('Time is up!')}}" class="w-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            (function() {
                'use strict';
                
                const maxSeconds = {{$memory_quiz->seconds}};
                const totalCards = {{$memory_quiz->memory_cards->count() * 2}};
                const cards = document.querySelectorAll('.memory-card');
                
                let count = maxSeconds;
                let timerObj = null;
                let timer = null;
                let hasFlippedCard = false;
                let lockBoard = false;
                let firstCard = null;
                let secondCard = null;

                function initGame() {
                    timerObj = document.querySelector('#timer span');
                    
                    // Start game session on backend
                    axios.post('{{ route('game.start', ['tenant' => tenant('id')]) }}')
                        .then(({ data }) => {
                            // Game session initialized
                        });

                    // Start countdown timer
                    timer = setInterval(function() {
                        count--;
                        timerObj.innerHTML = count;
                        
                        if (count === 0) {
                            clearInterval(timer);
                            handleTimeout();
                        }
                    }, 1000);

                    // Shuffle cards
                    shuffleCards();
                    
                    // Attach event listeners
                    cards.forEach(card => card.addEventListener('click', flipCard));
                }

                function handleTimeout() {
                    const memoryGame = document.querySelector('.memory-game');
                    const timerEl = document.querySelector('#timer');
                    const tryAgain = document.querySelector('#try-again');

                    if (memoryGame) memoryGame.remove();
                    if (timerEl) timerEl.remove();
                    if (tryAgain) tryAgain.classList.remove('hidden');
                }

                function flipCard() {
                    if (lockBoard) return;
                    if (this === firstCard) return;

                    this.classList.add('flip');

                    if (!hasFlippedCard) {
                        hasFlippedCard = true;
                        firstCard = this;
                        return;
                    }

                    secondCard = this;
                    checkForMatch();
                }

                function checkForMatch() {
                    const isMatch = firstCard.dataset.framework === secondCard.dataset.framework;
                    isMatch ? disableCards() : unflipCards();
                }

                function disableCards() {
                    firstCard.removeEventListener('click', flipCard);
                    secondCard.removeEventListener('click', flipCard);
                    resetBoard();
                    isFinished();
                }

                function unflipCards() {
                    lockBoard = true;

                    setTimeout(() => {
                        firstCard.classList.remove('flip');
                        secondCard.classList.remove('flip');
                        resetBoard();
                    }, 1500);
                }

                function resetBoard() {
                    hasFlippedCard = false;
                    lockBoard = false;
                    firstCard = null;
                    secondCard = null;
                }

                function isFinished() {
                    const cardsGuessed = document.querySelectorAll('.memory-card.flip');
                    const cardsLeft = totalCards - cardsGuessed.length;
                    
                    if (cardsLeft === 0) {
                        clearInterval(timer);
                        
                        axios.post('{{route('memory_quiz.complete', ['tenant' => tenant('id')])}}', {
                            data: '{{$memory_quiz->id}}'
                        }, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (response) {
                            window.location = '{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $memory_quiz->award])}}';
                        })
                        .catch(function (error) {
                            // Handle error silently or show user-friendly message
                        });
                    }
                }

                function shuffleCards() {
                    cards.forEach(card => {
                        const randomPos = Math.floor(Math.random() * totalCards);
                        card.style.order = randomPos;
                    });
                }

                // Initialize game when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initGame);
                } else {
                    initGame();
                }
            })();
            </script>
    @endsection
</x-app-layout>
