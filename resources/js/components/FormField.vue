<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div v-if="hasValue" class="mb-6">
                <template v-if="field.value">
                    <card class="flex item-center relative border border-lg border-50 overflow-hidden p-4">
                        <span class="truncate mr-3">{{ field.value.length }} files found.</span>
                    </card>
                </template>
            </div>

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
                :firstError="firstError"
                :hasError="hasError"
             />
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors, Errors } from "laravel-nova";
    const Vapor = require('laravel-vapor')

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
                uploadErrors: new Errors(),
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
