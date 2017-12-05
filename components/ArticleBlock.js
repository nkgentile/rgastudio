'use strict';

Vue.component('article-block', {
  template: `
    <article>
      <header>
        <slot name="header"></slot>
      </header>
      <main class="container">
        <slot></slot>
      </main>
      <footer>
        <slot name="footer"></slot>
      </footer>
    </article>
  `
});
