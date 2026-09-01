<x-app-layout>
    <x-slot name="title">
        {{ $smash_game->title }}
    </x-slot>
    <x-slot name="description">
        {{ $smash_game->description }}
    </x-slot>
    <x-slot name="settingspzl">
        {{ $puzzle_settings }}
    </x-slot>

    @section('header_scripts')
        <style>
        #containerGame {
            position: relative;
            overflow: hidden;
            height: 700px;
            background: url('{{ $smash_game->game_bg_image }}') no-repeat center center;
            background-size: cover;
        }
        #startButton{
            cursor: pointer;
        }
        .wrapper {
			--game-over: 0;
			position: relative;
			display: flex;
			justify-content: center;
		}

		.wrapper .score {
			position: absolute;
			top: 0;
			font-size: 2rem;
			padding: 1rem;
			color: #000;
			font-family: monospace;
			z-index: 0;
			pointer-events: none;
			opacity: .8;
		}

		.element--wrapper {
			inset: 0;
			filter: blur(calc(10px * var(--game-over))) grayscale(var(--game-over));
			transition: filter .5s ease;
            z-index: 1;
		}

		@keyframes move {
			to {
				transform: translateY(calc(100vh));
			}
		}

		.element::before {
			content: '';
			position: absolute;
			width: 1px;
			height: 1px;
			top: 0px;
			left: 0px;
			box-shadow: var(--particles);
		}

		.btn {
			--hover: 0;
			--active: 0;
			--show: 0;
			--btn-width: 175px;
			--btn-height: 50px;
			--block-size: 10px;
			--bg: hsl(22, 60%, max(50%, calc(var(--hover) * 60%)));
			position: absolute;
			left: 50%;
			opacity: var(--show);
			top: calc((100% - (60% * var(--show))) + var(--btn-height) + var(--block-size));
			translate: -50% -50%;
			width: var(--btn-width);
			height: calc(var(--btn-height));
			font-family: monospace;
			text-transform: uppercase;
			background-color: var(--bg);
			color: color-mix(in lch, var(--bg), black 85%);
			font-weight: 800;
			letter-spacing: -1px;
			border: none;
			font-size: 1.5rem;
			display: flex;
			align-items: center;
			justify-content: center;
			filter: drop-shadow(0 0 calc(5px * var(--active)) var(--bg)) drop-shadow(0 0 calc(15px * var(--active)) var(--bg));
			transition: filter 0s ease, top .3s ease;
		}

		.btn::before {
			content: '';
			position: absolute;
			width: 0px;
			height: 0px;
			top: 0px;
			left: 0px;
			color: var(--bg);
			box-shadow:
				calc(-1 * var(--block-size)) calc(-1 * var(--block-size)) 0px var(--block-size),
				calc(-1 * var(--block-size) - (var(--block-size)*1.5)) calc(-1 * var(--block-size) - (var(--block-size)*1.5)) 0 calc(var(--block-size)/2),
				calc(var(--btn-width) + var(--block-size)) calc(-1 * var(--block-size)) 0px var(--block-size) currentColor,
				calc(var(--btn-width) + var(--block-size) + (var(--block-size)*1.5)) calc((-1 * var(--block-size)) - (var(--block-size)*1.5)) 0px calc(var(--block-size)/2),
				calc(var(--btn-width) + var(--block-size)) calc(var(--btn-height) + var(--block-size)) 0px var(--block-size),
				calc(var(--btn-width) + (var(--block-size)*1.5) + var(--block-size)) calc(var(--btn-height) + (var(--block-size) * 1.5) + var(--block-size)) 0px calc(var(--block-size)/2),
				calc(-1 * var(--block-size)) calc(var(--btn-height) + var(--block-size)) 0px var(--block-size),
				calc(-1 * var(--block-size) - (var(--block-size)*1.5)) calc(var(--btn-height) + (var(--block-size)*1.5) + var(--block-size)) 0px calc(var(--block-size)/2);
		}
		.win{
			--hover: 0;
			--active: 0;
			--show: 0;
			--btn-width: 175px;
			--btn-height: 50px;
			--block-size: 10px;
			--bg: hsl(22, 100%, max(50%, calc(var(--hover) * 60%)));
			position: absolute;
			opacity: var(--show);
			left: 50%;
			top: calc((100% - (70% * var(--show))) + var(--btn-height) + var(--block-size));
			translate: -50% -20%;
			font-family: monospace;
			text-transform: uppercase;
			background-color: var(--bg);
			color: color-mix(in lch, var(--bg), black 85%);
			font-weight: 800;
			letter-spacing: -1px;
			border: none;
			font-size: 3rem;
			display: flex;
			align-items: center;
			justify-content: center;
			flex-direction: column;
			transition: filter 0s ease, top .3s ease;
			padding: 40px;
			border: solid #000 8px;
    		border-radius: 60px;
		}
		.win h2, .win h4 {
			margin: 0;
			color: #000;
			text-align: center;
		}
		.btn:hover {
			--hover: 1;
			cursor: pointer;
		}

		.btn:active {
			--active: 1;
		}

		.wrapper.game-over {
			--game-over: 1;
		}

		.wrapper.game-over .element--wrapper {
			pointer-events: none;
		}

		.lost.show {
			--show: 1;
		}
		 .win.show {
			--show: 1;
		}
        .text-shadow{
            text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2);
        }
        </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class=" p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $smash_game->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($smash_game->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$smash_game->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @endif

                        @if(is_null($smash_game->game_banner_video) && !is_null($smash_game->game_banner))
                            @if ($smash_game->game_banner_url)
                                <a href="{{ $smash_game->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{$smash_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                </a>
                            @else
                                <img src="{{$smash_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            @endif
                        @endif
                        
                        <div class="pt-3  z-40">
                            <h2
                                class="font-semibold text-2xl leading-tight pb-5 uppercase game-heading">
                                {{ $smash_game->title }}
                            </h2>
                            
                            <p class="font-bold mb-5 text-center">
                                {{ $smash_game->description }}
                            </p>

                            {{-- Game --}}
                            <div id="try-again" class="hidden text-center rounded mx-4 mb-5">
                                <h2 class="text-3xl mb-3 font-bold">
                                    {{__('Sorry, you lost!')}}
                                </h2>
                                <a href="{{route('smash_game.show', ['tenant' => tenant('id'), 'slug'=>$smash_game->slug])}}">
                                    <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                                </a>
                                <img src="{{$smash_game->failed_image}}" alt="{{__('Sorry, you lost!')}}" class="w-full">
                            </div>
                            <div id="containerGame" class="w-full rounded-lg">
                                <div id="hud" class="text-center font-bold mb-5 text-normal flex justify-between px-7 mt-6">
                                    <div class="bg-white rounded-full px-4 text-black">{{ __('Goal') }}: <span id="scoreToWin" class="text-black">20</span></div>
                                    <div class="bg-white rounded-full px-4 text-black">{{ __('Score') }}: <span id="score" class="text-black">0</span></div>
                                    <div class="bg-white rounded-full px-4 text-black">{{ __('Time') }}: <span id="time" class="text-black">30</span>s</div>
                                </div>
                                <div class="w-full flex justify-center items-center">
                                    <span id="startButton"
                                        class="text-shadow block mt-24 p-8 font-bold text-center w-[260px] rounded-lg text-3xl leading-8" 
                                        style="{{ $smash_game->btn_text_color ? 'color: ' . $smash_game->btn_text_color . ';' : '' }}background: {{ $smash_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $smash_game->btn_background_color_1 . ' 0%, ' . $smash_game->btn_background_color_2 . ' 85%);' }}">
                                        {{ __('Click here to start the game!') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16  w-full mt-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                        </svg>
                                    </span>
                                    
                                </div>
                                <div class="element--wrapper w-full h-full" ></div>
                            </div>
                            <div id="message" class="text-white text-center text-2xl text-shadow font-bold py-12"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script>
    const settingspzl = JSON.parse(document.getElementById('settingspzl').content)

    const maxSeconds = settingspzl.a;
    const puzzleId = settingspzl.b;
    const puzzleImageSrc = settingspzl.c;
    const nbPieces = settingspzl.d;

    const GAME_DURATION = settingspzl.b;

    const startButton = document.getElementById('startButton');
    const containerGame = document.getElementById('containerGame');
    const scoreEl = document.getElementById('score');
    const timeEl = document.getElementById('time');
    const goalEl = document.getElementById('scoreToWin');
    const messageEl = document.getElementById('message');
    const elements_wrapper = document.querySelector(".element--wrapper");

    messageEl.style.display = 'none';

    goalEl.textContent = settingspzl.c;
    timeEl.textContent = settingspzl.b;

    let gameOver = false;
    let gameStarted = false;
    let timeLeft = GAME_DURATION;

    let elements = [];
    let count = 4;
    let score = 0;

    let timer = null;

    /*
    |--------------------------------------------------------------------------
    | Particle
    |--------------------------------------------------------------------------
    */

    class Particle {
        constructor(x, y, size, hue) {
            this.x = x;
            this.y = y;
            this.size = size;
            this.hue = hue;

            this.el = '';

            this.dx = (.5 - Math.random()) * .15;
            this.dy = (-1 - Math.random()) * .1;

            this.alpha = 1;

            this.saturation = rand(50, 100);
            this.lightness = rand(40, 60);

            this.draw();
        }

        draw() {
            this.el = `
                ${(this.size / 2) + this.x * this.size}px
                ${(this.size / 2) + this.y * this.size}px
                0px
                ${this.size / 2}px
                hsla(
                    ${this.hue}
                    ${this.saturation}%
                    ${this.lightness}%
                    /
                    ${this.alpha}
                )
            `;
        }

        animate() {
            this.y += this.dy;
            this.x += this.dx;

            this.size += .01;
            this.alpha -= .03;

            this.draw();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Effect
    |--------------------------------------------------------------------------
    */

    class Effect {
        constructor(particle_size, particle_hue, parent) {
            this.particle_size = particle_size;
            this.particle_hue = particle_hue;
            this.parent = parent;

            this.particles = [];
            this.req = null;

            this.draw();
            this.animate();
        }

        draw() {
            const rect = this.parent.getBoundingClientRect();

            for (
                let x = 0;
                x < rect.height / this.particle_size;
                x++
            ) {
                for (
                    let y = 0;
                    y < rect.width / this.particle_size;
                    y++
                ) {
                    this.particles.push(
                        new Particle(
                            x,
                            y,
                            this.particle_size,
                            this.particle_hue
                        )
                    );
                }
            }

            this.parent.style.setProperty(
                "--particles",
                this.particles.map(p => p.el).join(",")
            );
        }

        update() {
            if (this.particles.length <= 0) {
                cancelAnimationFrame(this.req);

                if (this.parent?.isConnected) {
                    this.parent.remove();
                }

                return;
            }

            this.parent.style.setProperty(
                "--particles",
                this.particles.map(p => p.el).join(",")
            );

            this.parent.textContent = '';
        }

        animate() {
            this.req = requestAnimationFrame(() => this.animate());

            /*
             * filter evita hacer splice() mientras
             * estamos recorriendo el mismo array.
             */
            this.particles = this.particles.filter(particle => {
                if (particle.alpha <= 0) {
                    return false;
                }

                particle.animate();

                return true;
            });

            this.update();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Element
    |--------------------------------------------------------------------------
    */

    class Element {
        constructor(i, x, y, width, height, color, total, duration) {
            Object.assign(this, {
                i,
                x,
                y,
                width,
                height,
                color,
                total,
                duration
            });

            this.destroyed = false;
            this.clicked = false;

            this.init();
            this.addEventListeners();
        }

        init() {
            this.ELEMENT = document.createElement("div");

            this.ELEMENT.className = "element";

            /*
             * No dependemos del índice del array como ID.
             * Solo es una referencia del objeto.
             */
            this.ELEMENT.dataset.elementId = this.i;

            const image = document.createElement('img');

            image.src = getRandomImage();
            image.alt = 'element';

            /*
             * IMPORTANTE:
             * evita el drag & drop nativo de imágenes en desktop.
             */
            image.draggable = false;

            image.style.width = `${this.width}px`;
            image.style.height = `${this.height}px`;
            image.style.objectFit = 'contain';
            image.style.pointerEvents = 'none';
            image.style.userSelect = 'none';

            /*
             * También prevenimos cualquier intento de drag
             * directamente sobre la imagen.
             */
            image.addEventListener('dragstart', event => {
                event.preventDefault();
            });

            this.ELEMENT.appendChild(image);

            this.ELEMENT.style.cssText = `
                position: absolute;
                top: ${this.y}px;
                left: ${this.x}px;

                width: ${this.width}px;
                height: ${this.height}px;

                cursor: pointer;

                display: flex;
                align-items: center;
                justify-content: center;

                user-select: none;
                -webkit-user-select: none;
                -webkit-user-drag: none;

                touch-action: manipulation;

                transform: translateY(${-this.height}px);

                animation:
                    move
                    ${this.duration}ms
                    linear
                    infinite
                    forwards;

                animation-delay:
                    ${(
                        (this.duration / this.total)
                        * ((this.total - this.i) + 1)
                    )}ms;
            `;
        }

        addEventListeners() {

            /*
             * Si el objeto llegó al final de la pantalla:
             *
             * NO terminamos todo el juego.
             *
             * Simplemente aparece nuevamente arriba
             * en otra posición.
             */
            this.ELEMENT.addEventListener(
                'animationiteration',
                () => {
                    if (gameOver || this.destroyed) {
                        return;
                    }

                    this.moveToRandomPosition();
                }
            );

            /*
             * pointerdown funciona tanto para:
             *
             * mouse
             * touch
             * stylus
             *
             * y además evita muchos problemas derivados
             * de usar onclick.
             */
            this.ELEMENT.addEventListener(
                'pointerdown',
                event => {

                    event.preventDefault();
                    event.stopPropagation();

                    if (gameOver) {
                        return;
                    }

                    if (this.destroyed) {
                        return;
                    }

                    if (this.clicked) {
                        return;
                    }

                    this.clicked = true;

                    this.smash();
                },
                {
                    passive: false
                }
            );

            /*
             * Protección adicional contra drag & drop
             * del navegador.
             */
            this.ELEMENT.addEventListener(
                'dragstart',
                event => event.preventDefault()
            );

            this.ELEMENT.addEventListener(
                'mousedown',
                event => event.preventDefault()
            );
        }

        moveToRandomPosition() {
            const maxLeft = Math.max(
                0,
                containerGame.offsetWidth - this.width
            );

            this.ELEMENT.style.left =
                rand(0, maxLeft) + "px";
        }

        smash() {
            if (this.destroyed || gameOver) {
                return;
            }

            this.destroyed = true;

            /*
             * Detenemos inmediatamente la animación
             * para evitar eventos inesperados.
             */
            this.ELEMENT.style.animationPlayState = 'paused';

            /*
             * Efecto visual.
             */
            new Effect(
                35,
                22,
                this.ELEMENT
            );

            /*
             * Sumamos puntos.
             */
            score += Number(settingspzl.a);

            updateScore();

            /*
             * Utilizamos >= y no ==
             * para evitar que un incremento que se pase
             * del objetivo impida ganar.
             */
            const scoreToWin =
                parseInt(settingspzl.c) || 200;

            if (score >= scoreToWin) {
                endGame(true);
                return;
            }

            /*
             * Eliminamos la referencia del array
             * sin depender del índice original.
             */
            elements = elements.filter(
                element => element !== this
            );

            /*
             * Creamos uno nuevo para mantener
             * siempre la misma cantidad de elementos.
             */
            spawnElement();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Game
    |--------------------------------------------------------------------------
    */

    function startGame() {
        if (gameStarted) {
            return;
        }

        gameStarted = true;
        gameOver = false;

        score = 0;
        timeLeft = GAME_DURATION;

        elements = [];

        updateScore();

        timeEl.textContent = timeLeft;

        elements_wrapper.innerHTML = '';

        for (let i = 0; i < count; i++) {
            spawnElement(i);
        }

        startTimer();
    }

    /*
    |--------------------------------------------------------------------------
    | Spawn element
    |--------------------------------------------------------------------------
    */

    function spawnElement(id = null) {
        if (gameOver) {
            return;
        }

        /*
         * ID único para evitar depender de la posición
         * dentro del array.
         */
        const uniqueId =
            id ??
            `${Date.now()}-${Math.random()}`;

        const width = 80;
        const height = 80;

        const maxLeft = Math.max(
            0,
            containerGame.offsetWidth - width
        );

        const element = new Element(
            uniqueId,
            rand(0, maxLeft),
            0,
            width,
            height,
            "#E06015",
            count,
            3000
        );

        elements.push(element);

        elements_wrapper.append(
            element.ELEMENT
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Score
    |--------------------------------------------------------------------------
    */

    function updateScore() {
        scoreEl.textContent = score;
    }

    /*
    |--------------------------------------------------------------------------
    | End game
    |--------------------------------------------------------------------------
    */

    function endGame(win) {

        /*
         * Muy importante:
         *
         * timer + animaciones + clicks podrían
         * intentar terminar el juego simultáneamente.
         */
        if (gameOver) {
            return;
        }

        gameOver = true;

        if (timer) {
            clearInterval(timer);
            timer = null;
        }

        /*
         * Congelamos todos los objetos.
         */
        elements.forEach(element => {
            if (element.ELEMENT) {
                element.ELEMENT.style.animationPlayState =
                    'paused';
            }
        });

        if (win) {

            messageEl.style.display = 'block';

            messageEl.textContent =
                settingspzl.e + " 🎉";

            containerGame.remove();

            axios.post(
                settingspzl.f,
                {
                    data: settingspzl.g,
                    slug: settingspzl.j
                },
                {
                    headers: {
                        'Content-Type':
                            'multipart/form-data'
                    }
                }
            )
            .then(function (response) {

                window.location =
                    settingspzl.h;

            })
            .catch(function (error) {

                console.error(
                    'Error completing Smash Game:',
                    error
                );

            });

        } else {

            containerGame.remove();

            document
                .getElementById('try-again')
                .classList.remove('hidden');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Timer
    |--------------------------------------------------------------------------
    */

    function startTimer() {

        if (timer) {
            clearInterval(timer);
        }

        timer = setInterval(() => {

            if (gameOver) {

                clearInterval(timer);

                timer = null;

                return;
            }

            timeLeft--;

            timeEl.textContent = timeLeft;

            if (timeLeft <= 0) {

                clearInterval(timer);

                timer = null;

                endGame(false);
            }

        }, 1000);
    }

    /*
    |--------------------------------------------------------------------------
    | Random image
    |--------------------------------------------------------------------------
    */

    function getRandomImage() {

        const images = settingspzl.d;

        if (!Array.isArray(images) || images.length === 0) {
            return '';
        }

        const randomIndex =
            Math.floor(
                Math.random() * images.length
            );

        return images[randomIndex];
    }

    /*
    |--------------------------------------------------------------------------
    | Start button
    |--------------------------------------------------------------------------
    */

    startButton.addEventListener(
        'click',
        () => {

            if (gameStarted) {
                return;
            }

            startButton.style.display = 'none';

            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });

            /*
             * Informamos al backend que comenzó
             * el juego.
             *
             * No esperamos el POST para comenzar
             * para que la experiencia sea inmediata.
             */
            axios
                .post(settingspzl.i)
                .then(({ data }) => {
                    console.log(
                        'Game started at',
                        data.game_start
                    );
                })
                .catch(error => {
                    console.error(
                        'Error starting Smash Game:',
                        error
                    );
                });

            startGame();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const rand = (min, max) =>
        Math.floor(
            Math.random()
            * (max - min + 1)
            + min
        );
</script>
</x-app-layout>
