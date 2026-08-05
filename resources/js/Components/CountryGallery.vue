<script>
import { router } from '@inertiajs/vue3';

export default {
    props: {
        country: Object,
    },
    data() {
        return {
            uploading: false,
            videoUrl: '',
            videoCaption: '',
            addingVideo: false,
            editingCaption: null,
            captionDraft: '',
        };
    },
    computed: {
        media() {
            return this.country.media || [];
        },
    },
    methods: {
        onFilesChange(event) {
            const files = Array.from(event.target.files);
            if (!files.length) return;

            const data = new FormData();
            files.forEach((f) => data.append('files[]', f));

            this.uploading = true;
            router.post(route('admin.countries.media.store', this.country.slug), data, {
                forceFormData: true,
                preserveScroll: true,
                onFinish: () => {
                    this.uploading = false;
                    event.target.value = '';
                },
            });
        },

        addVideo() {
            if (!this.videoUrl.trim()) return;
            router.post(
                route('admin.countries.media.store-video', this.country.slug),
                { video_url: this.videoUrl, caption: this.videoCaption },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.videoUrl = '';
                        this.videoCaption = '';
                        this.addingVideo = false;
                    },
                }
            );
        },

        setCover(mediaId) {
            router.put(
                route('admin.countries.media.set-cover', { country: this.country.slug, media: mediaId }),
                {},
                { preserveScroll: true }
            );
        },

        destroy(mediaId) {
            router.delete(
                route('admin.countries.media.destroy', { country: this.country.slug, media: mediaId }),
                { preserveScroll: true }
            );
        },

        startCaption(item) {
            this.editingCaption = item.id;
            this.captionDraft = item.caption || '';
        },

        saveCaption(item) {
            router.patch(
                route('admin.countries.media.caption', { country: this.country.slug, media: item.id }),
                { caption: this.captionDraft },
                {
                    preserveScroll: true,
                    onSuccess: () => { this.editingCaption = null; },
                }
            );
        },

        isCover(item) {
            return this.country.cover_media_id === item.id;
        },

        videoThumb(url) {
            const yt = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([\w-]{11})/);
            if (yt) return `https://img.youtube.com/vi/${yt[1]}/mqdefault.jpg`;
            return null;
        },
    },
};
</script>

<template>
    <div class="space-y-4">

        <!-- Upload photos -->
        <div class="flex flex-wrap items-center gap-3">
            <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Ajouter des photos
                <input type="file" accept="image/*" multiple class="hidden" @change="onFilesChange" />
            </label>

            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50"
                @click="addingVideo = !addingVideo"
            >
                <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Ajouter une vidéo
            </button>

            <span v-if="uploading" class="text-xs text-gray-500">Envoi en cours…</span>
        </div>

        <!-- Formulaire vidéo -->
        <div v-if="addingVideo" class="rounded-lg border border-gray-200 bg-gray-50 p-4 space-y-3">
            <p class="text-xs font-medium text-gray-700">URL YouTube ou Vimeo</p>
            <input
                v-model="videoUrl"
                type="url"
                placeholder="https://www.youtube.com/watch?v=..."
                class="w-full rounded-md border-gray-300 text-sm"
            />
            <input
                v-model="videoCaption"
                type="text"
                placeholder="Légende (optionnel)"
                class="w-full rounded-md border-gray-300 text-sm"
            />
            <div class="flex gap-2">
                <button
                    type="button"
                    class="rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700"
                    @click="addVideo"
                >
                    Ajouter
                </button>
                <button type="button" class="text-xs text-gray-500 hover:underline" @click="addingVideo = false">Annuler</button>
            </div>
        </div>

        <!-- Grille des médias -->
        <div v-if="media.length" class="grid grid-cols-3 gap-3 sm:grid-cols-4 lg:grid-cols-5">
            <div
                v-for="item in media"
                :key="item.id"
                class="group relative overflow-hidden rounded-lg border-2 bg-gray-50"
                :class="isCover(item) ? 'border-gold-500' : 'border-transparent'"
            >
                <!-- Vignette photo -->
                <div v-if="item.type === 'photo'" class="aspect-square">
                    <img :src="item.url" class="h-full w-full object-cover" alt="" />
                </div>

                <!-- Vignette vidéo -->
                <div v-else class="relative aspect-square bg-gray-800">
                    <img v-if="videoThumb(item.url)" :src="videoThumb(item.url)" class="h-full w-full object-cover opacity-70" alt="" />
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="h-8 w-8 text-white drop-shadow" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                </div>

                <!-- Badge cover -->
                <span v-if="isCover(item)" class="absolute left-1 top-1 rounded-full bg-gold-500 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide text-primary-900">
                    Cover
                </span>

                <!-- Overlay actions -->
                <div class="absolute inset-0 flex flex-col justify-between bg-black/50 p-1.5 opacity-0 transition-opacity group-hover:opacity-100">
                    <div class="flex justify-end">
                        <button
                            type="button"
                            class="rounded bg-red-600 p-1 text-white hover:bg-red-700"
                            title="Supprimer"
                            @click="destroy(item.id)"
                        >
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="flex flex-col gap-1">
                        <button
                            v-if="item.type === 'photo' && !isCover(item)"
                            type="button"
                            class="w-full rounded bg-gold-500 px-1.5 py-1 text-[10px] font-semibold text-primary-900 hover:bg-gold-400"
                            @click="setCover(item.id)"
                        >
                            Choisir comme cover
                        </button>
                        <button
                            type="button"
                            class="w-full rounded bg-white/20 px-1.5 py-1 text-[10px] font-medium text-white hover:bg-white/30"
                            @click="startCaption(item)"
                        >
                            Légende
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <p v-else class="text-sm text-gray-400">Aucun média pour le moment.</p>

        <!-- Modal légende -->
        <div v-if="editingCaption !== null" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="editingCaption = null">
            <div class="w-80 rounded-xl bg-white p-5 shadow-lg">
                <p class="mb-3 text-sm font-medium text-gray-800">Modifier la légende</p>
                <input
                    v-model="captionDraft"
                    type="text"
                    maxlength="191"
                    class="w-full rounded-md border-gray-300 text-sm"
                    placeholder="Légende (optionnel)"
                    autofocus
                />
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" class="text-sm text-gray-500 hover:underline" @click="editingCaption = null">Annuler</button>
                    <button
                        type="button"
                        class="rounded-md bg-primary-600 px-4 py-1.5 text-sm font-semibold text-white hover:bg-primary-700"
                        @click="saveCaption({ id: editingCaption })"
                    >
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
