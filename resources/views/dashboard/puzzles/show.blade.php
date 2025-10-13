<x-app-layout>
    <x-slot name="title">
        {{ $puzzle->title }}
    </x-slot>
    <x-slot name="description">
        {{ $puzzle->description }}
    </x-slot>

    @section('header_scripts')
        <style>
            #forPuzzle {
                position: relative;
                width: 90%;
                height: 70vh;
                top: 0%;
                left: 5%;
                background-color: transparent;
                overflow: visible;
            }

            #forPuzzle img{
                border-radius: 15px;
            }

            .polypiece {
                display: block;
                overflow: hidden;
                position: absolute;
                cursor:grab;
                touch-action: none;
                user-select: none;
            }

            .moving {
                transition-property: top, left;
                transition-duration: 1s;
                transition-timing-function: linear;
            }

            .gameCanvas {
                display: none;
                overflow: hidden;
                position: absolute;
            }
        </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class="game-card rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $puzzle->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($puzzle->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$puzzle->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @endif

                    @if(is_null($puzzle->game_banner_video) && !is_null($puzzle->game_banner))
                        @if ($puzzle->game_banner_url)
                            <a href="{{ $puzzle->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{$puzzle->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            </a>
                        @else
                            <img src="{{$puzzle->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                        @endif
                    @endif
                        <h2
                            class="font-semibold text-2xl dark:text-gray-200 leading-tight pb-5 uppercase game-heading">
                            {{ __('Puzzle') }}
                        </h2>
                        
                        <p class="font-bold mb-5">
                            {{ $puzzle->description }}
                        </p>

                        <button id="startPuzzleBtn" type="button"
                            class="block rounded-full px-5 py-2.5 mx-auto mb-3 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 font-bold {{ $puzzle->btn_shadow ? 'buttons-shadow' : '' }}" style="{{ $puzzle->btn_border ? 'border: 2px solid ' . $puzzle->btn_border_color . ';' : '' }} {{ $puzzle->btn_text_color ? 'color: ' . $puzzle->btn_text_color . ';' : '' }}background: {{ $puzzle->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $puzzle->btn_background_color_1 . ' 0%, ' . $puzzle->btn_background_color_2 . ' 85%);' }}">
                            {{ __('Start') }}
                        </button>

                        <div id="timer" class="rounded p-3 mb-5 text-2xl text-center font-bold hidden">
                            {{ __('Remaining')}} <span></span> {{ __('seconds')}}
                        </div>
                        <input hidden id="shape" value="1">
                        <div class="dark:bg-gray-800 rounded puzzle-game relative" style="height: 70vh;">
                            <div id="forPuzzle" ></div>
                        </div>
                        <div id="try-again" class="hidden text-center rounded mx-3 mb-5">
                            <h2 class="text-3xl mb-3 font-bold">
                                {{__('Time is up!')}}
                            </h2>
                            <a href="{{route('puzzle.show', ['tenant' => tenant('id'), 'slug'=>$puzzle->slug])}}">
                                <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                            </a>
                            <img src="{{$puzzle->failed_image}}" alt="{{__('Time is up!')}}" class="w-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="/games/puzzle.js"></script>

        <script>
            let gameStarted = false;
            document.getElementById("startPuzzleBtn").addEventListener("click", () => {
                events.push({ event: "nbpieces", nbpieces: {{ $puzzle->pieces }} });

                gameStarted = true;
                let count = {{$puzzle->seconds}};
                let timerCont = document.querySelector('#timer');
                let timerObj = document.querySelector('#timer span');
                let buttonInit = document.querySelector('#startPuzzleBtn');
                buttonInit.remove();
                timerCont.classList.remove('hidden');

                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: 'smooth'
                });

                const timer = setInterval(function() {
                    count--;
                    timerObj.innerHTML = count;
                    if (count === 0) {
                        clearInterval(timer);
                        let puzzleGame = document.querySelector('.puzzle-game');
                        let timerEl = document.querySelector('#timer');
                        let tryAgain = document.querySelector('#try-again');

                        puzzleGame.remove();
                        timerEl.remove();
                        tryAgain.classList.remove('hidden');
                    }
                }, 1000);

                axios.post('{{ route('game.start', ['tenant' => tenant('id')]) }}')
                    .then(({ data }) => {
                        //console.log('Game started at', data.game_start);
                    });

            });

            loadInitialFile();

            function loadInitialFile() {
                puzzle.srcImage.src = "{{ $puzzle->puzzle_image }}";
            }


            function isFinishedGame(){
                clearInterval(timer); // Stop the timer
                axios.post('{{route('puzzle.complete', ['tenant' => tenant('id')])}}', {data: '{{$puzzle->id}}'}, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function (response)
                {
                    window.location = '{{route('dashboard.awards.show', ['tenant' => tenant('id'), 'award' => $puzzle->award])}}';
                })
                .catch(function (error)
                {
                    //console.log(error);
                });
            }

            const canvas = document.getElementById('forPuzzle');
            const pieces = document.querySelectorAll('.polypiece');
            let activePiece = null;
            let startX = 0, startY = 0;
            let currentX = 0, currentY = 0;

            pieces.forEach(piece => {
                piece.dataset.x = 0;
                piece.dataset.y = 0;

                piece.addEventListener('touchstart', startDrag, { passive: false });
                piece.addEventListener('mousedown', startDrag);
            });

            document.addEventListener('touchmove', moveDrag, { passive: false });
            document.addEventListener('mousemove', moveDrag);
            document.addEventListener('touchend', stopDrag);
            document.addEventListener('mouseup', stopDrag);

            function startDrag(e) {
                if (gameStarted) e.preventDefault();

                activePiece = e.target.closest('.polypiece');
                const touch = e.touches ? e.touches[0] : e;

                startX = touch.clientX;
                startY = touch.clientY;
                currentX = parseFloat(activePiece.dataset.x) || 0;
                currentY = parseFloat(activePiece.dataset.y) || 0;
            }

            function moveDrag(e) {
                if (!activePiece) return;

                if (gameStarted) e.preventDefault();

                const touch = e.touches ? e.touches[0] : e;
                const dx = touch.clientX - startX;
                const dy = touch.clientY - startY;

                let newX = currentX + dx;
                let newY = currentY + dy;

                const canvasRect = canvas.getBoundingClientRect();
                const pieceRect = activePiece.getBoundingClientRect();

                // Boundaries
                newX = Math.max(0, Math.min(newX, canvas.clientWidth - pieceRect.width));
                newY = Math.max(0, Math.min(newY, canvas.clientHeight - pieceRect.height));

                activePiece.style.transform = `translate(${newX}px, ${newY}px)`;
                activePiece.dataset.x = newX;
                activePiece.dataset.y = newY;
            }

            function stopDrag() {
                activePiece = null;
            }
            
        </script>
    @endsection
</x-app-layout>
