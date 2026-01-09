
/**
 * Verdant UI - Grid Component Logic
 */
window.gridComponent = function (config) {
  return {
    hasScroll: false,
    tileView: config.initialTileView,
    mobileBreakpoint: 768,

    init() {
      this.handleResize();
      window.addEventListener('resize', () => {
        this.handleResize();
      });
    },

    handleResize() {
      this.tileView = window.innerWidth <= this.mobileBreakpoint;

      this.checkScroll();
    },

    toggleView() {
      this.tileView = !this.tileView;

      this.$nextTick(() => this.checkScroll());
    },

    checkScroll() {
      if (this.tileView) {
        this.hasScroll = false;
        return;
      }
      this.$nextTick(() => {
        const wrapper = this.$refs.gridWrapper;
        if (wrapper) {
          this.hasScroll = wrapper.scrollWidth > wrapper.clientWidth;
        }
      });
    }
  };
};
