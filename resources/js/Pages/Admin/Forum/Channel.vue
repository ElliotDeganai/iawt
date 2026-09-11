<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
export default {
    components: { PublicLayout, Head, Link },
    props: { channel: Object, posts: Object },
    data() {
        return {
            showForm: false,
            form: useForm({ title: '', body: '', attachments: [], links: [] }),
            linkInput: '',
        };
    },
    methods: {
        submit() {
            this.form.post(route('forum.store', this.channel.slug), {
                onSuccess: () => { this.form.reset(); this.showForm = false; },
                forceFormData: true,
            });
        },
        addLink() {
            if (this.linkInput && this.form.links.length < 3) {
                this.form.links.push(this.linkInput);
                this.linkInput = '';
            }
        },
        removeLink(i) { this.form.links.splice(i, 1); },
        onFiles(e) { this.form.attachments = Array.from(e.target.files).slice(0, 5); },
        timeAgo(date) {
            const diff = Math.floor((new Date() - new Date(date)) / 1000);
            if (diff < 60) return 'à l\'instant';
            if (diff < 3600) return Math.floor(diff / 60) + ' min';
            if (diff < 86400) return Math.floor(diff / 3600) + ' h';
            return Math.floor(diff / 86400) + ' j';
        },
        initials(u) { return (u?.first_name?.[0] || '') + (u?.last_name?.[0] || ''); },
    },
};
</script>
<template>
    <Head :title="channel.name + ' — Le coin des palabres'" />
    <PublicLayout>
        <section class="bg-primary-800 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                <Link :href="route('forum.index')" class="text-xs text-primary-300 hover:text-white transition">← Le coin des palabres</Link>
                <h1 class="mt-2 font-serif text-2xl text-white">{{ channel.name }}</h1>
                <p class="mt-1 text-sm text-primary-200">{{ channel.description }}</p>
            </div>
        </section>

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- New post button -->
            <button v-if="!showForm && $page.props.auth?.user" type="button" class="mb-6 inline-flex items-center gap-2 rounded-full bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 transition" @click="showForm = true">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                Nouveau sujet
            </button>

            <!-- New post form -->
            <form v-if="showForm" class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm space-y-4" @submit.prevent="submit">
                <input v-model="form.title" type="text" class="w-full rounded-lg border-gray-200 text-sm font-medium" placeholder="Titre du sujet" />
                <textarea v-model="form.body" rows="4" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Votre message…"></textarea>
                <div class="flex flex-wrap items-center gap-3">
                    <label class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-600 cursor-pointer hover:bg-gray-50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                        Joindre
                        <input type="file" multiple class="hidden" @change="onFiles" />
                    </label>
                    <div class="flex items-center gap-1.5">
                        <input v-model="linkInput" type="url" class="rounded-lg border-gray-200 text-xs w-48" placeholder="https://..." @keydown.enter.prevent="addLink" />
                        <button type="button" class="text-xs text-primary-600 hover:underline" @click="addLink">+ Lien</button>
                    </div>
                </div>
                <div v-if="form.attachments.length" class="flex flex-wrap gap-1.5">
                    <span v-for="(f, i) in form.attachments" :key="i" class="rounded-full bg-gray-100 px-2.5 py-1 text-[10px] text-gray-600">{{ f.name }}</span>
                </div>
                <div v-if="form.links.length" class="flex flex-wrap gap-1.5">
                    <span v-for="(l, i) in form.links" :key="i" class="rounded-full bg-primary-50 px-2.5 py-1 text-[10px] text-primary-700">{{ l }} <button type="button" class="ml-1 text-primary-400" @click="removeLink(i)">×</button></span>
                </div>
                <div class="flex gap-2">
                    <button type="submit" :disabled="form.processing" class="rounded-full bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">Publier</button>
                    <button type="button" class="rounded-full border border-gray-200 px-5 py-2 text-sm text-gray-600 hover:bg-gray-50" @click="showForm = false; form.reset()">Annuler</button>
                </div>
            </form>

            <!-- Posts -->
            <div class="space-y-3">
                <Link v-for="p in posts.data" :key="p.id" :href="route('forum.post', [channel.slug, p.id])" class="block rounded-2xl border border-gray-100 bg-white px-5 py-4 shadow-sm hover:shadow-md transition group">
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary-600 to-primary-800 text-xs font-bold text-white">{{ initials(p.user) }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span v-if="p.is_pinned" class="text-gold-500 text-xs">📌</span>
                                <h3 class="text-sm font-semibold text-gray-800 group-hover:text-primary-700 transition">{{ p.title }}</h3>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ p.body }}</p>
                            <div class="mt-2 flex items-center gap-3 text-[10px] text-gray-400">
                                <span class="font-medium text-gray-600">{{ p.user?.first_name }} {{ p.user?.last_name }}</span>
                                <span>{{ timeAgo(p.created_at) }}</span>
                                <span>{{ p.replies_count }} réponse{{ p.replies_count > 1 ? 's' : '' }}</span>
                                <span v-if="p.attachments?.length">{{ p.attachments.length }} pièce{{ p.attachments.length > 1 ? 's' : '' }} jointe{{ p.attachments.length > 1 ? 's' : '' }}</span>
                            </div>
                        </div>
                    </div>
                </Link>
                <p v-if="!posts.data?.length" class="text-center py-12 text-sm text-gray-400">Aucun sujet dans cet espace. Soyez le premier à publier !</p>
            </div>
        </div>
    </PublicLayout>
</template>
