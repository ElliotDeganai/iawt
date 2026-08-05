<script>
export default {
    props: {
        message: {
            type: String,
            default: '',
        },
    },
};
</script>

<template>
    <p v-show="message" class="text-sm text-red-600 mt-1">{{ message }}</p>
</template>
