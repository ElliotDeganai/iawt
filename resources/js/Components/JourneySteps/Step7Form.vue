<script>

const PRE_REQUIS = [
    'Projet validé après le Business Lab',
    'Forme juridique choisie',
    'Nom / dénomination de l\'entreprise choisi',
    'Associés et répartition des parts définis (si nécessaire)',
    'Siège social identifié',
    'Capital et apports déterminés',
    'Dirigeant(s) identifié(s)',
    'Activité principale et activités secondaires définies',
    'Autorisations ou agréments nécessaires identifiés',
    'Documents personnels réunis',
    'Statuts et PV de constitution prêts (si société)',
    'Autorisations diverses réunies',
];

export default {
    props: {
        modelValue: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
        countrySheets: { type: Array, default: () => [] },
    },
    computed: {
        d() { return this.modelValue; },
        countryData() {
            const sheet = this.countrySheets.find(s => s.country_name === this.d.creation_country);
            if (!sheet) return null;
            return {
                flag_code: sheet.flag_code,
                title: sheet.title,
                intro: sheet.intro,
                warning: sheet.warning,
                actors: sheet.actors || [],
                organism: sheet.organism_name ? {
                    name: sheet.organism_name,
                    full: sheet.organism_full,
                    desc: sheet.organism_desc,
                    address: sheet.organism_address,
                    hours: sheet.organism_hours,
                    website: sheet.organism_website,
                } : null,
                documents: sheet.documents || [],
                individual_steps: sheet.individual_steps || [],
                company_steps: sheet.company_steps || [],
                fees: sheet.fees,
                sector_auth: sheet.sector_auth,
            };
        },
        countries() { return this.countrySheets.map(s => ({ flag_code: s.flag_code, name: s.country_name })); },
        preRequis() { return PRE_REQUIS; },
        trackSteps() {
            if (!this.countryData) return [];
            return this.d.track === 'individual' ? this.countryData.individual_steps : this.countryData.company_steps;
        },
    },
    methods: {
        hasErr(k) { return !!this.errors[k]; },
        errMsg(k) { return this.errors[k] || ''; },
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        toggleChecklist(val) {
            const arr = this.d.checklist || [];
            const idx = arr.indexOf(val);
            this.update('checklist', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
        toggleDocChecklist(val) {
            const arr = this.d.doc_checklist || [];
            const idx = arr.indexOf(val);
            this.update('doc_checklist', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
        toggleStepChecklist(val) {
            const arr = this.d.step_checklist || [];
            const idx = arr.indexOf(val);
            this.update('step_checklist', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
    },
};
</script>

<template>
    <div class="space-y-5">
        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 leading-relaxed">
            <p class="font-medium text-gray-700 mb-1">Étape 7 — De la décision à l'immatriculation</p>
            <p class="italic">Formalisez votre projet et lancez les démarches administratives de création d'entreprise.</p>
        </div>

        <!-- ══ 7.1 Pré-requis ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span><p class="text-sm font-medium text-gray-800">Avant de déposer le dossier</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Vérification des conditions</p><p class="mt-0.5 text-[10px] text-gray-400">Cochez chaque élément une fois qu'il est prêt.</p></div>
            <div class="space-y-1">
                <label v-for="item in preRequis" :key="item" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.checklist||[]).includes(item)" @change="toggleChecklist(item)" class="h-4 w-4 rounded text-primary-600" />
                    <span class="text-sm" :class="(d.checklist||[]).includes(item)?'text-gray-900 font-medium':'text-gray-600'">{{ item }}</span>
                </label>
            </div>
            <div class="mt-3 flex items-center gap-2 rounded-lg bg-primary-50 px-3 py-2">
                <span class="text-xs font-bold text-primary-700">{{ (d.checklist||[]).length }} / {{ preRequis.length }}</span>
                <div class="flex-1 h-1.5 bg-primary-100 rounded-full overflow-hidden"><div class="h-full bg-primary-600 rounded-full transition-all" :style="{ width: ((d.checklist||[]).length / preRequis.length * 100) + '%' }"></div></div>
            </div>
        </div>

        <!-- ══ 7.2 Pays de création ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span><p class="text-sm font-medium text-gray-800">Choisir le pays de création</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Pays d'immatriculation</p><p class="mt-0.5 text-[10px] text-gray-400">Sélectionnez le pays. La fiche technique des démarches s'affiche automatiquement.</p></div>
            <div class="flex flex-wrap gap-2" :class="hasErr('creation_country') ? 'rounded-lg border border-red-300 bg-red-50/50 p-2' : ''">
                <button v-for="c in countries" :key="c.name" type="button" class="flex items-center gap-2 rounded-lg border px-4 py-2.5 text-sm transition" :class="d.creation_country===c.name?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-600 hover:border-primary-300'" @click="update('creation_country',c.name)">
                    <span v-if="c.flag_code" :class="`fi fi-${c.flag_code}`" class="h-5 w-7 rounded-sm shrink-0"></span>
                    {{ c.name }}
                </button>
            </div>
            <p v-if="hasErr('creation_country')" class="mt-2 text-xs text-red-600">{{ errMsg('creation_country') }}</p>
        </div>

        <!-- ══ 7.3 Fiche technique pays (affichée si un pays est sélectionné) ══ -->
        <template v-if="countryData">
            <div class="rounded-xl border-2 border-primary-200 bg-white overflow-hidden">
                <!-- Header -->
                <div class="bg-primary-800 px-5 py-4">
                    <p class="font-serif text-lg text-white flex items-center gap-3">
                        <span v-if="countryData.flag_code" :class="`fi fi-${countryData.flag_code}`" class="h-7 w-10 rounded-sm"></span>
                        {{ countryData.title || ('Fiche technique — ' + d.creation_country) }}
                    </p>
                    <p class="mt-1 text-xs text-primary-200 leading-relaxed">{{ countryData.intro }}</p>
                </div>

                <div class="p-5 space-y-5">
                    <!-- Avertissement -->
                    <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2.5 flex items-start gap-2">
                        <svg class="h-4 w-4 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.58 14.88A1 1 0 002.58 20h18.84a1 1 0 00.86-1.26L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                        <p class="text-xs text-amber-700">{{ countryData.warning }}</p>
                    </div>

                    <!-- Carte des acteurs -->
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Carte des acteurs</p>
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50"><tr><th class="px-3 py-2 text-left font-medium text-gray-500">Organisme</th><th class="px-3 py-2 text-left font-medium text-gray-500">Rôle</th></tr></thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="a in countryData.actors" :key="a.name"><td class="px-3 py-2 font-medium text-gray-800">{{ a.name }}</td><td class="px-3 py-2 text-gray-600">{{ a.role }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Organisme compétent -->
                    <div v-if="countryData.organism" class="rounded-lg border border-primary-100 bg-primary-50/50 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">① Organisme compétent</p>
                        <p class="text-sm font-bold text-primary-800">{{ countryData.organism.name }} <span class="font-normal text-gray-500">— {{ countryData.organism.full }}</span></p>
                        <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ countryData.organism.desc }}</p>
                        <div class="mt-3 grid grid-cols-1 gap-1.5 sm:grid-cols-3 text-xs">
                            <div class="flex items-start gap-1.5">
                                <svg class="h-3.5 w-3.5 shrink-0 mt-0.5 text-primary-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-gray-600">{{ countryData.organism.address }}</span>
                            </div>
                            <div class="flex items-start gap-1.5">
                                <svg class="h-3.5 w-3.5 shrink-0 mt-0.5 text-primary-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-gray-600">{{ countryData.organism.hours }}</span>
                            </div>
                            <div v-if="countryData.organism.website" class="flex items-start gap-1.5">
                                <svg class="h-3.5 w-3.5 shrink-0 mt-0.5 text-primary-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                                <span class="text-primary-600">{{ countryData.organism.website }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Documents à préparer -->
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">② Documents à préparer — Kit Création</p>
                        <div class="space-y-1">
                            <label v-for="doc in countryData.documents" :key="doc" class="flex items-center gap-2.5 rounded-lg px-3 py-2 cursor-pointer hover:bg-gray-50 transition">
                                <input type="checkbox" :checked="(d.doc_checklist||[]).includes(doc)" @change="toggleDocChecklist(doc)" class="h-4 w-4 rounded text-primary-600" />
                                <span class="text-xs" :class="(d.doc_checklist||[]).includes(doc)?'text-gray-900 font-medium':'text-gray-600'">{{ doc }}</span>
                            </label>
                        </div>
                        <div class="mt-2 flex items-center gap-2 rounded-lg bg-primary-50 px-3 py-1.5">
                            <span class="text-[10px] font-bold text-primary-700">{{ (d.doc_checklist||[]).length }} / {{ countryData.documents.length }}</span>
                            <div class="flex-1 h-1 bg-primary-100 rounded-full overflow-hidden"><div class="h-full bg-primary-600 rounded-full transition-all" :style="{ width: ((d.doc_checklist||[]).length / countryData.documents.length * 100) + '%' }"></div></div>
                        </div>
                    </div>

                    <!-- Type de parcours -->
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">③ Parcours de création</p>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 mb-4" :class="hasErr('track') ? 'rounded-lg border border-red-300 bg-red-50/50 p-2' : ''">
                            <button type="button" class="rounded-xl border-2 p-5 text-center transition" :class="d.track==='individual'?'border-primary-600 bg-primary-50':'border-gray-200 hover:border-primary-300'" @click="update('track','individual')">
                                <svg class="mx-auto h-8 w-8 mb-2" :class="d.track==='individual'?'text-primary-700':'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
                                <p class="text-sm font-medium" :class="d.track==='individual'?'text-primary-700':'text-gray-700'">Parcours A — Individuel</p>
                                <p class="mt-1 text-[10px] text-gray-400">Commerçant, entreprenant, société de fait</p>
                            </button>
                            <button type="button" class="rounded-xl border-2 p-5 text-center transition" :class="d.track==='company'?'border-primary-600 bg-primary-50':'border-gray-200 hover:border-primary-300'" @click="update('track','company')">
                                <svg class="mx-auto h-8 w-8 mb-2" :class="d.track==='company'?'text-primary-700':'text-gray-400'" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M4 21V7l8-4 8 4v14M4 21h16M9 21v-4h6v4M9 11h.01M9 15h.01M15 11h.01M15 15h.01"/></svg>
                                <p class="text-sm font-medium" :class="d.track==='company'?'text-primary-700':'text-gray-700'">Parcours B — Société</p>
                                <p class="mt-1 text-[10px] text-gray-400">SARL, SAS, SA, coopérative…</p>
                            </button>
                        </div>
                        <p v-if="hasErr('track')" class="mb-3 text-xs text-red-600">{{ errMsg('track') }}</p>

                        <!-- Étapes du parcours sélectionné -->
                        <div v-if="trackSteps.length" class="rounded-lg border border-gray-200 bg-gray-50/50 p-4">
                            <p class="text-xs font-semibold text-gray-700 mb-3">{{ d.track === 'individual' ? 'Parcours A — Entrepreneur individuel' : 'Parcours B — Société' }}</p>
                            <div class="space-y-1.5">
                                <label v-for="(s, idx) in trackSteps" :key="idx" class="flex items-center gap-3 rounded-lg px-3 py-2 cursor-pointer hover:bg-white transition">
                                    <input type="checkbox" :checked="(d.step_checklist||[]).includes(s)" @change="toggleStepChecklist(s)" class="h-4 w-4 rounded text-green-600" />
                                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[9px] font-bold" :class="(d.step_checklist||[]).includes(s)?'bg-green-600 text-white':'bg-gray-200 text-gray-500'">{{ idx + 1 }}</span>
                                    <span class="text-xs" :class="(d.step_checklist||[]).includes(s)?'text-gray-900 font-medium line-through decoration-green-500':'text-gray-600'">{{ s }}</span>
                                </label>
                            </div>
                            <div class="mt-2 flex items-center gap-2 rounded-lg bg-green-50 px-3 py-1.5">
                                <span class="text-[10px] font-bold text-green-700">{{ (d.step_checklist||[]).length }} / {{ trackSteps.length }}</span>
                                <div class="flex-1 h-1 bg-green-100 rounded-full overflow-hidden"><div class="h-full bg-green-600 rounded-full transition-all" :style="{ width: ((d.step_checklist||[]).length / trackSteps.length * 100) + '%' }"></div></div>
                            </div>
                        </div>
                    </div>

                    <!-- Frais -->
                    <div v-if="countryData.fees" class="rounded-lg border border-gray-200 bg-white p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-1">④ Frais</p>
                        <p class="text-xs text-gray-600 leading-relaxed">{{ countryData.fees }}</p>
                    </div>

                    <!-- Autorisations sectorielles -->
                    <div v-if="countryData.sector_auth" class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-amber-700 mb-1">⑤ Autorisations sectorielles</p>
                        <p class="text-xs text-amber-700 leading-relaxed">{{ countryData.sector_auth }}</p>
                    </div>
                </div>
            </div>
        </template>

        <!-- Message si pas de fiche technique -->
        <div v-if="d.creation_country && !countryData" class="rounded-lg border border-amber-200 bg-amber-50 px-5 py-4">
            <p class="text-sm font-medium text-amber-800">Fiche technique non encore disponible pour {{ d.creation_country }}</p>
            <p class="mt-1 text-xs text-amber-600">La fiche technique de ce pays sera ajoutée prochainement. Vous pouvez continuer en remplissant les sections suivantes.</p>
        </div>

        <!-- ══ Notes ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span><p class="text-sm font-medium text-gray-800">Notes personnelles</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Observations</p><p class="mt-0.5 text-[10px] text-gray-400">Remarques sur les démarches, contacts utiles, questions pour l'accompagnateur.</p></div>
            <textarea :value="d.notes||''" @input="update('notes',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-sm" placeholder="Vos observations…"></textarea>
        </div>
    </div>
</template>
