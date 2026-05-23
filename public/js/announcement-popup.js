(function () {
  const popup = document.getElementById('announcementPopup');
  if (!popup) return;

  const card = document.getElementById('popupCard');
  const checkbox = document.getElementById('dontShowAgain');
  const closeBtn = popup.querySelector('.announcement-popup__close');
  const annId = popup.getAttribute('data-id');
  let lastFocusedElement = null;

  if (annId) {
    if (localStorage.getItem('dont_show_announcement_' + annId) === 'true') {
      return;
    }
    if (sessionStorage.getItem('dismissed_announcement_' + annId) === 'true') {
      return;
    }
  }

  function setIcons() {
    if (typeof window.iconsax?.createIcons === 'function') {
      window.iconsax.createIcons();
    }

    document
      .querySelectorAll(
        '.announcement-popup__feature-icon svg, .announcement-popup__badge svg, .announcement-popup__alert-icon svg'
      )
      .forEach((svg) => {
        svg.setAttribute('width', '24');
        svg.setAttribute('height', '24');
        svg.setAttribute('stroke-width', '2.2');
        svg.style.opacity = '1';
      });
  }

  function openPopup() {
    lastFocusedElement = document.activeElement;

    popup.hidden = false;
    popup.setAttribute('aria-hidden', 'false');
    document.body.classList.add('overflow-hidden');
    popup.classList.add('is-open');

    requestAnimationFrame(() => {
      card?.focus();
      setIcons();
    });
  }

  function closePopup() {
    if (annId) {
      sessionStorage.setItem('dismissed_announcement_' + annId, 'true');

      if (checkbox?.checked) {
        localStorage.setItem('dont_show_announcement_' + annId, 'true');
      }
    }

    popup.classList.remove('is-open');
    popup.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('overflow-hidden');

    window.setTimeout(() => {
      popup.hidden = true;

      if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
        lastFocusedElement.focus();
      }
    }, 280);
  }

  window.closeAnnouncementPopup = closePopup;

  closeBtn?.addEventListener('click', closePopup);

  popup.addEventListener('click', (event) => {
    if (event.target === popup) {
      closePopup();
    }
  });

  popup.querySelectorAll('[data-announcement-close-nav]').forEach((link) => {
    link.addEventListener('click', (event) => {
      if (annId) {
        localStorage.setItem('dont_show_announcement_' + annId, 'true');
      }

      try {
        const currentUrl = new URL(window.location.href);
        const targetUrl = new URL(link.href, window.location.href);

        if (
          currentUrl.pathname === targetUrl.pathname &&
          currentUrl.search === targetUrl.search
        ) {
          event.preventDefault();
          closePopup();
        }
      } catch (e) {
        closePopup();
      }
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && popup.classList.contains('is-open')) {
      closePopup();
    }
  });

  window.addEventListener('pageshow', () => {
    window.setTimeout(openPopup, 900);
  });
})();