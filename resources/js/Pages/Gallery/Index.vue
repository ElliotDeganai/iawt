<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

export default {
    components: { PublicLayout, Head, Link },
    props: {
        countries: Array,
    },
    data() {
        return {
            activeCountry: null,   // slug du pays actif dans la nav latérale
            lightbox: null,        // { url, caption, type, countryName }
        };
    },
    computed: {
        visibleCountry() {
            if (!this.activeCountry) return this.countries[0] ?? null;
            return this.countries.find(c => c.slug === this.activeCountry) ?? this.countries[0] ?? null;
        },
        photos() {
            return (this.visibleCountry?.media ?? []).filter(m => m.type === 'photo');
        },
        videos() {
            return (this.visibleCountry?.media ?? []).filter(m => m.type === 'video');
        },
        flagClass() {
            const code = this.visibleCountry?.flag_code;
            return code ? `fi fi-${code}` : '';
        },
    },
    mounted() {
        this._keyHandler = (e) => { if (e.key === 'Escape') this.lightbox = null; };
        document.addEventListener('keydown', this._keyHandler);
    },
    beforeUnmount() {
        document.removeEventListener('keydown', this._keyHandler);
    },
    methods: {
        selectCountry(slug) {
            this.activeCountry = slug;
        },
        openLightbox(media) {
            this.lightbox = { ...media, countryName: this.visibleCountry?.name };
        },
        embedUrl(url) {
            const yt = url?.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([\w-]{11})/);
            if (yt) return `https://www.youtube.com/embed/${yt[1]}`;
            const vm = url?.match(/vimeo\.com\/(\d+)/);
            if (vm) return `https://player.vimeo.com/video/${vm[1]}`;
            return url;
        },
        videoThumb(url) {
            const yt = url?.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([\w-]{11})/);
            if (yt) return `https://img.youtube.com/vi/${yt[1]}/mqdefault.jpg`;
            return null;
        },
    },
};
</script>

<template>
    <Head title="Galerie — InAfrikaWeTrust" />

    <PublicLayout>
        <!-- HERO -->
        <section class="bg-primary-800 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                <p class="mb-1 text-[10px] font-medium uppercase tracking-widest text-gold-400">Médias</p>
                <h1 class="font-serif text-3xl font-normal text-white">Galerie</h1>
                <p class="mt-2 text-sm text-primary-200">
                    Photos et vidéos des pays à l'honneur.
                </p>
            </div>
        </section>

        <!-- Pas de médias du tout -->
        <div v-if="!countries.length" class="mx-auto max-w-5xl px-4 py-20 text-center">
            <p class="text-gray-400">Aucun média disponible pour le moment.</p>
        </div>

        <!-- Layout principal -->
        <div v-else class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Sélecteur mobile -->
            <div class="mb-4 lg:hidden">
                <select
                    class="w-full rounded-lg border-gray-200 text-sm"
                    :value="activeCountry ?? countries[0]?.slug"
                    @change="selectCountry($event.target.value)"
                >
                    <option v-for="c in countries" :key="c.slug" :value="c.slug">
                        {{ c.name }} ({{ c.media.length }})
                    </option>
                </select>
            </div>

            <div class="flex gap-6 items-start">

                <!-- Sidebar pays (desktop) -->
                <nav class="hidden w-52 shrink-0 lg:block">
                    <p class="mb-3 text-[10px] font-medium uppercase tracking-widest text-gray-400">Pays</p>
                    <ul class="space-y-1">
                        <li v-for="c in countries" :key="c.slug">
                            <button
                                type="button"
                                class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition"
                                :class="(activeCountry ?? countries[0]?.slug) === c.slug
                                    ? 'bg-primary-600 text-white font-medium'
                                    : 'text-gray-700 hover:bg-gray-100'"
                                @click="selectCountry(c.slug)"
                            >
                                <span
                                    v-if="c.flag_code"
                                    :class="`fi fi-${c.flag_code}`"
                                    style="width:1.25rem;height:.875rem;border-radius:2px;display:inline-block;flex-shrink:0"
                                ></span>
                                <span v-else class="text-base leading-none">{{ c.flag_emoji }}</span>
                                <span class="truncate">{{ c.name }}</span>
                                <span class="ml-auto text-[10px] opacity-60">{{ c.media.length }}</span>
                            </button>
                        </li>
                    </ul>
                </nav>

                <!-- Contenu pays actif -->
                <div class="min-w-0 flex-1">
                    <template v-if="visibleCountry">
                        <!-- En-tête pays -->
                        <div class="mb-5 flex flex-wrap items-center gap-3">
                            <span
                                v-if="visibleCountry.flag_code"
                                :class="`fi fi-${visibleCountry.flag_code}`"
                                style="width:2rem;height:1.3rem;border-radius:3px;display:inline-block;flex-shrink:0"
                            ></span>
                            <span v-else class="text-2xl">{{ visibleCountry.flag_emoji }}</span>
                            <div class="min-w-0">
                                <h2 class="font-serif text-lg font-normal text-gray-900 sm:text-xl">{{ visibleCountry.name }}</h2>
                                <p v-if="visibleCountry.tags" class="text-xs text-gray-500">{{ visibleCountry.tags }}</p>
                            </div>
                            <Link
                                :href="route('countries.show', visibleCountry.slug)"
                                class="ml-auto shrink-0 text-xs text-primary-600 hover:underline"
                            >
                                Voir la fiche →
                            </Link>
                        </div>

                        <!-- Photos -->
                        <div v-if="photos.length">
                            <p class="mb-3 text-[10px] font-medium uppercase tracking-widest text-gold-600">
                                Photos · {{ photos.length }}
                            </p>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                                <button
                                    v-for="photo in photos"
                                    :key="photo.id"
                                    type="button"
                                    class="group relative overflow-hidden rounded-xl bg-gray-100"
                                    @click="openLightbox(photo)"
                                >
                                    <img
                                        :src="photo.url"
                                        :alt="photo.caption || visibleCountry.name"
                                        class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                    />
                                    <div class="absolute inset-0 flex items-end bg-gradient-to-t from-black/40 to-transparent opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                                        <p v-if="photo.caption" class="truncate px-2 pb-2 text-[11px] text-white">{{ photo.caption }}</p>
                                        <svg class="absolute right-2 top-2 h-5 w-5 text-white drop-shadow" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Vidéos -->
                        <div v-if="videos.length" :class="photos.length ? 'mt-8' : ''">
                            <p class="mb-3 text-[10px] font-medium uppercase tracking-widest text-gold-600">
                                Vidéos · {{ videos.length }}
                            </p>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <button
                                    v-for="video in videos"
                                    :key="video.id"
                                    type="button"
                                    class="group relative overflow-hidden rounded-xl bg-gray-900"
                                    @click="openLightbox(video)"
                                >
                                    <div class="relative aspect-video">
                                        <img
                                            v-if="videoThumb(video.url)"
                                            :src="videoThumb(video.url)"
                                            class="h-full w-full object-cover opacity-70 transition-opacity group-hover:opacity-90"
                                            alt=""
                                        />
                                        <div v-else class="h-full w-full bg-gray-800"></div>
                                        <!-- Play button -->
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-white/90 shadow transition-transform group-hover:scale-110">
                                                <svg class="ml-1 h-5 w-5 text-primary-800" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <p v-if="video.caption" class="px-3 py-2 text-left text-xs text-gray-300">{{ video.caption }}</p>
                                </button>
                            </div>
                        </div>

                        <!-- Aucun média -->
                        <p v-if="!photos.length && !videos.length" class="text-sm text-gray-400">
                            Aucun média pour ce pays.
                        </p>
                    </template>
                </div>
            </div>
        </div>

        <!-- LIGHTBOX -->
        <transition
            enter-active-class="transition ease-out duration-150"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="lightbox"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
                @click.self="lightbox = null"
            >
                <!-- Fermer -->
                <button
                    type="button"
                    class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                    @click="lightbox = null"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                </button>

                <div class="w-full max-w-3xl">
                    <!-- Photo -->
                    <img
                        v-if="lightbox.type === 'photo'"
                        :src="lightbox.url"
                        :alt="lightbox.caption || ''"
                        class="max-h-[80vh] w-full rounded-xl object-contain"
                    />
                    <!-- Vidéo -->
                    <div v-else class="aspect-video w-full overflow-hidden rounded-xl bg-black">
                        <iframe
                            :src="embedUrl(lightbox.url)"
                            class="h-full w-full"
                            frameborder="0"
                            allowfullscreen
                            allow="autoplay"
                        ></iframe>
                    </div>
                    <!-- Légende -->
                    <p v-if="lightbox.caption" class="mt-3 text-center text-sm text-white/70">{{ lightbox.caption }}</p>
                </div>
            </div>
        </transition>
    </PublicLayout>
</template>