<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Icon from '@/Components/Icon.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Icon, Head, Link },
    props: {
        steps: Array,
    },
};
</script>

<template>
    <Head title="Le parcours entrepreneur — InAfrikaWeTrust" />

    <PublicLayout>
        <!-- HERO -->
        <section class="bg-primary-800 px-4 py-16 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-3xl text-center">
                <p class="mb-2 text-[10px] font-medium uppercase tracking-widest text-gold-400">Notre programme</p>
                <h1 class="font-serif text-4xl font-normal text-white">Le parcours entrepreneur</h1>
                <p class="mt-4 text-sm leading-relaxed text-primary-200">
                    Un programme structuré en {{ steps.length }} étapes pour transformer votre idée en entreprise pérenne, de la conception à la création formelle.
                </p>
            </div>
        </section>

        <!-- TIMELINE DES ÉTAPES -->
        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">

            <div class="relative">
                <!-- Ligne verticale centrale (desktop) -->
                <div class="absolute left-1/2 top-0 hidden h-full w-px -translate-x-1/2 bg-primary-100 lg:block"></div>

                <div class="space-y-16">
                    <div
                        v-for="(step, i) in steps"
                        :key="step.id"
                        class="relative lg:flex lg:items-start"
                        :class="i % 2 === 0 ? 'lg:flex-row' : 'lg:flex-row-reverse'"
                    >
                        <!-- Numéro central (desktop) -->
                        <div class="absolute left-1/2 hidden -translate-x-1/2 lg:flex">
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 text-sm font-semibold text-white shadow-md ring-4 ring-white">
                                {{ String(i + 1).padStart(2, '0') }}
                            </span>
                        </div>

                        <!-- Contenu : texte -->
                        <div class="lg:w-[calc(50%-3rem)]" :class="i % 2 === 0 ? 'lg:pr-10 lg:text-right' : 'lg:pl-10'">
                            <!-- Numéro (mobile) -->
                            <div class="mb-3 flex items-center gap-3 lg:hidden">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-600 text-xs font-semibold text-white">
                                    {{ String(i + 1).padStart(2, '0') }}
                                </span>
                                <span class="text-xs font-medium uppercase tracking-widest text-gold-600">Étape {{ i + 1 }}</span>
                            </div>

                            <div class="hidden lg:block">
                                <span class="text-[10px] font-medium uppercase tracking-widest text-gold-600">Étape {{ i + 1 }}</span>
                            </div>

                            <div class="mt-1 flex items-center gap-3" :class="i % 2 === 0 ? 'lg:justify-end' : ''">
                                <span
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-50 text-primary-700"
                                    :class="i % 2 === 0 ? 'lg:order-last' : ''"
                                >
                                    <Icon :name="step.icon" class="h-5 w-5" />
                                </span>
                                <h2 class="font-serif text-xl font-medium text-gray-900">{{ step.label }}</h2>
                            </div>

                            <div
                                v-if="step.description"
                                class="prose prose-sm mt-3 max-w-none text-gray-600"
                                :class="i % 2 === 0 ? 'lg:text-right' : ''"
                                v-html="step.description"
                            ></div>
                        </div>

                        <!-- Spacer central (desktop) -->
                        <div class="hidden lg:block lg:w-24"></div>

                        <!-- Image d'illustration -->
                        <div class="mt-5 lg:mt-0 lg:w-[calc(50%-3rem)]">
                            <div
                                v-if="step.image"
                                class="overflow-hidden rounded-2xl shadow-sm"
                                :class="i % 2 === 0 ? 'lg:pl-10' : 'lg:pr-10'"
                            >
                                <img
                                    :src="`/storage/${step.image}`"
                                    class="aspect-[4/3] w-full object-cover"
                                    :alt="step.label"
                                />
                            </div>
                            <!-- Placeholder si pas d'image -->
                            <div
                                v-else
                                class="aspect-[4/3] w-full rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center"
                                :class="i % 2 === 0 ? 'lg:pl-10' : 'lg:pr-10'"
                            >
                                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-white/70 text-primary-400 shadow">
                                    <Icon :name="step.icon" class="h-8 w-8" />
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-20 rounded-2xl bg-primary-800 px-8 py-10 text-center">
                <h3 class="font-serif text-2xl font-normal text-white">Prêt à démarrer votre parcours ?</h3>
                <p class="mt-3 text-sm text-primary-200">
                    Rejoignez la prochaine promotion et transformez votre idée en impact réel.
                </p>
                <Link :href="route('application.index')" class="mt-6 inline-flex items-center gap-2 rounded-full bg-gold-500 px-8 py-3 text-sm font-semibold text-primary-900 hover:bg-gold-400">
                    Déposer ma candidature →
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>
