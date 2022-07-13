<template>
    <div class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0 border-t border-gray-100 dark:border-gray-700">
    <div class="md:w-1/4 md:py-3">
      <slot>
        <h4 class="font-normal text-80">{{ field.name }}</h4>
      </slot>
    </div>
    <div class="md:w-3/4 md:py-3 break-all lg:break-words">
      <slot name="value">
        <p v-if="field.value.length" class="text-90">
          <img
            loading="lazy"
            v-for="(image, index) in field.value"
            :key="'_fm_' + image.id"
            :src="image.path_url && image.path_url || field.pathPrefix + image.path"
            class="align-bottom inline-block w-full"
            @click="showLightbox(image.attachable_id, index)"
          />
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
