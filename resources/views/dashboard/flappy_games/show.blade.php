<x-app-layout>
    <x-slot name="title">
        {{ $flappy_game->title }}
    </x-slot>
    <x-slot name="description">
        {{ $flappy_game->description }}
    </x-slot>
    <x-slot name="settingspzl">
        {{ $puzzle_settings }}
    </x-slot>

    @section('header_scripts')
    <style>
      #game-container {
        position: relative;
        width: 100%;
      }
       #playBtn, #replayBtn {
        background: orange;
        color: #effbe3;
        font-weight: bold;
        font-size: 20px;
        padding: 5px 30px;
        cursor: pointer;
        border-radius: 8px;
        border: solid #fff 2px;
        text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;
      }
      #playBtn:hover, #replayBtn:hover {
        background: #ff8b3d;
      }

      #score {
        color: #effbe3;
        font-weight: bold;
        font-size: 20px;
        padding: 5px 10px;
        cursor: pointer;
        text-shadow: -2px 0 black, 0 2px black, 2px 0 black, 0 -2px black;
        font-family: "Press Start 2P", cursive, sans-serif;
      }

      #helpText {
        text-align: center;
        padding: 10px;
        font-family: "Press Start 2P", cursive, sans-serif;
        font-size: 18px;
        color: black;
        text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
      }

      #canvas {
        background-color: #ff4d93;
      }
    </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class="game-card rounded-lg dark:bg-gray-800 dark:border-gray-700 p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $flappy_game->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($flappy_game->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$flappy_game->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @endif

                        @if(is_null($flappy_game->game_banner_video) && !is_null($flappy_game->game_banner))
                            @if ($flappy_game->game_banner_url)
                                <a href="{{ $flappy_game->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{$flappy_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                </a>
                            @else
                                <img src="{{$flappy_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            @endif
                        @endif
                        
                        <div id="game-wrapper" class="pt-3  z-40">
                            <h2
                                class="font-semibold text-2xl leading-tight pb-5 uppercase game-heading">
                                {{ $flappy_game->title }}
                            </h2>
                            
                            <p class="font-bold mb-5 text-center">
                                {{ $flappy_game->description }}
                            </p>

                            {{-- Game --}}
                            <div id="try-again" class="hidden text-center rounded mx-4 mb-5">
                                <h2 class="text-3xl mb-3 font-bold">
                                    {{__('Sorry, you lost!')}}
                                </h2>
                                <a href="{{
                                  route('flappy_game.show', [
                                      'tenant' => tenant('id'),
                                      'slug' => $flappy_game->slug
                                  ])}}">
                                    <h3 class="text-2xl mb-3">{{__('Try again.')}}</h3>
                                </a>
                                <img src="{{$flappy_game->failed_image}}" alt="{{__('Sorry, you lost!')}}" class="w-full">
                            </div>
                            <div id="game-container">
                              <canvas id="canvas"></canvas>
                            </div>
                            {{-- <div id="containerGame" class="w-full rounded-lg">
                                <div id="hud" class="text-center font-bold mb-5 text-normal flex justify-between px-7 mt-6">
                                    <div class="bg-white rounded-full px-4 text-black">{{ __('Goal') }}: <span id="scoreToWin" class="text-black">20</span></div>
                                    <div class="bg-white rounded-full px-4 text-black">{{ __('Score') }}: <span id="score" class="text-black">0</span></div>
                                    <div class="bg-white rounded-full px-4 text-black">{{ __('Time') }}: <span id="time" class="text-black">30</span>s</div>
                                </div>
                                <div class="w-full flex justify-center items-center">
                                    <span id="startButton"
                                        class="text-shadow block mt-24 p-8 font-bold text-center w-[260px] rounded-lg text-3xl leading-8" 
                                        style="{{ $flappy_game->btn_text_color ? 'color: ' . $flappy_game->btn_text_color . ';' : '' }}background: {{ $flappy_game->btn_background_color_1 . '; background: linear-gradient(135deg, ' . $flappy_game->btn_background_color_1 . ' 0%, ' . $flappy_game->btn_background_color_2 . ' 85%);' }}">
                                        {{ __('Click here to start the game!') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16  w-full mt-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                        </svg>
                                    </span>
                                    
                                </div>
                                <div class="element--wrapper w-full h-full" ></div>
                            </div> --}}
                            <div id="message" class="text-white text-center text-2xl text-shadow font-bold py-12"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   <script>
    const settingspzl = JSON.parse(
        document.getElementById('settingspzl').content
    );

    const gameContainer = document.getElementById("game-container");
    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");

    /*
    |--------------------------------------------------------------------------
    | GAME CONFIG
    |--------------------------------------------------------------------------
    */

    const groundHeight = 75;

    const birdGravity = 0.24;
    const birdJump = -4.8;

    const pipeWidth = 46;

    // Espacio entre tubería superior e inferior.
    const pipeGap = 155;

    const pipeSpeed = 2;

    let birdImageframe = 0;
    const flapInterval = 80;

    let score = 0;
    let running = false;

    const pipes = [];

    const scoreToWin = Number(settingspzl.c) || 0;
    const pointsPerPipe = Number(settingspzl.a) || 1;


    /*
    |--------------------------------------------------------------------------
    | CANVAS
    |--------------------------------------------------------------------------
    */

    canvas.width = gameContainer.clientWidth;
    canvas.height = 610;


    /*
    |--------------------------------------------------------------------------
    | GAME ASSETS
    |--------------------------------------------------------------------------
    */

    const birdImg1 = new Image();
    const birdImg2 = new Image();
    const birdImg3 = new Image();

    const backgroundImg = new Image();
    const groundImg = new Image();
    const pipesBackgroundImg = new Image();

    birdImg1.src = @json($flappy_game->flappy_image_animated_1);
    birdImg2.src = @json($flappy_game->flappy_image_animated_2);
    birdImg3.src = @json($flappy_game->flappy_image_animated_3);

    backgroundImg.src = @json($flappy_game->game_bg_image);
    pipesBackgroundImg.src = @json($flappy_game->game_pipe_image);
    groundImg.src = @json($flappy_game->game_ground_image);


    /*
    |--------------------------------------------------------------------------
    | SOUNDS
    |--------------------------------------------------------------------------
    */

    const hitSound = new Audio(
        "https://assets.codepen.io/1290466/flappy-bird-hit.mp3"
    );

    const pointSound = new Audio(
        "https://assets.codepen.io/1290466/flappy-bird-point.mp3"
    );

    const backgroundMusic = new Audio(
        "https://assets.codepen.io/1290466/flappy-bird-background.mp3"
    );

    let isMuted = false;

    const muteBtn = document.createElement("button");

    muteBtn.id = "muteBtn";
    muteBtn.innerText = "🔊";

    muteBtn.style.position = "absolute";
    muteBtn.style.left = "15px";
    muteBtn.style.top = "15px";
    muteBtn.style.zIndex = "60";

    muteBtn.style.width = "42px";
    muteBtn.style.height = "42px";
    muteBtn.style.borderRadius = "50%";
    muteBtn.style.background = "rgba(0, 0, 0, 0.45)";
    muteBtn.style.color = "#fff";
    muteBtn.style.border = "2px solid #fff";
    muteBtn.style.fontSize = "20px";
    muteBtn.style.cursor = "pointer";
    muteBtn.style.display = "flex";
    muteBtn.style.alignItems = "center";
    muteBtn.style.justifyContent = "center";
    gameContainer.appendChild(muteBtn);
    /*
    |--------------------------------------------------------------------------
    | BACKGROUND
    |--------------------------------------------------------------------------
    */
    const setMuted = function (muted) {
        isMuted = muted;

        hitSound.muted = muted;
        pointSound.muted = muted;
        backgroundMusic.muted = muted;

        muteBtn.innerText = muted ? "🔇" : "🔊";
    };
    muteBtn.addEventListener("click", function (event) {
        event.stopPropagation();

        setMuted(!isMuted);
    });
    const drawBackground = function () {
        if (!backgroundImg.complete) {
            return;
        }

        ctx.drawImage(
            backgroundImg,
            0,
            0,
            canvas.width,
            canvas.height - groundHeight
        );
    };


    /*
    |--------------------------------------------------------------------------
    | SCORE
    |--------------------------------------------------------------------------
    */

    const scoreElement = document.createElement("span");

    scoreElement.id = "score";
    scoreElement.textContent = "Score: 0";

    scoreElement.style.position = "absolute";
    scoreElement.style.left = "50%";
    scoreElement.style.top = "35px";
    scoreElement.style.transform = "translate(-50%, -50%)";
    scoreElement.style.zIndex = "20";

    canvas.after(scoreElement);


    /*
    |--------------------------------------------------------------------------
    | BIRD
    |--------------------------------------------------------------------------
    */

    const bird = {

        x: 60,

        y: canvas.height / 2,

        /*
         * Mantiene aproximadamente la proporción
         * de las imágenes 173x123.
         */
        width: 58,
        height: 41,

        speed: 0,

        gravity: birdGravity,

        jump: birdJump,

        update: function () {

            this.speed += this.gravity;

            this.y += this.speed;
        },

        draw: function () {

            ctx.save();

            ctx.translate(
                this.x + this.width / 2,
                this.y + this.height / 2
            );

            /*
             * Rotación del personaje.
             */
            if (this.speed < 0) {

                ctx.rotate(-Math.PI / 10);

            } else {

                const fallRotation = Math.min(
                    this.speed * 0.035,
                    Math.PI / 5
                );

                ctx.rotate(fallRotation);
            }


            /*
             * Animación de los 3 frames.
             */
            let currentBirdImage;

            if (birdImageframe % 3 === 0) {

                currentBirdImage = birdImg1;

            } else if (birdImageframe % 3 === 1) {

                currentBirdImage = birdImg2;

            } else {

                currentBirdImage = birdImg3;
            }


            if (currentBirdImage.complete) {

                ctx.drawImage(
                    currentBirdImage,
                    -this.width / 2,
                    -this.height / 2,
                    this.width,
                    this.height
                );
            }

            ctx.restore();
        }
    };


    /*
    |--------------------------------------------------------------------------
    | GROUND
    |--------------------------------------------------------------------------
    */

    const ground = {

        x: 0,

        y: canvas.height - groundHeight,

        width: canvas.width,

        height: groundHeight,

        speed: pipeSpeed,

        update: function () {

            this.x -= this.speed;

            if (this.x <= -this.width) {

                this.x = 0;
            }
        },

        draw: function () {

            if (!groundImg.complete) {
                return;
            }

            ctx.drawImage(
                groundImg,
                this.x,
                this.y,
                this.width,
                this.height
            );

            ctx.drawImage(
                groundImg,
                this.x + this.width,
                this.y,
                this.width,
                this.height
            );
        }
    };


    /*
    |--------------------------------------------------------------------------
    | CREATE PIPE
    |--------------------------------------------------------------------------
    */

    const addPipe = function () {

        /*
         * Evita generar huecos demasiado arriba
         * o demasiado abajo.
         */
        const minTopHeight = 90;

        const maxTopHeight =
            canvas.height
            - groundHeight
            - pipeGap
            - 90;

        const topHeight =
            Math.floor(
                Math.random()
                * (maxTopHeight - minTopHeight + 1)
            )
            + minTopHeight;


        pipes.push({
            x: canvas.width,
            y: topHeight,
            width: pipeWidth,
            passed: false
        });
    };


    /*
    |--------------------------------------------------------------------------
    | DRAW TOP PIPE
    |--------------------------------------------------------------------------
    |
    | IMPORTANTE:
    |
    | No hacemos:
    |
    |     drawImage(... width, pipe.y)
    |
    | porque eso aplastaría la imagen.
    |
    | Conservamos la proporción original.
    | La imagen simplemente sale fuera del canvas.
    |
    */

    const drawTopPipe = function (pipe) {

        if (!pipesBackgroundImg.complete) {
            return;
        }

        const sourceWidth =
            pipesBackgroundImg.naturalWidth || 50;

        const sourceHeight =
            pipesBackgroundImg.naturalHeight || 450;


        /*
         * Alto proporcional de la imagen.
         *
         * Ejemplo:
         *
         * original: 50 x 450
         * dibujo:   46 x 414
         */
        const renderedHeight =
            sourceHeight
            * (pipe.width / sourceWidth);


        ctx.save();

        /*
         * Nos colocamos exactamente donde empieza
         * el hueco entre pipes.
         */
        ctx.translate(
            pipe.x,
            pipe.y
        );

        /*
         * Volteamos verticalmente.
         *
         * El aro de la tubería queda hacia abajo.
         */
        ctx.scale(1, -1);


        ctx.drawImage(
            pipesBackgroundImg,

            0,
            0,

            sourceWidth,
            sourceHeight,

            0,
            0,

            pipe.width,
            renderedHeight
        );

        ctx.restore();
    };


    /*
    |--------------------------------------------------------------------------
    | DRAW BOTTOM PIPE
    |--------------------------------------------------------------------------
    */

    const drawBottomPipe = function (pipe) {

        if (!pipesBackgroundImg.complete) {
            return;
        }

        const sourceWidth =
            pipesBackgroundImg.naturalWidth || 50;

        const sourceHeight =
            pipesBackgroundImg.naturalHeight || 450;


        const renderedHeight =
            sourceHeight
            * (pipe.width / sourceWidth);


        const bottomPipeY =
            pipe.y + pipeGap;


        /*
         * Tampoco deformamos la imagen.
         *
         * Si la tubería es más larga que el espacio
         * disponible, queda detrás del suelo.
         */
        ctx.drawImage(
            pipesBackgroundImg,

            0,
            0,

            sourceWidth,
            sourceHeight,

            pipe.x,
            bottomPipeY,

            pipe.width,
            renderedHeight
        );
    };


    /*
    |--------------------------------------------------------------------------
    | BIRD / PIPE COLLISION
    |--------------------------------------------------------------------------
    |
    | El PNG del pájaro tiene transparencia alrededor.
    |
    | Por eso NO usamos todo:
    |
    |     bird.x
    |     bird.y
    |     bird.width
    |     bird.height
    |
    | Reducimos ligeramente la hitbox.
    |
    */

    const isBirdCollidingWithPipe = function (pipe) {

        /*
         * HITBOX DEL PÁJARO
         */
        const birdLeft =
            bird.x + 9;

        const birdRight =
            bird.x + bird.width - 10;

        const birdTop =
            bird.y + 7;

        const birdBottom =
            bird.y + bird.height - 7;


        /*
         * HITBOX DEL PIPE
         *
         * También quitamos algunos pixels
         * para hacer la colisión visualmente justa.
         */
        const pipeLeft =
            pipe.x + 3;

        const pipeRight =
            pipe.x + pipe.width - 3;


        /*
         * Límites del espacio libre.
         */
        const gapTop =
            pipe.y;

        const gapBottom =
            pipe.y + pipeGap;


        /*
         * Primero debemos estar horizontalmente
         * dentro del pipe.
         */
        const overlapsX =
            birdRight > pipeLeft
            &&
            birdLeft < pipeRight;


        if (!overlapsX) {

            return false;
        }


        /*
         * Pegó arriba.
         */
        const hitsTopPipe =
            birdTop < gapTop;


        /*
         * Pegó abajo.
         */
        const hitsBottomPipe =
            birdBottom > gapBottom;


        return (
            hitsTopPipe
            ||
            hitsBottomPipe
        );
    };


    /*
    |--------------------------------------------------------------------------
    | RESET GAME
    |--------------------------------------------------------------------------
    */

    const restartGame = function () {

        score = 0;

        scoreElement.textContent =
            "Score: 0";


        /*
         * Limpiar tubos anteriores.
         */
        pipes.length = 0;


        /*
         * Reset bird.
         */
        bird.x = 60;

        bird.y =
            canvas.height / 2;

        bird.speed = 0;


        /*
         * Primera tubería.
         */
        addPipe();


        running = true;


        /*
         * Reiniciar música.
         */
        backgroundMusic.currentTime = 0;

        backgroundMusic.loop = true;

        /*
         * Déjalo comentado si no quieres música.
         */
        backgroundMusic.play();


        gameLoop();
    };


    /*
    |--------------------------------------------------------------------------
    | GAME OVER
    |--------------------------------------------------------------------------
    */

    const gameOver = function () {

        /*
         * Evita múltiples ejecuciones.
         */
        if (!running) {

            return;
        }


        running = false;


        /*
         * Sonido de choque.
         */
        hitSound.currentTime = 0;

        hitSound
            .play()
            .catch(() => {});


        /*
         * Detener música.
         */
        backgroundMusic.pause();

        backgroundMusic.currentTime = 0;


        /*
         * Evitar crear dos botones Replay.
         */
        if (
            document.getElementById("replayBtn")
        ) {

            return;
        }


        const replayBtn =
            document.createElement("button");


        replayBtn.id =
            "replayBtn";


        replayBtn.innerText =
            "Replay";


        replayBtn.style.position =
            "absolute";

        replayBtn.style.left =
            "50%";

        replayBtn.style.top =
            "50%";

        replayBtn.style.transform =
            "translate(-50%, -50%)";

        replayBtn.style.zIndex =
            "50";


        replayBtn.addEventListener(
            "click",
            function () {

                replayBtn.remove();

                restartGame();
            }
        );


        canvas.after(replayBtn);
    };


    /*
    |--------------------------------------------------------------------------
    | BIRD ANIMATION
    |--------------------------------------------------------------------------
    */

    setInterval(function () {

        if (running) {

            birdImageframe++;
        }

    }, flapInterval);


    /*
    |--------------------------------------------------------------------------
    | JUMP - MOBILE / MOUSE
    |--------------------------------------------------------------------------
    */

    canvas.addEventListener(
        "click",
        function () {

            if (!running) {

                return;
            }

            bird.speed =
                bird.jump;
        }
    );


    /*
    |--------------------------------------------------------------------------
    | JUMP - KEYBOARD
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.code === "Space"
                &&
                running
            ) {

                event.preventDefault();

                bird.speed =
                    bird.jump;
            }
        }
    );


    /*
    |--------------------------------------------------------------------------
    | PLAY BUTTON
    |--------------------------------------------------------------------------
    */

    const playBtn =
        document.createElement("button");


    playBtn.id =
        "playBtn";


    playBtn.innerText =
        "Play";


    playBtn.style.position =
        "absolute";

    playBtn.style.left =
        "50%";

    playBtn.style.top =
        "40%";

    playBtn.style.transform =
        "translate(-50%, -50%)";

    playBtn.style.zIndex =
        "50";


    canvas.after(playBtn);


    /*
    |--------------------------------------------------------------------------
    | HELP TEXT
    |--------------------------------------------------------------------------
    */

    const helpText =
        document.createElement("p");


    helpText.id =
        "helpText";


    helpText.innerHTML =
        "TAP para saltar en Mobile<br>Espacio para saltar en PC";


    helpText.style.position =
        "absolute";

    helpText.style.left =
        "50%";

    helpText.style.top =
        "60%";

    helpText.style.transform =
        "translate(-50%, -50%)";

    helpText.style.zIndex =
        "50";


    canvas.after(helpText);


    /*
    |--------------------------------------------------------------------------
    | START GAME
    |--------------------------------------------------------------------------
    */

    playBtn.addEventListener(
        "click",
        function () {

            /*
             * Registrar inicio de partida.
             */
            axios
                .post(settingspzl.i)
                .then(({ data }) => {

                    console.log(
                        "Game started at",
                        data.game_start
                    );
                })
                .catch(error => {

                    console.error(
                        "Error starting game",
                        error
                    );
                });


            playBtn.remove();

            helpText.remove();


            restartGame();
        }
    );


    /*
    |--------------------------------------------------------------------------
    | PLAYER WINS
    |--------------------------------------------------------------------------
    */

    const gameWin = function () {

        if (!running) {

            return;
        }


        running = false;


        backgroundMusic.pause();

        backgroundMusic.currentTime = 0;


        axios.post(
            settingspzl.f,
            {
                data: settingspzl.g,
                slug: settingspzl.j
            },
            {
                headers: {
                    "Content-Type":
                        "multipart/form-data"
                }
            }
        )
        .then(function () {

            window.location =
                settingspzl.h;
        })
        .catch(function (error) {

            console.error(
                "Error saving Flappy Game result",
                error
            );
        });
    };


    /*
    |--------------------------------------------------------------------------
    | GAME LOOP
    |--------------------------------------------------------------------------
    */

    const gameLoop = function () {

        /*
         * Limpiar frame.
         */
        ctx.clearRect(
            0,
            0,
            canvas.width,
            canvas.height
        );


        /*
         * 1. BACKGROUND
         */
        drawBackground();


        /*
         * Si todavía no se está jugando,
         * solamente dibujamos el fondo y suelo.
         */
        if (!running) {

            ground.draw();

            return;
        }


        /*
         * 2. BIRD UPDATE
         */
        bird.update();


        /*
         * 3. DRAW PIPES
         */
        for (
            let i = 0;
            i < pipes.length;
            i++
        ) {

            const pipe =
                pipes[i];


            /*
             * Dibujar sin deformar.
             */
            drawTopPipe(pipe);

            drawBottomPipe(pipe);


            /*
             * Movimiento.
             */
            pipe.x -=
                pipeSpeed;


            /*
             * COLISIÓN CON PIPE
             */
            if (
                isBirdCollidingWithPipe(pipe)
            ) {

                /*
                 * Dibujamos el bird antes de congelar
                 * para verlo exactamente donde chocó.
                 */
                bird.draw();

                ground.draw();

                gameOver();

                return;
            }


            /*
             * PUNTUACIÓN
             */
            if (
                bird.x >
                    pipe.x
                    + pipe.width
                &&
                !pipe.passed
            ) {

                pipe.passed =
                    true;


                pointSound.currentTime =
                    0;


                pointSound
                    .play()
                    .catch(() => {});


                score +=
                    pointsPerPipe;


                scoreElement.textContent =
                    "Score: "
                    + score;


                /*
                 * WIN
                 */
                if (
                    score >=
                    scoreToWin
                ) {

                    bird.draw();

                    ground.draw();

                    gameWin();

                    return;
                }
            }


            /*
             * PIPE YA SALIÓ
             * DEL CANVAS.
             */
            if (
                pipe.x
                + pipe.width
                < 0
            ) {

                pipes.splice(
                    i,
                    1
                );

                i--;


                /*
                 * Crear siguiente obstáculo.
                 */
                addPipe();
            }
        }


        /*
         * 4. DRAW BIRD
         */
        bird.draw();


        /*
         * 5. COLISIÓN CON EL SUELO
         */
        if (
            bird.y
            + bird.height
            >=
            canvas.height
            - groundHeight
        ) {

            /*
             * Colocamos visualmente el bird
             * sobre el suelo.
             */
            bird.y =
                canvas.height
                - groundHeight
                - bird.height;


            bird.draw();

            ground.draw();


            gameOver();

            return;
        }


        /*
         * No permitimos salir por arriba.
         */
        if (
            bird.y < 0
        ) {

            bird.y = 0;

            bird.speed = 0;
        }


        /*
         * 6. GROUND
         */
        ground.update();

        ground.draw();


        /*
         * SCORE
         */
        scoreElement.textContent =
            "Score: "
            + score;


        /*
         * Siguiente frame.
         */
        requestAnimationFrame(
            gameLoop
        );
    };


    /*
    |--------------------------------------------------------------------------
    | INITIAL DRAW
    |--------------------------------------------------------------------------
    */

    /*
     * Cuando terminen de cargar las imágenes,
     * redibujamos la escena inicial.
     */
    backgroundImg.addEventListener(
        "load",
        function () {

            drawBackground();

            ground.draw();
        }
    );


    groundImg.addEventListener(
        "load",
        function () {

            drawBackground();

            ground.draw();
        }
    );


    /*
     * Primer render.
     */
    drawBackground();

    ground.draw();

</script>
</x-app-layout>
