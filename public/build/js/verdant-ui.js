document.addEventListener('DOMContentLoaded', function () {
  if (window.Alpine) {
    initializeVerdant();
  } else {
    document.addEventListener('alpine:init', initializeVerdant);
  }
});

function initializeVerdant() {
  console.log('Verdant - Alpine initialized');

  window.verdantPrefix = 'v-';

  window.vClass = function (classes) {
    if (!classes) return '';
    return classes.split(' ')
        .map(cls => cls.startsWith(window.verdantPrefix) ? cls : window.verdantPrefix + cls)
        .join(' ');
  };

  document.addEventListener('transitionend', function (e) {
    if (!e.target.parentNode) return;
  });

  if (window.Alpine) {
    Alpine.directive('vclass', (el, {expression}, {evaluateLater, effect}) => {
      const evaluate = evaluateLater(expression);

      effect(() => {
        evaluate(value => {
          if (!value) return;
          const classes = value.split(' ')
              .map(cls => cls.startsWith(window.verdantPrefix) ? cls : window.verdantPrefix + cls)
              .join(' ');
          el.setAttribute('class', classes);
        });
      });
    });
  }
}
