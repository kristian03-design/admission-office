/**
 * Baliwag Polytechnic College — Admissions Landing Page
 * main.js — All interactive functionality
 */

'use strict';

if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}

function resetLandingScrollPosition() {
  if (window.location.hash) return;
  window.scrollTo({ top: 0, left: 0, behavior: 'auto' });
}

resetLandingScrollPosition();
window.addEventListener('pageshow', resetLandingScrollPosition);

function hideSiteLoader() {
  const loader = document.getElementById('site-loader');
  if (!loader) return;
  loader.classList.add('is-hidden');
  document.body.classList.remove('site-loader-lock');
  setTimeout(() => loader.remove(), 550);
}

document.body.classList.add('site-loader-lock');
window.addEventListener('load', () => {
  setTimeout(hideSiteLoader, 350);
});
setTimeout(hideSiteLoader, 4500);

// ─────────────────────────────────────────
// UTILITIES
// ─────────────────────────────────────────

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

function apiUrl(path) {
  const isVercel = /\.vercel\.app$/i.test(window.location.hostname);
  const base = window.ADMISSION_API_BASE || (window.location.origin + (isVercel ? '/backend' : '/api'));
  const normalized = String(path || '').replace(/^\/api(?=\/|$)/, '');
  return base.replace(/\/$/, '') + (normalized.startsWith('/') ? normalized : '/' + normalized);
}

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content
    || document.querySelector('input[name="_token"]')?.value
    || '';
}

function debounce(fn, ms = 150) {
  let t;
  return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
}

// ─────────────────────────────────────────
// NAVBAR — scroll behavior
// ─────────────────────────────────────────

const navbar = $('#navbar');

function updateNavbar() {
  if (window.scrollY > 60) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
}

window.addEventListener('scroll', updateNavbar, { passive: true });
updateNavbar(); // init

// ─────────────────────────────────────────
// MOBILE MENU PREMIUM REDESIGN
// ─────────────────────────────────────────

const menuToggle = $('#menu-toggle');
const mobileMenu = $('#mobile-menu');
let menuOpen = false;

function toggleMenu(forceClose = false) {
  menuOpen = forceClose ? false : !menuOpen;

  menuToggle?.classList.toggle('active', menuOpen);
  mobileMenu?.classList.toggle('active', menuOpen);
  document.body.classList.toggle('menu-open', menuOpen);

  // Hide navbar CTA on mobile when menu is open to reduce clutter
  const navCta = $('.btn-primary-nav');
  if (navCta) {
    navCta.style.display = (menuOpen && window.innerWidth < 768) ? 'none' : '';
  }

  menuToggle?.setAttribute('aria-expanded', menuOpen ? 'true' : 'false');
}

menuToggle?.addEventListener('click', (e) => {
  e.stopPropagation();
  toggleMenu();
});

// Close mobile menu on link click
$$('.mobile-nav-link, .mobile-btn-primary').forEach(link => {
  link.addEventListener('click', () => {
    toggleMenu(true);
  });
});

// ─────────────────────────────────────────
// SCROLL ANIMATIONS (Premium Feel)
// ─────────────────────────────────────────

function initScrollAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        // Once visible, we can stop observing if we want it to stay
        // observer.unobserve(entry.target); 
      }
    });
  }, observerOptions);

  const animatedElements = document.querySelectorAll('[data-animate]');
  animatedElements.forEach(el => {
    // Add delay if specified
    const delay = el.getAttribute('data-delay');
    if (delay) {
      el.style.transitionDelay = `${delay}ms`;
    }
    observer.observe(el);
  });
}

// ─────────────────────────────────────────
// COUNTER ANIMATION
// ─────────────────────────────────────────

function initCounters() {
  const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        const target = parseInt(el.getAttribute('data-count'), 10);
        if (isNaN(target)) return;

        let count = 0;
        const duration = 2000; // 2 seconds
        const startTime = performance.now();

        function updateCount(currentTime) {
          const elapsed = currentTime - startTime;
          const progress = Math.min(elapsed / duration, 1);
          const currentCount = Math.floor(progress * target);

          el.textContent = currentCount;

          if (progress < 1) {
            requestAnimationFrame(updateCount);
          } else {
            el.textContent = target;
          }
        }

        requestAnimationFrame(updateCount);
        counterObserver.unobserve(el);
      }
    });
  }, { threshold: 0.5 });

  document.querySelectorAll('.stat-number').forEach(el => counterObserver.observe(el));
}

document.addEventListener('DOMContentLoaded', () => {
  initScrollAnimations();
  initCounters();
});

// Close on escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && menuOpen) toggleMenu(true);
});

// ─────────────────────────────────────────
// SCROLL ANIMATIONS (Intersection Observer)
// ─────────────────────────────────────────

const animObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    const el = entry.target;
    const delay = parseInt(el.dataset.delay || '0', 10);
    setTimeout(() => {
      el.classList.add('animated');
    }, delay);
    animObserver.unobserve(el);
  });
}, {
  threshold: 0.12,
  rootMargin: '0px 0px -40px 0px'
});

$$('[data-animate]').forEach(el => animObserver.observe(el));

// ─────────────────────────────────────────
// HERO ELEMENTS — initial staggered reveal
// ─────────────────────────────────────────

// Trigger hero animations after a brief delay
setTimeout(() => {
  $$('#hero [data-animate]').forEach(el => {
    const delay = parseInt(el.dataset.delay || '0', 10);
    setTimeout(() => el.classList.add('animated'), delay + 200);
  });
}, 100);

// ─────────────────────────────────────────
// ANIMATED COUNTERS
// ─────────────────────────────────────────

function animateCounter(el, target, duration = 1600) {
  let start = null;
  const step = (ts) => {
    if (!start) start = ts;
    const progress = Math.min((ts - start) / duration, 1);
    const eased = 1 - Math.pow(1 - progress, 3); // ease out cubic
    el.textContent = Math.round(eased * target);
    if (progress < 1) requestAnimationFrame(step);
  };
  requestAnimationFrame(step);
}

const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    const el = entry.target;
    const target = parseInt(el.dataset.count, 10);
    if (!isNaN(target)) animateCounter(el, target);
    counterObserver.unobserve(el);
  });
}, { threshold: 0.5 });

$$('[data-count]').forEach(el => counterObserver.observe(el));

// ─────────────────────────────────────────
// PROGRAM FILTER
// ─────────────────────────────────────────

const filterBtns = $$('.filter-btn');
const programCards = $$('.program-card');

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    // Update active button
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const filter = btn.dataset.filter;

    programCards.forEach(card => {
      const match = filter === 'all' || card.dataset.category === filter;
      if (match) {
        card.classList.remove('hidden-card');
        card.style.animation = 'fadeInCard .35s ease both';
      } else {
        card.classList.add('hidden-card');
      }
    });
  });
});

// Inject keyframe for filter animation
const styleEl = document.createElement('style');
styleEl.textContent = `
@keyframes fadeInCard {
  from { opacity: 0; transform: scale(.96) translateY(8px); }
  to   { opacity: 1; transform: scale(1) translateY(0); }
}`;
document.head.appendChild(styleEl);

// ─────────────────────────────────────────
// TESTIMONIALS CAROUSEL
// ─────────────────────────────────────────

(function initCarousel() {
  const carousel = $('#testimonials-carousel');
  const dotsContainer = $('#carousel-dots');
  const prevBtn = $('#carousel-prev');
  const nextBtn = $('#carousel-next');

  if (!carousel) return;

  const cards = $$('.testimonial-card', carousel);
  const visibleCount = () => window.innerWidth < 768 ? 1 : 2;
  let current = 0;
  let autoPlayTimer;

  function getMaxIndex() {
    return Math.max(0, cards.length - visibleCount());
  }

  function buildDots() {
    if (!dotsContainer) return;
    dotsContainer.innerHTML = '';
    const count = getMaxIndex() + 1;
    for (let i = 0; i < count; i++) {
      const dot = document.createElement('button');
      dot.className = 'carousel-dot' + (i === current ? ' active' : '');
      dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
      dot.addEventListener('click', () => goTo(i));
      dotsContainer.appendChild(dot);
    }
  }

  function updateDots() {
    if (!dotsContainer) return;
    $$('.carousel-dot', dotsContainer).forEach((dot, i) => {
      dot.classList.toggle('active', i === current);
    });
  }

  function goTo(index) {
    if (!cards.length) return;
    const max = getMaxIndex();
    current = Math.max(0, Math.min(index, max));

    const gap = parseFloat(getComputedStyle(carousel).columnGap || getComputedStyle(carousel).gap) || 0;
    const cardWidth = cards[0].offsetWidth + gap;
    carousel.style.transform = `translateX(-${current * cardWidth}px)`;
    updateDots();
  }

  prevBtn?.addEventListener('click', () => {
    resetAutoPlay();
    goTo(current - 1);
  });

  nextBtn?.addEventListener('click', () => {
    resetAutoPlay();
    goTo(current < getMaxIndex() ? current + 1 : 0);
  });

  function autoPlay() {
    autoPlayTimer = setInterval(() => {
      goTo(current < getMaxIndex() ? current + 1 : 0);
    }, 5000);
  }

  function resetAutoPlay() {
    clearInterval(autoPlayTimer);
    autoPlay();
  }

  // Touch swipe
  let touchStartX = 0;
  carousel.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
  carousel.addEventListener('touchend', e => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 50) {
      resetAutoPlay();
      goTo(diff > 0 ? current + 1 : current - 1);
    }
  });

  buildDots();
  autoPlay();

  window.addEventListener('resize', debounce(() => {
    buildDots();
    goTo(Math.min(current, getMaxIndex()));
  }, 200));
})();

// ─────────────────────────────────────────
// FORM VALIDATION & SUBMISSION
// ─────────────────────────────────────────

function validateEmail(val) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim());
}

function setError(inputEl, errorEl, msg) {
  if (!errorEl) return;
  errorEl.textContent = msg;
  errorEl.classList.toggle('visible', !!msg);
  inputEl?.classList.toggle('error', !!msg);
}

function clearError(inputEl, errorEl) {
  setError(inputEl, errorEl, '');
}

// ── Inquiry Form (Hero Card) ──
(function initInquiryForm() {
  const form = $('#inquiry-form');
  const successEl = $('#form-success');
  const submitBtn = $('#submit-btn');
  if (!form) return;
  const formStartedAt = Math.floor(Date.now() / 1000);

  const fields = {
    name: { input: $('#full-name'), error: $('#name-error') },
    email: { input: $('#email'), error: $('#email-error') },
    program: { input: $('#program'), error: $('#program-error') },
  };

  // Live validation
  fields.name.input?.addEventListener('input', () => {
    const val = fields.name.input.value.trim();
    clearError(fields.name.input, fields.name.error);
  });
  fields.email.input?.addEventListener('input', () => {
    clearError(fields.email.input, fields.email.error);
  });
  fields.program.input?.addEventListener('change', () => {
    clearError(fields.program.input, fields.program.error);
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    let valid = true;

    // Validate name
    const nameVal = fields.name.input?.value.trim() || '';
    if (!nameVal || nameVal.length < 2) {
      setError(fields.name.input, fields.name.error, 'Please enter your full name.');
      valid = false;
    }

    // Validate email
    const emailVal = fields.email.input?.value.trim() || '';
    if (!emailVal) {
      setError(fields.email.input, fields.email.error, 'Email address is required.');
      valid = false;
    } else if (!validateEmail(emailVal)) {
      setError(fields.email.input, fields.email.error, 'Please enter a valid email address.');
      valid = false;
    }

    // Validate program
    const programVal = fields.program.input?.value || '';
    if (!programVal) {
      setError(fields.program.input, fields.program.error, 'Please select a program.');
      valid = false;
    }

    if (!valid) return;

    const btnText = submitBtn.querySelector('.btn-text');
    const btnSpinner = submitBtn.querySelector('.btn-spinner');
    btnText.classList.add('hidden');
    btnSpinner.classList.remove('hidden');
    submitBtn.disabled = true;

    try {
      const nameParts = fields.name.input.value.trim().split(' ');
      const firstName = nameParts[0];
      const lastName = nameParts.length > 1 ? nameParts.slice(1).join(' ') : '---';

      const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
      const token = csrfToken();
      if (token) headers['X-CSRF-TOKEN'] = token;

      const response = await fetch(apiUrl('/contact'), {
        method: 'POST',
        headers,
        body: JSON.stringify({
          first_name: firstName,
          last_name: lastName,
          email: fields.email.input.value,
          subject: 'Program Inquiry: ' + fields.program.input.value,
          message: 'Interested in: ' + fields.program.input.value,
          form_started_at: formStartedAt,
          _hp: ''
        })
      });

      if (!response.ok) throw new Error('Submission failed');

      form.style.display = 'none';
      successEl.classList.remove('hidden');
      successEl.style.display = 'block';
    } catch (err) {
      alert('Sorry, there was an error. Please try again later.');
      btnText.classList.remove('hidden');
      btnSpinner.classList.add('hidden');
      submitBtn.disabled = false;
    }
  });
})();

// ── Contact Form ──
(function initContactForm() {
  const form = $('#contact-form');
  const submitBtn = $('#contact-submit-btn');
  if (!form) return;
  const formStartedAt = Math.floor(Date.now() / 1000);

  const formCard = form.closest('.contact-form-card');
  let notice = formCard?.querySelector('.contact-form-notice');
  if (!notice && formCard) {
    notice = document.createElement('div');
    notice.className = 'contact-form-notice hidden';
    notice.setAttribute('role', 'status');
    notice.setAttribute('aria-live', 'polite');
    formCard.insertBefore(notice, form);
  }

  function setContactNotice(type, message) {
    if (!notice) return;
    const isSuccess = type === 'success';
    notice.className = `contact-form-notice contact-form-notice--${type}`;
    notice.innerHTML = `
      <div style="display:flex;align-items:flex-start;gap:10px;">
        <div style="flex:1;">
          <strong style="display:block;color:${isSuccess ? '#166534' : '#991b1b'};font-size:.9rem;">${isSuccess ? 'Message sent' : 'Message not sent'}</strong>
          <span style="display:block;color:${isSuccess ? '#3f6f52' : '#7f1d1d'};font-size:.85rem;line-height:1.45;margin-top:2px;">${message}</span>
        </div>
        <button type="button" class="contact-form-notice-close" aria-label="Dismiss message" style="border:0;background:transparent;color:#64748b;font-size:18px;line-height:1;cursor:pointer;padding:0 2px;">&times;</button>
      </div>
    `;
    notice.querySelector('.contact-form-notice-close')?.addEventListener('click', () => {
      notice.className = 'contact-form-notice hidden';
      notice.innerHTML = '';
    });
  }

  function setSubmitting(isSubmitting) {
    const btnText = submitBtn?.querySelector('.btn-text');
    const btnSpinner = submitBtn?.querySelector('.btn-spinner');
    btnText?.classList.toggle('hidden', isSubmitting);
    btnSpinner?.classList.toggle('hidden', !isSubmitting);
    if (submitBtn) submitBtn.disabled = isSubmitting;
  }

  form.addEventListener('input', (event) => {
    event.target?.classList?.remove('error');
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const inputs = $$('.form-input', form);
    let valid = true;
    inputs.forEach(inp => {
      if (inp.hasAttribute('required') && !inp.value.trim()) {
        inp.classList.add('error');
        valid = false;
      } else {
        inp.classList.remove('error');
      }
    });

    const emailInput = form.querySelector('[name="email"]');
    if (emailInput && emailInput.value.trim() && !validateEmail(emailInput.value)) {
      emailInput.classList.add('error');
      setContactNotice('error', 'Please enter a valid email address.');
      valid = false;
    }

    if (!valid) return;

    setSubmitting(true);

    try {
      const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
      const token = csrfToken();
      if (token) headers['X-CSRF-TOKEN'] = token;

      const response = await fetch(apiUrl('/contact'), {
        method: 'POST',
        headers,
        body: JSON.stringify({
          first_name: form.querySelector('[name="first_name"]').value,
          last_name: form.querySelector('[name="last_name"]').value,
          email: form.querySelector('[name="email"]').value,
          subject: form.querySelector('[name="subject"]').value,
          message: form.querySelector('[name="message"]').value,
          form_started_at: formStartedAt,
          _hp: ''
        })
      });

      const data = await response.json().catch(() => ({}));
      if (!response.ok) {
        throw new Error(data.message || 'Failed to send message');
      }

      form.reset();
      inputs.forEach(inp => inp.classList.remove('error'));
      setContactNotice('success', "Thank you. We'll respond to your inquiry within 1-2 business days.");
      setSubmitting(false);
      return;

      // Replace with success state
      form.innerHTML = `
        <div class="text-center py-6">
          <div class="success-icon mx-auto mb-4" style="background:var(--sage);width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;">
            <i data-iconsax="check" style="width:28px;height:28px"></i>
          </div>
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.5rem;color:var(--navy);font-weight:600;">Message Received!</h3>
          <p style="color:var(--gray-600);margin-top:.5rem;font-size:.9rem;">We'll respond to your inquiry within 1–2 business days.</p>
        </div>`;
    } catch (err) {
      setContactNotice('error', 'Sorry, there was an error sending your message. Please try again later.');
      setSubmitting(false);
    }
  });
})();

// ─────────────────────────────────────────
// BACK TO TOP
// ─────────────────────────────────────────

const backToTop = $('#back-to-top');

window.addEventListener('scroll', () => {
  backToTop?.classList.toggle('visible', window.scrollY > 400);
}, { passive: true });

backToTop?.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ─────────────────────────────────────────
// SMOOTH SCROLL FOR ANCHOR LINKS
// ─────────────────────────────────────────

$$('a[href^="#"]').forEach(link => {
  link.addEventListener('click', e => {
    const target = document.getElementById(link.getAttribute('href').slice(1));
    if (!target) return;
    e.preventDefault();
    const offset = 80; // navbar height
    const top = target.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
  });
});

// ─────────────────────────────────────────
// ACTIVE NAV LINK highlight on scroll
// ─────────────────────────────────────────

(function initActiveNav() {
  const sections = $$('section[id]');
  const navLinks = $$('#navbar .nav-link');

  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const id = entry.target.id;
      navLinks.forEach(link => {
        const href = link.getAttribute('href');
        link.classList.toggle('active-nav', href === `#${id}`);
      });
    });
  }, { rootMargin: '-30% 0px -65% 0px' });

  sections.forEach(s => io.observe(s));
})();

// ─────────────────────────────────────────
// TICKER pause on hover
// ─────────────────────────────────────────

const tickerTrack = $('#ticker');
tickerTrack?.parentElement?.addEventListener('mouseenter', () => {
  tickerTrack.style.animationPlayState = 'paused';
});
tickerTrack?.parentElement?.addEventListener('mouseleave', () => {
  tickerTrack.style.animationPlayState = 'running';
});

// ─────────────────────────────────────────
// FORM INPUT focus enhancements
// ─────────────────────────────────────────

$$('.form-input, .form-select').forEach(el => {
  el.addEventListener('focus', () => {
    el.closest('.form-group')?.classList.add('focused');
  });
  el.addEventListener('blur', () => {
    el.closest('.form-group')?.classList.remove('focused');
  });
});

// ─────────────────────────────────────────
// LOG
// ─────────────────────────────────────────

console.log('%c BPC Admissions Landing Page loaded ✓', 'color:#254d82;font-weight:bold;font-size:1rem;');

/* =========================================================
   HERO ROTATING STUDENT SLIDER — ADD TO home-page.js
   Paste this block anywhere after your existing code.
   ========================================================= */

(function initHeroSlider() {
  const stage = document.getElementById('hero-img-stage');
  const progressBar = document.getElementById('hero-progress-bar');

  if (!stage) return; // guard — hero not on page

  function getHeroSlidesData() {
    const dataEl = document.getElementById('hero-slides-json');
    if (!dataEl?.textContent?.trim()) return [];

    try {
      const data = JSON.parse(dataEl.textContent);
      return Array.isArray(data) ? data : [];
    } catch (error) {
      console.warn('Hero slides JSON could not be parsed.', error);
      return [];
    }
  }

  function renderHeroSlides(slidesData) {
    const dotsWrap = document.getElementById('hero-slider-nav');
    if (!slidesData.length || !dotsWrap) return;

    stage.replaceChildren();
    dotsWrap.replaceChildren();

    slidesData.forEach((slide, index) => {
      const slideEl = document.createElement('div');
      slideEl.className = `hero-img-slide${index === 0 ? ' active' : ''}`;
      slideEl.dataset.program = slide.program || '';
      slideEl.dataset.icon = slide.code || '';
      slideEl.dataset.dept = slide.department || '';

      const img = document.createElement('img');
      img.src = slide.image || '';
      img.alt = slide.alt || slide.program || 'Program image';
      img.decoding = 'async';

      if (index === 0) {
        img.setAttribute('fetchpriority', 'high');
      } else {
        img.loading = 'lazy';
      }

      slideEl.appendChild(img);
      stage.appendChild(slideEl);

      const dot = document.createElement('button');
      dot.type = 'button';
      dot.className = `hero-slider-dot${index === 0 ? ' active' : ''}`;
      dot.dataset.index = String(index);
      dot.setAttribute('aria-label', slide.program || `Program ${index + 1}`);
      dotsWrap.appendChild(dot);
    });
  }

  renderHeroSlides(getHeroSlidesData());

  const slides = Array.from(stage.querySelectorAll('.hero-img-slide'));
  const dots = Array.from(document.querySelectorAll('.hero-slider-dot'));
  if (!slides.length) return;
  const INTERVAL = 4000;  // ms between slides
  const TICK = 50;    // ms for progress bar update

  let current = 0;
  let timer = null;
  let progress = 0;
  let progTimer = null;
  let isPaused = false;

  // ── Helpers ──────────────────────────────────────────

  function setSlide(index, dir = 'next') {
    const prev = slides[current];
    current = (index + slides.length) % slides.length;
    const next = slides[current];

    // Exit animation on previous
    prev.classList.remove('active');
    prev.classList.add('exit');

    // Small timeout so exit class renders before clearing it
    setTimeout(() => {
      prev.classList.remove('exit');
    }, 700);

    // Enter animation on next
    next.classList.add('active');

    // Update dots
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === current);
    });

    // Reset progress
    resetProgress();
  }

  function next() {
    setSlide(current + 1, 'next');
  }

  // ── Progress bar ─────────────────────────────────────

  function resetProgress() {
    progress = 0;
    if (progressBar) progressBar.style.width = '0%';
    clearInterval(progTimer);
    if (!isPaused) startProgress();
  }

  function startProgress() {
    progTimer = setInterval(() => {
      progress += (TICK / INTERVAL) * 100;
      if (progressBar) progressBar.style.width = progress + '%';
      if (progress >= 100) {
        clearInterval(progTimer);
      }
    }, TICK);
  }

  // ── Autoplay ─────────────────────────────────────────

  function startAutoplay() {
    clearInterval(timer);
    clearInterval(progTimer);
    timer = setInterval(next, INTERVAL);
    isPaused = false;
    startProgress();
  }

  function pauseAutoplay() {
    clearInterval(timer);
    clearInterval(progTimer);
    isPaused = true;
  }

  function resumeAutoplay() {
    startAutoplay();
  }

  // ── Dot clicks ───────────────────────────────────────

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      if (i === current) return;
      pauseAutoplay();
      setSlide(i);
      resumeAutoplay();
    });
  });

  // ── Pause on hover ───────────────────────────────────

  stage.addEventListener('mouseenter', pauseAutoplay);
  stage.addEventListener('mouseleave', resumeAutoplay);

  // ── Touch / swipe support ────────────────────────────

  let touchStartX = 0;

  stage.addEventListener('touchstart', (e) => {
    touchStartX = e.touches[0].clientX;
    pauseAutoplay();
  }, { passive: true });

  stage.addEventListener('touchend', (e) => {
    const diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 40) {
      setSlide(diff > 0 ? current + 1 : current - 1);
    }
    resumeAutoplay();
  });

  // ── Init ─────────────────────────────────────────────

  // Make sure first slide is visible
  slides[0].classList.add('active');


  // Small delay before autoplay starts (let page load)
  if (slides.length > 1) {
    setTimeout(startAutoplay, 800);
  }

  console.log('%c Hero slider initialized ✓', 'color:#c9933a;font-weight:bold;');
})();

// ─────────────────────────────────────────
// ADMISSION GUIDE MODAL
// ─────────────────────────────────────────

(function initGuideModal() {
  const openBtn = document.getElementById('open-guide');
  const closeBtn = document.getElementById('close-guide');
  const closeFooterBtn = document.getElementById('close-guide-footer');
  const printBtn = document.getElementById('print-guide');
  const modal = document.getElementById('guide-modal');

  if (!openBtn || !modal) return;

  function openModal() {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
  }

  function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = ''; // Restore scrolling
  }

  openBtn.addEventListener('click', (e) => {
    e.preventDefault();
    openModal();
  });

  closeBtn?.addEventListener('click', closeModal);
  closeFooterBtn?.addEventListener('click', closeModal);
  printBtn?.addEventListener('click', () => window.print());

  // Close on outside click
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) {
      closeModal();
    }
  });
})();
