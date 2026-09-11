<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
export default {
    components: { PublicLayout, Head, Link },
    props: { channels: Array },
    methods: {
        timeAgo(date) {
            if (!date) return '';
            const diff = Math.floor((new Date() - new Date(date)) / 1000);
            if (diff < 60) return 'à l\'instant';
            if (diff < 3600) return 'il y a ' + Math.floor(diff / 60) + ' min';
            if (diff < 86400) return 'il y a ' + Math.floor(diff / 3600) + ' h';
            return 'il y a ' + Math.floor(diff / 86400) + ' j';
        },
    },
};
</script>
<template>
    <Head title="Le coin des palabres — InAfrikaWeTrust" />
    <PublicLayout>
        <section class="bg-primary-800 px-4 py-12 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl text-center">
                <p class="mb-1 text-[10px] font-medium uppercase tracking-widest text-gold-400">Communauté</p>
                <h1 class="font-serif text-3xl text-white">Le coin des palabres</h1>
                <p class="mt-2 text-sm text-primary-200">Échangez, partagez et grandissez ensemble.</p>
            </div>
        </section>

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8 space-y-4">
            <Link v-for="ch in channels" :key="ch.id" :href="route('forum.channel', ch.slug)" class="block rounded-2xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md transition group">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-50 text-primary-600 group-hover:bg-primary-100 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <h2 class="text-base font-semibold text-gray-800 group-hover:text-primary-700 transition">{{ ch.name }}</h2>
                            <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-medium text-gray-500">{{ ch.topics_count }} sujet{{ ch.topics_count > 1 ? 's' : '' }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">{{ ch.description }}</p>
                        <p v-if="ch.latest_post" class="mt-2 text-xs text-gray-400">
                            Dernier message de <span class="font-medium text-gray-600">{{ ch.latest_post.user?.first_name }}</span> · {{ timeAgo(ch.latest_post.created_at) }}
                        </p>
                    </div>
                    <svg class="h-5 w-5 text-gray-300 group-hover:text-primary-500 transition shrink-0 mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
            </Link>
        </div>
    </PublicLayout>
</template>
