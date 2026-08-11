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
                    checklist: r.checklist || {},
                    country: r.country || '',
                    parcours: r.parcours || '',
                },
            }),
            checklistItems: [
                'Projet validé après Business Lab',
                'Forme juridique choisie',
                'Nom de l\'entreprise choisi',
                'Siège social identifié',
                'Capital et apports déterminés',
                'Dirigeant(s) identifié(s)',
                'Activités définies',
                'Autorisations identifiées',
                'Documents personnels réunis',
            ],
            countries: [
                { code: 'cf', flag: '🇨🇫', name: 'RCA' },
                { code: 'cm', flag: '🇨🇲', name: 'Cameroun' },
                { code: 'ci', flag: '🇨🇮', name: 'Côte d\'Ivoire' },
                { code: 'sn', flag: '🇸🇳', name: 'Sénégal' },
            ],
        };
    },
    methods: {
        toggleCheck(item) {
            this.form.responses.checklist[item] = !this.form.responses.checklist[item];
        },
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="De la décision à l'immatriculation" subtitle="Préparer et déposer votre dossier de création." :saving="form.processing" @save="save" @complete="complete">
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Checklist pré-dépôt</p>
            <div class="space-y-1">
                <label v-for="item in checklistItems" :key="item" class="flex items-center gap-3 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" :checked="form.responses.checklist[item]" class="rounded text-primary-600" @change="toggleCheck(item)" />
                    <span class="text-sm text-gray-700">{{ item }}</span>
                </label>
            </div>
        </div>
        <div class="mb-6 rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Pays de création</p>
            <div class="flex flex-wrap gap-2">
                <button v-for="c in countries" :key="c.code" type="button" class="flex items-center gap-2 rounded-lg border px-4 py-2.5 text-sm transition" :class="form.responses.country===c.code ? 'border-primary-600 bg-primary-50 text-primary-700' : 'border-gray-200 text-gray-600'" @click="form.responses.country=c.code">
                    <span>{{ c.flag }}</span> {{ c.name }}
                </button>
            </div>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Type de parcours</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <button type="button" class="rounded-xl border-2 p-5 text-center transition" :class="form.responses.parcours==='individual' ? 'border-primary-600 bg-primary-50' : 'border-gray-200'" @click="form.responses.parcours='individual'">
                    <p class="text-2xl mb-2">👤</p>
                    <p class="text-sm font-medium text-gray-800">Entrepreneur individuel</p>
                </button>
                <button type="button" class="rounded-xl border-2 p-5 text-center transition" :class="form.responses.parcours==='company' ? 'border-primary-600 bg-primary-50' : 'border-gray-200'" @click="form.responses.parcours='company'">
                    <p class="text-2xl mb-2">🏢</p>
                    <p class="text-sm font-medium text-gray-800">Société</p>
                </button>
            </div>
        </div>
    </StepLayout>
</template>
