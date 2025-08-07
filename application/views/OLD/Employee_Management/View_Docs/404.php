<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 - Page Lost in Space</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: radial-gradient(#0d0d0d, #000);
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
      height: 100vh;
      padding: 20px;
      position: relative;
    }

    .alien {
      position: relative;
      width: 150px;
      height: 200px;
      margin-bottom: 20px;
      animation: float 3s ease-in-out infinite;
      cursor: pointer;
    }

    .alien-head {
      width: 100px;
      height: 100px;
      background: #7efff5;
      border-radius: 50% 50% 45% 45%;
      position: absolute;
      top: 0;
      left: 25px;
    }

    .eye {
      width: 20px;
      height: 30px;
      background: black;
      border-radius: 50%;
      position: absolute;
      top: 30px;
      z-index: 2;
    }

    .eye.left { left: 20px; }
    .eye.right { right: 20px; }

    .pupil {
      width: 8px;
      height: 12px;
      background: white;
      border-radius: 50%;
      position: absolute;
      top: 5px;
      left: 6px;
    }

    .mouth {
      position: absolute;
      width: 40px;
      height: 15px;
      background: black;
      border-radius: 0 0 20px 20px;
      bottom: 20px;
      left: 30px;
    }

    .antenna {
      position: absolute;
      width: 4px;
      height: 40px;
      background: #7efff5;
      top: -40px;
      left: 50%;
      transform: translateX(-50%);
    }

    .antenna-ball {
      width: 12px;
      height: 12px;
      background: #ff00ff;
      border-radius: 50%;
      position: absolute;
      top: -10px;
      left: -4px;
      animation: bounce 1s infinite alternate;
    }

    .laser {
      position: absolute;
      width: 4px;
      height: 300px;
      background: red;
      top: 60px;
      z-index: 1;
      animation: shoot 0.5s ease-out forwards;
    }

    .laser.left { left: 40px; }
    .laser.right { right: 40px; }

    .ufo {
      position: absolute;
      width: 100px;
      height: 60px;
      background: #ccc;
      border-radius: 100px 100px 30px 30px;
      animation: fly 10s linear infinite;
      opacity: 0;
      animation: fly 10s linear infinite, appear 1s ease-out forwards;
    }

    .ufo::before {
      content: '';
      position: absolute;
      width: 30px;
      height: 30px;
      background: #fff;
      border-radius: 50%;
      top: 20px;
      left: 20px;
      box-shadow: 30px 0 #fff, 60px 0 #fff;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }

    @keyframes bounce {
      from { transform: translateY(0); }
      to { transform: translateY(-10px); }
    }

    @keyframes shoot {
      to { transform: translateY(-300px); opacity: 0; }
    }

    @keyframes fly {
      0% { left: -200px; top: 10%; }
      100% { left: 120%; top: 50%; }
    }

    @keyframes appear {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes meteor {
      to {
        transform: translate(500px, 500px);
        opacity: 0;
      }
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 10px;
      text-shadow: 2px 2px 10px #000;
    }

    p {
      font-size: 1.2rem;
      max-width: 500px;
      margin: 10px auto;
      color: #ccc;
    }

    a, button {
      color: #7efff5;
      background: #111;
      padding: 10px 20px;
      border-radius: 25px;
      margin: 5px;
      font-weight: bold;
      border: 2px solid #7efff5;
      cursor: pointer;
      transition: 0.3s ease;
      box-shadow: 0 0 10px #7efff5;
    }

    a:hover, button:hover {
      background: #7efff5;
      color: #000;
      box-shadow: 0 0 20px #7efff5;
    }

    .quote-box {
      font-style: italic;
      font-size: 1rem;
      margin: 15px;
      color: #ffdfba;
      height: 40px;
    }

    .meteor {
      width: 5px;
      height: 20px;
      background: yellow;
      position: absolute;
      top: 0;
      left: 0;
      transform: rotate(45deg);
      animation: meteor 4s linear infinite;
    }

  </style>
</head>
<body>

  <div class="alien" id="alien" title="Hover me for alien jokes!">
    <div class="antenna"><div class="antenna-ball"></div></div>
    <div class="alien-head">
      <div class="eye left"><div class="pupil" id="pupil-left"></div></div>
      <div class="eye right"><div class="pupil" id="pupil-right"></div></div>
      <div class="mouth"></div>
    </div>
  </div>

  <h1>404: Lost in Space!</h1>
  <p>This page is on a space vacation. Meanwhile, enjoy the madness 🤪</p>

  <div class="quote-box" id="quoteBox">"Alien says: Earth is overrated!"</div>

  <div>
    <a href="/">👽 Go Back to Earth</a>
    <button onclick="panic()">🚨 Panic Button</button>
    <button onclick="alienDance()">💃 Alien Dance</button>
    <button onclick="laserEyes()">🔫 Laser Eyes</button>
    <button onclick="summonUFO()">🛸 Summon UFOs</button>
  </div>

  <script>
    const quotes = [
      `"Alien says: Earth internet is too slow."`,
      `"404? Probably eaten by space worms."`,
      `"This page is hiding behind Pluto."`,
      `"Beam me up, this URL is toast!"`,
      `"You humans and your broken links..."`,
      `"Oops! Spilled some asteroid juice on it."`
    ];

    const jokes = [
      "I used to work at NASA… but they said I spaced out too much!",
      "Aliens love donuts. They’re the only food shaped like UFOs!",
      "404? Looks like someone forgot to carry the warp core!",
      "Have you tried turning the spaceship off and on again?",
      "This isn't the dark web. It's just deep space.",
    ];

    const quoteBox = document.getElementById("quoteBox");
    const alien = document.getElementById("alien");

    alien.addEventListener("mouseenter", () => {
      quoteBox.innerText = jokes[Math.floor(Math.random() * jokes.length)];
    });

    function panic() {
      const explosionSound = new Audio('https://www.soundjay.com/button/beep-07.wav');
      explosionSound.play();
      document.body.innerHTML = `<div style="color: red; font-size: 3rem;">💥 PANIC! PAGE EXPLODED 💥</div>`;
      setTimeout(() => location.reload(), 2500);
    }

    function alienDance() {
      const dances = ["spin 1s linear infinite", "shake 0.5s ease-in-out infinite", "bounce 1s ease-in-out infinite"];
      const randomDance = dances[Math.floor(Math.random() * dances.length)];
      alien.style.animation = randomDance;
      setTimeout(() => alien.style.animation = "float 3s ease-in-out infinite", 3000);
    }

    function laserEyes() {
      const left = document.createElement('div');
      const right = document.createElement('div');
      left.className = 'laser left';
      right.className = 'laser right';
      alien.appendChild(left);
      alien.appendChild(right);
      setTimeout(() => {
        left.remove();
        right.remove();
      }, 600);
    }

    function summonUFO() {
      const ufo = document.createElement('div');
      ufo.className = 'ufo';
      document.body.appendChild(ufo);
      setTimeout(() => ufo.remove(), 10000);
    }

    function spawnMeteors() {
      setInterval(() => {
        const meteor = document.createElement('div');
        meteor.className = 'meteor';
        meteor.style.top = Math.random() * window.innerHeight + "px";
        meteor.style.left = Math.random() * window.innerWidth + "px";
        document.body.appendChild(meteor);
        setTimeout(() => meteor.remove(), 4000);
      }, 1500);
    }

    spawnMeteors();

    document.addEventListener("mousemove", (e) => {
      const eyes = [document.getElementById("pupil-left"), document.getElementById("pupil-right")];
      eyes.forEach(eye => {
        const rect = eye.parentElement.getBoundingClientRect();
        const x = e.clientX - rect.left - 10;
        const y = e.clientY - rect.top - 15;
        eye.style.transform = `translate(${x/20}px, ${y/20}px)`;
      });
    });
  </script>
</body>
</html>
