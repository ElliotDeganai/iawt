<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
    props: {
        href: {
            type: String,
            required: true,
        },
        active: {
            type: Boolean,
            default: false,
        },
        method: {
            type: String,
            default: 'get',
        },
        as: {
            type: String,
            default: 'a',
        },
    },
    computed: {
        classes() {
            return this.active
                ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-primary-600 text-start text-base font-medium text-primary-700 bg-primary-50 focus:outline-none focus:text-primary-800 focus:bg-primary-100 focus:border-primary-700 transition'
                : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition';
        },
    },
};
</script>

<template>
    <Link :href="href" :method="method" :as="as" :class="classes">
        <slot />
    </Link>
</template>
