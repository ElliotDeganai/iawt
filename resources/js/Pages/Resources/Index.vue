<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';

const ICONS = {
    banknotes: 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z',
    scale: 'M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z',
    building: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
    calculator: 'M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V13.5zm0 2.25h.008v.008H8.25v-.008zm0 2.25h.008v.008H8.25V18zm2.498-6.75h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V13.5zm0 2.25h.007v.008h-.007v-.008zm0 2.25h.007v.008h-.007V18zm2.504-6.75h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V13.5zm0 2.25h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V18zm2.498-6.75h.008v.008H18v-.008zm0 2.25h.008v.008H18V13.5zM4.5 4.5h15A1.5 1.5 0 0121 6v12a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 18V6a1.5 1.5 0 011.5-1.5z',
    chart: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
    wrench: 'M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l5.653-4.655m2.588 2.588L17.5 6.5m-6.08 8.67L6.5 17.5m4.92-2.33l4.655-5.653a2.548 2.548 0 113.586 3.586l-5.653 4.655',
    book: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
    heart: 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z',
};

export default {
    components: { PublicLayout, Head },
    props: { categories: Array },
    data() { return { activeCategory: null }; },
    computed: {
        visibleCategories() {
            if (!this.activeCategory) return this.categories;
            return this.categories.filter(c => c.id === this.activeCategory);
        },
    },
    methods: {
        iconPath(name) { return ICONS[name] || ICONS.book; },
    },
};
</script>
<template>
    <Head title="Racines et ressources — InAfrikaWeTrust" />
    <PublicLayout>
        <section class="bg-primary-800 px-4 py-12 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl text-center">
                <p class="mb-1 text-[10px] font-medium uppercase tracking-widest text-gold-400">Documentation</p>
                <h1 class="font-serif text-3xl text-white">Racines et ressources</h1>
                <p class="mt-2 text-sm text-primary-200">Tout ce dont vous avez besoin pour avancer, au même endroit.</p>
            </div>
        </section>

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Category filter pills -->
            <div class="mb-6 flex flex-wrap gap-2">
                <button type="button" class="rounded-full border px-3 py-1.5 text-xs transition" :class="!activeCategory ? 'border-primary-600 bg-primary-50 text-primary-700 font-medium' : 'border-gray-200 text-gray-500 hover:border-gray-300'" @click="activeCategory = null">Tout</button>
                <button v-for="cat in categories" :key="cat.id" type="button" class="rounded-full border px-3 py-1.5 text-xs transition" :class="activeCategory === cat.id ? 'border-primary-600 bg-primary-50 text-primary-700 font-medium' : 'border-gray-200 text-gray-500 hover:border-gray-300'" @click="activeCategory = activeCategory === cat.id ? null : cat.id">{{ cat.name }}</button>
            </div>

            <!-- Categories -->
            <div class="space-y-6">
                <div v-for="cat in visibleCategories" :key="cat.id" class="rounded-2xl border border-gray-100 bg-white overflow-hidden shadow-sm">
                    <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100 bg-cream/50">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-50">
                            <svg class="h-5 w-5 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path :d="iconPath(cat.icon)" /></svg>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-gray-800">{{ cat.name }}</h2>
                            <p class="text-xs text-gray-500">{{ cat.description }}</p>
                        </div>
                    </div>
                    <div v-if="cat.resources?.length" class="divide-y divide-gray-50">
                        <a v-for="res in cat.resources" :key="res.id" :href="res.file_path ? `/storage/${res.file_path}` : res.url" target="_blank" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50/80 transition group">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-xs font-bold" :class="res.type === 'pdf' ? 'bg-red-50 text-red-600' : res.type === 'video' ? 'bg-purple-50 text-purple-600' : res.type === 'template' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-500'">{{ res.type === 'pdf' ? 'PDF' : res.type === 'video' ? '▶' : res.type === 'template' ? '📄' : '🔗' }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 group-hover:text-primary-700 transition">{{ res.title }}</p>
                                <p v-if="res.description" class="text-xs text-gray-400 truncate">{{ res.description }}</p>
                            </div>
                            <svg class="h-4 w-4 text-gray-300 group-hover:text-primary-500 transition shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    </div>
                    <div v-else class="px-5 py-6 text-center text-sm text-gray-400">Contenu à venir prochainement.</div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
