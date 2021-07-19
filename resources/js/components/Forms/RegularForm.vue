<template>
    <div>
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
