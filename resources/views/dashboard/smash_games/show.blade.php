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
                <div class="game-card rounded-lg dark:bg-gray-800 dark:border-gray-700">
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
                                    <div class="bg-white rounded-full px-4">{{ __('Goal') }}: <span id="scoreToWin" class="text-black">20</span></div>
                                    <div class="bg-white rounded-full px-4">{{ __('Score') }}: <span id="score" class="text-black">0</span></div>
                                    <div class="bg-white rounded-full px-4">{{ __('Time') }}: <span id="time" class="text-black">30</span>s</div>
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

    const GAME_DURATION = settingspzl.b
    
    const startButton = document.getElementById('startButton');
    const containerGame = document.getElementById('containerGame');
    const scoreEl = document.getElementById('score');
    const timeEl = document.getElementById('time');
    const goalEl = document.getElementById('scoreToWin');
    const messageEl = document.getElementById('message');
        messageEl.style.display = 'none';

    goalEl.textContent = settingspzl.c;
    timeEl.textContent = settingspzl.b;
    
    let gameOver = false;
    let timeLeft = GAME_DURATION;

    let elements = [],
            count = 4,
            score = 0;
    const elements_wrapper = document.querySelector(".element--wrapper")
    class Particle {
        constructor(x, y, size, hue) {
            this.x = x;
            this.y = y;
            this.size = size;
            this.hue = hue;
            this.el = '';
            this.dx = (.5 - Math.random()) * .15
            this.dy = (-1 - Math.random()) * .1
            this.alpha = 1
            this.saturation = rand(50, 100)
            this.lightness = rand(40, 60)
            this.draw()
        }
        draw() {
            this.el = `${(this.size / 2) + this.x * (this.size)}px ${(this.size / 2) + this.y * (this.size)}px 0px ${this.size / 2}px hsla(${this.hue} ${this.saturation}% ${this.lightness}% / ${this.alpha})`
        }
        animate() {
            this.y += this.dy
            this.x += this.dx
            this.size += .01
            this.alpha -= .03
            this.draw()
        }
    }
    class Effect {
        constructor(particle_size, particle_hue, parent) {
            this.particle_size = particle_size
            this.particle_hue = particle_hue
            this.parent = parent
            this.particles = []
            this.particlesStr = ''
            this.req = null
            this.draw()
            this.animate()
        }
        draw() {
            const rect = this.parent.getBoundingClientRect()
            for (let x = 0; x < rect.height / this.particle_size; x++) {
                for (let y = 0; y < rect.width / this.particle_size; y++) {
                    this.particles.push(
                        new Particle(x, y, this.particle_size, this.particle_hue)
                    )
                }
            }
            this.parent.style.setProperty("--particles", this.particles.map(p => p.el).join(","))
        }
        update() {
            if (this.particles.length <= 0) {
                cancelAnimationFrame(this.req)
                this.parent.remove()
            } else {
                this.parent.style.setProperty("--particles", this.particles.map(p => p.el).join(","))
                this.parent.textContent = ''
            }
        }
        animate() {
            this.req = requestAnimationFrame(() => this.animate())
            this.particles.forEach((p, i) => {
                if (p.alpha <= 0) this.particles.splice(i, 1)
                else p.animate()
            })
            this.update()
        }
    }
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
            })
            this.init()
            this.addEventListeners()
            this.animate()
        }
        init() {
            this.ELEMENT = document.createElement("div")
            this.ELEMENT.className = "element"
            this.ELEMENT.id = this.i
            //this.ELEMENT.textContent = getRandomIcon()
            this.ELEMENT.innerHTML = '<img src="' + getRandomImage() + '" style="width:' + this.width + 'px;" alt="element" />'
            this.ELEMENT.style = `
                position: absolute;
                top: ${this.y}px;
                left: ${this.x}px;
                width: ${this.width}px;
                height: ${this.height}px;
                font-size: ${this.width / 2}px;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                user-select: none;
                transform: translateY(${-this.height}px);
                animation: move ${this.duration}ms linear infinite forwards;
                animation-delay: ${((this.duration / this.total) * ((this.total - this.i) + 1)) * 1}ms;
                `
        }
        addEventListeners() {
            this.ELEMENT.onanimationiteration = () => {
                this.ELEMENT.style.left = rand(0, (containerGame.offsetWidth - this.width)) + "px"
                endGame(false)
            }
            let click = false;
            this.ELEMENT.onclick = () => {
                if (gameOver) return;
                if(!click) {
                    click = true;
                        if(this.remove()){
                    elements.forEach((p, i) => {
                        if (this.i === i) {
                            elements.splice(this.i, 1)
                            i--
                            elements = [...elements, new Element(
                                this.i,
                                rand(0, containerGame.offsetWidth - 100),
                                0,
                                80,
                                80,
                                "#E06015",
                                count,
                                3000
                            )]
                            elements_wrapper.append(elements[elements.length - 1].ELEMENT)
                        }
                    })
                }
                setTimeout(() => click = false, 700)
                }
            }
        }
        remove() {
            let scoreToWin = parseInt(settingspzl.c) || 200
            new Effect(35, 22, this.ELEMENT)
            score += settingspzl.a
            document.querySelector("#score").textContent = score < 100 ? `0${score}` : `0${score}`;
            if(score == scoreToWin) {
                endGame(true)
                return false;
            }
            return true;
        }
        animate() {}
    }
    
    function startGame(){
        const generate_elements = (count) => {
            const particles = []
            elements_wrapper.innerHTML = ''
            for (let i = 0; i < count; i++) {
                elements.push(new Element(
                    i,
                    rand(0, containerGame.offsetWidth - 100),
                    0,
                    80,
                    80,
                    "#E06015",
                    count,
                    3000
                ))
            }
            elements.forEach(p => elements_wrapper.append(p.ELEMENT))
        }
        
        startTimer();
        generate_elements(count)
    }
    function lostGameOver() {
        console.log("lost")
        window.location.reload();
    }
    function endGame(win) {

        gameOver = true;
        if (win) {
            messageEl.style.display = 'block';
            messageEl.textContent =  settingspzl.e+" 🎉";
            containerGame.remove();
            axios.post(settingspzl.f, {data: settingspzl.g,slug:settingspzl.j}, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })

            .then(function (response)
            {
                window.location = settingspzl.h;
            })
            .catch(function (error)
            {
                //console.log(error);
            });
        } else {
            containerGame.remove();
            document.getElementById('try-again').classList.remove('hidden');
        }

    }
    function restartGame() {
        elements = []
        count = 4
        generate_elements(count)
        score = 0
        document.querySelector("#score").textContent = score < 100 ? `00${score}` : `0${score}`;
        document.querySelector(".wrapper").classList.remove("game-over")
        document.querySelector(".lost").classList.remove("show")
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
    function getRandomImage() {
        const images = settingspzl.d;
        const randomIndex = Math.floor(Math.random() * images.length);
        return images[randomIndex];
    }
     startButton.addEventListener('click', () => {
        startButton.style.display = 'none';
        window.scrollTo({
            top: document.body.scrollHeight,
            behavior: 'smooth'
        });

        axios.post(settingspzl.i)
        .then(({ data }) => {
            console.log('Game started at', data.game_start);
        });

        startGame();
    });
	const rand = (min, max) => Math.floor(Math.random() * (max - min + 1) + min)
   </script>
</x-app-layout>
