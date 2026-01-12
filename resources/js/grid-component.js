
/**
 * GRID COMPONENT LOGIC
 * Manages the Verdant Grid: column visibility, tile/table view, responsive scroll
 */
window.gridComponent = function (config) {
  const id = (config.title || 'grid').replace(/\s+/g, '_').toLowerCase();
  const storageKey = `verdant_grid_hidden_${id}`;

  return {
    id: id,
    hiddenColumns: JSON.parse(localStorage.getItem(storageKey)) || config.hiddenColumns || [],
    totalColumns: config.totalColumns || 4,
    mobileBreakpoint: 768,

    // Getters and Setters
    get hasScroll() {
      return this.$store.grids.get(this.id).hasScroll;
    },
    get isAtStart() {
      return this.$store.grids.get(this.id).isAtStart;
    },
    get isAtEnd() {
      return this.$store.grids.get(this.id).isAtEnd;
    },
    get tileView() {
      return this.$store.grids.get(this.id).tileView;
    },
    set tileView(value) {
      this.$store.grids.update(this.id, { tileView: value });
    },

    /**
     * Component Lifecycle Initialization
     */
    init() {
      // Initialize store state
      this.$store.grids.update(this.id, {
        hasScroll: false,
        isAtStart: true,
        isAtEnd: false,
        tileView: config.initialTileView || false
      });

      this.handleResize();

      // Scroll event
      this.$nextTick(() => {
        const wrapper = this.$refs.gridWrapper;

        if (wrapper) {
          const scrollHandler = () => {
            this.updateScrollState(wrapper);
          };

          wrapper.addEventListener('scroll', scrollHandler, { passive: true });
          this.updateScrollState(wrapper);
        }
      });

      // Window resize
      window.addEventListener('resize', () => {
        this.handleResize();
      });

      // Watch tileview changes
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
     * Updates scroll state: hasScroll, isAtStart, isAtEnd
     */
    updateScrollState(wrapper) {
      const margin = 5;
      const state = {
        hasScroll: wrapper.scrollWidth > wrapper.clientWidth,
        isAtStart: wrapper.scrollLeft <= margin,
        isAtEnd: wrapper.scrollLeft + wrapper.clientWidth >= wrapper.scrollWidth - margin
      };

      this.$store.grids.update(this.id, state);
    },

    /**
     * Detects if the Table View content is wider than its container
     * Used to show/hide the "Scroll for more" indicator
     */
    checkScroll() {
      if (this.tileView) {
        this.$store.grids.update(this.id, {
          hasScroll: false,
          isAtStart: false,
          isAtEnd: false
        });
        return;
      }

      this.$nextTick(() => {
        const wrapper = this.$refs.gridWrapper;
        if (wrapper) {
          setTimeout(() => {
            this.updateScrollState(wrapper);
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
     * Programmatically scrolls the grid either to the start or the end.
     * Centralizes the CSS adjustments and fallback logic to avoid duplication.
     *
     * @param {'start'|'end'} direction - Scroll direction
     */
    scrollTo(direction = 'start') {
      const container = this.$refs.gridWrapper;
      const content = this.$refs.gridLayout;

      if (!container || !content) return;

      // Ensure scrollable CSS
      container.style.overflowX = 'scroll';
      container.style.display = 'block';

      content.style.width = 'max-content';
      content.style.display = 'grid';

      this.$nextTick(() => {
        const targetScroll = direction === 'end'
          ? container.scrollWidth - container.clientWidth
          : 0;

        container.scrollLeft = targetScroll;

        // Update scroll state na scrollen
        setTimeout(() => {
          this.updateScrollState(container);
        }, 50);

        // Fallback voor browsers die mogelijk niet direct reageren
        setTimeout(() => {
          this.updateScrollState(container);

          if (Math.abs(container.scrollLeft - targetScroll) > 5) {
            const parent = container.parentElement;
            if (parent) {
              parent.scrollLeft = targetScroll;
              this.updateScrollState(parent);
            }
          }
        }, 150);
      });
    },

    /**
     * Scroll to the far right of the grid
     */
    scrollToEnd() {
      this.scrollTo('end');
    },

    /**
     * Scroll to the far left of the grid
     */
    scrollToStart() {
      this.scrollTo('start');
    }
  }
}
