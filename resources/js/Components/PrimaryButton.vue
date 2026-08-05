<script>
export default {
    props: {
        type: {
            type: String,
            default: 'submit',
        },
    },
};
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition disabled:opacity-50"
    >
        <slot />
    </button>
</template>
