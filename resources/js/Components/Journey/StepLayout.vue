<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Head, Link },
    props: {
        stepNumber: Number,
        totalSteps: { type: Number, default: 8 },
        title: String,
        subtitle: String,
        saving: { type: Boolean, default: false },
    },
    emits: ['save', 'complete'],
};
</script>

<template>
    <Head :title="`Étape ${stepNumber} — ${title}`" />
    <PublicLayout>
        <!-- Hero -->
        <section class="bg-primary-800 px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <Link :href="route('dashboard', { tab: 'parcours' })" class="mb-3 inline-flex items-center gap-1.5 text-xs text-primary-300 hover:text-white">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                    Retour au parcours
                </Link>
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold-500 text-sm font-bold text-primary-900">
                        {{ String(stepNumber).padStart(2, '0') }}
                    </span>
                    <div>
                        <h1 class="font-serif text-xl font-normal text-white sm:text-2xl">{{ title }}</h1>
                        <p v-if="subtitle" class="mt-0.5 text-sm text-primary-200">{{ subtitle }}</p>
                    </div>
                </div>

                <!-- Barre de progression -->
                <div class="mt-5 flex gap-1">
                    <div
                        v-for="i in totalSteps"
                        :key="i"
                        class="h-1.5 flex-1 rounded-full"
                        :class="i <= stepNumber ? 'bg-gold-500' : 'bg-white/15'"
                    ></div>
                </div>
                <p class="mt-2 text-xs text-primary-300">Étape {{ stepNumber }} sur {{ totalSteps }}</p>
            </div>
        </section>

        <!-- Contenu -->
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <slot />

            <!-- Actions -->
            <div class="mt-8 flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                    :disabled="saving"
                    @click="$emit('save')"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Sauvegarder le brouillon
                </button>
                <button
                    type="button"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700"
                    :disabled="saving"
                    @click="$emit('complete')"
                >
                    Valider cette étape
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                </button>
            </div>
        </div>
    </PublicLayout>
</template>
