<template>
    <div>
        <template v-if="field.value && field.value.length">
            <img v-for="(image, index) in field.value" :key="'_fm_' + image.id" :src="field.pathPrefix + image.path" class="align-bottom inline rounded-full" @click="showLightbox(image.attachable_id, index)" />
        </template>
        <span v-else>&mdash;</span>

        <vue-easy-lightbox
            moveDisabled
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
