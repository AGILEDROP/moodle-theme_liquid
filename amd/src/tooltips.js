// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Enhanced tooltip functionality for theme Liquid.
 * - Smart positioning (auto-adjusts based on available space)
 * - Hover-only trigger (no focus or click triggers)
 * - Dark mode support
 *
 * @module     theme_liquid/tooltips
 * @copyright  2025 Agiledrop ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import * as Bootstrap from 'theme_boost/index';

/**
 * Initialize all tooltips with enhanced configuration
 */
const initializeTooltips = () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');

    // Enhanced tooltip configuration
    const tooltipConfig = {
        placement: 'auto', // Auto-position based on available space
        trigger: 'hover', // Only hover - no focus or click
        fallbackPlacements: ['top', 'right', 'bottom', 'left'], // Try all sides
        boundary: 'viewport', // Use viewport as boundary for positioning
        popperConfig: {
            strategy: 'fixed', // Better positioning for fixed/absolute elements
            modifiers: [
                {
                    name: 'flip',
                    options: {
                        fallbackPlacements: ['top', 'right', 'bottom', 'left'],
                        // Allow flipping to any side with space
                        allowedAutoPlacements: ['top', 'right', 'bottom', 'left'],
                    }
                },
                {
                    name: 'preventOverflow',
                    options: {
                        boundary: 'viewport',
                        padding: 8,
                        // Ensure tooltip stays within viewport
                        altAxis: true,
                        tether: false,
                    }
                }
            ]
        }
    };

    // Store tooltip instances
    const tooltipInstances = new Map();

    // Initialize tooltips with enhanced config
    [...tooltipTriggerList].forEach(tooltipTriggerEl => {
        // Destroy existing tooltip if it exists (to reconfigure)
        const existingTooltip = Bootstrap.Tooltip.getInstance(tooltipTriggerEl);
        if (existingTooltip) {
            existingTooltip.dispose();
        }

        const tooltip = new Bootstrap.Tooltip(tooltipTriggerEl, tooltipConfig);
        tooltipInstances.set(tooltipTriggerEl, tooltip);
    });

    // Hide all tooltips on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            tooltipInstances.forEach(tooltip => {
                tooltip.hide();
            });
        }
    });

    // Handle dynamically added tooltips (for AJAX content)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) { // Element node
                    const newTooltips = node.querySelectorAll('[data-bs-toggle="tooltip"]');
                    newTooltips.forEach((tooltipEl) => {
                        if (!tooltipInstances.has(tooltipEl)) {
                            const tooltip = new Bootstrap.Tooltip(tooltipEl, tooltipConfig);
                            tooltipInstances.set(tooltipEl, tooltip);
                        }
                    });
                }
            });
        });
    });

    // Observe the entire document for dynamic content
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
};

export const init = () => {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeTooltips);
    } else {
        initializeTooltips();
    }
};
