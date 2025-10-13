<x-app-layout>
    <x-slot name="title">
        {{ $catch_game->title }}
    </x-slot>
    <x-slot name="description">
        {{ $catch_game->description }}
    </x-slot>

    @section('header_scripts')
        <style>
            #gameContainer {
                position: relative;
                overflow: hidden;
                height: 600px;
                background: url('{{ $catch_game->game_bg_image }}') no-repeat center center;
                background-size: cover;
            }

            #basket {
                position: absolute;
                bottom: 0;
                left: 42%;
                width: 100px;
                height: 150px;
                background: url('{{ $catch_game->basket_image }}') no-repeat center center;
                background-size: contain;
                z-index: 10;
            }

            .object {
                position: absolute;
                top: 0;
                width: 50px;
                height: 50px;
                z-index: 5;
            }

            #hud {
                padding: 5px 10px;
                border-radius: 5px;
                z-index: 20;
            }

            #message {
                position: absolute;
                width: 90%;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 32px;
                font-weight: bold;
                display: none;
                z-index: 30;
            }
        </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class="game-card rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $catch_game->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($catch_game->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$catch_game->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @endif

                        @if(is_null($catch_game->game_banner_video) && !is_null($catch_game->game_banner))
                            @if ($catch_game->game_banner_url)
                                <a href="{{ $catch_game->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{$catch_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                </a>
                            @else
                                <img src="{{$catch_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            @endif
                        @endif
                        <h2
                            class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ $catch_game->title }}
                        </h2>
                        
                        <p class="font-bold mb-5 text-center">
                            {{ $catch_game->description }}
                        </p>

                        {{-- Game --}}
                        <div id="try-again" class="hidden text-center rounded mx-3 mb-5">
                            <h2 class="text-3xl mb-3 font-bold">
                                {{__('Time is up!')}}
                            </h2>
                            <a href="{{route('catch_game.show', ['tenant' => tenant('id'), 'slug'=>$catch_game->slug])}}">
                                <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                            </a>
                            <img src="{{$catch_game->failed_image}}" alt="{{__('Time is up!')}}" class="w-full">
                        </div>
                        <div id="gameContainer" class="w-full rounded-lg">
                            <div id="hud" class="text-center font-bold mb-5">
                                {{ __('Score') }}: <span id="score">0</span> |
                                {{ __('Goal') }}: <span id="goal">20</span> |
                                {{ __('Time') }}: <span id="time">30</span>s
                            </div>
                            <button id="startButton" type="button"
                                class="block rounded-full px-5 py-2.5 mx-auto mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $catch_game->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $catch_game->btn_border ? 'border: 2px solid ' . $catch_game->btn_border_color . ';' : '' }} {{ $catch_game->btn_text_color ? 'color: ' . $catch_game->btn_text_color . ';' : '' }}background: {{ $catch_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $catch_game->btn_background_color_1 . ' 0%, ' . $catch_game->btn_background_color_2 . ' 85%);' }}">
                                {{ __('Start') }}
                            </button>
                            <div id="basket"></div>
                            <div id="message" class="text-white text-center"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            (function () {
                // --- Hidden constants ---
                const POINTS_PER_OBJECT = {{ $catch_game->points_per_object ?? 1 }};
                const GAME_DURATION = {{ $catch_game->seconds ?? 30 }};
                const SCORE_GOAL = {{ $catch_game->max_points ?? 20 }};

                // --- Elements ---
                const gameContainer = document.getElementById('gameContainer');
                const basket = document.getElementById('basket');
                const scoreEl = document.getElementById('score');
                const timeEl = document.getElementById('time');
                const goalEl = document.getElementById('goal');
                const messageEl = document.getElementById('message');
                const startButton = document.getElementById('startButton');

                goalEl.textContent = SCORE_GOAL;
                timeEl.textContent = GAME_DURATION;

                let score = 0;
                let timeLeft = GAME_DURATION;
                let gameOver = false;
                let basketX = gameContainer.clientWidth / 2 - 50;
                let gameInterval = null;

                function updateBasketPosition() {
                    basketX = Math.max(0, Math.min(gameContainer.clientWidth - basket.offsetWidth, basketX));
                    basket.style.left = basketX + "px";
                }

                // Controls
                document.addEventListener('keydown', (e) => {
                    if (gameOver) return;
                    const step = 20;
                    if (e.key === 'ArrowLeft') basketX -= step;
                    if (e.key === 'ArrowRight') basketX += step;
                    updateBasketPosition();
                });

                document.addEventListener('mousemove', (e) => {
                    if (gameOver) return;
                    basketX = e.clientX - gameContainer.clientWidth - basket.offsetWidth;
                    updateBasketPosition();
                });

                document.addEventListener('touchmove', (e) => {
                    if (gameOver) return;
                    basketX = e.touches[0].clientX - basket.offsetWidth / 2;
                    updateBasketPosition();
                });

                // Falling object images
                const OBJECT_IMAGES = [@foreach ($catch_game->catch_objects as $object)"{{ $object->object_image }}",@endforeach];

                function spawnObject() {
                    if (gameOver) return;
                    const obj = document.createElement("div");
                    obj.classList.add("object");
                    const randomImg = OBJECT_IMAGES[Math.floor(Math.random() * OBJECT_IMAGES.length)];
                    obj.innerHTML = `<img src="${randomImg}" width="50" height="50" alt="object">`;
                    obj.style.left = Math.random() * (gameContainer.clientWidth - 50) + "px";
                    gameContainer.appendChild(obj);

                    let objY = 0;
                    const fallSpeed = 3 + Math.random() * 2;

                    const fallInterval = setInterval(() => {
                        if (gameOver) {
                            clearInterval(fallInterval);
                            obj.remove();
                            return;
                        }
                        objY += fallSpeed;
                        obj.style.top = objY + "px";

                        const objRect = obj.getBoundingClientRect();
                        const basketRect = basket.getBoundingClientRect();

                        if (
                            objRect.bottom >= basketRect.top &&
                            objRect.left < basketRect.right &&
                            objRect.right > basketRect.left
                        ) {
                            score += POINTS_PER_OBJECT;
                            scoreEl.textContent = score;
                            obj.remove();
                            clearInterval(fallInterval);
                            if (score >= SCORE_GOAL) {
                                endGame(true);
                            }
                        } else if (objY > window.innerHeight) {
                            obj.remove();
                            clearInterval(fallInterval);
                        }
                    }, 20);
                }

                function startTimer() {
                    const timer = setInterval(() => {
                        if (gameOver) {
                            clearInterval(timer);
                            return;
                        }
                        timeLeft--;
                        timeEl.textContent = timeLeft;
                        if (timeLeft <= 0) {
                            clearInterval(timer);
                            endGame(false);
                        }
                    }, 1000);
                }

                function endGame(win) {
                    gameOver = true;
                    messageEl.style.display = 'block';
                    if (win) {
                        messageEl.textContent = "{{ __('You Win!') }} 🎉";
                        axios.post('{{route('catch_game.complete', ['tenant' => tenant('id')])}}', {data: '{{$catch_game->id}}'}, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (response)
                        {
                            window.location = '{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $catch_game->award])}}';
                        })
                        .catch(function (error)
                        {
                            //console.log(error);
                        });
                    } else {
                        gameContainer.remove();
                        document.getElementById('try-again').classList.remove('hidden');
                    }
                }

                function startGame() {
                    score = 0;
                    timeLeft = GAME_DURATION;
                    gameOver = false;
                    messageEl.style.display = 'none';
                    scoreEl.textContent = score;
                    timeEl.textContent = timeLeft;

                    updateBasketPosition();
                    startTimer();
                    gameInterval = setInterval(spawnObject, 1000);
                }

                // Start button listener
                startButton.addEventListener('click', () => {
                    startButton.style.display = 'none';
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });

                    axios.post('{{ route('game.start', ['tenant' => tenant('id')]) }}')
                    .then(({ data }) => {
                        console.log('Game started at', data.game_start);
                    });

                    startGame();
                });

                window.addEventListener('resize', updateBasketPosition);
                window.addEventListener('orientationchange', updateBasketPosition);
            })();
        </script>
    @endsection
</x-app-layout>
