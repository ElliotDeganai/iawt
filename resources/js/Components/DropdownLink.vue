<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
    props: {
        href: {
            type: String,
            required: true,
        },
        as: {
            type: String,
            default: 'a',
        },
        method: {
            type: String,
            default: 'get',
        },
    },
};
</script>

<template>
    <Link v-if="as === 'button'" :href="href" :method="method" as="button" class="dropdown-link">
        <slot />
    </Link>

    <Link v-else :href="href" class="dropdown-link">
        <slot />
    </Link>
</template>

<style scoped>
.dropdown-link {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    text-align: start;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #374151;
    transition: background-color 0.15s ease-in-out;
}
.dropdown-link:hover {
    background-color: #f9fafb;
}
</style>
