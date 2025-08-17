/**
 * Centralized Time Management for Livewire Components
 * Handles local time detection and updates for all components that use LocalTimeAware trait
 */

window.TimeManager = {
    /**
     * Initialize time management for all components.
     */
    init() {
        // Wait for Livewire to be ready
        if (window.Livewire) {
            this.setupListeners();
            this.updateAllComponents();
        } else {
            document.addEventListener('livewire:initialized', () => {
                this.setupListeners();
                this.updateAllComponents();
            });
        }
    },

    /**
     * Setup event listeners for time update requests.
     */
    setupListeners() {
        // Listen for requests to get local time
        Livewire.on('get-local-time', () => {
            this.updateAllComponents();
        });

        // Also listen for dashboard-specific event (for backward compatibility)
        Livewire.on('dashboard-get-local-time', () => {
            this.updateAllComponents();
        });
    },

    /**
     * Get the current local hour.
     */
    getLocalHour() {
        return new Date().getHours();
    },

    /**
     * Update all Livewire components with local time.
     */
    updateAllComponents() {
        const localHour = this.getLocalHour();
        console.log('TimeManager: Updating components with local hour:', localHour);

        // Emit a global event that all components can listen to
        Livewire.emit('localTimeUpdated', localHour);

        // Also call updateLocalHour on all components that have it
        Livewire.all().forEach(component => {
            if (typeof component.updateLocalHour === 'function') {
                component.call('updateLocalHour', localHour);
            }
        });
    },

    /**
     * Start periodic updates (every hour).
     */
    startPeriodicUpdates() {
        // Update every hour
        setInterval(() => {
            this.updateAllComponents();
        }, 3600000); // 1 hour in milliseconds
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        TimeManager.init();
        TimeManager.startPeriodicUpdates();
    });
} else {
    TimeManager.init();
    TimeManager.startPeriodicUpdates();
}
