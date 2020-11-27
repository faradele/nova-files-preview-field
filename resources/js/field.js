Nova.booting((Vue, router, store) => {
    Vue.component('index-files', require('./components/IndexField'))
    Vue.component('detail-files', require('./components/DetailField'))
    Vue.component('form-files', require('./components/FormField'))
})
