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
                    country: r.country || '',
                    city: r.city || '',
                    current_status: r.current_status || '',
                    experience: r.experience || '',
                    project_name: r.project_name || '',
                    sector: r.sector || '',
                    sub_sector: r.sub_sector || '',
                    project_type: r.project_type || '',
                    founders_count: r.founders_count || '',
                    jobs_y1: r.jobs_y1 || '',
                    jobs_y3: r.jobs_y3 || '',
                    investment: r.investment || '',
                    personal_contribution: r.personal_contribution || '',
                    funding_needed: r.funding_needed || '',
                    revenue_y1: r.revenue_y1 || '',
                    revenue_y3: r.revenue_y3 || '',
                    bank_need: r.bank_need || '',
                    external_investors: r.external_investors || '',
                    chosen_form: r.chosen_form || '',
                    reason: r.reason || '',
                },
            }),
        };
    },
    methods: {
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="Forme juridique" subtitle="Choisir la bonne structure pour votre projet." :saving="form.processing" @save="save" @complete="complete">
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Votre profil</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Pays</label><input v-model="form.responses.country" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Ville</label><input v-model="form.responses.city" class="w-full rounded-lg border-gray-200 text-sm" /></div>
            </div>
            <div class="mt-3"><label class="mb-1 block text-xs font-medium text-gray-700">Statut actuel</label>
                <div class="flex flex-wrap gap-2"><button v-for="s in [\"Sans activité\",\"Informel\",\"Entreprise existante\",\"Association\"]" :key="s" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="form.responses.current_status===s ? \"border-primary-600 bg-primary-50 text-primary-700\" : \"border-gray-200 text-gray-600\"" @click="form.responses.current_status=s">{{ s }}</button></div>
            </div>
            <div class="mt-3"><label class="mb-1 block text-xs font-medium text-gray-700">Expérience</label>
                <div class="flex flex-wrap gap-2"><button v-for="e in [\"Débutant\",\"< 2 ans\",\"2-5 ans\",\"> 5 ans\"]" :key="e" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="form.responses.experience===e ? \"border-primary-600 bg-primary-50 text-primary-700\" : \"border-gray-200 text-gray-600\"" @click="form.responses.experience=e">{{ e }}</button></div>
            </div>
        </div>
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Votre projet</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Nom du projet</label><input v-model="form.responses.project_name" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Secteur</label><input v-model="form.responses.sector" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Type de projet</label>
                    <div class="flex flex-wrap gap-2"><button v-for="t in [\"Individuel\",\"Familial\",\"Associés\",\"Coopératif\"]" :key="t" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="form.responses.project_type===t ? \"border-primary-600 bg-primary-50 text-primary-700\" : \"border-gray-200 text-gray-600\"" @click="form.responses.project_type=t">{{ t }}</button></div>
                </div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Nombre de fondateurs</label><input v-model="form.responses.founders_count" type="number" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Investissement initial (FCFA)</label><input v-model="form.responses.investment" class="w-full rounded-lg border-gray-200 text-sm" /></div>
                <div><label class="mb-1 block text-xs font-medium text-gray-700">Besoin financement bancaire</label>
                    <div class="flex flex-wrap gap-2"><button v-for="b in [\"Aucun\",\"Faible\",\"Moyen\",\"Important\"]" :key="b" type="button" class="rounded-full border px-3 py-1.5 text-xs" :class="form.responses.bank_need===b ? \"border-primary-600 bg-primary-50 text-primary-700\" : \"border-gray-200 text-gray-600\"" @click="form.responses.bank_need=b">{{ b }}</button></div>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Forme choisie</p>
            <div><label class="mb-1 block text-xs font-medium text-gray-700">Forme juridique retenue</label><input v-model="form.responses.chosen_form" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Ex : SARL, SAS, Entreprenant…" /></div>
            <div class="mt-3"><label class="mb-1 block text-xs font-medium text-gray-700">Pourquoi ce choix ?</label><textarea v-model="form.responses.reason" rows="3" class="w-full rounded-lg border-gray-200 text-sm"></textarea></div>
        </div>
    </StepLayout>
</template>
