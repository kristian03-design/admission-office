(function () {
  const ICONSAX_STYLE = 'linear';

  const iconMap = {
    'alert-circle': 'danger',
    'alert-triangle': 'warning-2',
    'arrow-left': 'arrow-left',
    'arrow-right': 'arrow-right',
    'arrow-up': 'arrow-up',
    'badge-percent': 'discount-shape',
    'bar-chart-3': 'chart-2',
    bell: 'notification',
    'bell-off': 'notification-bing',
    'book-a': 'book',
    'book-open': 'book-1',
    briefcase: 'briefcase',
    'building-2': 'building',
    calendar: 'calendar',
    'calendar-days': 'calendar-2',
    'calendar-range': 'calendar-search',
    check: 'tick',
    'check-circle': 'tick-circle',
    'check-circle-2': 'tick-circle',
    'chevron-down': 'arrow-down-1',
    'chevron-left': 'arrow-left-2',
    'chevron-right': 'arrow-right-3',
    clock: 'clock',
    'circle-check': 'tick-circle',
    'clipboard-list': 'clipboard-text',
    code: 'code',
    database: 'document-code',
    download: 'document-download',
    eye: 'eye',
    'eye-off': 'eye-slash',
    'file-text': 'document-text',
    'flask-conical': 'glass',
    globe: 'global',
    'graduation-cap': 'teacher',
    handshake: 'like-shapes',
    image: 'gallery',
    instagram: 'instagram',
    'key-round': 'key',
    layout: 'category',
    'layout-grid': 'category',
    loader: 'refresh',
    'loader-circle': 'refresh-circle',
    lock: 'lock',
    'log-out': 'logout',
    mail: 'sms',
    'mail-check': 'sms-tick',
    map: 'map',
    'map-pin': 'location',
    megaphone: 'volume-high',
    menu: 'menu',
    messages: 'messages',
    'messages-square': 'messages-2',
    monitor: 'monitor',
    network: 'hierarchy',
    newspaper: 'note-2',
    party: 'happyemoji',
    'party-popper': 'happyemoji',
    phone: 'call',
    plus: 'add',
    presentation: 'presention-chart',
    printer: 'printer',
    'refresh-cw': 'refresh',
    'rotate-ccw': 'undo',
    save: 'document-upload',
    search: 'search-normal',
    send: 'send-2',
    settings: 'setting-2',
    shield: 'shield',
    'shield-check': 'shield-tick',
    smartphone: 'mobile',
    sparkles: 'magic-star',
    star: 'star',
    'sun-moon': 'sun-1',
    target: 'gps',
    'trash-2': 'trash',
    trending: 'trend-up',
    'trending-up': 'trend-up',
    twitter: 'x',
    'upload-cloud': 'cloud-add',
    user: 'user',
    'user-plus': 'user-add',
    users: 'people',
    'users-round': 'profile-2user',
    x: 'close-circle',
    'zoom-in': 'search-zoom-in'
  };

  function toIconsaxName(name) {
    if (!name) return `${ICONSAX_STYLE}-element-3`;
    const normalized = String(name).trim().toLowerCase();
    const mapped = iconMap[normalized] || normalized;
    return `iconsax:${ICONSAX_STYLE}-${mapped}`;
  }

  function copyAttributes(from, to) {
    Array.from(from.attributes).forEach((attr) => {
      if (attr.name === 'data-lucide' || attr.name === 'data-icon') return;
      to.setAttribute(attr.name, attr.value);
    });
  }

  function renderIcon(node) {
    const lucideName = node.getAttribute('data-lucide');
    const iconsaxName = node.getAttribute('data-iconsax');
    const iconName = iconsaxName
      ? (iconsaxName.includes(':') ? iconsaxName : `iconsax:${ICONSAX_STYLE}-${iconsaxName}`)
      : toIconsaxName(lucideName);

    const span = document.createElement('span');
    copyAttributes(node, span);
    span.classList.add('iconsax-icon');
    span.setAttribute('data-icon', iconName);
    span.setAttribute('aria-hidden', node.getAttribute('aria-hidden') || 'true');

    if (!span.style.width && node.style.width) span.style.width = node.style.width;
    if (!span.style.height && node.style.height) span.style.height = node.style.height;

    node.replaceWith(span);
  }

  function createIcons() {
    document.querySelectorAll('[data-lucide], [data-iconsax]').forEach(renderIcon);

    if (window.Iconify && typeof window.Iconify.scan === 'function') {
      window.Iconify.scan();
    }
  }

  window.iconsax = { createIcons };
  window.lucide = window.lucide || { createIcons };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', createIcons);
  } else {
    createIcons();
  }
})();
