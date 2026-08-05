<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Head, Link },
    props: {
        application: Object,
    },
    computed: {
        user() { return this.$page.props.auth.user; },
        stepLabel() {
            if (!this.application) return null;
            const labels = ['', 'Profil personnel', 'Votre projet', 'Documents & liens', 'Finaliser'];
            return labels[this.application.current_step] ?? 'Finaliser';
        },
    },
};
</script>

<template>
    <Head title="Candidater — InAfrikaWeTrust" />

    <PublicLayout>
        <!-- HERO -->
        <section class="bg-primary-800 px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="mb-2 text-[10px] font-medium uppercase tracking-widest text-gold-400">Promotion 2026</p>
                <h1 class="font-serif text-4xl font-normal text-white">Déposez votre candidature</h1>
                <p class="mt-4 text-sm leading-relaxed text-primary-200">
                    InAfrikaWeTrust accompagne chaque année une sélection de jeunes entrepreneurs africains dans les secteurs du tourisme et de l'agriculture. Rejoignez la prochaine promotion.
                </p>
            </div>
        </section>

        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 space-y-8">

            <!-- Étapes du processus -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div v-for="(step, i) in ['Profil personnel', 'Votre projet', 'Documents', 'Finaliser']" :key="i"
                     class="rounded-xl border border-gray-100 bg-white p-4 text-center">
                    <span class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-primary-600 text-xs font-semibold text-white">
                        {{ String(i + 1).padStart(2, '0') }}
                    </span>
                    <p class="text-xs font-medium text-gray-700">{{ step }}</p>
                </div>
            </div>

            <!-- Gate : non connecté -->
            <div v-if="!user" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-primary-50 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                        </span>
                        <div>
                            <p class="font-medium text-gray-900">Un compte est requis pour candidater</p>
                            <p class="text-sm text-gray-500">Créez votre espace personnel ou connectez-vous pour accéder au formulaire et sauvegarder votre progression.</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 flex flex-wrap gap-3">
                    <Link :href="route('register')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">
                        Créer mon espace →
                    </Link>
                    <Link :href="route('login')" class="inline-flex items-center gap-2 rounded-full border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Me connecter
                    </Link>
                </div>
            </div>

            <!-- Connecté : candidature en cours ou à démarrer -->
            <template v-else>
                <!-- Déjà soumis -->
                <div v-if="application?.status && application.status !== 'draft'" class="rounded-2xl border border-green-100 bg-green-50 px-6 py-5">
                    <p class="font-medium text-green-800">Votre candidature a bien été soumise.</p>
                    <p class="mt-1 text-sm text-green-700">Nous reviendrons vers vous dans les meilleurs délais.</p>
                </div>

                <!-- En cours ou à démarrer -->
                <div v-else class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 bg-primary-50 px-6 py-5">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 text-white">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ application ? `Reprendre votre candidature` : `Commencer votre candidature` }}
                                </p>
                                <p v-if="application" class="text-sm text-gray-500">
                                    Vous êtes à l'étape {{ application.current_step }} / 4 — {{ stepLabel }}
                                </p>
                                <p v-else class="text-sm text-gray-500">
                                    Bonjour {{ user.first_name }}, complétez les 4 étapes du formulaire ci-dessous.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Barre de progression -->
                    <div v-if="application" class="h-1.5 bg-gray-100">
                        <div class="h-full bg-gold-500 transition-all" :style="`width: ${((application.current_step - 1) / 4) * 100}%`"></div>
                    </div>

                    <div class="px-6 py-5">
                        <Link :href="route('application.show')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">
                            {{ application ? 'Reprendre le formulaire →' : 'Démarrer ma candidature →' }}
                        </Link>
                    </div>
                </div>
            </template>

            <!-- Critères d'éligibilité -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6">
                <h2 class="mb-4 font-serif text-lg font-normal text-gray-900">Critères d'éligibilité</h2>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-gold-600">✓</span>Être ressortissant d'un pays africain</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-gold-600">✓</span>Avoir un projet dans les secteurs tourisme ou agriculture</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-gold-600">✓</span>Pouvoir consacrer au moins 5h/semaine au programme</li>
                    <li class="flex items-start gap-2"><span class="mt-0.5 text-gold-600">✓</span>Disposer d'un accès internet stable pour les sessions en ligne</li>
                </ul>
            </div>
        </div>
    </PublicLayout>
</template>
