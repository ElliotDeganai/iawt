<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Head },
    props: { content: String },
};
</script>

<template>
    <Head title="Mentions légales — InAfrikaWeTrust" />
    <PublicLayout>
        <section class="bg-primary-800 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <h1 class="font-serif text-3xl font-normal text-white">Mentions légales</h1>
            </div>
        </section>
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div v-if="content" class="prose prose-sm max-w-none text-gray-700" v-html="content"></div>
            <p v-else class="text-gray-400">Aucun contenu pour le moment.</p>
        </div>
    </PublicLayout>
</template>