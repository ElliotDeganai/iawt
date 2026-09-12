<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

export default {
    components: { AdminLayout, Head, Link },
    props: { posts: Object, channels: Array, filters: Object, counts: Object },
    data() {
        return {
            search: this.filters.search || '',
            channel: this.filters.channel || '',
            filter: this.filters.filter || '',
            status: this.filters.status || 'pending',
            expandedPost: null,
        };
    },
    methods: {
        applyFilters() {
            router.get(route('admin.forum-moderation.index'), {
                search: this.search || undefined,
                channel: this.channel || undefined,
                filter: this.filter || undefined,
                status: this.status || undefined,
            }, { preserveState: true });
        },
        resetFilters() {
            this.search = ''; this.channel = ''; this.filter = ''; this.status = 'pending';
            router.get(route('admin.forum-moderation.index'));
        },
        approve(post) { router.put(route('admin.forum-moderation.approve', post.id), {}, { preserveScroll: true }); },
        hide(post) { router.put(route('admin.forum-moderation.hide', post.id), {}, { preserveScroll: true }); },
        togglePin(post) { router.put(route('admin.forum-moderation.pin', post.id), {}, { preserveScroll: true }); },
        destroy(post) {
            const label = post.title || 'cette réponse';
            if (confirm(`Supprimer « ${label} » définitivement ?`)) {
                router.delete(route('admin.forum-moderation.destroy', post.id), { preserveScroll: true });
            }
        },
        timeAgo(date) {
            const diff = Math.floor((new Date() - new Date(date)) / 1000);
            if (diff < 60) return 'à l\'instant';
            if (diff < 3600) return Math.floor(diff / 60) + ' min';
            if (diff < 86400) return Math.floor(diff / 3600) + ' h';
            return Math.floor(diff / 86400) + ' j';
        },
        initials(u) { return (u?.first_name?.[0] || '') + (u?.last_name?.[0] || ''); },
        statusLabel(s) { return { pending: 'En attente', approved: 'Approuvé', hidden: 'Masqué' }[s] || s; },
        statusColor(s) { return { pending: 'bg-amber-50 text-amber-700 border-amber-200', approved: 'bg-green-50 text-green-700 border-green-200', hidden: 'bg-gray-100 text-gray-500 border-gray-200' }[s] || ''; },
    },
};
</script>
<template>
    <Head title="Administration — Modération du forum" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">Modération du forum</h1></template>

        <!-- Status tabs -->
        <div class="mb-4 flex items-center gap-2">
            <button v-for="s in ['pending','approved','hidden']" :key="s" type="button" class="inline-flex items-center gap-1.5 rounded-full border px-4 py-2 text-xs font-medium transition" :class="status === s ? 'border-primary-600 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-500 hover:border-gray-300'" @click="status = s; applyFilters()">
                {{ statusLabel(s) }}
                <span class="rounded-full px-1.5 py-0.5 text-[10px] font-bold" :class="s === 'pending' && counts?.pending ? 'bg-amber-500 text-white' : 'bg-gray-200 text-gray-600'">{{ counts?.[s] || 0 }}</span>
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-5 flex flex-wrap items-end gap-3 rounded-xl bg-white p-4 shadow-sm border border-gray-100">
            <div>
                <label class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">Recherche</label>
                <input v-model="search" type="text" class="mt-1 w-52 rounded-md border-gray-300 text-sm" placeholder="Mot-clé…" @keydown.enter="applyFilters" />
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">Espace</label>
                <select v-model="channel" class="mt-1 rounded-md border-gray-300 text-sm" @change="applyFilters">
                    <option value="">Tous</option>
                    <option v-for="ch in channels" :key="ch.id" :value="ch.id">{{ ch.name }}</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">Type</label>
                <select v-model="filter" class="mt-1 rounded-md border-gray-300 text-sm" @change="applyFilters">
                    <option value="">Tout</option>
                    <option value="topics">Sujets</option>
                    <option value="replies">Réponses</option>
                </select>
            </div>
            <button type="button" class="rounded-md bg-primary-600 px-4 py-2 text-xs font-semibold text-white hover:bg-primary-700" @click="applyFilters">Filtrer</button>
            <button type="button" class="rounded-md border border-gray-200 px-4 py-2 text-xs text-gray-600 hover:bg-gray-50" @click="resetFilters">Réinitialiser</button>
        </div>

        <!-- Posts -->
        <div class="rounded-xl bg-white shadow-sm border border-gray-100 overflow-hidden">
            <div v-if="!posts.data?.length" class="px-6 py-12 text-center text-sm text-gray-400">Aucune publication trouvée.</div>
            <div v-for="post in posts.data" :key="post.id" class="border-b border-gray-50 last:border-0">
                <div class="flex items-start gap-3 px-5 py-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white" :class="post.parent_id ? 'bg-gray-300' : 'bg-gradient-to-br from-primary-600 to-primary-800'">{{ initials(post.user) }}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span v-if="post.is_pinned" class="text-xs">📌</span>
                            <span v-if="post.parent_id" class="rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-medium text-gray-500">Réponse</span>
                            <span class="text-sm font-semibold text-gray-800">{{ post.title || '(sans titre)' }}</span>
                            <span class="rounded-full bg-primary-50 px-2 py-0.5 text-[10px] font-medium text-primary-700">{{ post.channel?.name }}</span>
                            <span class="rounded-full border px-2 py-0.5 text-[10px] font-medium" :class="statusColor(post.status)">{{ statusLabel(post.status) }}</span>
                        </div>
                        <div class="mt-2 rounded-lg border border-gray-100 bg-gray-50/50 px-4 py-3">
                            <div class="prose prose-sm max-w-none text-gray-700" :class="expandedPost !== post.id ? 'line-clamp-3' : ''" v-html="post.body"></div>
                            <button v-if="post.body?.length > 200" type="button" class="mt-1 text-[10px] font-medium text-primary-600 hover:underline" @click="expandedPost = expandedPost === post.id ? null : post.id">
                                {{ expandedPost === post.id ? 'Réduire ↑' : 'Voir le message complet ↓' }}
                            </button>
                        </div>
                        <div v-if="post.attachments?.length" class="mt-2 flex flex-wrap gap-1.5">
                            <a v-for="a in post.attachments" :key="a.id" :href="a.path ? `/storage/${a.path}` : a.url" target="_blank" class="inline-flex items-center gap-1 rounded-md border border-gray-200 bg-white px-2 py-1 text-[10px] text-gray-600 hover:bg-gray-50">
                                <svg v-if="a.type==='image'" class="h-3 w-3 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 15l4-4 4 4 4-6 5 6"/></svg>
                                <svg v-else class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/><path d="M10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                {{ a.original_name || a.url || 'PJ' }}
                            </a>
                        </div>
                        <div class="mt-2 flex items-center gap-3 text-[10px] text-gray-400">
                            <span class="font-medium text-gray-600">{{ post.user?.first_name }} {{ post.user?.last_name }}</span>
                            <span>{{ post.user?.email }}</span>
                            <span>{{ timeAgo(post.created_at) }}</span>
                            <span v-if="!post.parent_id">{{ post.replies_count }} rép.</span>
                            <span v-if="post.attachments?.length">{{ post.attachments.length }} PJ</span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 shrink-0">
                        <button v-if="post.status !== 'approved'" type="button" class="rounded-md border border-green-300 bg-green-50 px-3 py-1 text-[10px] font-semibold text-green-700 hover:bg-green-100 transition" @click="approve(post)">Approuver</button>
                        <button v-if="post.status !== 'hidden'" type="button" class="rounded-md border border-gray-200 px-3 py-1 text-[10px] font-medium text-gray-500 hover:bg-gray-50 transition" @click="hide(post)">Masquer</button>
                        <button v-if="!post.parent_id" type="button" class="rounded-md border px-3 py-1 text-[10px] font-medium transition" :class="post.is_pinned ? 'border-gold-300 bg-gold-50 text-gold-700' : 'border-gray-200 text-gray-500 hover:bg-gray-50'" @click="togglePin(post)">{{ post.is_pinned ? 'Désépingler' : 'Épingler' }}</button>
                        <button type="button" class="rounded-md border border-red-200 px-3 py-1 text-[10px] font-medium text-red-600 hover:bg-red-50 transition" @click="destroy(post)">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="posts.links?.length > 3" class="mt-4 flex items-center justify-center gap-1">
            <Link v-for="link in posts.links" :key="link.label" :href="link.url || '#'" class="rounded-md px-3 py-1.5 text-xs" :class="link.active ? 'bg-primary-600 text-white font-semibold' : link.url ? 'text-gray-600 hover:bg-gray-100' : 'text-gray-300 cursor-default'" v-html="link.label" />
        </div>
    </AdminLayout>
</template>