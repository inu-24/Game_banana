function showGameOver(score, level, onConfirm) {

  // ── inject styles once 
  if (!document.getElementById('go-styles')) {
    const style = document.createElement('style');
    style.id = 'go-styles';
    style.textContent = `
      @import url('https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@700;900&display=swap');

      /* ---------- overlay ---------- */
      #go-overlay {
        position: fixed; inset: 0; z-index: 9999;
        display: flex; align-items: center; justify-content: center;
        background: rgba(0,0,0,0);
        backdrop-filter: blur(0px);
        transition: background .4s, backdrop-filter .4s;
      }
      #go-overlay.visible {
        background: rgba(0,0,0,.72);
        backdrop-filter: blur(6px);
      }

      /* ---------- panel ---------- */
      #go-panel {
        position: relative;
        width: min(420px, 92vw);
        background: linear-gradient(155deg, #3b1a00 0%, #1a0900 55%, #0a0300 100%);
        border-radius: 28px;
        border: 3px solid #f5a623;
        box-shadow:
          0 0 0 6px rgba(245,166,35,.18),
          0 0 70px rgba(245,100,0,.45),
          0 40px 90px rgba(0,0,0,.85);
        padding: 52px 36px 38px;
        text-align: center;
        transform: scale(.4) translateY(60px);
        opacity: 0;
        transition: transform .55s cubic-bezier(.34,1.56,.64,1), opacity .4s ease;
        overflow: hidden;
        font-family: 'Nunito', sans-serif;
      }
      #go-panel.visible {
        transform: scale(1) translateY(0);
        opacity: 1;
      }

      /* shiny top edge */
      #go-panel::before {
        content:'';
        position:absolute; top:0; left:10%; right:10%; height:3px;
        background: linear-gradient(90deg,transparent,#ffe066,#f5a623,#ffe066,transparent);
        border-radius:0 0 4px 4px;
        animation: shimmer 2.5s linear infinite;
      }
      @keyframes shimmer { 0%{opacity:.4} 50%{opacity:1} 100%{opacity:.4} }

      /* ---------- monkey bounce ---------- */
      #go-monkey {
        font-size: 4rem;
        display: block;
        margin: 0 auto 6px;
        animation: monkeyBounce 1s ease infinite alternate;
        filter: drop-shadow(0 4px 12px rgba(255,180,0,.5));
      }
      @keyframes monkeyBounce {
        from { transform: translateY(0) rotate(-6deg); }
        to   { transform: translateY(-10px) rotate(6deg); }
      }

      /* ---------- GAME OVER title ---------- */
      #go-title {
        font-family: 'Bangers', cursive;
        font-size: clamp(2.4rem, 8vw, 3.2rem);
        letter-spacing: 4px;
        background: linear-gradient(135deg, #ffe066, #f5a623, #ff6a00);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
        line-height: 1.1;
        margin-bottom: 6px;
      }

      /* ---------- level badge ---------- */
      #go-level {
        display: inline-block;
        background: rgba(245,166,35,.18);
        border: 1.5px solid rgba(245,166,35,.4);
        color: #f5c842;
        font-size: .75rem;
        font-weight: 900;
        letter-spacing: 3px;
        text-transform: uppercase;
        padding: 3px 14px;
        border-radius: 99px;
        margin-bottom: 28px;
      }

      /* ---------- score ring ---------- */
      #go-ring {
        position: relative;
        width: 140px; height: 140px;
        margin: 0 auto 24px;
      }
      #go-ring svg {
        transform: rotate(-90deg);
      }
      #go-ring-track { fill:none; stroke:rgba(255,255,255,.06); stroke-width:10; }
      #go-ring-fill  {
        fill:none; stroke:url(#ringGrad); stroke-width:10;
        stroke-linecap:round;
        stroke-dasharray: 345;
        stroke-dashoffset: 345;
        transition: stroke-dashoffset 1.4s cubic-bezier(.22,1,.36,1) .3s;
      }
      #go-ring-fill.filled { stroke-dashoffset: 0; }

      #go-score-num {
        position:absolute; inset:0;
        display:flex; flex-direction:column;
        align-items:center; justify-content:center;
        font-family:'Bangers',cursive;
        font-size: 3.4rem;
        color: #fff;
        letter-spacing: 2px;
        line-height:1;
        text-shadow: 0 0 20px rgba(255,180,0,.6);
      }
      #go-score-num span {
        font-size: .75rem;
        font-family:'Nunito',sans-serif;
        font-weight:900;
        color:#f5a623;
        letter-spacing:3px;
        text-transform:uppercase;
        margin-top:2px;
      }

      /* ---------- rating stars ---------- */
      #go-stars {
        font-size: 1.8rem;
        letter-spacing: 8px;
        margin-bottom: 10px;
      }
      .go-star { opacity:0; display:inline-block; transition: opacity .3s, transform .4s; transform:scale(0) rotate(-30deg); }
      .go-star.lit { opacity:1; transform:scale(1) rotate(0deg); filter:drop-shadow(0 0 6px gold); }

      /* ---------- message ---------- */
      #go-msg {
        color: #e0c080;
        font-size: .95rem;
        font-weight: 700;
        margin-bottom: 28px;
        min-height: 22px;
        opacity: 0;
        transform: translateY(6px);
        transition: opacity .4s .8s, transform .4s .8s;
      }
      #go-msg.visible { opacity:1; transform:translateY(0); }

      /* ---------- particles ---------- */
      .go-particle {
        position:absolute;
        pointer-events:none;
        font-size:1.4rem;
        animation: goFloat var(--dur) ease forwards;
        opacity:0;
      }
      @keyframes goFloat {
        0%   { transform: translate(0,0) scale(.6) rotate(0deg); opacity:1; }
        80%  { opacity:.7; }
        100% { transform: translate(var(--tx), var(--ty)) scale(1.1) rotate(var(--rot)); opacity:0; }
      }

      /* ---------- CTA button ---------- */
      #go-btn {
        display:inline-block;
        padding: 14px 44px;
        font-family:'Bangers',cursive;
        font-size:1.4rem;
        letter-spacing:3px;
        color:#1a0900;
        background: linear-gradient(135deg, #ffe066 0%, #f5a623 50%, #e8730a 100%);
        border:none; border-radius:50px;
        cursor:pointer;
        box-shadow: 0 6px 24px rgba(245,120,0,.5), 0 2px 0 rgba(255,255,255,.18) inset;
        transform: scale(.9);
        opacity:0;
        transition: transform .4s .9s cubic-bezier(.34,1.56,.64,1), opacity .3s .9s,
                    box-shadow .2s, filter .2s;
      }
      #go-btn.visible { transform:scale(1); opacity:1; }
      #go-btn:hover   { box-shadow:0 8px 32px rgba(245,120,0,.75); filter:brightness(1.1); transform:scale(1.05) !important; }
      #go-btn:active  { transform:scale(.97) !important; }

      /* ---------- confetti canvas ---------- */
      #go-confetti {
        position:fixed; inset:0; pointer-events:none; z-index:10000;
      }
    `;
    document.head.appendChild(style);
  }

  //  build DOM 
  const old = document.getElementById('go-overlay');
  if (old) old.remove();
  const confOld = document.getElementById('go-confetti');
  if (confOld) confOld.remove();

  // rating logic
  const ratings = [
    { min:0,  stars:1, msg:"Better luck next time! 🍌" },
    { min:3,  stars:2, msg:"Not bad, keep going! 🐒" },
    { min:6,  stars:3, msg:"Nice work, Puzzle Monkey! 🌟" },
    { min:10, stars:4, msg:"Impressive! You're on fire! 🔥" },
    { min:15, stars:5, msg:"LEGENDARY! Banana Master! 👑" },
  ];
  const rating = [...ratings].reverse().find(r => score >= r.min) || ratings[0];
  const starsHTML = Array.from({length:5}, (_,i) =>
    `<span class="go-star" data-i="${i}">${i < rating.stars ? '⭐' : '☆'}</span>`
  ).join('');

  const overlay = document.createElement('div');
  overlay.id = 'go-overlay';
  overlay.innerHTML = `
    <canvas id="go-confetti"></canvas>
    <div id="go-panel">
      <div id="go-monkey">🐒</div>
      <div id="go-title">GAME OVER</div>
      <div id="go-level">🍌 ${level} Mode</div>

      <div id="go-ring">
        <svg width="140" height="140" viewBox="0 0 120 120">
          <defs>
            <linearGradient id="ringGrad" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%"   stop-color="#ffe066"/>
              <stop offset="50%"  stop-color="#f5a623"/>
              <stop offset="100%" stop-color="#ff6a00"/>
            </linearGradient>
          </defs>
          <circle class="go-ring-track" cx="60" cy="60" r="55" id="go-ring-track"/>
          <circle id="go-ring-fill" cx="60" cy="60" r="55"/>
        </svg>
        <div id="go-score-num">
          <span>SCORE</span>
          <div id="go-score-display">0</div>
        </div>
      </div>

      <div id="go-stars">${starsHTML}</div>
      <div id="go-msg">${rating.msg}</div>
      <button id="go-btn">🏆 SEE LEADERBOARD</button>
    </div>
  `;
  document.body.appendChild(overlay);

  // animate in 
  requestAnimationFrame(() => {
    overlay.classList.add('visible');
    document.getElementById('go-panel').classList.add('visible');
  });

  // score count-up
  let displayed = 0;
  const target = score;
  const dur = Math.min(1200, target * 120);
  const step = Math.max(1, Math.ceil(target / 30));
  const interval = target > 0 ? dur / (target / step) : 0;
  const counter = setInterval(() => {
    displayed = Math.min(displayed + step, target);
    document.getElementById('go-score-display').textContent = displayed;
    if (displayed >= target) clearInterval(counter);
  }, interval || 50);

  // ring fill
  setTimeout(() => {
    document.getElementById('go-ring-fill').classList.add('filled');
  }, 100);

  // light up stars with stagger
  const starEls = overlay.querySelectorAll('.go-star');
  starEls.forEach((s, i) => {
    setTimeout(() => s.classList.add('lit'), 400 + i * 160);
  });

  // message + button
  setTimeout(() => document.getElementById('go-msg').classList.add('visible'), 700);
  setTimeout(() => document.getElementById('go-btn').classList.add('visible'), 900);

  // burst particles
  setTimeout(() => burstParticles(overlay), 300);

  // confetti for high scores
  if (score >= 6) startConfetti();

  // button action
  document.getElementById('go-btn').addEventListener('click', () => {
    stopConfetti();
    overlay.style.opacity = '0';
    overlay.style.transition = 'opacity .3s';
    setTimeout(() => { overlay.remove(); if (onConfirm) onConfirm(); }, 300);
  });
}

//  particles 
function burstParticles(panel) {
  const emojis = ['🍌','⭐','🎉','✨','🏆','🎊','💛'];
  const container = document.getElementById('go-panel');
  for (let i = 0; i < 18; i++) {
    const p = document.createElement('div');
    p.className = 'go-particle';
    p.textContent = emojis[Math.floor(Math.random() * emojis.length)];
    const angle = Math.random() * 360;
    const dist = 80 + Math.random() * 120;
    p.style.cssText = `
      left:50%; top:40%;
      --tx:${Math.cos(angle*Math.PI/180)*dist}px;
      --ty:${Math.sin(angle*Math.PI/180)*dist - 60}px;
      --rot:${-180+Math.random()*360}deg;
      --dur:${0.7+Math.random()*0.8}s;
      animation-delay:${Math.random()*0.3}s;
    `;
    container.appendChild(p);
    setTimeout(() => p.remove(), 1600);
  }
}

// confetti canvas 
let confettiRAF;
function startConfetti() {
  const canvas = document.getElementById('go-confetti');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;

  const pieces = Array.from({length:120}, () => ({
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height - canvas.height,
    w: 8 + Math.random() * 8,
    h: 4 + Math.random() * 5,
    color: ['#ffe066','#f5a623','#ff6a00','#fff','#85e89d','#79b8ff'][Math.floor(Math.random()*6)],
    vy: 2 + Math.random() * 3,
    vx: -1 + Math.random() * 2,
    rot: Math.random() * 360,
    rSpeed: -3 + Math.random() * 6,
  }));

  function draw() {
    ctx.clearRect(0,0,canvas.width,canvas.height);
    pieces.forEach(p => {
      ctx.save();
      ctx.translate(p.x + p.w/2, p.y + p.h/2);
      ctx.rotate(p.rot * Math.PI/180);
      ctx.fillStyle = p.color;
      ctx.fillRect(-p.w/2, -p.h/2, p.w, p.h);
      ctx.restore();
      p.x += p.vx; p.y += p.vy; p.rot += p.rSpeed;
      if (p.y > canvas.height) { p.y = -10; p.x = Math.random()*canvas.width; }
    });
    confettiRAF = requestAnimationFrame(draw);
  }
  draw();
  setTimeout(stopConfetti, 4000);
}

function stopConfetti() {
  cancelAnimationFrame(confettiRAF);
  const c = document.getElementById('go-confetti');
  if (c) c.remove();
}