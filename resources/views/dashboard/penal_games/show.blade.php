<x-app-layout>
  <x-slot name="title">
      {{ $penal_game->title }}
  </x-slot>
  <x-slot name="description">
      {{ $penal_game->description }}
  </x-slot>
  <x-slot name="settingspzl">
      {{ $puzzle_settings }}
  </x-slot>

    @section('header_scripts')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Penal Game
        |--------------------------------------------------------------------------
        |
        | El juego conserva siempre la misma relación 480x768.
        | En móvil ocupa el ancho disponible.
        | En escritorio nunca crece más de 480px.
        |
        */

        .penal-game-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            user-select: none;
            -webkit-user-select: none;
        }

        .game-container {
            position: relative;

            width: 100%;
            max-width: 480px;

            aspect-ratio: 480 / 768;

            overflow: hidden;

            border-radius: 20px;

            touch-action: none;

            user-select: none;
            -webkit-user-select: none;

            background: #111;
        }

        /*
        |--------------------------------------------------------------------------
        | Background
        |--------------------------------------------------------------------------
        */

        .background-penal {
            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            object-fit: cover;

            pointer-events: none;
            user-select: none;
            -webkit-user-drag: none;

            z-index: 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Goal area
        |--------------------------------------------------------------------------
        |
        | Esta zona representa únicamente el interior de la portería.
        |
        | IMPORTANTE:
        | Si tu imagen cambia posteriormente, solamente tendríamos que
        | modificar estos porcentajes.
        |
        */

        .goal-area {
            position: absolute;
            left: 20%;
            top: 48%;
            width: 60%;
            height: 22%;
            z-index: 2;
            pointer-events: none;
            /* background: rgba(255, 0, 0, .20); */
        }

        /*
        |--------------------------------------------------------------------------
        | Goalkeeper
        |--------------------------------------------------------------------------
        */

        .goalkeeper {
            position: absolute;
            left: 50%;
            top: 66%;
            width: 18%;
            transform:
                translate(-50%, -50%)
                rotate(0deg);
            transform-origin: center center;
            z-index: 4;
            pointer-events: none;
            user-select: none;
            -webkit-user-drag: none;
            transition:
                left .22s ease,
                top .22s ease,
                width .22s ease,
                transform .22s ease;
        }

        /*
        |--------------------------------------------------------------------------
        | Goalkeeper directions
        |--------------------------------------------------------------------------
        */

        .goalkeeper.zone-left-top {
            left: 32%;
            top: 46%;
            width: 35%;
            transform:
                translate(-50%, -50%)
                rotate(-12deg);
        }

        .goalkeeper.zone-center-top {
            left: 50%;
            top: 45%;
            width: 42%;
            transform:
                translate(-50%, -50%);
        }

        .goalkeeper.zone-right-top {
            left: 68%;
            top: 46%;
            width: 42%;
            transform:
                translate(-50%, -50%)
                rotate(12deg);
        }

        .goalkeeper.zone-left-middle {
            left: 32%;
            top: 52%;
            width: 37%;
            transform:
                translate(-50%, -50%)
                rotate(-8deg);
        }

        .goalkeeper.zone-center-middle {
            left: 50%;
            top: 52%;
            width: 38%;
            transform:
                translate(-50%, -50%);
        }

        .goalkeeper.zone-right-middle {
            left: 68%;
            top: 52%;
            width: 42%;
            transform:
                translate(-50%, -50%)
                rotate(8deg);
        }

        .goalkeeper.zone-left-bottom {
            left: 32%;
            top: 58%;
            width: 35%;
            transform:
                translate(-50%, -50%)
                rotate(-18deg);
        }

        .goalkeeper.zone-center-bottom {
            left: 50%;
            top: 57%;
            width: 25%;
            transform:
                translate(-50%, -50%);
        }

        .goalkeeper.zone-right-bottom {
            left: 68%;
            top: 58%;
            width: 40%;
            transform:
                translate(-50%, -50%)
                rotate(18deg);
        }

        /*
        |--------------------------------------------------------------------------
        | Ball
        |--------------------------------------------------------------------------
        */

        .ball {
            position: absolute;

            left: 50%;
            top: 84%;

            width: 15%;

            max-width: 78px;

            transform:
                translate(-50%, -50%)
                rotate(0deg)
                scale(1);

            transform-origin: center center;

            z-index: 10;

            cursor: grab;

            touch-action: none;

            user-select: none;
            -webkit-user-select: none;
            -webkit-user-drag: none;
        }

        .ball.dragging {
            cursor: grabbing;
        }

        /*
        |--------------------------------------------------------------------------
        | Aim line
        |--------------------------------------------------------------------------
        */

        .aim-line {
            position: absolute;

            left: 0;
            top: 0;

            height: 5px;

            width: 100px;

            border-radius: 999px;

            background:
                linear-gradient(
                    90deg,
                    rgba(255,255,255,.15),
                    rgba(255,255,255,.95)
                );

            transform-origin: left center;

            z-index: 8;

            pointer-events: none;

            display: none;

            filter: drop-shadow(0 0 4px rgba(0,0,0,.55));
        }

        .aim-line::after {
            content: '';

            position: absolute;

            right: -3px;
            top: 50%;

            transform: translateY(-50%);

            width: 0;
            height: 0;

            border-top: 9px solid transparent;
            border-bottom: 9px solid transparent;
            border-left: 15px solid white;
        }

        /*
        |--------------------------------------------------------------------------
        | Power
        |--------------------------------------------------------------------------
        */

        .power-container {
            position: absolute;

            left: 5%;
            bottom: 4%;

            width: 90%;
            height: 12px;

            background: rgba(0, 0, 0, .45);

            border: 2px solid rgba(255,255,255,.75);
            border-radius: 999px;

            overflow: hidden;

            z-index: 20;

            opacity: 0;

            transition: opacity .15s ease;

            pointer-events: none;
        }

        .power-container.show {
            opacity: 1;
        }

        .power-bar {
            width: 0%;
            height: 100%;

            background:
                linear-gradient(
                    90deg,
                    #22c55e 0%,
                    #eab308 60%,
                    #ef4444 100%
                );

            transition: width .04s linear;
        }

        /*
        |--------------------------------------------------------------------------
        | Message
        |--------------------------------------------------------------------------
        */

        .penal-message {
            position: absolute;

            left: 50%;
            top: 28%;

            transform: translate(-50%, -50%);

            width: 90%;

            text-align: center;

            color: white;

            font-size: clamp(22px, 6vw, 36px);
            font-weight: 800;

            text-shadow:
                0 3px 6px rgba(0,0,0,.8);

            z-index: 30;

            pointer-events: none;

            opacity: 0;

            transition:
                opacity .15s ease,
                transform .15s ease;
        }

        .penal-message.show {
            opacity: 1;

            transform:
                translate(-50%, -50%)
                scale(1.05);
        }

        /*
        |--------------------------------------------------------------------------
        | Instructions
        |--------------------------------------------------------------------------
        */

        .penal-instructions {
            position: absolute;
            left: 50%;
            bottom: 5%;
            transform: translateX(-50%);
            width: 85%;
            text-align: center;
            color: white;
            font-weight: 700;
            font-size: clamp(10px, 3.3vw, 15px);
            text-shadow:
                0 2px 5px rgba(0,0,0,.9);
            z-index: 7;
            pointer-events: none;
            transition: opacity .2s ease;
        }
    </style>
    @endsection

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800">
                <div class="game-card rounded-lg dark:bg-gray-800 dark:border-gray-700 p-3">
                    <div id="game-holder">

                        <x-campaign-menu :campaign-games="$campaign_games" :campaign-tickets="$campaign_tickets" :campaign-coupons="$campaign_coupons" :campaign-url="route('campaign.show', ['tenant' => tenant('id'), 'slug' => $penal_game->campaign->slug])" :active="'games'" />
                        
                        @if (!is_null($penal_game->game_banner_video))
                        <div class="aspect-w-16 aspect-h-9 mb-6">
                            <iframe src="{{$penal_game->game_banner_video}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @endif

                        @if(is_null($penal_game->game_banner_video) && !is_null($penal_game->game_banner))
                            @if ($penal_game->game_banner_url)
                                <a href="{{ $penal_game->game_banner_url }}" target="_blank" rel="noopener noreferrer">
                                    <img src="{{$penal_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                                </a>
                            @else
                                <img src="{{$penal_game->game_banner}}" alt="" class="w-full rounded mb-5 {{get_app_setting('cards_shadow') ? 'cards-shadow' : ''}}">
                            @endif
                        @endif
                        
                        <div id="game-container" class="pt-3  z-40">
                            <h2
                                class="font-semibold text-2xl leading-tight pb-5 uppercase game-heading">
                                {{ $penal_game->title }}
                            </h2>
                            
                            <p class="font-bold mb-5 text-center">
                                {{ $penal_game->description }}
                            </p>
                            {{-- Game --}}
                              <div class="penal-game-wrapper">

                                  <div
                                      id="penalGame"
                                      class="game-container"
                                  >

                                      {{-- Background --}}
                                      <img
                                          src="{{ $penal_game->game_bg_image_desktop }}"
                                          alt="{{ $penal_game->title }}"
                                          class="background-penal"
                                          draggable="false"
                                      >

                                      {{-- Portería lógica --}}
                                      <div
                                          id="goalArea"
                                          class="goal-area"
                                      ></div>

                                      {{-- Portero --}}
                                      <img
                                          id="goalkeeper"
                                          src="/storage/dummy_assets/gk1.png"
                                          alt="Goalkeeper"
                                          class="goalkeeper"
                                          draggable="false"
                                      >

                                      {{-- Flecha --}}
                                      <div
                                          id="aimLine"
                                          class="aim-line"
                                      ></div>

                                      {{-- Balón --}}
                                      <img
                                          id="ball"
                                          src="/storage/dummy_assets/ball.png"
                                          alt="Ball"
                                          class="ball"
                                          draggable="false"
                                      >

                                      {{-- Mensajes --}}
                                      <div
                                          id="penalMessage"
                                          class="penal-message"
                                      ></div>

                                      {{-- Instrucciones --}}
                                      <div
                                          id="penalInstructions"
                                          class="penal-instructions"
                                      >
                                          Jala el balón hacia atrás y suéltalo para disparar
                                      </div>

                                      {{-- Potencia --}}
                                      <div
                                          id="powerContainer"
                                          class="power-container"
                                      >
                                          <div
                                              id="powerBar"
                                              class="power-bar"
                                          ></div>
                                      </div>

                                  </div>

                              </div>
                              <div
                                  id="message"
                                  class="text-white text-center text-2xl text-shadow font-bold py-12"
                              ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <script>
      const settingspzl =
          JSON.parse(
              document.getElementById('settingspzl').content
          );

      /*
      |--------------------------------------------------------------------------
      | Elements
      |--------------------------------------------------------------------------
      */

      const gameContainer =
          document.getElementById('penalGame');

      const ball =
          document.getElementById('ball');

      const goalkeeper =
          document.getElementById('goalkeeper');

      const goalArea =
          document.getElementById('goalArea');

      const aimLine =
          document.getElementById('aimLine');

      const powerContainer =
          document.getElementById('powerContainer');

      const powerBar =
          document.getElementById('powerBar');

      const penalMessage =
          document.getElementById('penalMessage');

      const instructions =
          document.getElementById('penalInstructions');

      /*
      |--------------------------------------------------------------------------
      | Audio
      |--------------------------------------------------------------------------
      */

      const hitSound =
          new Audio(
              "/storage/dummy_assets/ball-kick.wav"
          );

      const backgroundMusic =
          new Audio(
              "/storage/dummy_assets/playing.wav"
          );

      hitSound.preload = 'auto';
      backgroundMusic.preload = 'auto';

      /*
      |--------------------------------------------------------------------------
      | Goalkeeper images
      |--------------------------------------------------------------------------
      */

      const goalkeeperImages = {

          center:"/storage/dummy_assets/gk1.png",

          left:"/storage/dummy_assets/gk2.png",

          right:"/storage/dummy_assets/gk3.png",

          up:"/storage/dummy_assets/gk4.png"
      };

      /*
      |--------------------------------------------------------------------------
      | Messages
      |--------------------------------------------------------------------------
      */

      const messagesGoal = [
          "🎯 ¡Esa fue con chanfle!",
          "⚽ ¡Golazoooo!",
          "🔥 ¡Eres la reencarnación de Pelé!",
          "⚽ ¡Gol Gol Gol!",
          "🕷️ ¡Donde las arañas tejen su nido!"
      ];

      /*
      |--------------------------------------------------------------------------
      | Configuration
      |--------------------------------------------------------------------------
      */

      const MAX_DRAG_DISTANCE = 120;

      /*
      * El usuario debe jalar una cantidad mínima
      * para poder disparar.
      */
      const MIN_POWER = 0.12;

      /*
      * Probabilidad de que el portero adivine
      * correctamente.
      */
      const SAVE_PROBABILITY = 0.30;

      /*
      |--------------------------------------------------------------------------
      | State
      |--------------------------------------------------------------------------
      */

      let dragging = false;

      let shooting = false;

      let pointerId = null;

      let origin = {
          x: 0,
          y: 0
      };

      let dragPosition = {
          x: 0,
          y: 0
      };

      let aimVector = {
          x: 0,
          y: -1
      };

      let power = 0;

      let rotation = 0;

      /*
      |--------------------------------------------------------------------------
      | Helpers
      |--------------------------------------------------------------------------
      */

      function clamp(value, min, max) {
          return Math.min(
              Math.max(value, min),
              max
          );
      }

      function random(min, max) {
          return (
              Math.random() * (max - min)
              + min
          );
      }

      /*
      |--------------------------------------------------------------------------
      | Ball origin
      |--------------------------------------------------------------------------
      |
      | Obtenemos la posición REAL del balón dentro del contenedor.
      |
      */

      function getBallOrigin() {

          const containerRect =
              gameContainer.getBoundingClientRect();

          const ballRect =
              ball.getBoundingClientRect();

          return {

              x:
                  ballRect.left
                  - containerRect.left
                  + ballRect.width / 2,

              y:
                  ballRect.top
                  - containerRect.top
                  + ballRect.height / 2
          };
      }

      /*
      |--------------------------------------------------------------------------
      | Pointer position
      |--------------------------------------------------------------------------
      */

      function getPointerPosition(event) {

          const rect =
              gameContainer.getBoundingClientRect();

          return {

              x:
                  event.clientX
                  - rect.left,

              y:
                  event.clientY
                  - rect.top
          };
      }

      /*
      |--------------------------------------------------------------------------
      | Start drag
      |--------------------------------------------------------------------------
      */

      function startDrag(event) {

          if (shooting) {
              return;
          }

          event.preventDefault();

          dragging = true;

          pointerId =
              event.pointerId;

          /*
          * Capturamos el pointer.
          *
          * Esto es especialmente importante en móvil porque
          * aunque el dedo salga visualmente del balón,
          * continuamos recibiendo los eventos.
          */
          ball.setPointerCapture(pointerId);

          origin =
              getBallOrigin();

          dragPosition = {
              ...origin
          };

          ball.classList.add(
              'dragging'
          );

          aimLine.style.display =
              'block';

          powerContainer.classList.add(
              'show'
          );

          instructions.style.opacity =
              '0';

          updateAim(event);
      }

      /*
      |--------------------------------------------------------------------------
      | Drag
      |--------------------------------------------------------------------------
      */

      function updateAim(event) {

          if (!dragging) {
              return;
          }

          if (
              pointerId !== null
              && event.pointerId !== pointerId
          ) {
              return;
          }

          event.preventDefault();

          const pointer =
              getPointerPosition(event);

          /*
          * Vector desde el origen hacia donde
          * estamos jalando.
          */
          let dragX =
              pointer.x - origin.x;

          let dragY =
              pointer.y - origin.y;

          let distance =
              Math.sqrt(
                  dragX * dragX
                  +
                  dragY * dragY
              );

          /*
          * Limitamos cuánto podemos jalar.
          */
          if (
              distance >
              MAX_DRAG_DISTANCE
          ) {

              const scale =
                  MAX_DRAG_DISTANCE
                  / distance;

              dragX *= scale;
              dragY *= scale;

              distance =
                  MAX_DRAG_DISTANCE;
          }

          /*
          * Impedimos jalar demasiado hacia arriba.
          *
          * La mecánica natural es jalar
          * hacia abajo/lados para disparar arriba.
          */
          dragY =
              Math.max(
                  dragY,
                  -25
              );

          dragPosition = {

              x:
                  origin.x
                  + dragX,

              y:
                  origin.y
                  + dragY
          };

          /*
          * POTENCIA
          */

          power =
              clamp(
                  distance
                  / MAX_DRAG_DISTANCE,

                  0,
                  1
              );

          /*
          * Dirección del disparo.
          *
          * Es exactamente contraria
          * al movimiento de jalado.
          */
          let shotX =
              -dragX;

          let shotY =
              -dragY;

          /*
          * Siempre debe dirigirse hacia arriba.
          */
          shotY =
              Math.min(
                  shotY,
                  -20
              );

          const shotLength =
              Math.sqrt(
                  shotX * shotX
                  +
                  shotY * shotY
              ) || 1;

          aimVector = {

              x:
                  shotX
                  / shotLength,

              y:
                  shotY
                  / shotLength
          };

          moveBallWhileDragging();

          drawAim();

          updatePowerBar();
      }

      /*
      |--------------------------------------------------------------------------
      | Move ball during drag
      |--------------------------------------------------------------------------
      */

      function moveBallWhileDragging() {

          const containerRect =
              gameContainer
                  .getBoundingClientRect();

          const left =
              (
                  dragPosition.x
                  / containerRect.width
              ) * 100;

          const top =
              (
                  dragPosition.y
                  / containerRect.height
              ) * 100;

          ball.style.left =
              `${left}%`;

          ball.style.top =
              `${top}%`;

          ball.style.transform =
              `
                  translate(-50%, -50%)
                  scale(${1 + power * .08})
              `;
      }

      /*
      |--------------------------------------------------------------------------
      | Aim arrow
      |--------------------------------------------------------------------------
      */

      function drawAim() {

          const length =
              70
              +
              (power * 120);

          const angle =
              Math.atan2(
                  aimVector.y,
                  aimVector.x
              );

          const angleDeg =
              angle
              * 180
              / Math.PI;

          aimLine.style.left =
              `${origin.x}px`;

          aimLine.style.top =
              `${origin.y}px`;

          aimLine.style.width =
              `${length}px`;

          aimLine.style.transform =
              `
                  rotate(${angleDeg}deg)
              `;

          aimLine.style.opacity =
              `${0.35 + power * 0.65}`;
      }

      /*
      |--------------------------------------------------------------------------
      | Power UI
      |--------------------------------------------------------------------------
      */

      function updatePowerBar() {

          powerBar.style.width =
              `${power * 100}%`;
      }

      /*
      |--------------------------------------------------------------------------
      | Release
      |--------------------------------------------------------------------------
      */

      function releaseBall(event) {

          if (!dragging) {
              return;
          }

          if (
              pointerId !== null
              &&
              event.pointerId !== pointerId
          ) {
              return;
          }

          dragging = false;

          ball.classList.remove(
              'dragging'
          );

          try {

              ball.releasePointerCapture(
                  pointerId
              );

          } catch (error) {}

          pointerId = null;

          aimLine.style.display =
              'none';

          powerContainer.classList.remove(
              'show'
          );

          /*
          * Jaló demasiado poco.
          */
          if (
              power <
              MIN_POWER
          ) {

              resetBall();

              instructions.style.opacity =
                  '1';

              return;
          }

          shootBall();
      }

      /*
      |--------------------------------------------------------------------------
      | Shoot
      |--------------------------------------------------------------------------
      */

      function shootBall() {

          if (shooting) {
              return;
          }

          shooting = true;

          try {

              hitSound.currentTime = 0;

              hitSound.play().catch(() => {});

          } catch (error) {}

          /*
          * Calculamos dónde intersectaría
          * la trayectoria con la altura
          * aproximada de la portería.
          */

          const goalRect =
              goalArea
                  .getBoundingClientRect();

          const containerRect =
              gameContainer
                  .getBoundingClientRect();

          /*
          * Centro vertical de la portería.
          */
          const goalY =
              goalRect.top
              - containerRect.top
              +
              goalRect.height * .55;

          const deltaY =
              goalY
              - origin.y;

          /*
          * Evitamos una trayectoria horizontal.
          */
          const directionY =
              Math.min(
                  aimVector.y,
                  -0.15
              );

          const distanceToGoal =
              deltaY
              / directionY;

          let targetX =
              origin.x
              +
              aimVector.x
              * distanceToGoal;

          let targetY =
              goalY;

          /*
          * La potencia modifica ligeramente
          * la altura final.
          *
          * Mucha potencia = balón más alto.
          */
          targetY -=
              (power - .5)
              *
              goalRect.height
              *
              .45;

          /*
          * Si el tiro tiene muy poca potencia,
          * no llega completamente.
          */
          if (power < .35) {

              targetY +=
                  goalRect.height
                  * .70;
          }

          animateShot(
              targetX,
              targetY,
              power
          );
      }

      /*
      |--------------------------------------------------------------------------
      | Ball animation
      |--------------------------------------------------------------------------
      */

      function animateShot(
          targetX,
          targetY,
          shotPower
      ) {

          const rect =
              gameContainer
                  .getBoundingClientRect();

          const startX =
              dragPosition.x;

          const startY =
              dragPosition.y;

          /*
          * Más potencia = disparo ligeramente más rápido.
          */
          const duration =
              720
              -
              (shotPower * 250);

          const startTime =
              performance.now();

          const arcHeight =
              rect.height
              *
              (
                  .06
                  +
                  shotPower * .07
              );

          function frame(now) {

              const elapsed =
                  now - startTime;

              const progress =
                  clamp(
                      elapsed / duration,
                      0,
                      1
                  );

              /*
              * Easing.
              */
              const ease =
                  1
                  -
                  Math.pow(
                      1 - progress,
                      3
                  );

              /*
              * Movimiento base.
              */
              const x =
                  startX
                  +
                  (
                      targetX
                      - startX
                  )
                  * ease;

              const linearY =
                  startY
                  +
                  (
                      targetY
                      - startY
                  )
                  * ease;

              /*
              * Pequeño arco.
              */
              const arc =
                  Math.sin(
                      progress
                      * Math.PI
                  )
                  * arcHeight;

              const y =
                  linearY
                  - arc;

              rotation +=
                  28
                  +
                  shotPower * 30;

              /*
              * Conforme se acerca a portería
              * se hace pequeño para dar sensación
              * de profundidad.
              */
              const scale =
                  1
                  -
                  progress
                  * .52;

              ball.style.left =
                  `${x}px`;

              ball.style.top =
                  `${y}px`;

              ball.style.transform =
                  `
                      translate(-50%, -50%)
                      rotate(${rotation}deg)
                      scale(${scale})
                  `;

              if (
                  progress < 1
              ) {

                  requestAnimationFrame(
                      frame
                  );

                  return;
              }

              resolveShot(
                  targetX,
                  targetY
              );
          }

          requestAnimationFrame(
              frame
          );
      }

      /*
      |--------------------------------------------------------------------------
      | Resolve shot
      |--------------------------------------------------------------------------
      */

      function resolveShot(
          targetX,
          targetY
      ) {

          const goalRect =
              goalArea
                  .getBoundingClientRect();

          const containerRect =
              gameContainer
                  .getBoundingClientRect();

          const goalLeft =
              goalRect.left
              - containerRect.left;

          const goalTop =
              goalRect.top
              - containerRect.top;

          const goalRight =
              goalLeft
              +
              goalRect.width;

          const goalBottom =
              goalTop
              +
              goalRect.height;

          /*
          * Primero validamos si realmente
          * entró dentro del marco.
          */
          const insideGoal =

              targetX >= goalLeft
              &&
              targetX <= goalRight
              &&
              targetY >= goalTop
              &&
              targetY <= goalBottom;

          if (!insideGoal) {

              goalkeeperStay();

              showMessage(
                  "😬 ¡Fuera!"
              );

              scheduleReset();

              return;
          }

          /*
          * Calculamos la zona relativa
          * dentro de la portería.
          */
          const relativeX =
              (
                  targetX
                  - goalLeft
              )
              /
              goalRect.width;

          const relativeY =
              (
                  targetY
                  - goalTop
              )
              /
              goalRect.height;

          const zone =
              getGoalZone(
                  relativeX,
                  relativeY
              );

          /*
          * El portero decide si adivina.
          */
          const willSave =
              Math.random()
              <= SAVE_PROBABILITY;

          let goalkeeperZone;

          if (willSave) {

              animateGoalkeeperToBall(
                  zone,
                  targetX,
                  targetY
              );

          } else {
              goalkeeperZone =
                  getDifferentZone(
                      zone
                  );

              animateGoalkeeper(
                  goalkeeperZone
              );
          }

          if (willSave) {

            setTimeout(() => {

                ball.style.transition =
                    'transform .15s ease';

                ball.style.transform +=
                    ' scale(.75)';

                showMessage(
                    "🧤 ¡Atajadón!"
                );

            }, 120);

            scheduleReset();

            return;
        }

          /*
          * GOAL
          */

          const message =
              messagesGoal[
                  Math.floor(
                      Math.random()
                      *
                      messagesGoal.length
                  )
              ];

          setTimeout(() => {

              showMessage(
                  message
              );

          }, 120);

          completeGame();
      }
      function animateGoalkeeperToBall(
            zone,
            targetX,
            targetY
        ) {

            resetGoalkeeperClass();

            const containerRect =
                gameContainer.getBoundingClientRect();

            /*
            * Convertimos la posición final
            * del balón a porcentaje.
            */
            let leftPercent =
                (
                    targetX
                    / containerRect.width
                )
                * 100;

            let topPercent =
                (
                    targetY
                    / containerRect.height
                )
                * 100;

            /*
            * Evitamos que el portero pueda
            * salir demasiado de la portería.
            */
            leftPercent =
                clamp(
                    leftPercent,
                    27,
                    73
                );

            topPercent =
                clamp(
                    topPercent,
                    43,
                    61
                );

            /*
            * Seleccionamos imagen según
            * dirección del tiro.
            */
            let directionImage =
                'center';

            if (
                zone.startsWith('left')
            ) {

                directionImage =
                    'left';

            } else if (
                zone.startsWith('right')
            ) {

                directionImage =
                    'right';

            } else if (
                zone.endsWith('top')
            ) {

                directionImage =
                    'up';
            }

            goalkeeper.src =
                goalkeeperImages[
                    directionImage
                ];

            /*
            * Tamaño dependiendo del tipo
            * de clavado.
            */
            if (
                zone.startsWith('left')
                ||
                zone.startsWith('right')
            ) {

                goalkeeper.style.width =
                    '40%';

            } else if (
                zone.endsWith('top')
            ) {

                goalkeeper.style.width =
                    '42%';

            } else {

                goalkeeper.style.width =
                    '30%';
            }

            /*
            * AQUÍ ESTÁ LA PARTE IMPORTANTE:
            *
            * el portero se mueve hacia
            * la posición REAL del balón.
            */
            goalkeeper.style.left =
                `${leftPercent}%`;

            goalkeeper.style.top =
                `${topPercent}%`;

            /*
            * Orientación visual.
            */
            let rotation =
                0;

            if (
                zone.startsWith('left')
            ) {

                rotation =
                    -12;

            }

            if (
                zone.startsWith('right')
            ) {

                rotation =
                    12;

            }

            goalkeeper.style.transform =
                `
                    translate(-50%, -50%)
                    rotate(${rotation}deg)
                `;
        }
      /*
      |--------------------------------------------------------------------------
      | Goal zones
      |--------------------------------------------------------------------------
      */

      function getGoalZone(
          x,
          y
      ) {

          let column;

          if (x < .33) {

              column =
                  'left';

          } else if (x < .66) {

              column =
                  'center';

          } else {

              column =
                  'right';
          }

          let row;

          if (y < .33) {

              row =
                  'top';

          } else if (y < .66) {

              row =
                  'middle';

          } else {

              row =
                  'bottom';
          }

          return `${column}-${row}`;
      }

      /*
      |--------------------------------------------------------------------------
      | Goalkeeper
      |--------------------------------------------------------------------------
      */

      function animateGoalkeeper(zone) {

          resetGoalkeeperClass();

          let directionImage =
              'center';

          if (
              zone.startsWith(
                  'left'
              )
          ) {

              directionImage =
                  'left';

          } else if (
              zone.startsWith(
                  'right'
              )
          ) {

              directionImage =
                  'right';

          } else if (
              zone.endsWith(
                  'top'
              )
          ) {

              directionImage =
                  'up';
          }

          goalkeeper.src =
              goalkeeperImages[
                  directionImage
              ];

          goalkeeper.classList.add(
              `zone-${zone}`
          );
      }

      function goalkeeperStay() {

          resetGoalkeeperClass();

          goalkeeper.src =
              goalkeeperImages.center;
      }

      function resetGoalkeeperClass() {

          goalkeeper.className =
              'goalkeeper';

          goalkeeper.style.left =
              '';

          goalkeeper.style.top =
              '';

          goalkeeper.style.width =
              '';

          goalkeeper.style.transform =
              '';
      }

      /*
      |--------------------------------------------------------------------------
      | Other goalkeeper zone
      |--------------------------------------------------------------------------
      */

      function getDifferentZone(
          current
      ) {

          const zones = [

              'left-top',
              'center-top',
              'right-top',

              'left-middle',
              'center-middle',
              'right-middle',

              'left-bottom',
              'center-bottom',
              'right-bottom'
          ];

          const alternatives =
              zones.filter(
                  zone =>
                      zone !== current
              );

          return alternatives[
              Math.floor(
                  Math.random()
                  *
                  alternatives.length
              )
          ];
      }

      /*
      |--------------------------------------------------------------------------
      | Reset
      |--------------------------------------------------------------------------
      */

      function scheduleReset() {

          setTimeout(() => {

              resetBall();

              resetGoalkeeperClass();

              goalkeeper.src =
                  goalkeeperImages.center;

              shooting = false;

              instructions.style.opacity =
                  '1';

          }, 1100);
      }

      function resetBall() {

          rotation = 0;

          power = 0;

          powerBar.style.width =
              '0%';

          ball.style.transition =
              'all .25s ease';

          ball.style.left =
              '50%';

          ball.style.top =
              '84%';

          ball.style.transform =
              `
                  translate(-50%, -50%)
                  rotate(0deg)
                  scale(1)
              `;

          setTimeout(() => {

              ball.style.transition =
                  'none';

          }, 260);
      }

      /*
      |--------------------------------------------------------------------------
      | Messages
      |--------------------------------------------------------------------------
      */

      function showMessage(text) {

          penalMessage.textContent =
              text;

          penalMessage.classList.add(
              'show'
          );

          setTimeout(() => {

              penalMessage
                  .classList
                  .remove('show');

          }, 1000);
      }

      /*
      |--------------------------------------------------------------------------
      | Complete game
      |--------------------------------------------------------------------------
      */

      function completeGame() {

          shooting = true;

          axios.post(

              settingspzl.f,

              {
                  data:
                      settingspzl.g,

                  slug:
                      settingspzl.j
              },

              {
                  headers: {

                      'Content-Type':
                          'multipart/form-data'
                  }
              }

          )
          .then(function(response) {

              setTimeout(() => {

                  window.location =
                      settingspzl.h;

              }, 1000);

          })
          .catch(function(error) {

              console.error(
                  'Error completing Penal Game:',
                  error
              );

              /*
              * Si falla el backend,
              * permitimos seguir jugando.
              */
              scheduleReset();
          });
      }

      /*
      |--------------------------------------------------------------------------
      | Events
      |--------------------------------------------------------------------------
      */

      ball.addEventListener(
          'pointerdown',
          startDrag,
          {
              passive: false
          }
      );

      ball.addEventListener(
          'pointermove',
          updateAim,
          {
              passive: false
          }
      );

      ball.addEventListener(
          'pointerup',
          releaseBall,
          {
              passive: false
          }
      );

      ball.addEventListener(
          'pointercancel',
          releaseBall,
          {
              passive: false
          }
      );

      /*
      * Evitamos drag nativo del navegador.
      */
      ball.addEventListener(
          'dragstart',
          event =>
              event.preventDefault()
      );

      /*
      * También prevenimos menú contextual
      * al mantener presionado.
      */
      gameContainer.addEventListener(
          'contextmenu',
          event =>
              event.preventDefault()
      );
  </script>
</x-app-layout>
