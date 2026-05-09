(function () {
  const ICONSAX_STYLE = 'linear';
  const ICONSAX_SPRITE_URL = 'https://cdn.jsdelivr.net/npm/web-elements-icons@0.1.3/iconsax-sprite.svg';
  let spritePromise = null;

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

  function ensureSprite() {
    if (document.getElementById('iconsax-inline-sprite')) {
      return Promise.resolve();
    }

    if (!spritePromise) {
      spritePromise = fetch(ICONSAX_SPRITE_URL)
        .then((response) => {
          if (!response.ok) throw new Error('Unable to load Iconsax sprite');
          return response.text();
        })
        .then((svgText) => {
          if (document.getElementById('iconsax-inline-sprite')) return;

          const holder = document.createElement('div');
          holder.id = 'iconsax-inline-sprite';
          holder.style.cssText = 'position:absolute;width:0;height:0;overflow:hidden;visibility:hidden;';
          holder.innerHTML = svgText;
          document.body.prepend(holder);
        })
        .catch(() => {
          spritePromise = null;
        });
    }

    return spritePromise;
  }

  function toIconsaxName(name) {
    if (!name) return `${ICONSAX_STYLE}-element-3`;
    const normalized = String(name).trim().toLowerCase();
    const mapped = iconMap[normalized] || normalized;
    return `${ICONSAX_STYLE}-${mapped}`;
  }

  function copyAttributes(from, to) {
    Array.from(from.attributes).forEach((attr) => {
      if (attr.name === 'data-lucide' || attr.name === 'data-iconsax') return;
      to.setAttribute(attr.name, attr.value);
    });
  }

  function renderIcon(node) {
    const lucideName = node.getAttribute('data-lucide');
    const iconsaxName = node.getAttribute('data-iconsax');
    const symbolId = iconsaxName
      ? (iconsaxName.startsWith(`${ICONSAX_STYLE}-`) ? iconsaxName : `${ICONSAX_STYLE}-${iconsaxName}`)
      : toIconsaxName(lucideName);

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    const use = document.createElementNS('http://www.w3.org/2000/svg', 'use');
    copyAttributes(node, svg);
    svg.classList.add('iconsax-icon');
    svg.setAttribute('aria-hidden', node.getAttribute('aria-hidden') || 'true');
    svg.setAttribute('focusable', 'false');
    svg.setAttribute('viewBox', '0 0 24 24');
    use.setAttribute('href', `#${symbolId}`);
    use.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', `#${symbolId}`);
    svg.appendChild(use);

    if (!svg.style.width && node.style.width) svg.style.width = node.style.width;
    if (!svg.style.height && node.style.height) svg.style.height = node.style.height;

    node.replaceWith(svg);
  }

  function createIcons() {
    ensureSprite().finally(() => {
      document.querySelectorAll('[data-lucide], [data-iconsax]').forEach(renderIcon);
    });
  }

  window.iconsax = { createIcons };
  window.lucide = window.lucide || { createIcons };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', createIcons);
  } else {
    createIcons();
  }
})();
