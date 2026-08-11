<script>
import StepLayout from '@/Components/Journey/StepLayout.vue';
import { useForm } from '@inertiajs/vue3';

export default {
    components: { StepLayout },
    props: { stepNumber: Number, journeyResponse: Object, totalSteps: Number },
    data() {
        const r = this.journeyResponse.responses || {};
        return {
            form: useForm({
                responses: {
                    host_company: r.host_company || '',
                    activity_tested: r.activity_tested || '',
                    date_from: r.date_from || '',
                    date_to: r.date_to || '',
                    days: r.days || '',
                    how_found: r.how_found || '',
                    why_chosen: r.why_chosen || '',
                    host_activity: r.host_activity || '',
                    host_products: r.host_products || '',
                    host_clients: r.host_clients || '',
                    learning_goal: r.learning_goal || '',
                    activities_done: r.activities_done || [],
                    sales_count: r.sales_count || '',
                    revenue: r.revenue || '',
                    expenses: r.expenses || '',
                    result: r.result || '',
                    worked_well: r.worked_well || '',
                    was_difficult: r.was_difficult || '',
                    must_change: r.must_change || '',
                    found_clients: r.found_clients || '',
                    client_count: r.client_count || '',
                    ready_to_pay: r.ready_to_pay || '',
                    best_product: r.best_product || '',
                    key_feedback: r.key_feedback || '',
                    project_status: r.project_status || '',
                    status_reason: r.status_reason || '',
                    next_30_days: r.next_30_days || '',
                    support_need: r.support_need || '',
                },
            }),
        };
    },
    computed: {
        activityOptions() {
            return ['Production','Vente','Accueil client','Prospection','Achats','Gestion des stocks','Communication','Gestion financière','Autre'];
        },
    },
    methods: {
        toggleActivity(a) {
            const i = this.form.responses.activities_done.indexOf(a);
            if (i >= 0) this.form.responses.activities_done.splice(i, 1);
            else this.form.responses.activities_done.push(a);
        },
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="Business ground lab" subtitle="3 semaines pour tester en conditions réelles." :saving="form.processing" @save="save" @complete="complete">
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Mon expérimentation</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Entreprise d'accueil</label><input v-model="form.responses.host_company" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Activité testée</label><input v-model="form.responses.activity_tested" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Du</label><input v-model="form.responses.date_from" type="date" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Au</label><input v-model="form.responses.date_to" type="date" class="w-full rounded-lg border-gray-200 text-sm" /></div>
            </div>
        </div>
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Activités réalisées</p>
            <div class="flex flex-wrap gap-2">
                <button v-for="a in activityOptions" :key="a" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="form.responses.activities_done.includes(a) ? 'border-primary-600 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600'" @click="toggleActivity(a)">{{ a }}</button>
            </div>
        </div>
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Test économique</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Nombre de ventes</label><input v-model="form.responses.sales_count" type="number" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Chiffre d'affaires (FCFA)</label><input v-model="form.responses.revenue" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Total dépenses (FCFA)</label><input v-model="form.responses.expenses" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Résultat brut (FCFA)</label><input v-model="form.responses.result" class="w-full rounded-lg border-gray-200 text-sm" /></div>
            </div>
        </div>
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Observations</p>
            <div class="space-y-3">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Ce qui a bien fonctionné</label><textarea v-model="form.responses.worked_well" rows="2" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Ce qui a été difficile</label><textarea v-model="form.responses.was_difficult" rows="2" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Ce que je dois changer</label><textarea v-model="form.responses.must_change" rows="2" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Bilan personnel</p>
            <div class="space-y-2">
                <label v-for="s in ['ready','improve','retest','rethink','abandon']" :key="s" class="flex items-center gap-3 rounded-lg border px-4 py-3 cursor-pointer" :class="form.responses.project_status===s ? 'border-primary-600 bg-primary-50' : 'border-gray-200'">
                    <input type="radio" v-model="form.responses.project_status" :value="s" class="text-primary-600" />
                    <span class="text-sm text-gray-700" v-text="{ready:'Prêt à être lancé',improve:'À améliorer',retest:'À tester une nouvelle fois',rethink:'À modifier fortement',abandon:'À abandonner / réorienter'}[s]"></span>
                </label>
            </div>
        </div>
    </StepLayout>
</template>
