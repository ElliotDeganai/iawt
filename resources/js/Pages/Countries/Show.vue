<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const PLACE_ICONS = {
    lodging:    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    shopping:   'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z',
    food:       'M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3',
    nightlife:  'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3',
    activities: 'M13 10V3L4 14h7v7l9-11h-7z',
    learn_more: 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
    // Fallback icons from Icon.vue names
    leaf:       'M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 18h5l-1-4 7-7-1 1z',
    palm:       'M12 3c0 0-4.5 2-4.5 8H12V3zm0 0c0 0 4.5 2 4.5 8H12V3zM12 11v10',
    rocket:     'M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 00-2.91-.09zM12 15l-3-3a22 22 0 012-3.95A12.88 12.88 0 0122 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 01-4 2z',
    lightbulb:  'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
    search:     'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
    chart:      'M16 8v8m-4-5v5m-4-2v2m-2 4h16a2 2 0 002-2V6a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z',
    currency:   'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    scale:      'M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3',
    target:     'M12 2a10 10 0 100 20 10 10 0 000-20zm0 4a6 6 0 100 12 6 6 0 000-12zm0 4a2 2 0 100 4 2 2 0 000-4z',
    building:   'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
};

const INFO_ICONS = {
    population: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    currency:   'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    timezone:   'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    language:   'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10',
    sunrise:    'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z',
    sunset:     'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
    transport:  'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z',
};

export default {
    components: { PublicLayout, Head, Link },
    props: {
        country: Object,
    },
    data() {
        return {
            lightbox: null, // { index, photos[] }
        };
    },
    computed: {
        bonASavoir() {
            return [
                { label: "Nombre d'habitants", value: this.country.population,        iconKey: 'population' },
                { label: 'Monnaie',            value: this.country.currency,          iconKey: 'currency' },
                { label: 'Fuseau horaire',     value: this.country.timezone,          iconKey: 'timezone' },
                { label: 'Langue officielle',  value: this.country.official_language, iconKey: 'language' },
                { label: 'Lever du soleil',    value: this.country.sunrise_time,      iconKey: 'sunrise' },
                { label: 'Coucher du soleil',  value: this.country.sunset_time,       iconKey: 'sunset' },
                { label: 'Transport conseillé',value: this.country.best_transport,    iconKey: 'transport' },
            ].filter((f) => f.value);
        },
        placesEntries() {
            const groups = {};
            for (const p of (this.country.country_places || [])) {
                const cat = p.category;
                if (!cat) continue;
                if (!groups[cat.id]) {
                    groups[cat.id] = {
                        key: cat.slug,
                        label: cat.name,
                        iconPath: PLACE_ICONS[cat.icon] || PLACE_ICONS[cat.slug] || PLACE_ICONS.building,
                        items: [],
                    };
                }
                groups[cat.id].items.push(p);
            }
            return Object.values(groups);
        },
        mapSrc() {
            if (this.country.map_image) return `/storage/${this.country.map_image}`;
            if (this.country.flag_code) return `/storage/images/country-maps/${this.country.flag_code}.svg`;
            return null;
        },
        galleryPhotos() {
            return (this.country.media || []).filter((m) => m.type === 'photo');
        },
        galleryVideos() {
            return (this.country.media || []).filter((m) => m.type === 'video');
        },
        flagClass() {
            return this.country.flag_code ? `fi fi-${this.country.flag_code}` : '';
        },
        lightboxPhoto() {
            if (!this.lightbox) return null;
            return this.lightbox.photos[this.lightbox.index];
        },
    },
    mounted() {
        this._keyHandler = (e) => {
            if (!this.lightbox) return;
            if (e.key === 'Escape') this.closeLightbox();
            if (e.key === 'ArrowRight') this.lightboxNext();
            if (e.key === 'ArrowLeft') this.lightboxPrev();
        };
        window.addEventListener('keydown', this._keyHandler);
    },
    beforeUnmount() {
        window.removeEventListener('keydown', this._keyHandler);
    },
    methods: {
        formatMonth(date) {
            return new Date(date).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
        },
        infoIcon(key) {
            return INFO_ICONS[key] || '';
        },
        embedUrl(url) {
            const yt = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([\w-]{11})/);
            if (yt) return `https://www.youtube.com/embed/${yt[1]}`;
            const vm = url.match(/vimeo\.com\/(\d+)/);
            if (vm) return `https://player.vimeo.com/video/${vm[1]}`;
            return url;
        },
        openLightbox(index) {
            this.lightbox = { index, photos: this.galleryPhotos };
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightbox = null;
            document.body.style.overflow = '';
        },
        lightboxNext() {
            if (!this.lightbox) return;
            this.lightbox.index = (this.lightbox.index + 1) % this.lightbox.photos.length;
        },
        lightboxPrev() {
            if (!this.lightbox) return;
            this.lightbox.index = (this.lightbox.index - 1 + this.lightbox.photos.length) % this.lightbox.photos.length;
        },
    },
};
</script>

<template>
    <Head :title="`${country.name} — Pays à l'honneur`" />

    <PublicLayout>

        <!-- HERO : fond uni bordeaux, pas d'image de fond -->
        <section class="bg-primary-800 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">

                <Link :href="route('countries.index')" class="mb-6 inline-flex items-center gap-1.5 text-xs text-primary-200 hover:text-white">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Archives des pays à l'honneur
                </Link>

                <div class="flex items-start gap-8">

                    <!-- Gauche : identité + bon à savoir -->
                    <div class="flex-1 min-w-0">
                        <p class="mb-2 text-[10px] font-medium uppercase tracking-widest text-gold-400 capitalize">
                            {{ formatMonth(country.featured_month) }} · Pays à l'honneur
                        </p>

                        <h1 class="mb-1 flex items-center gap-3 font-serif text-3xl font-normal text-white">
                            <img v-if="country.flag_image" :src="`/storage/${country.flag_image}`" class="h-7 w-11 rounded object-cover" alt="" />
                            <span v-else-if="country.flag_code" :class="flagClass" style="width:2.75rem;height:1.75rem;border-radius:3px;display:inline-block;flex-shrink:0;"></span>
                            <span v-else-if="country.flag_emoji">{{ country.flag_emoji }}</span>
                            {{ country.name }}
                        </h1>

                        <p v-if="country.tags" class="mb-6 text-sm text-gold-300 opacity-80">{{ country.tags }}</p>

                        <p v-if="country.content" class="mb-6 text-sm leading-relaxed text-primary-100 opacity-90">
                            {{ country.content }}
                        </p>

                        <!-- Bon à savoir -->
                        <div v-if="bonASavoir.length" class="grid grid-cols-2 gap-2">
                            <div
                                v-for="f in bonASavoir"
                                :key="f.label"
                                :class="f.iconKey === 'transport' ? 'col-span-2' : ''"
                                class="rounded-lg border border-white/10 bg-white/7 px-3 py-2.5"
                            >
                                <p class="mb-0.5 text-[9px] font-medium uppercase tracking-wider text-white/40">{{ f.label }}</p>
                                <p class="text-xs font-medium text-white">{{ f.value }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Droite : carte -->
                    <div v-if="mapSrc" class="hidden shrink-0 lg:block" style="width:200px">
                        <img
                            :src="mapSrc"
                            class="h-full w-full object-contain"
                            :style="!country.map_image ? 'filter: brightness(0) invert(1); opacity: 0.55;' : ''"
                            alt="Carte du pays"
                        />
                    </div>

                </div>
            </div>
        </section>

        <!-- CORPS -->
        <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 space-y-12">

            <!-- Sites incontournables -->
            <section v-if="country.highlights?.length">
                <p class="mb-3 text-[10px] font-medium uppercase tracking-widest text-gold-600">Sites incontournables</p>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div
                        v-for="(h, i) in country.highlights"
                        :key="i"
                        class="flex items-start gap-3 rounded-xl border border-gray-100 bg-white p-4"
                    >
                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary-600 text-[10px] font-semibold text-white">
                            {{ i + 1 }}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ h.name }}</p>
                            <p v-if="h.description" class="mt-1 text-xs leading-relaxed text-gray-500">{{ h.description }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sociétés de tourisme -->
            <section v-if="country.tourism_agencies?.length">
                <p class="mb-3 text-[10px] font-medium uppercase tracking-widest text-gold-600">Sociétés de tourisme locales</p>
                <div class="divide-y divide-gray-100 overflow-hidden rounded-xl border border-gray-100 bg-white">
                    <div
                        v-for="(a, i) in country.tourism_agencies"
                        :key="i"
                        class="flex flex-wrap items-center justify-between gap-2 px-5 py-3.5"
                    >
                        <p class="text-sm font-medium text-gray-900">{{ a.name }}</p>
                        <div class="flex flex-wrap gap-4 text-xs">
                            <a v-if="a.website" :href="a.website" target="_blank" rel="noopener" class="text-primary-600 hover:underline">Site web →</a>
                            <a v-if="a.email" :href="`mailto:${a.email}`" class="text-primary-600 hover:underline">{{ a.email }}</a>
                            <span v-if="a.phone" class="text-gray-500">{{ a.phone }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sections Où... -->
            <section v-for="section in placesEntries" :key="section.key">
                <div class="mb-3 flex items-center gap-2.5">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-600 text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path :d="section.iconPath" />
                        </svg>
                    </span>
                    <p class="text-[10px] font-medium uppercase tracking-widest text-gold-600">{{ section.label }}</p>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div
                        v-for="(p, i) in section.items"
                        :key="i"
                        class="rounded-xl border border-gray-100 bg-white p-4"
                    >
                        <p class="text-sm font-medium text-gray-900">{{ p.name }}</p>
                        <p v-if="p.description" class="mt-1 text-xs leading-relaxed text-gray-500">{{ p.description }}</p>
                        <a
                            v-if="p.link"
                            :href="p.link"
                            target="_blank"
                            rel="noopener"
                            class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:underline"
                        >
                            En savoir plus →
                        </a>
                    </div>
                </div>
            </section>

            <!-- Galerie -->
            <section v-if="galleryPhotos.length || galleryVideos.length">
                <p class="mb-3 text-[10px] font-medium uppercase tracking-widest text-gold-600">Galerie</p>

                <!-- Photos cliquables -->
                <div v-if="galleryPhotos.length" class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                    <button
                        v-for="(photo, index) in galleryPhotos"
                        :key="photo.id"
                        type="button"
                        class="group relative overflow-hidden rounded-xl bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500"
                        @click="openLightbox(index)"
                    >
                        <img
                            :src="photo.url"
                            class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-105"
                            :alt="photo.caption || ''"
                        />
                        <!-- Loupe au hover -->
                        <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition-colors duration-200 group-hover:bg-black/20">
                            <svg class="h-8 w-8 text-white opacity-0 drop-shadow transition-opacity duration-200 group-hover:opacity-100" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35M11 8v6M8 11h6"/>
                            </svg>
                        </div>
                        <div v-if="photo.caption" class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 px-3 py-2 text-left">
                            <p class="text-xs text-white">{{ photo.caption }}</p>
                        </div>
                    </button>
                </div>

                <!-- Vidéos -->
                <div v-if="galleryVideos.length" class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div v-for="video in galleryVideos" :key="video.id" class="overflow-hidden rounded-xl bg-gray-900">
                        <div class="relative aspect-video">
                            <iframe
                                v-if="video.url"
                                :src="embedUrl(video.url)"
                                class="absolute inset-0 h-full w-full"
                                frameborder="0"
                                allowfullscreen
                            ></iframe>
                        </div>
                        <p v-if="video.caption" class="px-3 py-2 text-xs text-gray-300">{{ video.caption }}</p>
                    </div>
                </div>
            </section>

        </div>

        <!-- LIGHTBOX -->
        <Teleport to="body">
            <div
                v-if="lightbox && lightboxPhoto"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/90"
                @click.self="closeLightbox"
            >
                <!-- Fermer -->
                <button
                    type="button"
                    class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                    @click="closeLightbox"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                <!-- Précédent -->
                <button
                    v-if="lightbox.photos.length > 1"
                    type="button"
                    class="absolute left-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                    @click="lightboxPrev"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </button>

                <!-- Image -->
                <div class="mx-20 flex max-h-[90vh] max-w-5xl flex-col items-center">
                    <img
                        :key="lightboxPhoto.id"
                        :src="lightboxPhoto.url"
                        class="max-h-[80vh] max-w-full rounded-lg object-contain shadow-2xl"
                        :alt="lightboxPhoto.caption || ''"
                    />
                    <div v-if="lightboxPhoto.caption" class="mt-3 text-sm text-white/70">
                        {{ lightboxPhoto.caption }}
                    </div>
                    <div v-if="lightbox.photos.length > 1" class="mt-2 text-xs text-white/40">
                        {{ lightbox.index + 1 }} / {{ lightbox.photos.length }}
                    </div>
                </div>

                <!-- Suivant -->
                <button
                    v-if="lightbox.photos.length > 1"
                    type="button"
                    class="absolute right-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                    @click="lightboxNext"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </Teleport>

    </PublicLayout>
</template>
