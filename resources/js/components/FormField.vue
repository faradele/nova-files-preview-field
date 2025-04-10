<template>
    <div class="field-wrapper flex flex-col border-b border-gray-100 dark:border-gray-700 md:flex-row">
    <div class="px-6 md:px-8 mt-2 md:mt-0 w-full md:w-1/5 md:py-5">
      <slot>
        <h4 class="font-normal text-80">{{ field.name }}</h4>
      </slot>
    </div>
    <div class="mt-1 md:mt-0 pb-5 px-6 md:px-8 md:w-4/5 w-full md:w-3/5 md:py-5">
      <slot name="value">
        <vapor-form
            v-if="field.vapor"
            :field="field"
            :isReadonly="isReadonly"
            :idAttr="idAttr"
            :currentLabel="currentLabel"
            :labelFor="labelFor"
            :stillUploadingFiles="stillUploadingFiles"
            :completedUploads="completedUploads"
            :selectedFiles="selectedFiles"
            :handleFileSelection="handleFileSelection"
            :removeFile="removeFile"
        />

        <regular-form v-else
            :isReadonly="isReadonly"
            :field="field"
            :idAttr="idAttr"
            :currentLabel="reg_currentLabel"
            :labelFor="labelFor"
            :fileChange="fileChange"
            />
      </slot>
    </div>
    </div>
</template>

<script>
    import { FormField, HandlesValidationErrors } from "laravel-nova";
    // const Vapor = require('laravel-vapor')

    import VaporForm from './Forms/VaporForm.vue'
    import RegularForm from './Forms/RegularForm.vue'

    import vaporFormLogic from '../vaporForm'
    import regularFormLogic from '../regularForm'

    export default {
        components: {VaporForm, RegularForm},

        mixins: [
            FormField, HandlesValidationErrors,
            vaporFormLogic,
            regularFormLogic
        ],

        props: ["resourceName", "resourceId", "field"],

        data() {
            return {

            }
        },

        methods: {
            fill(formData) {
                if (this.field.vapor) {
                    this.vapor_fill(formData)
                } else {
                    this.reg_fill(formData)
                }
            },

            /*
            * Set the initial, internal value for the field.
            */
            setInitialValue() {
                this.value = this.field.value || null;
            },

            /**
             * The ID attribute to use for the file field.
             */
            idAttr() {
                return this.labelFor;
            },

            /**
             * The label attribute to use for the file field.
             */
            labelFor() {
                return `file-${this.field.attribute}`;
            },
        }
    };
</script>
