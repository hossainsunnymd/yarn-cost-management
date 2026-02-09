import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '../css/app.css'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue')
    return pages[`./Pages/${name}.vue`]()
  },

  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })

    app.use(plugin)

    // Lazy-load EasyDataTable only when used
    import('vue3-easy-data-table').then(module => {
      app.component('EasyDataTable', module.default)
      import('vue3-easy-data-table/dist/style.css')
    })

    app.mount(el)
  },
})
