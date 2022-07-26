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
            @change="fileChange"
            :disabled="isReadonly"
            :accept="field.acceptedTypes"
        />

        <span class="text-gray-50 select-none">{{ currentLabel }}</span>

        <p v-if="hasError" class="text-xs mt-2 text-danger">{{ firstError }}</p>
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
        hasError: Boolean,
        firstError: String,
        fileChange: Function
    }
}
</script>
