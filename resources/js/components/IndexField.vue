<template>
    <div>
        <div class="flex flex-wrap">
            <template v-if="field.value && field.value.length">
                <template v-if="field.maskUnopenedImages">
                    <img
                        loading="lazy"
                        v-for="(image, index) in field.value"
                        :key="'_fm_' + image.id"
                        :src="field.blankImageURL"
                        class="align-bottom inline rounded-full"
                        alt="Blank placeholder images"
                    />
                </template>
                <template v-if="! field.maskUnopenedImages">
                    <img
                        loading="lazy"
                        v-for="(image, index) in field.value"
                        :key="'_fm_' + image.id"
                        :src="image.path_url && image.path_url || field.pathPrefix + image.path"
                        class="align-bottom inline rounded-full"
                        @click="showLightbox(image.attachable_id, index)"
                    />
                </template>
            </template>
        </div>
        <span v-if="! field.value || ! field.value.length">&mdash;</span>

        <!-- we can use #modals or just body as target elements for teleportation -->
        <!-- UPDATE: do not use the teleport="#xyz" option. It was the source of a long-standing bug I just got fixed -->

        <vue-easy-lightbox
            moveDisabled
            :visible="lightboxVisible"
            :imgs="images"
            :index="currentImageIndex"
            @hide="hideLightBox"
            @on-index-change="handleLightboxIndexChange"
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
