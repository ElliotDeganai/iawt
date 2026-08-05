<script>
export default {
    props: {
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        value: {
            type: String,
            default: null,
        },
    },
    emits: ['update:checked'],
    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },
            set(val) {
                this.$emit('update:checked', val);
            },
        },
    },
};
</script>

<template>
    <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
    />
</template>
