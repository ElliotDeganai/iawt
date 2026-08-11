<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: {
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        toggleActivity(val) {
            const arr = this.d.activities_done || [];
            const idx = arr.indexOf(val);
            this.update('activities_done', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">1. Mon expérimentation</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-500 mb-1">Entreprise d'accueil</label><input :value="d.host_company||''" @input="update('host_company',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Activité observée/testée</label><input :value="d.tested_activity||''" @input="update('tested_activity',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Date de début</label><input type="date" :value="d.date_start||''" @input="update('date_start',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Date de fin</label><input type="date" :value="d.date_end||''" @input="update('date_end',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Pourquoi cette entreprise ?</label><textarea :value="d.why_company||''" @input="update('why_company',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">4. Activités réalisées</p>
            <div class="flex flex-wrap gap-2">
                <button v-for="a in ['Production','Vente','Accueil client','Prospection','Achats','Gestion des stocks','Communication','Gestion financière']" :key="a" type="button"
                    class="rounded-full border px-3 py-1.5 text-xs" :class="(d.activities_done||[]).includes(a)?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'"
                    @click="toggleActivity(a)">{{ a }}</button>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Ce que j'ai appris</label><textarea :value="d.learnings||''" @input="update('learnings',$event.target.value)" rows="3" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">5. Test économique (FCFA)</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-500 mb-1">Nombre de ventes</label><input type="number" :value="d.sales_count||''" @input="update('sales_count',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Chiffre d'affaires</label><input :value="d.revenue||''" @input="update('revenue',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="FCFA" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Total des dépenses</label><input :value="d.expenses||''" @input="update('expenses',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="FCFA" /></div>
                <div><label class="block text-xs text-gray-500 mb-1">Résultat brut</label><input :value="d.gross_result||''" @input="update('gross_result',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="FCFA" /></div>
            </div>
            <div class="mt-3 space-y-2">
                <div><label class="block text-xs text-gray-500 mb-1">Ce qui a bien fonctionné</label><textarea :value="d.what_worked||''" @input="update('what_worked',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-500 mb-1">Ce qui a été difficile</label><textarea :value="d.what_difficult||''" @input="update('what_difficult',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-500 mb-1">Ce que je dois changer</label><textarea :value="d.what_change||''" @input="update('what_change',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">7. Bilan et décision</p>
            <div class="space-y-2">
                <label v-for="opt in ['Prêt à être lancé','À améliorer avant lancement','À tester une nouvelle fois','À modifier fortement','À abandonner / réorienter']" :key="opt" class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" :checked="d.assessment===opt" @change="update('assessment',opt)" class="text-primary-600" />
                    <span class="text-sm text-gray-700">{{ opt }}</span>
                </label>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Pourquoi ?</label><textarea :value="d.assessment_why||''" @input="update('assessment_why',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            <div class="mt-3"><label class="block text-xs text-gray-500 mb-1">Dans les 30 prochains jours, je vais…</label><textarea :value="d.next_30_days||''" @input="update('next_30_days',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
        </div>
    </div>
</template>
