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
                },
            }),
            items: [
                'RCCM obtenu',
                'NIF obtenu',
                'Documents constitutifs récupérés',
                'Formalités sociales effectuées',
                'Autorisations sectorielles obtenues',
                'Compte bancaire professionnel ouvert',
                'Première facture / première vente prête',
            ],
        };
    },
    methods: {
        toggleCheck(item) { this.form.responses.checklist[item] = !this.form.responses.checklist[item]; },
        save() { this.form.put(route('journey-response.save', this.stepNumber)); },
        complete() { this.form.put(route('journey-response.complete', this.stepNumber)); },
    },
};
</script>

<template>
    <StepLayout :step-number="stepNumber" :total-steps="totalSteps" title="Mon entreprise est créée" subtitle="Checklist finale avant le grand départ." :saving="form.processing" @save="save" @complete="complete">
        <div class="mb-6 text-center">
            <p class="text-4xl mb-3">🎉</p>
            <p class="text-lg font-medium text-gray-800">Félicitations !</p>
            <p class="text-sm text-gray-500">Cochez chaque élément au fur et à mesure.</p>
        </div>
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="space-y-1">
                <label v-for="item in items" :key="item" class="flex items-center gap-3 rounded-lg px-3 py-3 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="form.responses.checklist[item]" class="h-5 w-5 rounded text-primary-600" @change="toggleCheck(item)" />
                    <span class="text-sm" :class="form.responses.checklist[item] ? 'text-gray-800 font-medium' : 'text-gray-600'">{{ item }}</span>
                </label>
            </div>
        </div>
    </StepLayout>
</template>
