(function () {
  const popup = document.getElementById('announcementPopup');
  if (!popup) return;

  const card = document.getElementById('popupCard');
  const checkbox = document.getElementById('dontShowAgain');
  const closeBtn = popup.querySelector('.announcement-popup__close');
  const annId = popup.getAttribute('data-id');
  let lastFocusedElement = null;

  function setIcons() {
    if (typeof window.iconsax?.createIcons === 'function') {
      window.iconsax.createIcons();
    }
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
      // Mark that the user has interacted with the announcement call-to-action
      if (annId) {
        localStorage.setItem('dont_show_announcement_' + annId, 'true');
      }

      try {
        const currentUrl = new URL(window.location.href);
        const targetUrl = new URL(link.href, window.location.href);

        // If already on the same page, close the popup smoothly without a hard browser reload
        if (currentUrl.pathname === targetUrl.pathname && currentUrl.search === targetUrl.search) {
          event.preventDefault();
          closePopup();
        }
      } catch (e) {
        // Fallback
        closePopup();
      }
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && popup.classList.contains('is-open')) {
      closePopup();
    }
  });

  window.addEventListener('DOMContentLoaded', () => {
    window.setTimeout(openPopup, 900);
  });
})();
