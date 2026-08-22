<script>
export default {
    props: {
        modelValue: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    computed: { d() { return this.modelValue; } },
    methods: {
        hasErr(k) { return !!this.errors[k]; },
        errMsg(k) { return this.errors[k] || ''; },
        errClass(k) { return this.hasErr(k) ? 'border-red-400 bg-red-50/30' : 'border-gray-200'; },
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        fmtApos(n) { return String(Math.abs(n)).replace(/\B(?=(\d{3})+(?!\d))/g, "'"); },
        fmt(n) { return (n < 0 ? '-' : '') + this.fmtApos(n); },
        fmtVal(k) {
            const raw = this.d[k];
            if (!raw && raw !== 0) return '';
            const n = parseInt(String(raw).replace(/[^\d]/g, ''));
            return isNaN(n) || n === 0 ? '' : this.fmtApos(n);
        },
        onNumInput(k, e) {
            const digits = e.target.value.replace(/[^\d]/g, '');
            const n = digits ? parseInt(digits) : '';
            this.update(k, n);
            this.$nextTick(() => { e.target.value = this.fmtVal(k); });
        },
        num(k) { return parseInt(String(this.d[k]).replace(/[^\d-]/g, '')) || 0; },
        togglePill(k, val) {
            const arr = this.d[k] || [];
            const idx = arr.indexOf(val);
            this.update(k, idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
        toggleActivity(val) {
            const arr = this.d.activities_done || [];
            const idx = arr.indexOf(val);
            this.update('activities_done', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
        updateActivityLearning(act, val) {
            const obj = { ...(this.d.activity_learnings || {}) };
            obj[act] = val;
            this.update('activity_learnings', obj);
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 leading-relaxed">
            <p class="font-medium text-gray-700 mb-1">Business Ground Lab — Teste ton projet dans la vraie vie</p>
            <p class="italic">3 semaines pour tester son projet en conditions réelles. Teste – Vends – Mesure – Apprends – Décide.</p>
        </div>

        <!-- ══ 1. Mon expérimentation ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span><p class="text-sm font-medium text-gray-800">Mon expérimentation</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Fiche d'identité</p><p class="mt-0.5 text-[10px] text-gray-400">Informations de base sur votre expérimentation terrain.</p></div>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-600 mb-1">Nom du porteur</label><input :value="d.holder_name||''" @input="update('holder_name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Nom du projet</label><input :value="d.project_name||''" @input="update('project_name',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Secteur</label>
                <div class="flex flex-wrap gap-2">
                    <button v-for="s in ['Agriculture','Tourisme','Autre']" :key="s" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="d.sector===s?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('sector',s)">{{ s }}</button>
                </div>
                <input v-if="d.sector==='Autre'" :value="d.sector_other||''" @input="update('sector_other',$event.target.value)" class="mt-2 w-full rounded-md border-gray-200 text-sm" placeholder="Précisez…" />
            </div>
            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="block text-xs text-gray-600 mb-1">Entreprise d'accueil</label><input :value="d.host_company||''" @input="update('host_company',$event.target.value)" class="w-full rounded-md text-sm" :class="errClass('host_company')" /><p v-if="hasErr('host_company')" class="mt-1 text-xs text-red-600">{{ errMsg('host_company') }}</p></div>
                <div><label class="block text-xs text-gray-600 mb-1">Activité observée/testée</label><input :value="d.tested_activity||''" @input="update('tested_activity',$event.target.value)" class="w-full rounded-md text-sm" :class="errClass('tested_activity')" /><p v-if="hasErr('tested_activity')" class="mt-1 text-xs text-red-600">{{ errMsg('tested_activity') }}</p></div>
                <div><label class="block text-xs text-gray-600 mb-1">Date de début</label><input type="date" :value="d.date_start||''" @input="update('date_start',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Date de fin</label><input type="date" :value="d.date_end||''" @input="update('date_end',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Nombre de jours</label><input type="number" :value="d.days_count||''" @input="update('days_count',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
            </div>
        </div>

        <!-- ══ 2. Comment ai-je trouvé l'entreprise ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span><p class="text-sm font-medium text-gray-800">Comment ai-je trouvé l'entreprise</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Identification de l'entreprise</p><p class="mt-0.5 text-[10px] text-gray-400">Comment avez-vous identifié et choisi cette entreprise ?</p></div>
            <div class="flex flex-wrap gap-2">
                <button v-for="m in ['Réseau personnel','Démarchage direct','Recommandation','Association','Internet / réseaux sociaux','Autre']" :key="m" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="(d.how_found||[]).includes(m)?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="togglePill('how_found',m)">{{ m }}</button>
            </div>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Pourquoi ai-je choisi cette entreprise ?</label><textarea :value="d.why_company||''" @input="update('why_company',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
        </div>

        <!-- ══ 3. Présentation rapide ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span><p class="text-sm font-medium text-gray-800">Présentation rapide de l'entreprise</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Contexte de l'entreprise</p><p class="mt-0.5 text-[10px] text-gray-400">Décrivez brièvement l'entreprise qui vous a accueilli.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">Activité principale</label><input :value="d.company_activity||''" @input="update('company_activity',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Produits / services proposés</label><textarea :value="d.company_products||''" @input="update('company_products',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Type de clientèle</label><input :value="d.company_clients||''" @input="update('company_clients',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div><label class="block text-xs text-gray-600 mb-1">Ce que j'ai voulu apprendre ou tester</label><textarea :value="d.learning_goal||''" @input="update('learning_goal',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- ══ 4. Ce que j'ai réellement fait ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">4</span><p class="text-sm font-medium text-gray-800">Ce que j'ai réellement fait</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Activités réalisées</p><p class="mt-0.5 text-[10px] text-gray-400">Cochez les activités pratiquées et notez ce que vous en avez tiré.</p></div>
            <div class="space-y-2">
                <div v-for="a in ['Production','Vente','Accueil client','Prospection','Achats','Gestion des stocks','Communication','Gestion financière','Autre']" :key="a" class="rounded-lg border border-gray-100 px-4 py-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" :checked="(d.activities_done||[]).includes(a)" @change="toggleActivity(a)" class="h-4 w-4 rounded text-primary-600" />
                        <span class="text-sm" :class="(d.activities_done||[]).includes(a)?'text-gray-900 font-medium':'text-gray-500'">{{ a }}</span>
                    </label>
                    <div v-if="(d.activities_done||[]).includes(a)" class="mt-2 ml-7">
                        <input :value="(d.activity_learnings||{})[a]||''" @input="updateActivityLearning(a,$event.target.value)" class="w-full rounded-md border-gray-200 text-xs" placeholder="Ce que j'ai appris…" />
                    </div>
                </div>
            </div>
        </div>

        <!-- ══ 5. Mon test économique ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5</span><p class="text-sm font-medium text-gray-800">Mon test économique</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Résultats chiffrés (FCFA)</p><p class="mt-0.5 text-[10px] text-gray-400">Mesurez la performance économique de votre expérimentation.</p></div>
            <div class="space-y-1.5">
                <div v-for="row in [{k:'sales_count',l:'Nombre de ventes réalisées'},{k:'revenue',l:'Chiffre d\'affaires généré'},{k:'expense_purchases',l:'Achats / matières premières'},{k:'expense_transport',l:'Transport'},{k:'expense_comm',l:'Communication'},{k:'expense_other',l:'Autres dépenses'}]" :key="row.k" class="flex items-center gap-2">
                    <label class="flex-1 text-xs text-gray-600">{{ row.l }}</label>
                    <input :value="fmtVal(row.k)" @input="onNumInput(row.k,$event)" type="text" inputmode="numeric" class="w-32 rounded border-gray-200 text-xs text-right" placeholder="0" />
                </div>
                <div class="flex items-center gap-2 pt-2 border-t border-gray-200">
                    <span class="flex-1 text-xs font-medium text-gray-800">Total des dépenses</span>
                    <span class="w-32 text-right text-xs font-bold text-gray-900">{{ fmt(num('expense_purchases')+num('expense_transport')+num('expense_comm')+num('expense_other')) }}</span>
                </div>
                <div class="flex items-center gap-2 pt-1 border-t border-gray-300">
                    <span class="flex-1 text-xs font-bold text-gray-900">Résultat brut</span>
                    <span class="w-32 text-right text-sm font-bold" :class="(num('revenue')-(num('expense_purchases')+num('expense_transport')+num('expense_comm')+num('expense_other')))>=0?'text-green-700':'text-red-700'">{{ fmt(num('revenue')-(num('expense_purchases')+num('expense_transport')+num('expense_comm')+num('expense_other'))) }} FCFA</span>
                </div>
            </div>

            <div class="mt-5 mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Mes 3 principales observations</p><p class="mt-0.5 text-[10px] text-gray-400">Vos constats principaux après l'expérimentation.</p></div>
            <div class="space-y-3">
                <div><label class="block text-xs text-gray-600 mb-1">1. Ce qui a bien fonctionné</label><textarea :value="d.what_worked||''" @input="update('what_worked',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">2. Ce qui a été difficile</label><textarea :value="d.what_difficult||''" @input="update('what_difficult',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">3. Ce que je dois changer</label><textarea :value="d.what_change||''" @input="update('what_change',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            </div>
        </div>

        <!-- ══ 6. Les clients ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">6</span><p class="text-sm font-medium text-gray-800">Les clients</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Retours clients</p><p class="mt-0.5 text-[10px] text-gray-400">Ce que vous avez appris sur vos clients pendant l'expérimentation.</p></div>
            <div class="space-y-3">
                <div class="flex items-center gap-4"><label class="text-xs text-gray-600 w-48">Ai-je trouvé des clients ?</label>
                    <div class="flex gap-2"><button v-for="o in ['Oui','Non']" :key="o" type="button" class="rounded-full border px-3 py-1 text-xs" :class="d.found_clients===o?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('found_clients',o)">{{ o }}</button></div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Combien ?</label><input type="number" :value="d.client_count||''" @input="update('client_count',$event.target.value)" class="w-full rounded-md border-gray-200 text-sm" /></div>
                <div class="flex items-center gap-4"><label class="text-xs text-gray-600 w-48">Les clients étaient-ils prêts à payer ?</label>
                    <div class="flex gap-2"><button v-for="o in ['Oui','Non','Partiellement']" :key="o" type="button" class="rounded-full border px-3 py-1 text-xs" :class="d.clients_willing_pay===o?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="update('clients_willing_pay',o)">{{ o }}</button></div>
                </div>
                <div><label class="block text-xs text-gray-600 mb-1">Quel produit/service a le mieux fonctionné ?</label><textarea :value="d.best_product||''" @input="update('best_product',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div><label class="block text-xs text-gray-600 mb-1">Quel retour client revient le plus souvent ?</label><textarea :value="d.common_feedback||''" @input="update('common_feedback',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
                <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3"><label class="block text-xs font-medium text-gray-700 mb-1">Le retour client le plus important</label><textarea :value="d.key_feedback||''" @input="update('key_feedback',$event.target.value)" rows="2" class="w-full rounded-md border-gold-200 bg-white text-sm"></textarea></div>
            </div>
        </div>

        <!-- ══ 7. Mon bilan personnel ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">7</span><p class="text-sm font-medium text-gray-800">Mon bilan personnel</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Évaluation personnelle</p><p class="mt-0.5 text-[10px] text-gray-400">Après cette expérimentation, quel est votre verdict ?</p></div>
            <div class="space-y-2" :class="hasErr('assessment') ? 'rounded-lg border border-red-300 bg-red-50/50 p-3' : ''">
                <label v-for="opt in ['Prêt à être lancé','À améliorer avant lancement','À tester une nouvelle fois','À modifier fortement','À abandonner / réorienter']" :key="opt" class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                    <input type="radio" :checked="d.assessment===opt" @change="update('assessment',opt)" class="text-primary-600" />
                    <span class="text-sm text-gray-700">{{ opt }}</span>
                </label>
            </div>
            <p v-if="hasErr('assessment')" class="mt-2 text-xs text-red-600">{{ errMsg('assessment') }}</p>
            <div class="mt-3"><label class="block text-xs text-gray-600 mb-1">Pourquoi ?</label><textarea :value="d.assessment_why||''" @input="update('assessment_why',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
        </div>

        <!-- ══ 8. Ma prochaine étape ══ -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-2"><span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">8</span><p class="text-sm font-medium text-gray-800">Ma prochaine étape</p></div>
            <div class="mb-4 border-l-[3px] border-primary-600 pl-3"><p class="text-xs font-medium text-primary-700">Plan d'action</p><p class="mt-0.5 text-[10px] text-gray-400">Ce que vous comptez faire dans les 30 prochains jours.</p></div>
            <div><label class="block text-xs text-gray-600 mb-1">Dans les 30 prochains jours, je vais :</label><textarea :value="d.next_30_days||''" @input="update('next_30_days',$event.target.value)" rows="3" class="w-full rounded-md border-gray-200 text-sm"></textarea></div>
            <div class="mt-4"><label class="block text-xs text-gray-600 mb-2">Mon besoin principal d'accompagnement</label>
                <div class="flex flex-wrap gap-2">
                    <button v-for="n in ['Financement','Commercial','Gestion financière','Marketing / communication','Juridique','Technique / production','Formation','Autre']" :key="n" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="(d.support_needs||[]).includes(n)?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-500'" @click="togglePill('support_needs',n)">{{ n }}</button>
                </div>
            </div>
        </div>
    </div>
</template>