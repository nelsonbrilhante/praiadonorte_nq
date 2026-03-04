/**
 * Praia do Norte — Navigation JS
 * Mobile hamburger toggle + mobile dropdown expand/collapse.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // --- Mobile Hamburger Toggle ---
        var toggle = document.querySelector('.pn-mobile-toggle');
        var mobileMenu = document.querySelector('.pn-mobile-menu');
        var hamburgerIcon = document.querySelector('.pn-hamburger-icon');
        var closeIcon = document.querySelector('.pn-close-icon');

        if (toggle && mobileMenu) {
            toggle.addEventListener('click', function () {
                var isOpen = mobileMenu.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                mobileMenu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
                document.body.style.overflow = isOpen ? 'hidden' : '';

                if (hamburgerIcon && closeIcon) {
                    hamburgerIcon.style.display = isOpen ? 'none' : '';
                    closeIcon.style.display = isOpen ? '' : 'none';
                }
            });
        }

        // --- Mobile Section Expand/Collapse ---
        var sectionToggles = document.querySelectorAll('.pn-mobile-section-toggle');
        sectionToggles.forEach(function (btn) {
            btn.addEventListener('click', function () {
                var section = btn.closest('.pn-mobile-section');
                var items = section.querySelector('.pn-mobile-section-items');
                var isExpanded = section.classList.toggle('is-expanded');
                btn.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');

                if (items) {
                    items.style.maxHeight = isExpanded ? items.scrollHeight + 'px' : '0';
                }
            });
        });

        // --- Close mobile menu on Escape ---
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('is-open')) {
                mobileMenu.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                mobileMenu.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';

                if (hamburgerIcon && closeIcon) {
                    hamburgerIcon.style.display = '';
                    closeIcon.style.display = 'none';
                }
            }
        });
    });
})();
