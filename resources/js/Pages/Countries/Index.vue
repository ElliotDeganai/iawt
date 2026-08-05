<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Head, Link },
    props: {
        countries: Array,
    },
    methods: {
        formatMonth(date) {
            return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
        },
    },
};
</script>

<template>
    <Head title="Le pays à l'honneur" />

    <PublicLayout>
        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-600">Chaque mois, un pays à l'honneur</p>
            <h1 class="mt-2 font-serif text-3xl font-bold text-gray-900">Le pays à l'honneur</h1>

            <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="c in countries" :key="c.slug"
                    :href="route('countries.show', c.slug)"
                    class="block overflow-hidden rounded-xl bg-white shadow hover:shadow-md transition"
                >
                    <div class="aspect-[16/9] bg-gradient-to-br from-primary-200 via-amber-100 to-primary-300">
                        <img v-if="c.cover_url || c.cover_image" :src="c.cover_url || `/storage/${c.cover_image}`" class="h-full w-full object-cover" />
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-medium uppercase tracking-wide text-gold-600">{{ formatMonth(c.featured_month) }}</p>
                        <h2 class="mt-1 font-serif text-lg font-bold text-primary-800">{{ c.flag_emoji }} {{ c.name }}</h2>
                        <p class="mt-2 text-sm text-gray-600 line-clamp-3">{{ c.summary }}</p>
                    </div>
                </Link>

                <p v-if="!countries.length" class="text-gray-400">Aucun pays à l'honneur publié pour le moment.</p>
            </div>
        </section>
    </PublicLayout>
</template>
