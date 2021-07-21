const Vapor = require('laravel-vapor')

export default {
    data() {
        return {
            selectedFiles: [],
            completedUploads: [],
        };
    },

    computed: {
        /**
         * The current label of the file field.
         */
        currentLabel() {
            if (this.selectedFiles.length == 0) {
                return this.__("no file selected")
            } else {
                return `${this.selectedFiles.length} files selected`
            }
        },

        /**
         * Determine whether the field has a value.
         */
        hasValue() {
            return this.field.value && this.field.value.length
        },

        stillUploadingFiles() {
            return this.completedUploads.length < this.selectedFiles.length
        }
    },

    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        vapor_fill(formData) {
            let formFieldName = this.field.attribute
            formData.append(formFieldName, JSON.stringify(this.completedUploads))
        },

        removeFile(index) {
            let entry = this.selectedFiles[index]
            this.selectedFiles.splice(index, 1)
            let uploadedIndex = this.completedUploads.findIndex(f => f.name == entry.name)
            if (uploadedIndex != -1) this.completedUploads.splice(uploadedIndex, 1)
            URL.revokeObjectURL(entry.objUrl)
        },

        handleFileSelection(e) {
            if (!e.target.files.length) return

            for (let index = 0; index < e.target.files.length; index++) {
                let file = e.target.files[index]
                let count = this.selectedFiles.push({
                    objUrl: URL.createObjectURL(file),
                    name: file.name,
                    s3: null,
                    progress: 0,
                })
                this.uploadToStorage(file, count - 1)
            }
        },

        uploadToStorage(file, selectionIndex) {
            let entry = this.selectedFiles[selectionIndex]
            Vapor.store(file, {
                // we'll make it public when copying the file to its final destination on the server
                // visibility: 'public-read',
                progress: progress => {
                    entry.progress = Math.round(progress * 100);
                }
            }).then(response => {
                entry.s3 = response
                entry.progress = 100
                this.completedUploads.push({
                    name: entry.name,
                    uuid: response.uuid,
                    key: response.key,
                    ext: response.extension
                })
            });
        },
    },
}
