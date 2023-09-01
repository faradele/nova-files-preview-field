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
                .map((entry, index) => ({
                    // * Lightbox can take an array of image urls or an object with the 'src' and 'title' keys
                    src: entry.path_url && entry.path_url || this.field.pathPrefix + entry.path,
                    title: `Media #${entry.id}`,
                    alt: `Media #${entry.id}`,
                    media_id: entry.id || null,
                    attachable_id: entry.attachable_id || null,
                    attachable_type: entry.attachable_type || null,
                }))
                // .map(entry => entry.path_url && entry.path_url || this.field.pathPrefix + entry.path)

            this.lightboxVisible = true

            this.logImageViewToServer(this.images[index])
        },

        hideLightBox() {
            this.images = []
            this.currentImageIndex = 0
            this.attachableId = null
            this.lightboxVisible = false
        },

        handleLightboxIndexChange(oldIndex, newIndex) {
            this.logImageViewToServer(this.images[newIndex])
        },

        async logImageViewToServer(imageData) {
            if (this.field && this.field.logViewHistory === true) {
                try {
                    await Nova.request().post("/nova-vendor/nova-files-preview-field/log-view", {
                        ...imageData,
                        viewed_at: new Date().toISOString()
                    });
                } catch (e) {
                    console.log(e.response || e);
                    Nova.error("Failed to log image view.")
                }
            }
        }
    },
}
