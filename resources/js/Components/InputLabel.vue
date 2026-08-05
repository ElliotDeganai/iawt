<script>
export default {
    props: {
        value: {
            type: String,
            default: '',
        },
    },
};
</script>

<template>
    <label class="block text-sm font-medium text-gray-700">
        <span v-if="value">{{ value }}</span>
        <slot v-else />
    </label>
</template>
