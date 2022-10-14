<template>
    <div>
        <div class="flex flex-wrap">
            <template v-if="field.value && field.value.length">
                <img
                    loading="lazy"
                    v-for="(image, index) in field.value"
                    :key="'_fm_' + image.id"
                    :src="image.path_url && image.path_url || field.pathPrefix + image.path"
                    class="align-bottom inline rounded-full"
                    @click="showLightbox(image.attachable_id, index)"
                />
            </template>
        </div>
        <span v-if="! field.value || ! field.value.length">&mdash;</span>

        <!-- we can use #modals or just body as target elements for teleportation -->
        <vue-easy-lightbox
            moveDisabled
            teleport="#modals"
            :visible="lightboxVisible"
            :imgs="images"
            :index="currentImageIndex"
            @hide="hideLightBox"
        >
        </vue-easy-lightbox>
    </div>
</template>

<script>
import lightboxImpl from '../lightbox'

export default {
    props: ['resourceName', 'field'],

    mixins: [lightboxImpl],
}
</script>

<style scoped>
    img {
        width: 1.5rem;
        height: 1.5rem;
        padding-left: 3px;
        object-fit: cover;
        margin-top: 3px;
    }
</style>
