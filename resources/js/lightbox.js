import VueEasyLightbox from 'vue-easy-lightbox'

export default {
    components: {
        VueEasyLightbox
    },

    data() {
        return {
            lightboxVisible: false,
            currentImageIndex: 0,
            attachableId: null,
            images: []
        }
    },

    methods: {
        showLightbox(attachable_id, index) {
            this.attachableId = attachable_id
            this.currentImageIndex = index
            this.images = this.field.value
                .filter(entry => entry.attachable_id == this.attachableId)
                .map(entry => this.field.pathPrefix + entry.path)

            this.lightboxVisible = true
        },

        hideLightBox() {
            this.images = []
            this.currentImageIndex = 0
            this.attachableId = null
            this.lightboxVisible = false
        }
    },
}
