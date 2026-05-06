/**
 * Gorurhaat Shop Page — Filter interactions
 * - Dual-handle price range slider
 * - Mobile sidebar drawer (open / close / overlay)
 * - Filter section accordion (toggle open/closed)
 */
(function () {
  'use strict';

  /* =========================================================
     Utility helpers
     ========================================================= */

  function qs(sel, ctx) { return (ctx || document).querySelector(sel); }
  function qsa(sel, ctx) { return (ctx || document).querySelectorAll(sel); }

  function formatBDT(n) {
    return '৳' + Number(n).toLocaleString('en-IN');
  }

  /* =========================================================
     Price Range Slider
     ========================================================= */

  function initPriceRange() {
    var wrap    = qs('#sfRangeWrap');
    if (!wrap) return;

    var lowIn   = qs('#sfRangeMinInput');
    var highIn  = qs('#sfRangeMaxInput');
    var fill    = qs('#sfRangeFill');
    var minDisp = qs('#sfPriceMin');
    var maxDisp = qs('#sfPriceMax');

    if (!lowIn || !highIn || !fill) return;

    var globalMin = parseInt(wrap.dataset.min, 10) || 0;
    var globalMax = parseInt(wrap.dataset.max, 10) || 500000;

    function updateSlider() {
      var lo = parseInt(lowIn.value, 10);
      var hi = parseInt(highIn.value, 10);

      // Keep low ≤ high
      if (lo > hi) {
        if (this === lowIn)  { lo = hi; lowIn.value  = lo; }
        else                 { hi = lo; highIn.value = hi; }
      }

      var pctLo = ((lo - globalMin) / (globalMax - globalMin)) * 100;
      var pctHi = ((hi - globalMin) / (globalMax - globalMin)) * 100;

      fill.style.left  = pctLo + '%';
      fill.style.width = (pctHi - pctLo) + '%';

      if (minDisp) minDisp.textContent = formatBDT(lo);
      if (maxDisp) maxDisp.textContent = formatBDT(hi);
    }

    lowIn.addEventListener('input', updateSlider);
    highIn.addEventListener('input', updateSlider);

    // Initialise fill on page load
    updateSlider.call(lowIn);
  }

  /* =========================================================
     Sidebar accordion sections
     ========================================================= */

  function initAccordion() {
    qsa('.sf-toggle').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var expanded = btn.getAttribute('aria-expanded') === 'true';
        var bodyId   = btn.getAttribute('aria-controls');
        var body     = bodyId ? qs('#' + bodyId) : null;

        if (!body) return;

        btn.setAttribute('aria-expanded', !expanded);
        body.classList.toggle('is-closed', expanded);
      });
    });
  }

  /* =========================================================
     Mobile sidebar drawer
     ========================================================= */

  function initSidebarDrawer() {
    var toggleBtn  = qs('#shopFilterToggle');
    var sidebar    = qs('#shopSidebar');
    var closeBtn   = qs('#sidebarClose');
    var overlay    = qs('#sidebarOverlay');

    if (!sidebar) return;

    function openSidebar() {
      sidebar.classList.add('is-open');
      if (overlay)    { overlay.classList.add('active'); overlay.removeAttribute('aria-hidden'); }
      if (toggleBtn)  { toggleBtn.setAttribute('aria-expanded', 'true'); }
      document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
      sidebar.classList.remove('is-open');
      if (overlay)    { overlay.classList.remove('active'); overlay.setAttribute('aria-hidden', 'true'); }
      if (toggleBtn)  { toggleBtn.setAttribute('aria-expanded', 'false'); }
      document.body.style.overflow = '';
    }

    if (toggleBtn) { toggleBtn.addEventListener('click', openSidebar); }
    if (closeBtn)  { closeBtn.addEventListener('click',  closeSidebar); }
    if (overlay)   { overlay.addEventListener('click',   closeSidebar); }

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && sidebar.classList.contains('is-open')) {
        closeSidebar();
        if (toggleBtn) toggleBtn.focus();
      }
    });
  }

  /* =========================================================
     Active radio highlight on click
     ========================================================= */

  function initRadioHighlight() {
    qsa('.sf-radio input[type="radio"]').forEach(function (radio) {
      radio.addEventListener('change', function () {
        var group = radio.getAttribute('name');
        qsa('.sf-radio input[name="' + group + '"]').forEach(function (r) {
          r.closest('.sf-radio').classList.toggle('is-active', r.checked);
        });
      });
    });
  }

  /* =========================================================
     Auto-submit form when a radio is changed
     (skips submit button click — feels instant)
     ========================================================= */

  function initAutoSubmit() {
    var form = qs('#shopFilterForm');
    if (!form) return;

    qsa('.sf-radio input[type="radio"]', form).forEach(function (radio) {
      radio.addEventListener('change', function () {
        form.submit();
      });
    });

    var stockToggle = qs('#sfInStock', form);
    if (stockToggle) {
      stockToggle.addEventListener('change', function () {
        form.submit();
      });
    }
  }

  /* =========================================================
     Init
     ========================================================= */

  function init() {
    initPriceRange();
    initAccordion();
    initSidebarDrawer();
    initRadioHighlight();
    initAutoSubmit();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
