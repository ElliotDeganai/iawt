<script>
export default {
    props: {
        step: Number,
        data: { type: Object, default: () => ({}) },
    },
    methods: {
        v(k) { return this.data[k]; },
        has(k) { const val = this.data[k]; return val !== null && val !== undefined && val !== '' && !(Array.isArray(val) && !val.length); },
        fmtNum(val) { if (!val && val !== 0) return '—'; const n = parseInt(String(val).replace(/[^\d-]/g, '')); return isNaN(n) ? val : (n < 0 ? '-' : '') + String(Math.abs(n)).replace(/\B(?=(\d{3})+(?!\d))/g, "'"); },
        arr(k) { const val = this.data[k]; return Array.isArray(val) ? val : []; },
        obj(k) { const val = this.data[k]; return (val && typeof val === 'object' && !Array.isArray(val)) ? val : {}; },
    },
};
</script>

<template>
<div class="space-y-4 text-sm">

    <!-- ══════════ ÉTAPE 1 ══════════ -->
    <template v-if="step === 1">
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Origine de l'idée</p>
            <div class="flex flex-wrap gap-1.5">
                <span v-for="o in arr('origins')" :key="o" class="rounded-full bg-primary-50 border border-primary-200 px-3 py-1 text-xs text-primary-700">{{ o }}</span>
            </div>
        </div>
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Présentation du projet</p>
            <div class="space-y-2">
                <div v-for="q in [{k:'who',l:'Qui'},{k:'what',l:'Quoi'},{k:'why',l:'Pourquoi'},{k:'how',l:'Comment'},{k:'where',l:'Où'}]" :key="q.k" class="flex gap-3">
                    <span class="flex h-6 w-12 shrink-0 items-center justify-center rounded bg-primary-50 text-[10px] font-semibold text-primary-700">{{ q.l }}</span>
                    <p class="text-gray-700">{{ v(q.k) || '—' }}</p>
                </div>
            </div>
        </div>
        <div v-if="has('adequation')" class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Adéquation</p>
            <div class="flex items-center gap-1">
                <span v-for="n in 5" :key="n" class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold" :class="n <= (v('adequation')||0) ? 'bg-primary-600 text-white' : 'border border-gray-200 text-gray-300'">{{ n }}</span>
            </div>
        </div>
    </template>

    <!-- ══════════ ÉTAPE 2 ══════════ -->
    <template v-if="step === 2">
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Projet</p>
            <div class="grid grid-cols-2 gap-3">
                <div><p class="text-[10px] text-gray-400">Nom du projet</p><p class="font-medium text-gray-800">{{ v('project_name') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Zone</p><p class="font-medium text-gray-800">{{ [v('zone_country'), v('zone_region'), v('zone_city')].filter(Boolean).join(', ') || '—' }}</p></div>
            </div>
            <div class="mt-3"><p class="text-[10px] text-gray-400">Description</p><p class="text-gray-700">{{ v('description') || '—' }}</p></div>
        </div>
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Problème & Cibles</p>
            <div class="space-y-2">
                <div><p class="text-[10px] text-gray-400">Problème identifié</p><p class="text-gray-700">{{ v('problem') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Client principal</p><p class="text-gray-700">{{ v('main_client') || '—' }}</p></div>
                <div v-if="has('client_age')" class="grid grid-cols-3 gap-2">
                    <div><p class="text-[10px] text-gray-400">Âge</p><p class="text-gray-700">{{ v('client_age') }}</p></div>
                    <div><p class="text-[10px] text-gray-400">Situation</p><p class="text-gray-700">{{ v('client_situation') }}</p></div>
                    <div><p class="text-[10px] text-gray-400">Activité</p><p class="text-gray-700">{{ v('client_activity') }}</p></div>
                </div>
            </div>
        </div>
        <div v-if="has('decision')" class="rounded-lg border-2 px-4 py-3" :class="v('decision')?.includes('sans modification') ? 'border-green-200 bg-green-50' : v('decision')?.includes('pause') || v('decision')?.includes('abandonne') ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50'">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500 mb-1">Décision</p>
            <p class="font-medium text-gray-800">{{ v('decision') }}</p>
        </div>
    </template>

    <!-- ══════════ ÉTAPE 3 ══════════ -->
    <template v-if="step === 3">
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div v-for="block in [{k:'value_prop',l:'Proposition de valeur',bg:'#FBF0DC'},{k:'segments',l:'Segments',bg:'#F7E5DE'},{k:'partners',l:'Partenaires clés',bg:'#E8EEE3'},{k:'activities',l:'Activités clés',bg:'#E8EEE3'},{k:'resources',l:'Ressources clés',bg:'#E8EEE3'},{k:'channels',l:'Canaux',bg:'#F7E5DE'},{k:'rel_client',l:'Relation client',bg:'#F7E5DE'},{k:'costs',l:'Structure de coûts',bg:'#F1EADA'},{k:'revenue',l:'Sources de revenus',bg:'#F1EADA'}]" :key="block.k" class="rounded-lg border border-gray-100 p-3" :style="{ backgroundColor: block.bg + '60' }">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500 mb-1">{{ block.l }}</p>
                <p class="text-gray-700">{{ v(block.k) || '—' }}</p>
            </div>
        </div>
    </template>

    <!-- ══════════ ÉTAPE 4 ══════════ -->
    <template v-if="step === 4">
        <div v-for="section in [{title:'Plan de financement initial',prefixes:['n_','r_']},{title:'Compte de résultat',prefixes:['p_','c_','fin_','tax_']},{title:'Plan de trésorerie',prefixes:['ti_','to_']}]" :key="section.title" class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">{{ section.title }}</p>
            <table class="w-full text-xs">
                <tbody>
                    <tr v-for="(val, key) in data" :key="key" v-show="section.prefixes.some(p => key.startsWith(p))" class="border-b border-gray-50">
                        <td class="py-1.5 pr-4 text-gray-600">{{ key.replace(/^(n_|r_|p_|c_|ti_|to_|fin_|tax_)/, '').replace(/_y(\d)$/, ' — A$1').replace(/_m(\d+)$/, ' — M$1') }}</td>
                        <td class="py-1.5 text-right font-medium text-gray-800">{{ fmtNum(val) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </template>

    <!-- ══════════ ÉTAPE 5 ══════════ -->
    <template v-if="step === 5">
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Profil & Projet</p>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div v-for="f in [{k:'country',l:'Pays'},{k:'city',l:'Ville'},{k:'status',l:'Statut'},{k:'experience',l:'Expérience'},{k:'sector',l:'Secteur'},{k:'project_type',l:'Type de projet'},{k:'founders',l:'Fondateurs'},{k:'investment',l:'Investissement'},{k:'bank_need',l:'Financement bancaire'},{k:'ext_investors',l:'Investisseurs externes'},{k:'objective',l:'Objectif 3-5 ans'}]" :key="f.k" v-show="has(f.k)">
                    <p class="text-[10px] text-gray-400">{{ f.l }}</p>
                    <p class="font-medium text-gray-800">{{ v(f.k) }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Questionnaire</p>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <div v-for="n in 20" :key="n" v-show="has('Q'+n)" class="flex items-center gap-2 rounded bg-gray-50 px-3 py-1.5">
                    <span class="text-[10px] font-bold text-primary-600 shrink-0">Q{{ n }}</span>
                    <span class="text-xs text-gray-700">{{ v('Q'+n) }}</span>
                </div>
            </div>
        </div>
        <div v-if="has('legal_form')" class="rounded-lg border-2 border-primary-200 bg-primary-50 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Recommandation</p>
            <div class="flex items-center gap-3">
                <span class="rounded-full bg-primary-600 px-3 py-1 text-xs font-bold text-white">1. {{ v('legal_form') }}</span>
                <span v-if="has('legal_form_2')" class="rounded-full bg-gray-200 px-3 py-1 text-xs font-medium text-gray-700">2. {{ v('legal_form_2') }}</span>
            </div>
        </div>
        <div v-if="arr('auto_verif').length" class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Auto-vérification</p>
            <div class="space-y-1">
                <div v-for="item in arr('auto_verif')" :key="item" class="flex items-center gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-xs text-gray-700">{{ item }}</span>
                </div>
            </div>
        </div>
    </template>

    <!-- ══════════ ÉTAPE 6 ══════════ -->
    <template v-if="step === 6">
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Expérimentation</p>
            <div class="grid grid-cols-2 gap-3">
                <div><p class="text-[10px] text-gray-400">Porteur</p><p class="font-medium text-gray-800">{{ v('holder_name') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Secteur</p><p class="font-medium text-gray-800">{{ v('sector') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Entreprise</p><p class="font-medium text-gray-800">{{ v('host_company') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Activité testée</p><p class="font-medium text-gray-800">{{ v('tested_activity') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Période</p><p class="font-medium text-gray-800">{{ v('date_start') || '?' }} → {{ v('date_end') || '?' }} ({{ v('days_count') || '?' }} jours)</p></div>
            </div>
        </div>
        <div v-if="arr('activities_done').length" class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Activités réalisées</p>
            <div class="space-y-1.5">
                <div v-for="a in arr('activities_done')" :key="a" class="flex items-start gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0 mt-0.5 text-primary-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <div>
                        <span class="text-xs font-medium text-gray-800">{{ a }}</span>
                        <p v-if="obj('activity_learnings')[a]" class="text-[11px] text-gray-500 italic">{{ obj('activity_learnings')[a] }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-3">Test économique</p>
            <div class="space-y-1 text-xs">
                <div class="flex justify-between"><span class="text-gray-500">Ventes</span><span class="font-medium">{{ v('sales_count') || '—' }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Chiffre d'affaires</span><span class="font-medium">{{ fmtNum(v('revenue')) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Achats</span><span class="font-medium">{{ fmtNum(v('expense_purchases')) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Transport</span><span class="font-medium">{{ fmtNum(v('expense_transport')) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Communication</span><span class="font-medium">{{ fmtNum(v('expense_comm')) }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Autres</span><span class="font-medium">{{ fmtNum(v('expense_other')) }}</span></div>
            </div>
        </div>
        <div v-if="has('assessment')" class="rounded-lg border-2 px-4 py-3" :class="v('assessment')?.includes('lancé') ? 'border-green-200 bg-green-50' : v('assessment')?.includes('abandonner') ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50'">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500 mb-1">Bilan personnel</p>
            <p class="font-medium text-gray-800">{{ v('assessment') }}</p>
            <p v-if="has('assessment_why')" class="mt-1 text-xs text-gray-600 italic">{{ v('assessment_why') }}</p>
        </div>
    </template>

    <!-- ══════════ ÉTAPE 7 ══════════ -->
    <template v-if="step === 7">
        <div class="rounded-lg border border-gray-100 p-4">
            <div class="grid grid-cols-2 gap-3">
                <div><p class="text-[10px] text-gray-400">Pays de création</p><p class="font-medium text-gray-800">{{ v('creation_country') || '—' }}</p></div>
                <div><p class="text-[10px] text-gray-400">Type de parcours</p><p class="font-medium text-gray-800">{{ v('track') === 'individual' ? 'Individuel' : v('track') === 'company' ? 'Société' : (v('track') || '—') }}</p></div>
            </div>
        </div>
        <div v-if="arr('checklist').length" class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Pré-requis cochés</p>
            <div class="space-y-1">
                <div v-for="item in arr('checklist')" :key="item" class="flex items-center gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-xs text-gray-700">{{ item }}</span>
                </div>
            </div>
        </div>
        <div v-if="has('notes')"><p class="text-[10px] text-gray-400">Notes</p><p class="text-xs text-gray-700">{{ v('notes') }}</p></div>
    </template>

    <!-- ══════════ ÉTAPE 8 ══════════ -->
    <template v-if="step === 8">
        <div v-if="arr('checklist').length" class="rounded-lg border border-gray-100 p-4">
            <p class="text-[10px] font-semibold uppercase tracking-widest text-primary-600 mb-2">Checklist finale</p>
            <div class="space-y-1">
                <div v-for="item in arr('checklist')" :key="item" class="flex items-center gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0 text-green-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span class="text-xs text-gray-700">{{ item }}</span>
                </div>
            </div>
        </div>
    </template>

</div>
</template>