/**
 * Baliwag Polytechnic College — Admissions Landing Page
 * main.js — All interactive functionality
 */

'use strict';

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

// Mobile menu toggle
const menuToggle = $('#menu-toggle');
const mobileMenu = $('#mobile-menu');
let menuOpen = false;

menuToggle?.addEventListener('click', () => {
  menuOpen = !menuOpen;
  mobileMenu.classList.toggle('hidden', !menuOpen);
});

// Close mobile menu on link click
$$('#mobile-menu a').forEach(link => {
  link.addEventListener('click', () => {
    menuOpen = false;
    mobileMenu.classList.add('hidden');
  });
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

    const cardWidth = cards[0].offsetWidth + 24; // gap 1.5rem = 24px
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

      const response = await fetch(apiUrl('/contact'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          first_name: firstName,
          last_name: lastName,
          email: fields.email.input.value,
          subject: 'Program Inquiry: ' + fields.program.input.value,
          message: 'Interested in: ' + fields.program.input.value
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
      const response = await fetch(apiUrl('/contact'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({
          first_name: form.querySelector('[name="first_name"]').value,
          last_name: form.querySelector('[name="last_name"]').value,
          email: form.querySelector('[name="email"]').value,
          subject: form.querySelector('[name="subject"]').value,
          message: form.querySelector('[name="message"]').value
        })
      });

      if (!response.ok) throw new Error('Failed to send message');

      form.reset();
      inputs.forEach(inp => inp.classList.remove('error'));
      setContactNotice('success', "Thank you. We'll respond to your inquiry within 1-2 business days.");
      setSubmitting(false);
      return;

      // Replace with success state
      form.innerHTML = `
        <div class="text-center py-6">
          <div class="success-icon mx-auto mb-4" style="background:var(--sage);width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;">
            <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
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

console.log('%c BTC Admissions Landing Page loaded ✓', 'color:#254d82;font-weight:bold;font-size:1rem;');

/* =========================================================
   HERO ROTATING STUDENT SLIDER — ADD TO home-page.js
   Paste this block anywhere after your existing code.
   ========================================================= */

(function initHeroSlider() {
  const stage = document.getElementById('hero-img-stage');
  const progressBar = document.getElementById('hero-progress-bar');
  const programName = document.getElementById('hero-program-name');
  const badgeFloat = document.getElementById('hero-badge-float');
  const badgeIcon = document.getElementById('hero-badge-icon');
  const badgeTitle = document.getElementById('hero-badge-title');
  const dots = document.querySelectorAll('.hero-slider-dot');

  if (!stage) return; // guard — hero not on page

  const slides = Array.from(stage.querySelectorAll('.hero-img-slide'));
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

    // Update program label
    const program = next.dataset.program || '';
    const icon = next.dataset.icon || '🎓';
    const dept = next.dataset.dept || '';

    // Fade out then update text
    if (programName) {
      programName.style.opacity = '0';
      setTimeout(() => {
        programName.textContent = program;
        programName.style.opacity = '1';
      }, 200);
    }

    if (badgeIcon) badgeIcon.textContent = icon;
    if (badgeTitle) {
      badgeTitle.style.opacity = '0';
      setTimeout(() => {
        badgeTitle.textContent = dept;
        badgeTitle.style.opacity = '1';
      }, 200);
    }

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

  // Set initial badge content from first slide
  const firstSlide = slides[0];
  if (badgeIcon) badgeIcon.textContent = firstSlide.dataset.icon || '🍳';
  if (badgeTitle) badgeTitle.textContent = firstSlide.dataset.dept || 'Hospitality';
  if (programName) programName.textContent = firstSlide.dataset.program || 'Hospitality Management';

  // Small delay before autoplay starts (let page load)
  setTimeout(startAutoplay, 800);

  console.log('%c Hero slider initialized ✓', 'color:#c9933a;font-weight:bold;');
})();

// ─────────────────────────────────────────
// ADMISSION GUIDE MODAL
// ─────────────────────────────────────────

(function initGuideModal() {
  const openBtn = document.getElementById('open-guide');
  const closeBtn = document.getElementById('close-guide');
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
