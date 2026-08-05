<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: { Link },
};
</script>

<template>
    <div class="min-h-screen flex flex-col items-center bg-cream pt-6 sm:justify-center sm:pt-0">
        <div>
            <Link href="/" class="flex items-center gap-2">
                <img :src="$page.props.siteSettings.logo" class="h-10 w-10 rounded-full object-cover" alt="Logo" />
                <span class="text-xl font-serif font-bold text-primary-600">{{ $page.props.siteSettings.name }}</span>
            </Link>
        </div>

        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
