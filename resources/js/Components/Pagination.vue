<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
    props: {
        links: {
            type: Array,
            required: true,
        },
    },
};
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap gap-1">
        <template v-for="(link, i) in links" :key="i">
            <div
                v-if="link.url === null"
                class="px-3 py-1 text-sm text-gray-400"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                class="px-3 py-1 text-sm rounded-md border"
                :class="link.active
                    ? 'bg-primary-600 border-primary-600 text-white'
                    : 'border-gray-200 text-gray-600 hover:bg-gray-50'"
                v-html="link.label"
            />
        </template>
    </div>
</template>
