<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import RichTextEditor from '@/Components/RichTextEditor.vue';
export default {
    components: { PublicLayout, Head, Link, RichTextEditor },
    props: { channel: Object, post: Object },
    data() {
        return {
            form: useForm({ body: '', attachments: [] }),
            flashMsg: null,
        };
    },
    computed: {
        visibleReplies() {
            const userId = this.$page?.props?.auth?.user?.id;
            return (this.post.replies || []).filter(r =>
                r.status === 'approved' || r.user_id === userId
            );
        },
    },
    mounted() {
        this.flashMsg = this.$page?.props?.flash?.success || this.$attrs?.flash?.success || null;
    },
    methods: {
        submitReply() {
            this.form.post(route('forum.reply', [this.channel.slug, this.post.id]), {
                onSuccess: (page) => {
                    this.form.reset();
                    this.flashMsg = page?.props?.flash?.success || 'Votre réponse a été soumise.';
                },
                forceFormData: true,
            });
        },
        onFiles(e) { this.form.attachments = Array.from(e.target.files).slice(0, 3); },
        timeAgo(date) {
            const diff = Math.floor((new Date() - new Date(date)) / 1000);
            if (diff < 3600) return Math.max(1, Math.floor(diff / 60)) + ' min';
            if (diff < 86400) return Math.floor(diff / 3600) + ' h';
            return Math.floor(diff / 86400) + ' j';
        },
        initials(u) { return (u?.first_name?.[0] || '') + (u?.last_name?.[0] || ''); },
        attachUrl(a) { return a.path ? `/storage/${a.path}` : a.url; },
    },
};
</script>
<template>
    <Head :title="post.title + ' — Le coin des palabres'" />
    <PublicLayout>
        <section class="bg-primary-800 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <Link :href="route('forum.channel', channel.slug)" class="text-xs text-primary-300 hover:text-white transition">← {{ channel.name }}</Link>
                <h1 class="mt-2 font-serif text-xl text-white">{{ post.title }}</h1>
            </div>
        </section>
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <!-- Flash -->
            <div v-if="flashMsg" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3 flex items-center gap-3">
                <svg class="h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <p class="text-sm text-green-700">{{ flashMsg }}</p>
            </div>
            <!-- Original post -->
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm mb-6">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary-600 to-primary-800 text-xs font-bold text-white">{{ initials(post.user) }}</div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-semibold text-gray-800">{{ post.user?.first_name }} {{ post.user?.last_name }}</p>
                            <span class="text-[10px] text-gray-400">{{ timeAgo(post.created_at) }}</span>
                            <span v-if="post.status === 'pending'" class="rounded-full bg-amber-100 border border-amber-200 px-2 py-0.5 text-[9px] font-medium text-amber-700">En attente de validation</span>
                            <span v-if="post.status === 'hidden'" class="rounded-full bg-red-50 border border-red-200 px-2 py-0.5 text-[9px] font-medium text-red-600">Masqué</span>
                        </div>
                        <div class="mt-3 prose prose-sm max-w-none text-gray-700" v-html="post.body"></div>
                        <div v-if="post.attachments?.length" class="mt-3 flex flex-wrap gap-2">
                            <a v-for="a in post.attachments" :key="a.id" :href="attachUrl(a)" target="_blank" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-600 hover:bg-gray-50">
                                <svg v-if="a.type==='image'" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 15l4-4 4 4 4-6 5 6"/></svg>
                                <svg v-else class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/><path d="M10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                {{ a.original_name || a.url || 'Pièce jointe' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Replies -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">{{ visibleReplies.length }} réponse{{ visibleReplies.length > 1 ? 's' : '' }}</p>
            <div class="space-y-3 mb-6">
                <div v-for="r in visibleReplies" :key="r.id" class="rounded-xl border border-gray-100 bg-white px-5 py-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gray-100 text-[10px] font-semibold text-gray-500">{{ initials(r.user) }}</div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-gray-800">{{ r.user?.first_name }} {{ r.user?.last_name }}</p>
                                <span class="text-[10px] text-gray-400">{{ timeAgo(r.created_at) }}</span>
                                <span v-if="r.status === 'pending'" class="rounded-full bg-amber-100 border border-amber-200 px-2 py-0.5 text-[9px] font-medium text-amber-700">En attente</span>
                                <span v-if="r.status === 'hidden'" class="rounded-full bg-red-50 border border-red-200 px-2 py-0.5 text-[9px] font-medium text-red-600">Masqué</span>
                            </div>
                            <div class="mt-2 prose prose-sm max-w-none text-gray-600" v-html="r.body"></div>
                            <div v-if="r.attachments?.length" class="mt-2 flex flex-wrap gap-1.5">
                                <a v-for="a in r.attachments" :key="a.id" :href="attachUrl(a)" target="_blank" class="text-xs text-primary-600 hover:underline">{{ a.original_name || a.url }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply form -->
            <form v-if="$page.props.auth?.user" class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm" @submit.prevent="submitReply">
                <RichTextEditor v-model="form.body" placeholder="Votre réponse…" min-height="100px" />
                <div class="mt-3 flex items-center gap-3">
                    <button type="submit" :disabled="form.processing || !form.body || form.body === '<p></p>'" class="rounded-full bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-50">Répondre</button>
                    <label class="inline-flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer hover:text-gray-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32"/></svg>
                        Joindre
                        <input type="file" multiple class="hidden" @change="onFiles" />
                    </label>
                </div>
            </form>
        </div>
    </PublicLayout>
</template>