<template>
    <div>
        <input
            ref="fileField"
            :dusk="field.attribute"
            class="w-full form-control form-input form-input-bordered py-3 h-auto"
            type="file"
            multiple
            :id="idAttr"
            name="name"
            @change="handleFileSelection"
            :disabled="isReadonly"
            :accept="field.acceptedTypes"
        />

        <div class="text-red-50 font-bold mt-3 flex align-start" v-show="stillUploadingFiles">
            <img
                style="height: 20px;"
                ref="loaderImg"
                alt="Please wait..."
            />
            <div class="pl-4">Uploading {{ selectedFiles.length - completedUploads.length }} file(s). Please wait...</div>
        </div>

        <div class="text-gray-50 font-semibold select-none mt-2">{{ currentLabel }}</div>

        <div class="mt-3">
            <div class="py-2 px-2" v-for="(fileObj, index) in selectedFiles" :key="'_file_' + index">
                <div class="w-full">
                    <img class="w-full h-auto" :src="fileObj.objUrl" :alt="fileObj.name" />

                    <div class="mt-2">
                        {{ fileObj.name.substr(-25) }}
                        <strong class="pl-2" v-show="fileObj.progress < 100">- {{ fileObj.progress }}%</strong>
                        <a href="#" class="text-danger pl-2" @click.prevent="removeFile(index)" v-show="fileObj.progress == 100" title="Remove"> X</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isReadonly: Boolean,
        field: Object,
        idAttr: String,
        labelFor: String,
        currentLabel: String,
        handleFileSelection: Function,
        removeFile: Function,
        stillUploadingFiles: Boolean,
        selectedFiles: Array,
        completedUploads: Array,
    },
    mounted() {
        this.$refs.loaderImg.setAttribute(
            'src', Nova.config('assetUrl') + '/vendor/files/images/loading.gif'
        )
    }
}
</script>
