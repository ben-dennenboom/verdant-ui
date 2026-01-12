/**
 * GRID STORE
 * Shared Alpine store for Verdant Grid state
 */
document.addEventListener('alpine:init', () => {
  Alpine.store('grids', {
    states: {},

    /**
     * Returns the state object for a given gridId.
     * Initializes it if it doesn't exist yet.
     * @param {string} gridId
     */
    get(gridId) {
      if (!this.states[gridId]) {
        this.states[gridId] = {
          hasScroll: false,
          isAtStart: true,
          isAtEnd: false,
          tileView: false
        };
      }
      return this.states[gridId];
    },

    /**
     * Updates the state for a given gridId with the provided object.
     * @param {string} gridId
     * @param {Object} state
     */
    update(gridId, state) {
      if (!this.states[gridId]) {
        this.states[gridId] = {};
      }
      Object.assign(this.states[gridId], state);
    }
  });
});

