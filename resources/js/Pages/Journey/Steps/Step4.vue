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
                    needs: r.needs || { registration:'', legal:'', equipment:'', premises:'', furniture:'', vehicle:'', software:'', training:'', brand:'', bfr:'', treasury:'' },
                    sources: r.sources || { personal:'', partner:'', grant1:'', grant2:'', loan_honor:'', bank:'', crowdfunding:'', other:'' },
                    income_y1: r.income_y1 || { products:'', services:'', grants:'' },
                    income_y2: r.income_y2 || { products:'', services:'', grants:'' },
                    income_y3: r.income_y3 || { products:'', services:'', grants:'' },
                    expenses_y1: r.expenses_y1 || { purchases:'', rent:'', salaries:'', transport:'', comms:'', other:'' },
                    expenses_y2: r.expenses_y2 || { purchases:'', rent:'', salaries:'', transport:'', comms:'', other:'' },
                    expenses_y3: r.expenses_y3 || { purchases:'', rent:'', salaries:'', transport:'', comms:'', other:'' },
                },
            }),
        };
    },
    computed: {
        needsFields() {
            return [
                { key:'registration', label:"Frais d'immatriculation" },{ key:'legal', label:'Conseil juridique/comptable' },
                { key:'equipment', label:'Matériel et équipement' },{ key:'premises', label:'Aménagement local' },
                { key:'furniture', label:'Mobilier' },{ key:'vehicle', label:'Véhicule' },
                { key:'software', label:'Logiciels et outils' },{ key:'training', label:'Formation' },
                { key:'brand', label:'Dépôt de marque' },{ key:'bfr', label:'BFR de démarrage' },
                { key:'treasury', label:'Trésorerie de départ' },
            ];
        },
        sourcesFields() {
            return [
                { key:'personal', label:'Apport personnel' },{ key:'partner', label:"Apport associé" },
                { key:'grant1', label:'Subvention 1' },{ key:'grant2', label:'Subvention 2' },
                { key:'loan_honor', label:"Prêt d'honneur" },{ key:'bank', label:'Emprunt bancaire' },
                { key:'crowdfunding', label:'Crowdfunding / dons' },{ key:'other', label:'Autres' },
            ];
        },
    },
    methods: {
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="Structuration financière" subtitle="Chiffrer votre projet pour vérifier qu'il tient debout." :saving="form.processing" @save="save" @complete="complete">

        <!-- Plan de financement -->
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Plan de financement initial</p>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="mb-2 text-xs font-semibold text-primary-700">Besoins</p>
                    <div v-for="f in needsFields" :key="f.key" class="mb-2">
                        <label class="mb-1 block text-[11px] text-gray-600">{{ f.label }}</label>
                        <input v-model="form.responses.needs[f.key]" class="w-full rounded border-gray-200 text-sm" placeholder="FCFA" />
                    </div>
                </div>
                <div>
                    <p class="mb-2 text-xs font-semibold text-green-700">Ressources</p>
                    <div v-for="f in sourcesFields" :key="f.key" class="mb-2">
                        <label class="mb-1 block text-[11px] text-gray-600">{{ f.label }}</label>
                        <input v-model="form.responses.sources[f.key]" class="w-full rounded border-gray-200 text-sm" placeholder="FCFA" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Compte de résultat -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Compte de résultat prévisionnel</p>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[500px] text-sm">
                    <thead><tr class="border-b border-gray-100 text-xs text-gray-500"><th class="px-2 py-2 text-left">Rubrique</th><th class="px-2 py-2">Année 1</th><th class="px-2 py-2">Année 2</th><th class="px-2 py-2">Année 3</th></tr></thead>
                    <tbody>
                        <tr class="bg-green-50/50"><td class="px-2 py-1.5 text-xs font-medium text-green-700" colspan="4">Produits</td></tr>
                        <tr v-for="k in ['products','services','grants']" :key="k">
                            <td class="px-2 py-1 text-xs text-gray-600">{{ {products:'Ventes produits',services:'Prestations',grants:'Subventions'}[k] }}</td>
                            <td class="px-2 py-1"><input v-model="form.responses.income_y1[k]" class="w-full rounded border-gray-200 text-xs" /></td>
                            <td class="px-2 py-1"><input v-model="form.responses.income_y2[k]" class="w-full rounded border-gray-200 text-xs" /></td>
                            <td class="px-2 py-1"><input v-model="form.responses.income_y3[k]" class="w-full rounded border-gray-200 text-xs" /></td>
                        </tr>
                        <tr class="bg-red-50/50"><td class="px-2 py-1.5 text-xs font-medium text-red-700" colspan="4">Charges</td></tr>
                        <tr v-for="k in ['purchases','rent','salaries','transport','comms','other']" :key="k">
                            <td class="px-2 py-1 text-xs text-gray-600">{{ {purchases:'Achats',rent:'Loyer',salaries:'Salaires',transport:'Transport',comms:'Communication',other:'Autres'}[k] }}</td>
                            <td class="px-2 py-1"><input v-model="form.responses.expenses_y1[k]" class="w-full rounded border-gray-200 text-xs" /></td>
                            <td class="px-2 py-1"><input v-model="form.responses.expenses_y2[k]" class="w-full rounded border-gray-200 text-xs" /></td>
                            <td class="px-2 py-1"><input v-model="form.responses.expenses_y3[k]" class="w-full rounded border-gray-200 text-xs" /></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </StepLayout>
</template>
