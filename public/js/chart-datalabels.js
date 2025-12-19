// Chart.js DataLabels Plugin Registration
// This file ensures the plugin is registered when Chart.js is available

(function () {
    'use strict';

    // Function to register the plugin
    function registerDataLabels() {
        if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
            // Check if already registered
            if (!Chart.defaults.plugins.datalabels) {
                Chart.register(ChartDataLabels);
                console.log('ChartDataLabels plugin registered successfully');
            }
            return true;
        }
        return false;
    }

    // Try immediately
    if (!registerDataLabels()) {
        // Set up a MutationObserver to watch for chart canvas elements
        const observer = new MutationObserver(function (mutations) {
            if (registerDataLabels()) {
                observer.disconnect();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        // Also try periodically as a fallback
        let attempts = 0;
        const maxAttempts = 50;
        const interval = setInterval(function () {
            attempts++;
            if (registerDataLabels() || attempts >= maxAttempts) {
                clearInterval(interval);
            }
        }, 100);
    }
})();
