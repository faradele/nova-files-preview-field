<template>
    <div class="flex border-b border-40">
    <div class="w-1/4 py-4">
      <slot>
        <h4 class="font-normal text-80">{{ field.name }}</h4>
      </slot>
    </div>
    <div class="w-3/4 py-4 break-words">
      <slot name="value">
        <p v-if="field.value.length" class="text-90">
          <img
            loading="lazy"
            v-for="(image, index) in field.value"
            :key="'_fm_' + image.id"
            :src="field.pathPrefix + image.path"
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

    mounted() {
        // console.log(this.field)
    }
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
