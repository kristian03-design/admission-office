(function () {
  const popup = document.getElementById('announcementPopup');
  if (!popup) return;

  const card = document.getElementById('popupCard');
  const checkbox = document.getElementById('dontShowAgain');
  const annId = popup.getAttribute('data-id');
  let lastFocusedElement = null;

  const openClasses = ['opacity-100', 'visible', 'pointer-events-auto'];
  const closedClasses = ['opacity-0', 'invisible', 'pointer-events-none'];
  const cardOpenClasses = ['opacity-100', 'scale-100', 'translate-y-0'];
  const cardClosedClasses = ['opacity-0', 'scale-[0.96]', 'translate-y-5'];

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

    popup.classList.remove(...closedClasses);
    popup.classList.add(...openClasses);
    card?.classList.remove(...cardClosedClasses);
    card?.classList.add(...cardOpenClasses);

    requestAnimationFrame(() => {
      card?.focus();
      setIcons();
    });
  }

  function closePopup() {
    if (checkbox?.checked && annId) {
      localStorage.setItem('dont_show_announcement_' + annId, 'true');
    }

    popup.classList.remove(...openClasses);
    popup.classList.add(...closedClasses);
    card?.classList.remove(...cardOpenClasses);
    card?.classList.add(...cardClosedClasses);

    document.body.classList.remove('overflow-hidden');

    window.setTimeout(() => {
      popup.hidden = true;
      popup.setAttribute('aria-hidden', 'true');
      if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
        lastFocusedElement.focus();
      }
    }, 280);
  }

  window.closeAnnouncementPopup = closePopup;

  popup.addEventListener('click', (event) => {
    if (event.target === popup) {
      closePopup();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !popup.hidden && popup.classList.contains('opacity-100')) {
      closePopup();
    }
  });

  window.addEventListener('DOMContentLoaded', () => {
    if (annId && localStorage.getItem('dont_show_announcement_' + annId) === 'true') {
      return;
    }
    window.setTimeout(openPopup, 900);
  });
})();
