export default {
    data() {
        return {
            files: [],
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
        reg_currentLabel() {
            if (this.files.length == 0) {
                return this.__("no file selected")
            } else if (this.files.length == 1) {
                return this.files[0].fileName
            } else {
                return `${this.files.length} files selected`
            }
        },

        /**
         * Determine whether the field has a value.
         */
        hasValue() {
            return this.field.value && this.field.value.length
        },
    },

    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        reg_fill(formData) {
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
    },
}
