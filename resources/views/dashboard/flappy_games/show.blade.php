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
        background-color: #150d24;
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
                        
                        <div id="game-container" class="pt-3  z-40">
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
                                <a href="{{route('smash_game.show', ['tenant' => tenant('id'), 'slug'=>$flappy_game->slug])}}">
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
    const settingspzl = JSON.parse(document.getElementById('settingspzl').content)
        const gameContainer = document.getElementById("game-container");
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
        const groundHeight = 30;
        let birdImageframe = 0;
        const flapInterval = 50;
        const birdGravity = 0.24;
        const birdJump = -4.8;
        const pipes = [];
        const pipeWidth = 52;
        const minGap = 110;
        const maxGap = 190;
        const pipeGap = Math.floor(Math.random() * (maxGap - minGap + 1) + minGap);
        let score = 0;
        let running = false;
        const scoreToWin = settingspzl.c;
        const pointsPerPipe = settingspzl.a;
        // Set canvas size
        canvas.width = gameContainer.clientWidth;
        canvas.height = 610;

        // const birdImg1 = new Image();
        // birdImg1.src = "https://storage.cloud.google.com/takis-bucket/media_elements/palomitas-cinepolis-png-7.webp?format=auto";
        // const birdImg2 = new Image();
        // birdImg2.src = "https://storage.cloud.google.com/takis-bucket/media_elements/palomitas-cinepolis-png-7.webp?format=auto";
        // const birdImg3 = new Image();
        // birdImg3.src = "https://storage.cloud.google.com/takis-bucket/media_elements/palomitas-cinepolis-png-7.webp?format=auto";
        // const birdImg4 = new Image();
        // birdImg4.src = "https://storage.cloud.google.com/takis-bucket/media_elements/palomitas-cinepolis-png-7.webp?format=auto";

        const birdImg1 = new Image();
        birdImg1.src = "https://assets.codepen.io/1290466/flappy-bird-1.png?format=auto";
        const birdImg2 = new Image();
        birdImg2.src = "https://assets.codepen.io/1290466/flappy-bird-2.png?format=auto";
        const birdImg3 = new Image();
        birdImg3.src = "https://assets.codepen.io/1290466/flappy-bird-3.png?format=auto";
        const birdImg4 = new Image();
        birdImg4.src = "https://assets.codepen.io/1290466/flappy-bird-2.png?format=auto";

        const backgroundImg = new Image();
        backgroundImg.src = "/storage/dummy_assets/flappy-bg-v1.jpg?format=auto";
        const groundImg = new Image();
        groundImg.src = "/storage/dummy_assets/flappy-ground2-v1.jpg?format=auto";
        const pipesBackgroundImg = new Image();
        pipesBackgroundImg.src = "https://assets.codepen.io/1290466/pipe-bg.jpg?format=auto";

        // Sounds
        const hitSound = new Audio("https://assets.codepen.io/1290466/flappy-bird-hit.mp3");
        const pointSound = new Audio("https://assets.codepen.io/1290466/flappy-bird-point.mp3");
        const backgroundMusic = new Audio("https://assets.codepen.io/1290466/flappy-bird-background.mp3"); //https://assets.codepen.io/1290466/flappy-bird-background.mp3

        const drawBackground = function () {
        ctx.fillStyle = "#150d24";
        ctx.fillRect(0, 0, canvas.width, canvas.height - groundHeight);
        ctx.drawImage(backgroundImg, 0, canvas.height - backgroundImg.height);
        };

        const scoreElement = document.createElement("span");
        scoreElement.id = "score";
        scoreElement.textContent = 0;
        scoreElement.style.position = "absolute";
        scoreElement.style.left = "50%";
        scoreElement.style.top = "35px";
        scoreElement.style.transform = "translate(-50%, -50%)";
        canvas.after(scoreElement);

        // Create the bird object
        const bird = {
        x: 50,
        y: canvas.height / 2,
        width: 42,
        height: 30,
        speed: 0,
        gravity: birdGravity,
        jump: birdJump,
        update: function () {
          this.speed += this.gravity;
          this.y += this.speed;
        },
        draw: function () {
          // Rotate the bird up when it goes up
          if (this.speed < 0) {
            ctx.save();
            ctx.translate(this.x + this.width / 2, this.y + this.height / 2);
            ctx.rotate(-Math.PI / 16);

            // bird flap animation
            if (birdImageframe % 3 === 0) {
              ctx.drawImage(birdImg1, -this.width / 2, -this.height / 2, this.width, this.height);
            } else if (birdImageframe % 3 === 1) {
              ctx.drawImage(birdImg2, -this.width / 2, -this.height / 2, this.width, this.height);
            } else if (birdImageframe % 3 === 2) {
              ctx.drawImage(birdImg3, -this.width / 2, -this.height / 2, this.width, this.height);
            } else {
              ctx.drawImage(birdImg4, -this.width / 2, -this.height / 2, this.width, this.height);
            }

            ctx.restore();
          }
          // Rotate the bird down when it goes down
          else {
              ctx.save();
              ctx.translate(this.x + this.width / 2, this.y + this.height / 2);
              ctx.rotate(Math.PI / 16);
              // ctx.drawImage(birdImg1, -this.width / 2, -this.height / 2, this.width, this.height);

              // bird flap animation
              if (birdImageframe % 3 === 0) {
                ctx.drawImage(birdImg1, -this.width / 2, -this.height / 2, this.width, this.height);
              } else if (birdImageframe % 3 === 1) {
                ctx.drawImage(birdImg2, -this.width / 2, -this.height / 2, this.width, this.height);
              } else if (birdImageframe % 3 === 2) {
                ctx.drawImage(birdImg3, -this.width / 2, -this.height / 2, this.width, this.height);
              } else {
                ctx.drawImage(birdImg4, -this.width / 2, -this.height / 2, this.width, this.height);
              }

              ctx.restore();
            }
        } };


        const ground = {
        x: 0,
        y: canvas.height - groundHeight,
        width: canvas.width,
        height: groundHeight,
        speed: 1,
        update: function () {
          this.x -= this.speed;
          if (this.x <= -this.width) this.x = 0;
        },
        draw: function () {
          ctx.drawImage(groundImg, this.x, this.y, this.width, this.height);
          ctx.drawImage(groundImg, this.x + this.width, this.y, this.width, this.height);
        } };


        const addPipe = function () {
        const height = Math.floor(Math.random() * canvas.height / 2) + 50;
        const y = height - pipeGap / 2;
        pipes.push({
          x: canvas.width,
          y: y,
          width: pipeWidth,
          height: height });

        };

        setInterval(function () {
        birdImageframe++;
        }, flapInterval);

        addPipe();

        // Listen for clicks to make the bird jump
        canvas.addEventListener("click", function () {
        bird.speed = bird.jump;
        });

        // Listen for sparebar press to make the bird jump
        document.addEventListener("keydown", function (event) {
        if (event.keyCode === 32) {
          bird.speed = bird.jump;
        }
        });

        const playBtn = document.createElement("button");
        playBtn.id = "playBtn";
        playBtn.innerText = "Play";
        playBtn.style.position = "absolute";
        playBtn.style.left = "50%";
        playBtn.style.top = "40%";
        playBtn.style.transform = "translate(-50%, -50%)";
        playBtn.addEventListener("click", function () {
           axios.post(settingspzl.i)
            .then(({ data }) => {
                console.log('Game started at', data.game_start);
            });
          playBtn.remove();
          helpText.remove();
          running = true;
          // Set game variables
          score = 0;
          pipes.length = 0;
          addPipe();
          gameLoop();

          backgroundMusic.loop = true;
          backgroundMusic.play();
        });

        canvas.after(playBtn);

        const helpText = document.createElement("p");
        helpText.id = "helpText";
        helpText.innerHTML = "TAP para Saltar en Mobile<br /> Espacio para saltar en PC";
        helpText.style.position = "absolute";
        helpText.style.left = "50%";
        helpText.style.top = "60%";
        helpText.style.transform = "translate(-50%, -50%)";
        canvas.after(helpText);

        // The game loop
        const gameLoop = function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ground.draw();
        drawBackground();

        if (!running) return;

        bird.update();
        bird.draw();

        // Draw and update pipes
        for (let i = 0; i < pipes.length; i++) {
          // ctx.fillStyle = ctx.createPattern(pipesBackgroundImg, "repeat");  
          ctx.fillRect(pipes[i].x, 0, pipes[i].width, pipes[i].y);
          ctx.fillRect(pipes[i].x, pipes[i].y + pipeGap, pipes[i].width, canvas.height - pipes[i].y - pipeGap);

          // Top pipe
          ctx.beginPath();
          ctx.strokeStyle = "#618842";
          ctx.lineWidth = 4;
          ctx.moveTo(pipes[i].x, pipes[i].y);
          ctx.lineTo(pipes[i].x + pipes[i].width, pipes[i].y);
          ctx.stroke();
          ctx.drawImage(pipesBackgroundImg, pipes[i].x, 0, pipes[i].width, pipes[i].y);

          // Bottom pipe
          ctx.beginPath();
          ctx.strokeStyle = "#618842";
          ctx.lineWidth = 4;
          ctx.moveTo(pipes[i].x, pipes[i].y + pipeGap);
          ctx.lineTo(pipes[i].x + pipes[i].width, pipes[i].y + pipeGap);
          ctx.stroke();
          ctx.drawImage(pipesBackgroundImg, pipes[i].x, pipes[i].y + pipeGap, pipes[i].width, canvas.height - pipes[i].y - pipeGap - groundHeight);

          pipes[i].x -= 1;

          // if game over / Check for collisions
          if (
          bird.x < pipes[i].x + pipes[i].width &&
          bird.x + bird.width > pipes[i].x && (
          bird.y < pipes[i].y || bird.y + bird.height > pipes[i].y + pipeGap))
          {
            running = false;

            hitSound.play();

            ground.draw();

            backgroundMusic.pause();
            backgroundMusic.currentTime = 0;

            console.log("Game Over!");

            const replayBtn = document.createElement("button");
            replayBtn.id = "replayBtn";
            replayBtn.innerText = "Replay";
            replayBtn.style.position = "absolute";
            replayBtn.style.left = "50%";
            replayBtn.style.top = "50%";
            replayBtn.style.transform = "translate(-50%, -50%)";
            replayBtn.addEventListener("click", function () {
              replayBtn.remove();
              running = true;
              // Reset game variables to their initial values
              score = 0;
              pipes.length = 0;
              addPipe();
              gameLoop();

              backgroundMusic.loop = true;
              backgroundMusic.play();
            });

            canvas.after(replayBtn);

            return;
          }

          // Check if bird has passed the pipe and add point to score
          if (bird.x > pipes[i].x + pipes[i].width && !pipes[i].passed) {
            pipes[i].passed = true;
            pointSound.play();
            score += pointsPerPipe;
            if(score == scoreToWin) {
              running = false;
              // add win sound
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
            }
          }

          // Add a new pipe when the current pipe has moved off the screen
          if (pipes[i].x + pipes[i].width < 0) {
            pipes.splice(i, 1);
            i--;
            addPipe();
          }
        }

        ground.update();
        ground.draw();
        console.log(score);
        scoreElement.textContent = 'Score: ' + score;


        // Keep the bird within the bounds of the canvas
        if (bird.y + bird.height > canvas.height - groundHeight) {
          bird.y = canvas.height - groundHeight - bird.height;
          bird.speed = 0;
        } else if (bird.y < 0) {
          bird.y = 0;
          bird.speed = 0;
        }

        requestAnimationFrame(gameLoop);
        };

        gameLoop();
   </script>
</x-app-layout>
