<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div v-if="hasValue" class="mb-6">
                <template v-if="field.value">
                <card
                    class="flex item-center relative border border-lg border-50 overflow-hidden p-4"
                >
                    <span class="truncate mr-3">{{ field.value.length }} files found.</span>
                </card>
                </template>
            </div>

            <span class="form-file mr-4" :class="{ 'opacity-75': isReadonly }">
                <input
                    ref="fileField"
                    :dusk="field.attribute"
                    class="form-file-input select-none"
                    type="file"
                    multiple
                    :id="idAttr"
                    name="name"
                    @change="fileChange"
                    :disabled="isReadonly"
                    :accept="field.acceptedTypes"
                />
                <label
                    :for="labelFor"
                    class="form-file-btn btn btn-default btn-primary select-none"
                >{{ __('Choose File') }}</label>
            </span>

            <span class="text-gray-50 select-none">{{ currentLabel }}</span>

            <p v-if="hasError" class="text-xs mt-2 text-danger">{{ firstError }}</p>
        </template>
    </default-field>
</template>

<script>
    import { FormField, HandlesValidationErrors, Errors } from "laravel-nova";

    export default {
        mixins: [FormField, HandlesValidationErrors],

        props: ["resourceName", "resourceId", "field"],

        data() {
            return {
                files: [],
                uploadErrors: new Errors(),
            };
        },

        computed: {
            /**
             * Determine if the field has an upload error.
             */
            hasError() {
                return this.uploadErrors.has(this.fieldAttribute);
            },

            /**
             * Return the first error for the field.
             */
            firstError() {
                if (this.hasError) {
                    return this.uploadErrors.first(this.fieldAttribute);
                }
            },

            /**
             * The current label of the file field.
             */
            currentLabel() {
                if (this.files.length == 0) {
                    return this.__("no file selected")
                } else if (this.files.length == 1) {
                    return this.files[0].fileName
                } else {
                    return `${this.files.length} files selected`
                }
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

            /**
             * Determine whether the field has a value.
             */
            hasValue() {
                return this.field.value && this.field.value.length
            },
        },

        methods: {
            /*
             * Set the initial, internal value for the field.
             */
            setInitialValue() {
                this.value = this.field.value || null;
            },

            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                let formFieldName = `${this.field.attribute}[]`
                if (this.files.length) {
                    this.files.forEach(file => formData.append(formFieldName, file))
                } else {
                    formData.append(formFieldName, "");
                }
            },

            /**
             * Respond to the file change
             */
            fileChange(event) {
                let path = event.target.value;
                let fileName = path.match(/[^\\/]*$/)[0];
                // this.fileName = fileName;
                // this.file = this.$refs.fileField.files[0];
                for (let index = 0; index < event.target.files.length; index++) {
                    let file = event.target.files[index]
                    file.fileName = fileName
                    if (this.files.findIndex(f => f.name == file.name) == -1) {
                        this.files.push(file)
                    }
                }
            }
        }
    };
</script>
