<template>
    <div class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0 border-t border-gray-100 dark:border-gray-700">
    <div class="md:w-1/4 md:py-3">
      <slot>
        <h4 class="font-normal text-80">{{ field.name }}</h4>
      </slot>
    </div>
    <div class="md:w-3/4 md:py-3 break-all lg:break-words">
      <slot name="value">
        <p v-if="field.value.length" class="text-90 flex flex-row">
            <div style="margin-left: 0.5rem;"
                v-for="(image, index) in field.value"
                :key="'_fm_' + image.id"
            >
                <template v-if="field.maskUnopenedImages">
                    <img
                        loading="lazy"
                        :src="field.blankImageURL"
                        class="align-bottom inline-block w-full"
                        alt="Blank placeholder images"
                        @click="showLightbox(image.attachable_id, index)"
                    />
                </template>
                <template v-if="! field.maskUnopenedImages">
                    <img
                        loading="lazy"
                        :src="image.path_url && image.path_url || field.pathPrefix + image.path"
                        class="align-bottom inline-block w-full"
                        :alt="image.id"
                        @click="showLightbox(image.attachable_id, index)"
                    />
                </template>
                <div style="margin-top: 5px;">{{ (image && image.label) ? image.label : "Media #" + image.id }}</div>
            </div>
        </p>
        <p v-else>&mdash;</p>
      </slot>
    </div>

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
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    mixins: [lightboxImpl],
}
</script>

<style scoped>
    img {
        width: 7rem;
        height: 7rem;
        margin-top: 10px;
        padding-left: 5px;
        object-fit: cover;
    }
</style>
