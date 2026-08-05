<script>
export default {
    props: {
        modelValue: {
            type: String,
            default: '',
        },
    },
    emits: ['update:modelValue'],
    mounted() {
        if (this.$attrs.autofocus) {
            this.$refs.input.focus();
        }
    },
    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
};
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm w-full"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
