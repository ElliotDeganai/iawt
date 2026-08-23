<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const MONTHS_FR = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
const DAYS_FR = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];

export default {
    components: { PublicLayout, Head },
    props: {
        events: Array,
        categories: { type: Array, default: () => [] },
        year:   Number,
        month:  Number,
    },
    data() {
        return {
            selectedEvent: null,
            activeFilter: null,
        };
    },
    computed: {
        monthLabel() {
            return `${MONTHS_FR[this.month - 1]} ${this.year}`;
        },
        filteredEvents() {
            if (!this.activeFilter) return this.events;
            return this.events.filter(e => e.event_category_id === this.activeFilter);
        },
        eventsByDate() {
            const map = {};
            for (const e of this.filteredEvents) {
                if (!map[e.date]) map[e.date] = [];
                map[e.date].push(e);
            }
            return map;
        },
        cells() {
            const firstDay = new Date(this.year, this.month - 1, 1);
            const startPad = (firstDay.getDay() + 6) % 7;
            const daysInMonth = new Date(this.year, this.month, 0).getDate();
            const prevMonthDays = new Date(this.year, this.month - 1, 0).getDate();
            const cells = [];
            for (let i = startPad - 1; i >= 0; i--) {
                cells.push({ day: prevMonthDays - i, current: false, dateStr: null });
            }
            for (let d = 1; d <= daysInMonth; d++) {
                const dateStr = `${this.year}-${String(this.month).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                cells.push({ day: d, current: true, dateStr, isToday: dateStr === this.todayStr });
            }
            const remaining = 42 - cells.length;
            for (let d = 1; d <= remaining; d++) {
                cells.push({ day: d, current: false, dateStr: null });
            }
            return cells;
        },
        todayStr() {
            const t = new Date();
            return `${t.getFullYear()}-${String(t.getMonth()+1).padStart(2,'0')}-${String(t.getDate()).padStart(2,'0')}`;
        },
        days() { return DAYS_FR; },
    },
    methods: {
        navigate(offset) {
            let m = this.month + offset;
            let y = this.year;
            if (m < 1) { m = 12; y--; }
            if (m > 12) { m = 1; y++; }
            router.get(route('agenda.index'), { year: y, month: m }, { preserveState: true, preserveScroll: true });
        },
        eventsFor(dateStr) {
            return this.eventsByDate[dateStr] || [];
        },
        selectEvent(event) {
            this.selectedEvent = this.selectedEvent?.id === event.id ? null : event;
        },
        formatDate(dateStr) {
            const d = new Date(dateStr + 'T00:00:00');
            return d.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        },
        toggleFilter(catId) {
            this.activeFilter = this.activeFilter === catId ? null : catId;
            this.selectedEvent = null;
        },
        catColor(event) {
            return event.category?.color || null;
        },
        eventBorderColor(event) {
            return this.catColor(event) || (event.is_featured ? '' : '');
        },
    },
};
</script>

<template>
    <Head title="Agenda — InAfrikaWeTrust" />

    <PublicLayout>
        <!-- HERO -->
        <section class="bg-primary-800 px-4 py-10 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                <p class="mb-1 text-[10px] font-medium uppercase tracking-widest text-gold-400">Programme</p>
                <h1 class="font-serif text-3xl font-normal text-white">Agenda</h1>
            </div>
        </section>

        <div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- Filtre catégories -->
            <div v-if="categories.length" class="mb-6">
                <p class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Filtrer par catégorie</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs transition" :class="!activeFilter ? 'border-primary-600 bg-primary-50 text-primary-700 font-medium' : 'border-gray-200 text-gray-500 hover:border-gray-300'" @click="toggleFilter(null)">Tous</button>
                    <button v-for="cat in categories" :key="cat.id" type="button" class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-xs transition" :class="activeFilter === cat.id ? 'font-medium' : 'border-gray-200 text-gray-600 hover:border-gray-300'" :style="activeFilter === cat.id ? { borderColor: cat.color, backgroundColor: cat.color + '15', color: cat.color } : {}" @click="toggleFilter(cat.id)">
                        <span class="h-2.5 w-2.5 rounded-full shrink-0" :style="{ background: cat.color }"></span>
                        {{ cat.name }}
                    </button>
                </div>
            </div>

            <!-- Calendrier -->
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

                <!-- Header mois + nav -->
                <div class="flex items-center justify-between bg-primary-800 px-5 py-4">
                    <h2 class="font-serif text-lg font-normal capitalize text-white">{{ monthLabel }}</h2>
                    <div class="flex items-center gap-2">
                        <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition" @click="navigate(-1)">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>
                        <button type="button" class="flex h-8 w-8 items-center justify-center rounded-full border border-white/20 text-white hover:bg-white/10 transition" @click="navigate(1)">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Jours de semaine -->
                <div class="grid grid-cols-7 border-b border-gray-100 bg-primary-900/5">
                    <div v-for="d in days" :key="d" class="py-2 text-center text-[10px] font-medium uppercase tracking-widest text-gray-400">{{ d }}</div>
                </div>

                <!-- Grille des jours -->
                <div class="grid grid-cols-7">
                    <div
                        v-for="(cell, i) in cells" :key="i"
                        class="relative min-h-[60px] border-b border-r border-gray-100 p-1 last:border-r-0 sm:min-h-[88px] sm:p-1.5"
                        :class="[
                            !cell.current ? 'bg-gray-50/60' : '',
                            (i + 1) % 7 === 0 ? 'border-r-0' : '',
                        ]"
                    >
                        <div class="mb-1 flex items-center justify-center">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full text-[11px]" :class="[cell.isToday ? 'bg-primary-600 font-semibold text-white' : '', !cell.current ? 'text-gray-300' : 'text-gray-500']">{{ cell.day }}</span>
                        </div>

                        <template v-if="cell.dateStr">
                            <div
                                v-for="event in eventsFor(cell.dateStr)"
                                :key="event.id"
                                class="mb-0.5 cursor-pointer rounded border-l-2 px-1.5 py-0.5 transition"
                                :style="catColor(event) ? { borderColor: catColor(event), backgroundColor: catColor(event) + '12' } : {}"
                                :class="[
                                    !catColor(event) && event.is_featured ? 'border-gold-500 bg-gold-50 hover:bg-gold-100' : '',
                                    !catColor(event) && !event.is_featured ? 'border-primary-600 bg-primary-50 hover:bg-primary-100' : '',
                                    catColor(event) ? 'hover:opacity-80' : '',
                                    selectedEvent?.id === event.id ? 'ring-1 ring-offset-0 ring-gray-400' : '',
                                ]"
                                @click="selectEvent(event)"
                            >
                                <p class="truncate text-[10px] font-medium leading-tight" :style="catColor(event) ? { color: catColor(event) } : {}" :class="!catColor(event) ? (event.is_featured ? 'text-gold-800' : 'text-primary-800') : ''">{{ event.title }}</p>
                                <p v-if="event.time" class="text-[9px] leading-tight" :style="catColor(event) ? { color: catColor(event), opacity: 0.7 } : {}" :class="!catColor(event) ? (event.is_featured ? 'text-gold-600' : 'text-primary-600') : ''">{{ event.time }}</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Drawer de détail -->
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-2"
            >
                <div v-if="selectedEvent" class="mt-4 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span v-if="selectedEvent.category" class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-medium" :style="{ background: catColor(selectedEvent) + '18', color: catColor(selectedEvent) }">
                                    <span class="h-1.5 w-1.5 rounded-full" :style="{ background: catColor(selectedEvent) }"></span>
                                    {{ selectedEvent.category.name }}
                                </span>
                                <span v-if="selectedEvent.is_featured" class="inline-flex items-center rounded-full bg-gold-100 px-2 py-0.5 text-[10px] font-semibold text-gold-700">En vedette</span>
                            </div>
                            <h3 class="mt-1 font-serif text-xl font-normal text-gray-900">{{ selectedEvent.title }}</h3>
                            <p class="mt-0.5 text-sm text-gray-500 capitalize">
                                {{ formatDate(selectedEvent.date) }}
                                <span v-if="selectedEvent.time"> · {{ selectedEvent.time }}</span>
                            </p>
                        </div>
                        <button type="button" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-gray-200 text-gray-400 hover:bg-gray-50" @click="selectedEvent = null">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-5 sm:grid-cols-2">
                        <div v-if="selectedEvent.image" class="overflow-hidden rounded-xl">
                            <img :src="selectedEvent.image" class="aspect-video w-full object-cover" :alt="selectedEvent.title" />
                        </div>
                        <div v-else class="flex aspect-video items-center justify-center rounded-xl bg-gray-50">
                            <svg class="h-10 w-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9l4-4 4 4 4-6 4 6"/></svg>
                        </div>
                        <div class="flex flex-col justify-between">
                            <div v-if="selectedEvent.description" class="prose prose-sm max-w-none text-gray-600" v-html="selectedEvent.description"></div>
                            <a v-if="selectedEvent.url" :href="selectedEvent.url" target="_blank" rel="noopener" class="mt-4 inline-flex items-center gap-2 self-start rounded-full bg-primary-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">
                                S'inscrire / En savoir plus
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- Légende -->
            <div class="mt-4 flex flex-wrap items-center gap-4">
                <div v-for="cat in categories" :key="cat.id" class="flex items-center gap-1.5">
                    <span class="h-3 w-3 rounded-sm border-l-2" :style="{ borderColor: cat.color, backgroundColor: cat.color + '12' }"></span>
                    <span class="text-xs text-gray-500">{{ cat.name }}</span>
                </div>
                <div v-if="!categories.length" class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="h-3 w-3 rounded-sm border-l-2 border-primary-600 bg-primary-50"></span>
                        <span class="text-xs text-gray-500">Événement standard</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-3 w-3 rounded-sm border-l-2 border-gold-500 bg-gold-50"></span>
                        <span class="text-xs text-gray-500">Événement vedette</span>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>