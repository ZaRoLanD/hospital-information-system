/* ============================================================
   HIS — app shell behaviour
   - Theme toggle (light/dark) with localStorage persistence
   - Sidebar collapse (desktop) / off-canvas (mobile)
   - Active nav highlighting
   ============================================================ */
(function () {
  'use strict';

  /* ---------- Theme ---------- */
  var THEME_KEY = 'his-theme';

  function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
  }

  // Expose for the pre-paint inline script in header.php
  window.hisApplyTheme = applyTheme;

  function bindThemeToggle() {
    var btn = document.getElementById('themeToggle');
    if (!btn) return;
    btn.addEventListener('click', function () {
      var next =
        document.documentElement.getAttribute('data-theme') === 'dark'
          ? 'light'
          : 'dark';
      applyTheme(next);
      try { localStorage.setItem(THEME_KEY, next); } catch (e) { /* private mode */ }
    });
  }

  /* ---------- Sidebar ---------- */
  var SIDEBAR_KEY = 'his-sidebar';

  function isMobile() {
    return window.matchMedia('(max-width: 991.98px)').matches;
  }

  function bindSidebar() {
    var toggle = document.getElementById('sidebarToggle');
    var backdrop = document.getElementById('sidebarBackdrop');
    if (!toggle) return;

    toggle.addEventListener('click', function () {
      if (isMobile()) {
        document.body.classList.toggle('sidebar-open');
      } else {
        document.body.classList.toggle('sidebar-collapsed');
        try {
          localStorage.setItem(
            SIDEBAR_KEY,
            document.body.classList.contains('sidebar-collapsed') ? 'collapsed' : 'open'
          );
        } catch (e) { /* ignore */ }
      }
    });

    if (backdrop) {
      backdrop.addEventListener('click', function () {
        document.body.classList.remove('sidebar-open');
      });
    }

    // Mobile: close the drawer after navigating via a sidebar link
    document.querySelectorAll('.app-nav a').forEach(function (a) {
      a.addEventListener('click', function () {
        document.body.classList.remove('sidebar-open');
      });
    });
  }

  function restoreSidebar() {
    try {
      if (localStorage.getItem(SIDEBAR_KEY) === 'collapsed' && !isMobile()) {
        document.body.classList.add('sidebar-collapsed');
      }
    } catch (e) { /* ignore */ }
  }

  /* ---------- Active nav highlighting ---------- */
  function markActiveNav() {
    var page = (window.location.pathname.split('/').pop() || '').toLowerCase();
    if (!page) return;

    document.querySelectorAll('.app-nav a[href]').forEach(function (a) {
      var href = (a.getAttribute('href') || '').split('/').pop().toLowerCase();
      var hrefBase = href.split('?')[0];
      if (hrefBase && hrefBase === page) {
        a.classList.add('active');
        var group = a.closest('.nav-group');
        if (group) group.classList.add('open');
        var submenu = a.closest('.nav-submenu');
        if (submenu && group) {
          var toggle = group.querySelector('.nav-group-toggle');
          if (toggle) toggle.classList.add('active');
        }
      }
    });
  }

  /* ---------- Nav accordion ---------- */
  function bindAccordion() {
    document.querySelectorAll('.nav-group-toggle').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        btn.parentElement.classList.toggle('open');
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    bindThemeToggle();
    bindSidebar();
    restoreSidebar();
    markActiveNav();
    bindAccordion();
  });
})();
