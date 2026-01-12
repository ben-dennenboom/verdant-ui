
/**
 * GRID COMPONENT LOGIC
 * Manages the state and behavior of the Verdant Grid, including view toggling (Table vs. Tile),
 * column visibility management, and responsive overflow detection.
 */
window.gridComponent = function (config) {
  const id = (config.title || 'grid').replace(/\s+/g, '_').toLowerCase();
  const storageKey = `verdant_grid_hidden_${id}`;

  return {
    id: id,
    hasScroll: false,
    tileView: config.initialTileView,
    hiddenColumns: JSON.parse(localStorage.getItem(storageKey)) || config.hiddenColumns || [],
    totalColumns: config.totalColumns || 4,
    mobileBreakpoint: 768,

    /**
     * Component Lifecycle Initialization
     */
    init() {
      this.handleResize();

      window.addEventListener('resize', () => {
        this.handleResize();
      });

      this.$watch('tileView', () => {
        this.$nextTick(() => this.checkScroll());
      });

      this.$nextTick(() => {
        this.checkScroll();
      });
    },

    /**
     * Handles automatic switching to Tile View on small screens
     */
    handleResize() {
      this.tileView = window.innerWidth <= this.mobileBreakpoint;
      this.$nextTick(() => this.checkScroll());
    },

    /**
     * Manually toggles between Table and Tile view modes
     */
    toggleView() {
      this.tileView = !this.tileView;
      this.$nextTick(() => {
        this.checkScroll();
      });
    },

    /**
     * Detects if the Table View content is wider than its container
     * Used to show/hide the "Scroll for more" indicator
     */
    checkScroll() {
      if (this.tileView) {
        this.hasScroll = false;
        return;
      }
      this.$nextTick(() => {
        const wrapper = this.$refs.gridWrapper;
        if (wrapper) {
          setTimeout(() => {
            this.hasScroll = wrapper.scrollWidth > wrapper.clientWidth;
          }, 10);
        }
      });
    },

    /**
     * Checks if a specific column is currently visible
     * @param {string} columnId
     * @returns {boolean}
     */
    isColumnVisible(columnId) {
      return !this.hiddenColumns.includes(columnId);
    },

    /**
     * Toggles the visibility of a column and persists the choice to LocalStorage
     * @param {string} columnId
     */
    toggleColumn(columnId) {
      if (this.hiddenColumns.includes(columnId)) {
        this.hiddenColumns = this.hiddenColumns.filter(id => id !== columnId);
      } else {
        this.hiddenColumns.push(columnId);
      }

      localStorage.setItem(storageKey, JSON.stringify(this.hiddenColumns));

      this.$nextTick(() => {
        this.checkScroll();
      });
    },

    /**
     * Resets all hidden columns and clears browser storage
     */
    resetColumns() {
      this.hiddenColumns = [];
      localStorage.removeItem(storageKey);
      this.$nextTick(() => this.checkScroll());
    },

    /**
     * Dynamically generates the CSS Grid Template Columns string for Table View.
     * It accounts for visible columns and their respective data-spans.
     * @returns {string} Inline style string
     */
    getGridStyle() {
      if (this.tileView) {
        return '';
      }

      const grid = this.$refs.gridLayout;
      if (!grid) {
        return `grid-template-columns: repeat(${this.totalColumns}, minmax(0, 1fr)); width: max-content; min-width: 100%;`;
      }

      const headers = grid.querySelectorAll('.v-grid-header-wrapper > [data-column-id]');
      let totalSpan = 0;

      headers.forEach(header => {
        const columnId = header.getAttribute('data-column-id');

        if (this.isColumnVisible(columnId)) {
          const span = parseInt(header.getAttribute('data-span')) || 1;
          totalSpan += span;
        }
      });

      if (totalSpan === 0) {
        totalSpan = this.totalColumns;
      }

      return `grid-template-columns: repeat(${totalSpan}, minmax(0, 1fr)); width: max-content; min-width: 100%;`;
    },

    /**
     * Programmatically scrolls the grid to the far right end.
     * It forces the necessary CSS properties to ensure the scroll action is 
     */
    scrollToEnd() {
      const container = this.$refs.gridWrapper;
      const content = this.$refs.gridLayout;

      if (container && content) {
        container.style.overflowX = 'scroll';
        container.style.display = 'block';

        content.style.width = 'max-content';
        content.style.display = 'grid';

        this.$nextTick(() => {
          const targetScroll = container.scrollWidth - container.clientWidth;

          container.scrollLeft = targetScroll;

          setTimeout(() => {
            if (container.scrollLeft === 0) {
              const parent = container.parentElement;
              parent.scrollLeft = targetScroll;
            }
          }, 50);
        });
      }
    }
  }
}
