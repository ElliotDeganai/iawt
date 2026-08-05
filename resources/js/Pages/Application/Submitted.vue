<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Head, Link },
};
</script>

<template>
    <Head title="Candidature soumise — InAfrikaWeTrust" />

    <PublicLayout>
        <div class="mx-auto max-w-2xl px-4 py-24 text-center sm:px-6 lg:px-8">
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="mb-3 font-serif text-3xl font-normal text-gray-900">Candidature soumise !</h1>
            <p class="mb-2 text-gray-600">
                Votre candidature a bien été reçue. Notre équipe va l'examiner et reviendra vers vous dans les meilleurs délais.
            </p>
            <p class="mb-8 text-sm text-gray-400">Un e-mail de confirmation vous a été envoyé à l'adresse renseignée.</p>

            <Link :href="route('home')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700">
                Retour à l'accueil
            </Link>
        </div>
    </PublicLayout>
</template>
