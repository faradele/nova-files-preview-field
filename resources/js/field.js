import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
    app.component('index-files', IndexField)
    app.component('detail-files', DetailField)
    app.component('form-files', FormField)
})
