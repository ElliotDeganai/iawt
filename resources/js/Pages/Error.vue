<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const ERRORS = {
    403: {
        title: 'Accès refusé',
        description: "Vous n'avez pas les droits nécessaires pour accéder à cette page.",
    },
    404: {
        title: 'Page introuvable',
        description: "La page que vous cherchez n'existe pas ou a été déplacée.",
    },
    419: {
        title: 'Session expirée',
        description: 'Votre session a expiré. Veuillez rafraîchir la page et réessayer.',
    },
    429: {
        title: 'Trop de requêtes',
        description: 'Vous avez effectué trop de requêtes. Veuillez patienter quelques instants.',
    },
    500: {
        title: 'Erreur serveur',
        description: "Une erreur inattendue s'est produite. Notre équipe a été notifiée.",
    },
    503: {
        title: 'Maintenance en cours',
        description: "Le site est temporairement en maintenance. Nous serons bientôt de retour.",
    },
};

export default {
    components: { PublicLayout, Head, Link },
    props: { status: Number },
    computed: {
        error() {
            return ERRORS[this.status] || { title: 'Erreur', description: "Une erreur s'est produite." };
        },
    },
};
</script>

<template>
    <Head :title="`${status} — ${error.title}`" />

    <PublicLayout>
        <section class="relative overflow-hidden">
            <!-- Fond décoratif -->
            <div class="absolute inset-0 opacity-[0.03]">
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 font-serif text-[20rem] font-bold text-primary-900 select-none">{{ status }}</div>
            </div>

            <div class="relative mx-auto flex min-h-[60vh] max-w-2xl flex-col items-center justify-center px-4 py-20 text-center sm:px-6">

                <!-- Illustration SVG -->
                <div class="flex h-28 w-28 items-center justify-center rounded-full bg-primary-50">
                    <!-- 403 — cadenas -->
                    <svg v-if="status === 403" class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        <circle cx="12" cy="16" r="1" fill="currentColor" />
                    </svg>
                    <!-- 404 — loupe brisée -->
                    <svg v-else-if="status === 404" class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4.35-4.35M8 8l6 6M14 8l-6 6" />
                    </svg>
                    <!-- 419 — sablier -->
                    <svg v-else-if="status === 419" class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M5 4h14M5 20h14M12 4v3a5 5 0 0 1-3.54 4.78L7 12.5l1.46.72A5 5 0 0 1 12 18v2M12 4v3a5 5 0 0 0 3.54 4.78L17 12.5l-1.46.72A5 5 0 0 0 12 18v2" />
                        <circle cx="12" cy="15" r="1" fill="currentColor" />
                    </svg>
                    <!-- 429 — horloge -->
                    <svg v-else-if="status === 429" class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 3" stroke-linecap="round" />
                        <path d="M4.5 4.5l2 2M19.5 4.5l-2 2" stroke-linecap="round" />
                    </svg>
                    <!-- 500 — engrenage cassé -->
                    <svg v-else-if="status === 500" class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" />
                        <path d="M9 9l6 6" stroke-linecap="round" stroke-width="2" />
                    </svg>
                    <!-- 503 — clé à molette -->
                    <svg v-else-if="status === 503" class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76Z" />
                    </svg>
                    <!-- Fallback -->
                    <svg v-else class="h-14 w-14 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 8v4M12 16h.01" stroke-linecap="round" />
                    </svg>
                </div>

                <!-- Code -->
                <p class="mt-6 font-serif text-7xl font-bold text-primary-700 sm:text-8xl">{{ status }}</p>

                <!-- Titre -->
                <h1 class="mt-4 font-serif text-2xl font-bold text-gray-900 sm:text-3xl">{{ error.title }}</h1>

                <!-- Description -->
                <p class="mt-3 text-sm leading-relaxed text-gray-600 sm:text-base">{{ error.description }}</p>

                <!-- Actions -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <Link :href="route('home')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1"/></svg>
                        Retour à l'accueil
                    </Link>

                    <button v-if="status === 419" type="button" class="inline-flex items-center gap-2 rounded-full border border-primary-600 px-6 py-3 text-sm font-semibold text-primary-700 hover:bg-primary-50" @click="location.reload()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Rafraîchir la page
                    </button>

                    <button v-else-if="status !== 503" type="button" class="inline-flex items-center gap-2 rounded-full border border-primary-600 px-6 py-3 text-sm font-semibold text-primary-700 hover:bg-primary-50" @click="history.back()">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Page précédente
                    </button>
                </div>

                <!-- Contact -->
                <p v-if="status === 500" class="mt-8 text-xs text-gray-400">
                    Si le problème persiste, contactez-nous à
                    <a href="mailto:contact@inafrikawetrust.org" class="text-primary-600 hover:underline">contact@inafrikawetrust.org</a>
                </p>
            </div>
        </section>
    </PublicLayout>
</template>