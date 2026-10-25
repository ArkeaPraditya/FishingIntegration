(function () {
  'use strict';

  var header = document.querySelector('.site-header');
  var toggle = document.querySelector('.nav-toggle');
  var menu = document.getElementById('menu');

  /* ---------- Header: transparan di atas hero, solid saat di-scroll ---------- */
  function onScroll() {
    header.classList.toggle('is-top', window.scrollY < 24);
  }
  window.addEventListener('scroll', onScroll, {
    passive: true
  });
  onScroll();

  /* ---------- Menu mobile ---------- */
  function setMenu(open) {
    header.classList.toggle('nav-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
  }

  toggle.addEventListener('click', function () {
    setMenu(!header.classList.contains('nav-open'));
  });

  menu.addEventListener('click', function (e) {
    if (e.target.closest('a')) setMenu(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && header.classList.contains('nav-open')) {
      setMenu(false);
      toggle.focus();
    }
  });

  window.matchMedia('(min-width: 861px)').addEventListener('change', function () {
    setMenu(false);
  });

  /* ---------- Penanda menu aktif ---------- */
  if ('IntersectionObserver' in window) {
    var links = [].slice.call(document.querySelectorAll('.nav-links a'));
    var byId = {};
    links.forEach(function (a) {
      byId[a.getAttribute('href').slice(1)] = a;
    });

    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        links.forEach(function (a) {
          a.removeAttribute('aria-current');
        });
        var active = byId[en.target.id];
        if (active) active.setAttribute('aria-current', 'location');
      });
    }, {
      rootMargin: '-40% 0px -55% 0px'
    });

    [].forEach.call(document.querySelectorAll('section[id]'), function (s) {
      spy.observe(s);
    });

  }

  /* ---------- Galeri: perbesar foto ---------- */
  var box = document.getElementById('lightbox');
  if (box && typeof box.showModal === 'function') {
    var boxImg = box.querySelector('img');

    [].forEach.call(document.querySelectorAll('.gallery-btn'), function (btn) {
      btn.addEventListener('click', function () {
        var img = btn.querySelector('img');
        boxImg.src = img.currentSrc || img.src;
        boxImg.alt = img.alt;
        box.showModal();
      });
    });

    box.querySelector('.lightbox-close').addEventListener('click', function () {
      box.close();
    });

    // klik di luar foto menutup
    box.addEventListener('click', function (e) {
      if (e.target === box) box.close();
    });
  }
})();