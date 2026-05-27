@extends('layouts.quiz')
@section('title', 'Skin Quiz — Kominhoo Beauty')

@section('head')
<style>
.quiz-slide { display: none; animation: slide-in .35s ease; }
.quiz-slide.active { display: block; }
@keyframes slide-in { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }
.overall-progress {
  position: fixed; top: 0; left: 0; right: 0; z-index: 200;
  height: 3px; background: var(--border);
}
.overall-fill {
  height: 100%; background: var(--lime);
  transition: width .5s ease;
}
.quiz-options.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.quiz-wrapper { background: var(--bg-primary); min-height: 100vh; }

/* Stage transition overlay */
#stage-transition {
  position: fixed; inset: 0; z-index: 500;
  background: var(--bg-primary);
  display: flex; align-items: center; justify-content: center;
  padding: 24px;
}
#stage-transition.fade-in { animation: st-fade-in .4s ease forwards; }
#stage-transition.fade-out { animation: st-fade-out .3s ease forwards; }
@keyframes st-fade-in { from { opacity:0; transform:scale(.97); } to { opacity:1; transform:scale(1); } }
@keyframes st-fade-out { from { opacity:1; transform:scale(1); } to { opacity:0; transform:scale(1.02); } }
.st-inner {
  text-align: center; max-width: 460px; width: 100%;
  display: flex; flex-direction: column; align-items: center; gap: 20px;
}
.st-badge {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: .78rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
  padding: 6px 14px; border-radius: 100px;
  background: var(--bg-secondary); color: var(--text-secondary);
}
.st-check {
  width: 72px; height: 72px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem;
}
.st-heading {
  font-family: var(--font-display);
  font-size: clamp(1.6rem, 5vw, 2.2rem);
  line-height: 1.2; margin: 0;
}
.st-message {
  font-size: .97rem; line-height: 1.65;
  color: var(--text-secondary); max-width: 380px; margin: 0;
}
.st-pips {
  display: flex; gap: 8px; align-items: center;
}
.st-pip {
  height: 6px; border-radius: 3px;
  background: var(--border); transition: background .4s, width .4s;
}
.st-pip.done { background: var(--lime); }
.st-pip.current { background: var(--red); }
.st-cta { min-width: 200px; }
@media (max-width: 600px) {
  .quiz-options.two-col { grid-template-columns: 1fr; }
  #concerns-grid { grid-template-columns: 1fr !important; }
  .quiz-body { padding: 32px var(--pad); }
  .quiz-question { font-size: clamp(1.3rem, 5vw, 1.8rem); }
  .st-inner { gap: 16px; }
}
@media (max-width: 480px) {
  .quiz-header-inner { gap: 10px; }
  .quiz-step-label { font-size: .72rem; }
  .quiz-skip { font-size: .72rem; }
  .quiz-option { padding: 14px 14px; }
  .option-emoji { font-size: 1.1rem; }
}
</style>
@endsection

@section('content')

<!-- Overall progress strip -->
<div class="overall-progress"><div class="overall-fill" id="overall-fill" style="width:0%"></div></div>

<!-- Quiz Header -->
<header class="quiz-header">
  <div class="quiz-header-inner">
    <a href="{{ route('home') }}" style="font-size:1.1rem;font-weight:700;font-family:var(--font-display);flex-shrink:0">KOMIN<span style="color:var(--red)">H</span>OO</a>
    <span class="quiz-step-label" id="step-label">Stage 1 of 5</span>
    <div class="quiz-progress">
      <div class="progress-bar"><div class="progress-fill" id="progress-fill" style="width:0%"></div></div>
    </div>
    <a href="{{ route('home') }}" class="quiz-skip">Skip Quiz →</a>
  </div>
</header>

<!-- Login status strip — shown above every slide -->
@if(session('user'))
  @php $qUser = session('user'); @endphp
  <div style="background:rgba(212,217,148,.12);border-bottom:1px solid rgba(212,217,148,.25);padding:10px 20px;display:flex;align-items:center;gap:10px;font-size:.82rem;">
    <span style="width:28px;height:28px;border-radius:50%;background:var(--black);color:var(--lime);display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0;">{{ strtoupper(substr($qUser['name'] ?? 'U', 0, 1)) }}</span>
    <span style="color:rgba(10,10,10,.7)">Signed in as <strong>{{ $qUser['name'] ?? 'User' }}</strong> — your skin profile will be saved automatically at each stage.</span>
  </div>
@else
  <div style="background:#fff8ec;border-bottom:1px solid #fde68a;padding:10px 20px;display:flex;align-items:center;justify-content:space-between;gap:12px;font-size:.82rem;flex-wrap:wrap;">
    <span style="color:rgba(10,10,10,.6)">⚡ Taking the quiz as a guest — results won't be saved to a profile.</span>
    <a href="{{ route('login') }}?redirect={{ urlencode(url()->current()) }}" style="font-weight:700;color:var(--black);text-decoration:underline;white-space:nowrap;">Sign in to save →</a>
  </div>
@endif

<!-- Stage Completion Banner (hidden) -->
<div id="stage-banner" style="display:none;background:var(--lime);padding:16px;text-align:center">
  <span style="font-weight:700;font-size:.95rem" id="stage-banner-text"></span>
</div>

<!-- Stage Transition Overlay -->
<div id="stage-transition" style="display:none">
  <div class="st-inner">
    <div class="st-badge" id="st-badge"></div>
    <div class="st-check" id="st-check"></div>
    <h2 class="st-heading" id="st-heading"></h2>
    <p class="st-message" id="st-message"></p>
    <div class="st-pips" id="st-pips"></div>
    <button class="btn btn-primary st-cta" onclick="dismissTransition()">Continue →</button>
  </div>
</div>

<form id="quiz-form" method="POST" action="{{ route('quiz.submit') }}">
  @csrf
  <input type="hidden" name="skin_type" id="skin_type_field" value="Normal">

<div class="quiz-body">

  <div id="quiz-slides-container"></div>

  <!-- ——— LOADING SCREEN ——— -->
  <div id="quiz-loading" style="display:none;text-align:center;padding:80px 24px;min-height:60vh;flex-direction:column;align-items:center;justify-content:center;gap:24px">
    <div class="loader-ring"></div>
    <h2 style="font-family:var(--font-display);font-size:2rem">Analyzing Your Skin Profile…</h2>
    <p style="color:var(--text-secondary);max-width:380px">Our Skin OS is matching your answers to the perfect products and building your personalized routine.</p>
    <div style="display:flex;flex-direction:column;gap:10px;width:100%;max-width:340px;text-align:left" id="analysis-steps"></div>
  </div>

</div><!-- /quiz-body -->
</form>

@endsection

@section('scripts')
<script>
const HOME_URL = "{{ route('home') }}";

// Default quiz config — overridable from admin CMS
const DEFAULT_QUIZ_CONFIG = {
  slides: [
    {id:1,stage:1,stageLabel:'🟢 Stage 1 — Skin Type',question:'How does your skin feel 30 minutes after washing your face?',subtext:'No product, no moisturizer — just your bare skin.',type:'single',twoCol:false,nextAction:'next',options:[{val:'dry',emoji:'🏜️',label:'Tight & Dry',sub:'Feels uncomfortable, pulling sensation'},{val:'normal',emoji:'😊',label:'Normal / Comfortable',sub:'Feels balanced, no dryness or oiliness'},{val:'combination',emoji:'😐',label:'Slightly Oily in T-zone',sub:'Forehead and nose get shiny, cheeks are fine'},{val:'oily',emoji:'🧴',label:'Oily All Over',sub:'Entire face gets shiny fairly quickly'},{val:'unsure',emoji:'🤷',label:'Not Sure',sub:'It changes depending on the day or weather'}]},
    {id:2,stage:1,stageLabel:'🟢 Stage 1 — Skin Type',question:'How often do you experience shine during the day?',subtext:'Think about a typical day without blotting or touching up.',type:'single',twoCol:false,nextAction:'next',options:[{val:'rarely',emoji:'🤍',label:'Rarely',sub:'My skin stays matte most of the day'},{val:'tzone',emoji:'🌡️',label:'Only on Forehead/Nose',sub:'T-zone gets shiny by afternoon'},{val:'frequently',emoji:'☀️',label:'Frequently',sub:'Multiple times a day, especially in heat'},{val:'very_oily',emoji:'💦',label:'Very Oily Within Hours',sub:'My skin gets shiny within 2–3 hours'}]},
    {id:3,stage:1,stageLabel:'🟢 Stage 1 — Skin Type',question:'How visible are your pores?',subtext:'Look in a mirror at normal distance — what do you notice?',type:'single',twoCol:true,nextAction:'stageTransition:1',options:[{val:'barely',emoji:'🔬',label:'Barely Visible',sub:''},{val:'tzone_pores',emoji:'😑',label:'Visible in T-zone only',sub:''},{val:'large',emoji:'😮',label:'Large and Noticeable',sub:''},{val:'very_large',emoji:'🕳️',label:'Very Large & Clogged',sub:''}]},
    {id:4,stage:2,stageLabel:'🔴 Stage 2 — Skin Concerns',question:'What are your top skin concerns?',subtext:'Select up to 3 that bother you most. These drive your product recommendations.',type:'multi',twoCol:true,nextAction:'next',options:[]},
    {id:5,stage:2,stageLabel:'🔴 Stage 2 — Skin Concerns',question:'How severe are these concerns?',subtext:'This helps us determine product strength and key ingredients.',type:'single',twoCol:false,nextAction:'stageTransition:2',options:[{val:'mild',emoji:'🌱',label:'Mild',sub:'Occasional, barely noticeable — maintenance is key'},{val:'moderate',emoji:'⚠️',label:'Moderate',sub:'Regular occurrence, clearly visible — needs targeted treatment'},{val:'severe',emoji:'🚨',label:'Severe',sub:'Persistent and significant — needs strong active ingredients'}]},
    {id:6,stage:3,stageLabel:'🟡 Stage 3 — Skin Behavior',question:'How does your skin react to new products?',subtext:'Think about the last time you introduced something new to your routine.',type:'single',twoCol:false,nextAction:'next',options:[{val:'no_reaction',emoji:'✅',label:'No Reaction',sub:'I can use most things without any issues'},{val:'occasional',emoji:'😬',label:'Occasional Breakouts',sub:'New products sometimes cause a purge or pimple'},{val:'easily_irritated',emoji:'😣',label:'Easily Irritated',sub:'Redness or stinging with many products'},{val:'very_sensitive',emoji:'🚨',label:'Very Sensitive',sub:'Most products cause a reaction — I need fragrance-free, minimal ingredients'}]},
    {id:7,stage:3,stageLabel:'🟡 Stage 3 — Skin Behavior',question:'How often do you break out?',subtext:'',type:'single',twoCol:true,nextAction:'next',options:[{val:'rarely',emoji:'🌸',label:'Rarely',sub:'Barely ever'},{val:'periodic',emoji:'🌙',label:'Around Periods/Stress',sub:'Hormonal pattern'},{val:'frequently',emoji:'🤒',label:'Frequently',sub:'Multiple times a month'},{val:'constantly',emoji:'😔',label:'Constantly',sub:'Always breaking out somewhere'}]},
    {id:8,stage:3,stageLabel:'🟡 Stage 3 — Skin Behavior',question:'Do you currently use active ingredients?',subtext:'This helps us avoid recommending things that could irritate your current routine.',type:'single',twoCol:false,nextAction:'stageTransition:3',options:[{val:'no',emoji:'🚫',label:"No — I'm a beginner",sub:'Just starting my skincare journey'},{val:'yes',emoji:'🧪',label:'Yes — Retinol, AHA/BHA, Vitamin C',sub:'I already use actives in my routine'},{val:'not_sure',emoji:'🤔',label:'Not Sure',sub:"I use some products but don't know the ingredients"}]},
    {id:9,stage:4,stageLabel:'🔵 Stage 4 — Lifestyle',question:'How much water do you drink daily?',subtext:'Hydration from inside affects your skin from outside!',type:'single',twoCol:true,nextAction:'next',options:[{val:'less_1l',emoji:'🚫',label:'Less than 1L',sub:''},{val:'1_2l',emoji:'🧃',label:'1–2 Litres',sub:''},{val:'2_3l',emoji:'🍶',label:'2–3 Litres',sub:''},{val:'3l_plus',emoji:'🏆',label:'3L+',sub:''}]},
    {id:10,stage:4,stageLabel:'🔵 Stage 4 — Lifestyle',question:'How often are you exposed to the sun?',subtext:'Nigerian sun is intense — your SPF needs matter here!',type:'single',twoCol:false,nextAction:'next',options:[{val:'rarely',emoji:'🏠',label:'Rarely',sub:'Mostly indoors'},{val:'occasionally',emoji:'🤏',label:'Occasionally',sub:'Some outdoor time a few times a week'},{val:'daily',emoji:'☀️',label:'Daily',sub:'Outdoor exposure every day'}]},
    {id:11,stage:4,stageLabel:'🔵 Stage 4 — Lifestyle',question:'What best describes your environment?',subtext:'',type:'single',twoCol:false,nextAction:'stageTransition:4',options:[{val:'aircon',emoji:'❄️',label:'Air-conditioned Most of the Day',sub:'Office or home AC — skin tends to get dehydrated'},{val:'humid',emoji:'🌴',label:'Hot & Humid Climate',sub:'Lagos, PH, Warri weather — skin gets oily & sweaty'},{val:'mixed',emoji:'🌡️',label:'Mixed Environment',sub:'Move between AC and outdoor settings'}]},
    {id:12,stage:5,stageLabel:'🟠 Stage 5 — Personalization',question:"What's your budget per routine?",subtext:"We'll match products to your price range — no surprises.",type:'single',twoCol:false,nextAction:'next',options:[{val:'basic',emoji:'💰',label:'Basic — ₦50,000–₦100,000',sub:'Essentials only, quality on a budget'},{val:'mid',emoji:'💳',label:'Mid-Range — ₦100,000–₦250,000',sub:'Great products with proven actives'},{val:'premium',emoji:'💎',label:'Premium — ₦250,000+',sub:'The absolute best — results at any price'}]},
    {id:13,stage:5,stageLabel:'🟠 Stage 5 — Personalization',question:'What results are you looking for?',subtext:'',type:'single',twoCol:false,nextAction:'next',options:[{val:'quick',emoji:'⚡',label:'Quick Visible Results',sub:'I want to see changes within 2–4 weeks'},{val:'longterm',emoji:'🌳',label:'Long-Term Skin Health',sub:"I'm building a sustainable routine for years of great skin"},{val:'both',emoji:'🎯',label:'Both!',sub:"I want visible results AND I'm in it for the long haul"}]},
    {id:14,stage:5,stageLabel:'🟠 Stage 5 — Personalization',question:'Would you consider professional skin treatments?',subtext:'Like facials, chemical peels, or dermatology visits.',type:'single',twoCol:false,nextAction:'submit',options:[{val:'yes',emoji:'🏥',label:'Yes, absolutely',sub:"I'm open to professional treatments for better results"},{val:'maybe',emoji:'🤔',label:'Maybe Later',sub:"Not right now but I'm open to it"},{val:'no',emoji:'🏠',label:'No, home care only',sub:'I prefer to manage my skin with home products'}]},
  ],
  concerns: [
    {val:'acne',label:'Acne / Breakouts',emoji:'🤢'},
    {val:'dark_spots',label:'Dark Spots / Hyperpigmentation',emoji:'🔘'},
    {val:'dull',label:'Dull Skin',emoji:'😴'},
    {val:'texture',label:'Uneven Texture',emoji:'🏔️'},
    {val:'fine_lines',label:'Fine Lines / Wrinkles',emoji:'⏰'},
    {val:'sensitive',label:'Sensitive / Irritated Skin',emoji:'🌡️'},
    {val:'dehydration',label:'Dehydration',emoji:'🏜️'},
    {val:'large_pores',label:'Large Pores',emoji:'🔍'},
  ],
  stageTransitions: {
    1:{badge:'🟢 Stage 1 of 5 Complete',check:'✓',checkColor:'#b5f000',heading:'Your skin type is mapped.',message:"These questions lay the foundation of your entire routine — knowing how your skin naturally behaves lets us curate the ideal box for your concerns rather than guessing. Keep going!",nextSlide:4,doneStages:1},
    2:{badge:'🔴 Stage 2 of 5 Complete',check:'✓',checkColor:'#ff4444',heading:'Your concerns are on file.',message:"Every answer here helps us choose actives and products that target what matters most to you. Let these questions curate the ideal box for your concerns — you're almost halfway there!",nextSlide:6,doneStages:2},
    3:{badge:'🟡 Stage 3 of 5 Complete',check:'✓',checkColor:'#f5c518',heading:'Your skin behaviour is logged.',message:"Knowing how your skin reacts helps us avoid anything irritating and recommend only what's safe for you. We're using all of this to build a box that truly fits — you're more than halfway there!",nextSlide:9,doneStages:3},
    4:{badge:'🔵 Stage 4 of 5 Complete',check:'✓',checkColor:'#4488ff',heading:'Your lifestyle profile is set.',message:"Your environment, habits and hydration shape what your skin needs day-to-day. These details let us fine-tune your recommendations so your routine works with your life. One final stage — you're almost there!",nextSlide:12,doneStages:4},
  },
  loadingSteps: [
    '🔬 Analyzing skin type profile…',
    '🎯 Matching skin concerns to actives…',
    '🌡️ Calculating sensitivity score…',
    '🌍 Adjusting for Nigerian climate…',
    '💰 Filtering by your budget…',
    '✨ Building your personalized routine…',
  ],
  settings: {enabled:true, maxConcernSelections:3, loadingDelayMs:3500}
};

const CMS_QUIZ_CONFIG = @json($quizConfig ?? []);

function getQuizConfig() {
  if (CMS_QUIZ_CONFIG && Array.isArray(CMS_QUIZ_CONFIG.slides) && CMS_QUIZ_CONFIG.slides.length) {
    return CMS_QUIZ_CONFIG;
  }

  return DEFAULT_QUIZ_CONFIG;
}

const quizConfig = getQuizConfig();
const TOTAL_SLIDES = quizConfig.slides.length;

// —— Slide rendering ——
function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

function buildSlideHtml(s) {
  let nextBtn;
  if (s.nextAction === 'submit') {
    nextBtn = `<button type="button" class="btn btn-primary btn-lg" onclick="submitQuiz()" id="next-${s.id}" disabled>✨ Build My Routine →</button>`;
  } else if (s.nextAction.startsWith('stageTransition:')) {
    const n = s.nextAction.split(':')[1];
    nextBtn = `<button type="button" class="btn btn-primary" onclick="showStageTransition(${n})" id="next-${s.id}" disabled>Continue →</button>`;
  } else {
    nextBtn = `<button type="button" class="btn btn-primary" onclick="nextSlide()" id="next-${s.id}" disabled>Continue →</button>`;
  }
  const backBtn = s.id === 1
    ? `<a href="${HOME_URL}" class="btn btn-ghost">← Back</a>`
    : `<button type="button" class="btn btn-ghost" onclick="prevSlide()">← Back</button>`;
  let optHtml;
  if (s.type === 'multi') {
    optHtml = `<div class="quiz-options" style="display:grid;grid-template-columns:1fr 1fr;gap:10px" id="concerns-grid"></div>`;
  } else {
    const cls = `quiz-options${s.twoCol ? ' two-col' : ''}`;
    optHtml = `<div class="${cls}">${s.options.map(o =>
      `<div class="quiz-option" data-answer="${o.val}" onclick="selectOption(this,${s.id})">
        <div class="option-radio"></div><div class="option-emoji">${o.emoji}</div>
        <div class="option-text"><div class="option-label">${esc(o.label)}</div>${o.sub ? `<div class="option-sub">${esc(o.sub)}</div>` : ''}</div>
      </div>`).join('')}</div>`;
  }
  const navExtra = s.type === 'multi'
    ? `<div class="selected-count" id="concern-count">0 / ${quizConfig.settings.maxConcernSelections} selected</div>` : '';
  return `<div class="quiz-slide${s.id === 1 ? ' active' : ''}" data-slide="${s.id}" data-stage="${s.stage}">
    <div class="quiz-stage-pill stage-${s.stage}">${esc(s.stageLabel)}</div>
    <h1 class="quiz-question">${esc(s.question)}</h1>
    ${s.subtext ? `<p class="quiz-subtext">${esc(s.subtext)}</p>` : ''}
    ${optHtml}
    <div class="quiz-nav-btns">${backBtn}${navExtra}${nextBtn}</div>
  </div>`;
}

function renderQuizSlides() {
  document.getElementById('quiz-slides-container').innerHTML = quizConfig.slides.map(s => buildSlideHtml(s)).join('');
  buildConcernsGrid();
}

// —— Quiz State ——
let currentSlide = 1;
const answers = {};
let concernSelections = [];

function buildConcernsGrid() {
  const grid = document.getElementById('concerns-grid');
  if (!grid) return;
  const maxSel = quizConfig.settings.maxConcernSelections;
  grid.innerHTML = quizConfig.concerns.map(c =>
    `<div class="quiz-option multi-select" data-answer="${c.val}" onclick="toggleConcern(this,'${c.val}')">
      <div class="option-radio"></div><div class="option-emoji">${c.emoji}</div>
      <div class="option-text"><div class="option-label">${esc(c.label)}</div></div>
    </div>`).join('');
}

function toggleConcern(el, val) {
  const maxSel = quizConfig.settings.maxConcernSelections;
  if (el.classList.contains('selected')) {
    el.classList.remove('selected');
    concernSelections = concernSelections.filter(v => v !== val);
  } else if (concernSelections.length < maxSel) {
    el.classList.add('selected');
    concernSelections.push(val);
  }
  document.getElementById('concern-count').textContent = `${concernSelections.length} / ${maxSel} selected`;
  const multiSlide = quizConfig.slides.find(s => s.type === 'multi');
  if (multiSlide) document.getElementById(`next-${multiSlide.id}`).disabled = concernSelections.length === 0;
}

function selectOption(el, slide) {
  el.closest('.quiz-options').querySelectorAll('.quiz-option').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
  answers[slide] = el.dataset.answer;
  const btn = document.getElementById(`next-${slide}`);
  if (btn) btn.disabled = false;
}

function updateProgress() {
  const pct = Math.round((currentSlide - 1) / TOTAL_SLIDES * 100);
  document.getElementById('overall-fill').style.width = pct + '%';
  document.getElementById('progress-fill').style.width = pct + '%';
  const stage = quizConfig.slides[currentSlide - 1]?.stage || 5;
  document.getElementById('step-label').textContent = `Stage ${stage} of 5`;
}

function showSlide(n) {
  document.querySelectorAll('.quiz-slide').forEach(s => s.classList.remove('active'));
  const target = document.querySelector(`[data-slide="${n}"]`);
  if (target) target.classList.add('active');
  currentSlide = n;
  updateProgress();
  window.scrollTo({top: 0, behavior: 'smooth'});
}

function nextSlide() { if (currentSlide < TOTAL_SLIDES) showSlide(currentSlide + 1); }
function prevSlide() { if (currentSlide > 1) showSlide(currentSlide - 1); }

// —— Helpers ——
function collectAnswers() {
  return {
    skin_feel:    answers[1]  || '',
    shine:        answers[2]  || '',
    pores:        answers[3]  || '',
    concerns:     concernSelections,          // array
    severity:     answers[5]  || '',
    reactivity:   answers[6]  || '',
    breakouts:    answers[7]  || '',
    actives:      answers[8]  || '',
    water:        answers[9]  || '',
    sun:          answers[10] || '',
    environment:  answers[11] || '',
    budget:       answers[12] || '',
    results_goal: answers[13] || '',
    treatments:   answers[14] || '',
  };
}

// Fire-and-forget progress save after each stage transition
function saveQuizProgress(stage) {
  const payload = { stage: stage, answers: collectAnswers() };
  fetch('{{ route("quiz.progress") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
    },
    body: JSON.stringify(payload),
  }).catch(() => {});
}

function submitQuiz() {
  document.querySelectorAll('.quiz-slide').forEach(s => s.classList.remove('active'));
  const loading = document.getElementById('quiz-loading');
  loading.style.display = 'flex';
  document.getElementById('overall-fill').style.width = '100%';

  const steps = quizConfig.loadingSteps;
  const stepsWrap = document.getElementById('analysis-steps');
  steps.forEach((s, i) => {
    setTimeout(() => {
      const el = document.createElement('div');
      el.style.cssText = 'display:flex;align-items:center;gap:12px;padding:10px 16px;background:#fff;border-radius:var(--r-md);font-size:.88rem;font-weight:600;box-shadow:var(--s-sm);opacity:0;transition:opacity .3s';
      el.innerHTML = s;
      stepsWrap.appendChild(el);
      requestAnimationFrame(() => el.style.opacity = '1');
    }, i * 350);
  });

  setTimeout(() => {
    const form = document.getElementById('quiz-form');
    // Remove the legacy skin_type_field — backend now computes it from answers
    const legacy = document.getElementById('skin_type_field');
    if (legacy) legacy.remove();

    // Append all answers as semantic hidden fields
    const allAnswers = collectAnswers();
    Object.entries(allAnswers).forEach(([key, val]) => {
      if (Array.isArray(val)) {
        // concerns[] array
        val.forEach(v => {
          const el = document.createElement('input');
          el.type = 'hidden'; el.name = `answers[${key}][]`; el.value = v;
          form.appendChild(el);
        });
        if (val.length === 0) {
          // send empty placeholder so the key exists in request
          const el = document.createElement('input');
          el.type = 'hidden'; el.name = `answers[${key}]`; el.value = '';
          form.appendChild(el);
        }
      } else {
        const el = document.createElement('input');
        el.type = 'hidden'; el.name = `answers[${key}]`; el.value = val;
        form.appendChild(el);
      }
    });
    form.submit();
  }, quizConfig.settings.loadingDelayMs || 3500);
}

// —— Stage Transition Overlay ——
const STAGE_TRANSITIONS = quizConfig.stageTransitions;
let pendingNextSlide = null;

function showStageTransition(stage) {
  const data = STAGE_TRANSITIONS[stage];
  if (!data) { nextSlide(); return; }
  pendingNextSlide = data.nextSlide;
  saveQuizProgress(stage); // persist best estimate after each completed stage
  document.getElementById('st-badge').textContent = data.badge;
  const check = document.getElementById('st-check');
  check.textContent = data.check || '✓';
  check.style.background = data.checkColor + '22';
  check.style.color = data.checkColor;
  check.style.fontSize = '2rem';
  check.style.fontWeight = '800';
  document.getElementById('st-heading').textContent = data.heading;
  document.getElementById('st-message').textContent = data.message;
  const pips = document.getElementById('st-pips');
  pips.innerHTML = '';
  for (let i = 1; i <= 5; i++) {
    const pip = document.createElement('div');
    pip.className = 'st-pip' + (i <= data.doneStages ? ' done' : (i === data.doneStages + 1 ? ' current' : ''));
    pip.style.width = i <= data.doneStages ? '32px' : '18px';
    pips.appendChild(pip);
  }
  const overlay = document.getElementById('stage-transition');
  overlay.style.display = 'flex';
  overlay.classList.remove('fade-out');
  overlay.classList.add('fade-in');
}

function dismissTransition() {
  const overlay = document.getElementById('stage-transition');
  overlay.classList.remove('fade-in');
  overlay.classList.add('fade-out');
  overlay.addEventListener('animationend', function handler() {
    overlay.style.display = 'none';
    overlay.classList.remove('fade-out');
    overlay.removeEventListener('animationend', handler);
    if (pendingNextSlide !== null) { showSlide(pendingNextSlide); pendingNextSlide = null; }
  });
}

renderQuizSlides();
updateProgress();
</script>
@endsection
