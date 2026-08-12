<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Icon from '@/Components/Icon.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Icon, Head, Link },
    props: {
        settings: Object,
        engagements: Array,
        journeySteps: Array,
        featuredCountry: Object,
        stats: Array,
        partners: Array,
        testimonials: Array,
    },
};
</script>

<template>
    <Head :title="settings.tagline" />

    <PublicLayout>
<!-- HERO -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-100 via-orange-50 to-primary-100">
                <img v-if="settings.hero_image" :src="settings.hero_image" class="h-full w-full object-cover" alt="" />
            </div>
            <div class="w-full h-full absolute bg-black opacity-50"></div>

            <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
                <div class="max-w-xl">
                    <h1 class="font-serif text-4xl font-bold leading-tight text-white sm:text-5xl">
                        {{ settings.hero_title }}
                    </h1>
                    <p class="mt-5 text-gray-300">
                        {{ settings.hero_subtitle }}
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <Link :href="route('application.index')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-3 text-sm font-semibold text-white hover:bg-primary-700">
                            Déposer ma candidature →
                        </Link>
                        <a href="#parcours" class="inline-flex items-center gap-3 rounded-full border border-primary-300 bg-white/80 py-2 pl-2 pr-6 text-sm font-semibold text-primary-700 hover:bg-white">
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-primary-300 bg-white text-primary-600">▶</span>
                            Découvrir le programme
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- LE PROGRAMME -->
        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-gold-600">Le programme</p>
                    <h2 class="mt-2 font-serif text-3xl font-bold text-gray-900">
                        {{ settings.program_title }}
                    </h2>
                    <p class="mt-4 text-gray-600">
                        {{ settings.program_text || `${settings.site_name} accompagne une douzaine de porteurs de projet par promotion, de l'idée à la création d'entreprise, à travers un parcours structuré, des ressources expertes et un réseau solide.` }}
                    </p>
                    <a href="#parcours" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-primary-700 hover:underline">
                        En savoir plus sur le programme →
                    </a>
                </div>

                <div class="relative">
                    <div class="aspect-[4/3] w-full overflow-hidden rounded-xl bg-gradient-to-br from-primary-200 via-amber-100 to-primary-300">
                        <img v-if="settings.program_image" :src="settings.program_image" class="h-full w-full object-cover" alt="" />
                    </div>
                    <div class="absolute -bottom-6 -left-6 rounded-lg bg-white px-5 py-4 shadow-lg">
                        <p class="text-2xl font-bold text-primary-700">12</p>
                        <p class="text-xs text-gray-500">projets accompagnés<br />par promotion</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- NOS ENGAGEMENTS -->
        <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-600">Nos engagements</p>

            <div class="mt-6 grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div v-for="item in engagements" :key="item.title">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-primary-600 text-white">
                        <Icon :name="item.icon" class="h-6 w-6" />
                    </div>
                    <h3 class="mt-4 text-sm font-bold uppercase tracking-wide text-gray-900">{{ item.title }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ item.description }}</p>
                    <a href="#" class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-primary-700 hover:underline">
                        En savoir plus →
                    </a>
                </div>
            </div>
        </section>

        <!-- PARCOURS ENTREPRENEUR -->
        <section id="parcours" class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-600">Un parcours en {{ journeySteps.length }} étapes</p>

            <div class="relative mt-8 grid grid-cols-2 gap-6 sm:grid-cols-4 lg:grid-cols-8">
                <div class="absolute left-[6%] right-[6%] top-4 hidden border-t-2 border-dotted border-primary-200 lg:block"></div>

                <div v-for="(step, i) in journeySteps" :key="step.label" class="relative flex flex-col items-center text-center">
                    <span class="relative z-10 flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">
                        {{ String(i + 1).padStart(2, '0') }}
                    </span>
                    <div class="mt-2.5 flex h-12 w-12 items-center justify-center rounded-full border-2 border-primary-600 text-primary-700">
                        <Icon :name="step.icon" class="h-5 w-5" />
                    </div>
                    <p class="mt-2 text-[11px] font-medium leading-tight text-gray-700">{{ step.label }}</p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a href="#" class="inline-flex items-center gap-2 rounded-full border border-primary-600 px-6 py-3 text-sm font-semibold text-primary-700 hover:bg-primary-50">
                    Découvrir le parcours en détail →
                </a>
            </div>
        </section>

        <!-- PAYS À L'HONNEUR -->
        <section v-if="featuredCountry" id="pays-a-l-honneur" class="relative overflow-hidden bg-primary-800">
            <img v-if="featuredCountry.cover_image" :src="featuredCountry.cover_image" class="absolute inset-0 h-full w-full object-cover" alt="" />
            <div
                class="absolute inset-0"
                style="background: linear-gradient(to right, rgba(58,15,23,0.96) 0%, rgba(58,15,23,0.96) 32%, rgba(58,15,23,0) 50%, rgba(58,15,23,0) 100%);"
            ></div>

            <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-gold-400">Le pays à l'honneur</p>
                <h2 class="mt-2 flex items-center gap-3 font-serif text-3xl font-bold text-white">
                    <img v-if="featuredCountry.flag_image" :src="featuredCountry.flag_image" class="h-8 w-12 rounded object-cover shadow" alt="" />
                    <span v-else-if="featuredCountry.flag">{{ featuredCountry.flag }}</span>
                    {{ featuredCountry.name }}
                </h2>
                <p class="mt-1 text-sm font-medium text-gold-300">{{ featuredCountry.tags }}</p>
                <p class="mt-4 max-w-md text-sm text-primary-100">{{ featuredCountry.description }}</p>

                <Link :href="route('countries.show', featuredCountry.slug)" class="mt-6 inline-flex items-center gap-2 rounded-full bg-gold-500 px-6 py-3 text-sm font-semibold text-primary-900 hover:bg-gold-400">
                    Découvrir ce pays →
                </Link>
            </div>
        </section>

        <!-- STATISTIQUES -->
        <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 sm:grid-cols-4">
                <div v-for="stat in stats" :key="stat.label" class="text-center sm:text-left">
                    <p class="font-serif text-3xl font-bold text-primary-700">{{ stat.value }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ stat.label }}</p>
                </div>
            </div>
        </section>

        <!-- PARTENAIRES -->
        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-gold-600">Ils nous soutiennent</p>
            <div class="mt-6 flex flex-wrap items-center gap-x-10 gap-y-4">
                <template v-for="partner in partners" :key="partner.name">
                    <a v-if="partner.url" :href="partner.url" target="_blank" rel="noopener" class="flex items-center gap-2 opacity-70 grayscale hover:opacity-100 hover:grayscale-0 transition">
                        <img v-if="partner.logo" :src="partner.logo" class="h-8 w-auto" :alt="partner.name" />
                        <span v-else class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ partner.name }}</span>
                    </a>
                    <span v-else class="flex items-center gap-2 opacity-70 grayscale">
                        <img v-if="partner.logo" :src="partner.logo" class="h-8 w-auto" :alt="partner.name" />
                        <span v-else class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ partner.name }}</span>
                    </span>
                </template>
            </div>
        </section>

        <!-- TÉMOIGNAGES -->
        <section v-if="testimonials.length" class="mx-auto max-w-5xl px-4 pb-16 sm:px-6 lg:px-8">
            <p class="mb-6 text-xs font-semibold uppercase tracking-widest text-gold-600">Ce qu'ils en disent</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="t in testimonials"
                    :key="t.name"
                    class="flex flex-col rounded-xl bg-white p-5 shadow"
                >
                    <p class="text-2xl leading-none text-primary-200">"</p>
                    <p class="flex-1 text-sm text-gray-700 leading-relaxed">{{ t.quote }}</p>
                    <div class="mt-4 flex items-center gap-3 border-t border-gray-100 pt-4">
                        <div class="h-10 w-10 shrink-0 rounded-full bg-primary-100 flex items-center justify-center overflow-hidden">
                            <img v-if="t.photo" :src="t.photo" class="h-full w-full object-cover" :alt="t.name" />
                            <span v-else class="text-sm font-semibold text-primary-400">{{ t.name?.[0] }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ t.name }}</p>
                            <p v-if="t.meta" class="text-xs text-gray-500">{{ t.meta }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA FINAL -->
        <section class="bg-primary-800">
            <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-6 px-4 py-10 sm:flex-row sm:items-center sm:px-6 lg:px-8">
                <div>
                    <h3 class="text-lg font-bold text-white">Prêt à transformer votre idée en impact réel ?</h3>
                    <p class="mt-1 text-sm text-primary-200">
                        Rejoignez la communauté des entrepreneurs qui bâtissent l'Afrique de demain.
                    </p>
                </div>
                <Link :href="route('application.index')" class="inline-flex items-center gap-2 rounded-full bg-gold-500 px-6 py-3 text-sm font-semibold text-primary-900 hover:bg-gold-400">
                    Déposer ma candidature →
                </Link>
            </div>
        </section>
    </PublicLayout>
</template>