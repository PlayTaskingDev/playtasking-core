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
    .scoreboard {
      color: white;
      font-size: 24px;
      z-index: 10;
    }

    .game-container {
      position: relative;
      width: 100%;
      height: 768px;
      overflow: hidden;
      border-radius: 25px;
    }

    .background-penal {
      position: absolute;
      width: 100%;
      height: 100%;
      object-fit: contain;
      z-index: 0;
    }

    .goalkeeper {
      position: absolute;
      width: 90px;
      left: 48%;
      transform: translateX(-45%);
      bottom: 175px;
      z-index: 2;
      transition: all 0.3s ease;
    }

    .goalkeeper.plongeon-gauche {
      width: 200px;
      bottom: 115px;
    }

    .goalkeeper.plongeon-droite {
      width: 265px;
      bottom: 115px;
    }

    .goalkeeper.plongeon-haut {
      width: 300px;  
      bottom: 175px;
    }

    .goalkeeper.plongeon-bas {
      width: 100px;
      bottom: 110px;
    }

    .goalkeeper.plongeon-milieu {
      width: 240px;
      bottom: 125px;
      left: 48%;
      transform: translateX(-50%);
    }

    .goalkeeper.plongeon-haut-gauche {
      width: 200px;
      bottom: 185px;
      left: 42%;
      transform: translateX(-50%) rotate(-15deg);
    }

    .goalkeeper.plongeon-milieu-gauche {
      width: 200px;
      bottom: 80px;
      left: 39%;
      transform: translateX(-50%) rotate(--8deg);
    }

    .goalkeeper.plongeon-bas-gauche {
      width: 200px;
      bottom: 50px;
      left: 39%;
      transform: translateX(-50%) rotate(-38deg);
    }

    .goalkeeper.plongeon-haut-droite {
      width: 270px;
      bottom: 240px;
      left: 54%;
      transform: translateX(-50%) rotate(-20deg);
    }

    .goalkeeper.plongeon-milieu-droite {
      width: 270px;
      bottom: 130px;
      left: 56%;
      transform: translateX(-50%) rotate(-28deg);
    }

    .goalkeeper.plongeon-bas-droite {
      width: 250px;
      bottom: 105px;
      left: 54%;
      transform: translateX(-50%) rotate(8deg);
    }

    .ball {
      position: absolute;
      width: 80px;
      left: 48%;
      transform: translateX(-50%);
      bottom: 10px;
      z-index: 5;
      transition: all 0.4s ease;
    }

    .goal-area {
      position: absolute;
      top: 58%;
      left: 50.40%;
      transform: translate(-50%, -50%);
      width: 320px;
      height: 172px;
      z-index: 4;
      cursor: crosshair;
      background-color: rgba(255, 255, 0, 0.0); 
    }

    .target-zone {
      position: absolute;
      width: 60px; 
      height: 60px;
      background-image: url('https://i.imgur.com/vjJwph2.png');
      background-size: contain;
      background-repeat: no-repeat;
      opacity: 0;
      animation: pulseTarget 2s ease-in-out infinite;
      z-index: 6;
      pointer-events: auto;
    }

    @keyframes pulseTarget {
      0%, 100% { opacity: 0; transform: scale(0.9); }
      50% { opacity: 0.4; transform: scale(1); }
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
                            <!--<div class="scoreboard" style="top: 40px;">
                              Marcación: <span id="score">0</span>
                            </div>-->
                            <div class="game-container">
                              <img src="/storage/dummy_assets/fondo-penal-game.webp" alt="Background" class="background-penal" />
                              <img src="https://i.imgur.com/aX1zBPZ.png" alt="Goalkeeper" class="goalkeeper" />
                              <img src="https://i.imgur.com/qarbcqB.png" alt="Ball" class="ball" />
                              <div class="goal-area"></div>

                              <!-- line 2 -->
                              <div class="target-zone clickable" data-zone="haut-gauche" style="top: 360px; left: 95px;"></div>
                              <div class="target-zone clickable" data-zone="haut-centre" style="top: 360px; left: 220px;"></div>
                              <div class="target-zone clickable" data-zone="haut-droite" style="top: 360px; left: 330px;"></div>
                              <!-- line 1 -->
                              <div class="target-zone clickable" data-zone="milieu-gauche" style="top: 425px; left: 95px;"></div>
                              <div class="target-zone clickable" data-zone="milieu-centre" style="top: 425px; left: 215px;"></div>
                              <div class="target-zone clickable" data-zone="milieu-droite" style="top: 425px; left: 330px;"></div>
                              <!-- Line 2 -->
                              <div class="target-zone clickable" data-zone="bas-gauche" style="top: 490px; left: 95px;"></div>
                              <!--<div class="target-zone clickable" data-zone="bas-centre" style="top: 490px; left: 215px;"></div>-->
                              <div class="target-zone clickable" data-zone="bas-droite" style="top: 490px; left: 330px;"></div>
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
      const ball = document.querySelector(".ball");
      const goalkeeper = document.querySelector(".goalkeeper");
      //const scoreDisplay = document.getElementById("score");
      let score = 0;
      let isAnimating = false; // Flag para evitar múltiples clicks

      const startLeft = ball.offsetLeft;
      const startBottom = parseInt(window.getComputedStyle(ball).bottom);

      // 📸 Goalkeeper images
      const goalkeeperImages = {
        centre: "https://i.imgur.com/aX1zBPZ.png",
        gauche: "https://i.imgur.com/TGLyMBD.png",
        droite: "https://i.imgur.com/sIBXdcx.png",
        haut: "https://i.imgur.com/zH8ceJX.png" // also used for middle-center
      };

      const hitSound = new Audio("/storage/dummy_assets/ball-kick.wav");
      const backgroundMusic = new Audio("/storage/dummy_assets/playing.wav");



      // Define zone classes
      const zoneClasses = {
        0: "plongeon-haut-gauche",
        1: "plongeon-haut",
        2: "plongeon-haut-droite",
        3: "plongeon-milieu-gauche",
        4: "plongeon-milieu",
        5: "plongeon-milieu-droite",
        6: "plongeon-bas-gauche",
        7: "plongeon-bas",
        8: "plongeon-bas-droite"
      };

      const messagesGoal = [
        "🎯 ¡Esa fue con chanflee!",
        "🎯 ¡Golazoooo!",
        "🎯 ¡Eres la reencarnación de Pele!",
        "🎯 ¡Gol Gol Gol!",
        "🎯 ¡En donde las arañas tejen su nido!",
      ];

      document.querySelectorAll(".target-zone").forEach((zone, index) => {
        zone.addEventListener("click", (e) => {
          // Evitar múltiples clicks mientras está en progreso
          if (isAnimating) return;
          e.stopPropagation();
          
          hitSound.play();
          const container = document.querySelector(".game-container");
          const rect = container.getBoundingClientRect();
          const clickX = e.clientX - rect.left;
          const clickY = e.clientY - rect.top;

          // Realistic ball animation with parabolic trajectory and rotation
          animateBallRealistic(clickX, clickY);
        });
      });

      // Realistic ball animation with parabolic trajectory
      function animateBallRealistic(targetX, targetY) {
        isAnimating = true; // Activar flag de animación
        const duration = 500; // Animation duration in ms
        const startTime = Date.now();
        const startX = parseInt(ball.style.left) || startLeft;
        const startY = parseInt(ball.style.bottom) || startBottom;
        
        // Calculate distance for rotation speed
        const distance = Math.sqrt(Math.pow(targetX - startX, 2) + Math.pow(targetY - startY, 2));
        const rotationAmount = (distance / 200) * 360 * 3; // Multiple rotations based on distance
        
        function animateStep() {
          const elapsed = Date.now() - startTime;
          const progress = Math.min(elapsed / duration, 1);
          
          // Cubic-bezier easing for realistic physics (ease-in-out)
          const easeProgress = progress < 0.5
            ? 2 * progress * progress
            : -1 + (4 - 2 * progress) * progress;
          
          // Parabolic arc: ball goes up then down
          const arcHeight = 150; // Maximum height of the arc
          const arcProgress = Math.sin(progress * Math.PI); // Creates smooth arc
          
          const currentX = startX + (targetX - startX) * easeProgress;
          const currentY = startY + (targetY - startY) * easeProgress + (arcHeight * arcProgress);
          const currentRotation = rotationAmount * progress;
          
          // Apply transformations
          ball.style.left = currentX + "px";
          ball.style.bottom = currentY + "px";
          ball.style.transform = `translateX(-50%) rotateZ(${currentRotation}deg)`;
          
          if (progress < 1) {
            requestAnimationFrame(animateStep);
          } else {
            // Final position
            ball.style.left = targetX + "px";
            ball.style.bottom = (768 - targetY - 40) + "px";
            ball.style.transform = `translateX(-50%) rotateZ(${rotationAmount}deg)`;
            
            // Continue with the rest of the logic
            continueGameLogic(targetX, targetY);
          }
        }
        
        requestAnimationFrame(animateStep);
      }

      // Continue the game logic after animation
      function continueGameLogic(clickX, clickY) {
        // Define las posiciones de cada zona en píxeles
        const zonePositions = {
          0: { top: 360, left: 95, name: "haut-gauche" },      // top-left
          1: { top: 360, left: 220, name: "haut-centre" },     // top-center
          2: { top: 360, left: 330, name: "haut-droite" },     // top-right
          3: { top: 425, left: 95, name: "milieu-gauche" },    // middle-left
          4: { top: 425, left: 215, name: "milieu-centre" },   // middle-center
          5: { top: 425, left: 330, name: "milieu-droite" },   // middle-right
          6: { top: 490, left: 95, name: "bas-gauche" },       // bottom-left
          8: { top: 490, left: 330, name: "bas-droite" }       // bottom-right (no 7)
        };
        
        // Encontrar la zona más cercana
        let zoneId = -1;
        let minDistance = Infinity;
        for (let id in zonePositions) {
          const zone = zonePositions[id];
          const distance = Math.abs(clickX - zone.left) + Math.abs(clickY - zone.top);
          if (distance < minDistance) {
            minDistance = distance;
            zoneId = parseInt(id);
          }
        }
        
        const willSave = Math.random() <= 0.6;
        let effectiveZone = willSave ? zoneId : getRandomOtherZone(zoneId);

        // Apply goalkeeper image
        let direction = "centre";
        goalkeeper.className = "goalkeeper";

        if ([0, 3, 6].includes(effectiveZone)) {
          direction = "gauche";
        } else if ([2, 5, 8].includes(effectiveZone)) {
          direction = "droite";
        } else if ([1, 4].includes(effectiveZone)) {
          direction = "haut";
        } else {
          direction = "centre";
        }

        goalkeeper.classList.add(zoneClasses[effectiveZone]);
        goalkeeper.src = goalkeeperImages[direction];

        // Display message
        if (willSave) {
          setTimeout(() => {
            showMessage("🧤 No Goal !");
          }, 100);
        } else {
          score++;
          setTimeout(() => {
            showMessage(messagesGoal[Math.floor(Math.random() * messagesGoal.length)]);
            //scoreDisplay.textContent = score;
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
          }, 100);
        }

        // Reset
        setTimeout(() => {
          ball.style.left = startLeft + "px";
          ball.style.bottom = startBottom + "px";
          ball.style.transform = "translateX(-50%)";
          goalkeeper.className = "goalkeeper";
          goalkeeper.src = goalkeeperImages.centre;
          isAnimating = false; // Desactivar flag de animación para permitir nuevo click
        }, 800);
      }

      function getRandomOtherZone(exclude) {
        let otherZones = [0,1,2,3,4,5,6,8].filter(z => z !== exclude);
        return otherZones[Math.floor(Math.random() * otherZones.length)];
      }

      function showMessage(text) {
        const existing = document.getElementById("game-msg");
        if (existing) existing.remove();

        const msg = document.createElement("div");
        msg.id = "game-msg";
        msg.textContent = text;
        msg.style.position = "absolute";
        msg.style.top = "50%";
        msg.style.left = "50%";
        msg.style.transform = "translate(-50%, -50%)";
        msg.style.color = "white";
        msg.style.fontSize = "36px";
        msg.style.zIndex = "99";
        document.body.appendChild(msg);

        setTimeout(() => {
          msg.remove();
        }, 1200);
      }
   </script>
</x-app-layout>
