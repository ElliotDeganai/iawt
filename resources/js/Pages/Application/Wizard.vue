<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const SECTORS = [
    'Agriculture', 'Bâtiment - Travaux publics', 'Commerce et distribution',
    'Énergie', 'Hôtellerie - Restauration', 'Industrie agroalimentaire',
    'Industrie automobile', 'Industrie pharmaceutique', 'Industrie textile',
    'Luxe', 'Maritime et fluvial', 'Numérique et télécommunications',
    'Services automobiles', 'Soin et accompagnement', 'Tourisme', 'Transport',
];

const SUPPORT_NEEDS = [
    { value: 'idea',           label: "Définition de l'idée" },
    { value: 'market',         label: 'Étude de marché' },
    { value: 'business_model', label: 'Modelage du business model' },
    { value: 'legal',          label: 'Structuration financière et juridique' },
    { value: 'funding',        label: 'Recherche de financement' },
];

const STAGES = [
    { value: 'wish',           label: 'Une envie' },
    { value: 'idea',           label: 'Une idée' },
    { value: 'business_model', label: 'Business model' },
    { value: 'funding',        label: 'Recherche de financement' },
    { value: 'legal',          label: 'Accompagnement administratif et juridique' },
];

const STATUSES = [
    { value: 'student',       label: 'Étudiant(e)' },
    { value: 'employee',      label: 'Salarié(e)' },
    { value: 'entrepreneur',  label: "Chef(fe) d'entreprise" },
    { value: 'unemployed',    label: 'Sans emploi' },
];

export default {
    components: { PublicLayout, InputError, Head },
    props: {
        application: Object,
        step: Number,
    },
    data() {
        const a = this.application;
        return {
            sectors:      SECTORS,
            supportNeeds: SUPPORT_NEEDS,
            stages:       STAGES,
            statuses:     STATUSES,
            cvPreview:      a.cv_file       ? `/storage/${a.cv_file}`       : null,
            uniDocPreview:  a.university_doc ? `/storage/${a.university_doc}` : null,
            wordCounts: { problem: 0, solution: 0, competition: 0, pitch: 0 },
            form: useForm({
                step:   this.step,
                is_final: false,
                // Étape 1
                gender:               a.gender               ?? '',
                country_of_residence: a.country_of_residence ?? '',
                city_of_residence:    a.city_of_residence    ?? '',
                postal_address:       a.postal_address       ?? '',
                personal_email:       a.personal_email       ?? '',
                whatsapp_phone:       a.whatsapp_phone       ?? '',
                has_internet_access:  a.has_internet_access  ?? '',
                current_status:       a.current_status       ?? '',
                university_name:      a.university_name      ?? '',
                university_doc:       null,
                weekly_hours:         a.weekly_hours         ?? '',
                // Étape 2
                project_name:        a.project_name        ?? '',
                project_problem:     a.project_problem     ?? '',
                project_solution:    a.project_solution    ?? '',
                project_sector:      a.project_sector      ?? '',
                project_competition: a.project_competition ?? '',
                project_pitch:       a.project_pitch       ?? '',
                project_stage:       a.project_stage       ?? '',
                team_project:        a.team_project        ?? '',
                // Étape 3
                cv_file:      null,
                linkedin_url: a.linkedin_url ?? '',
                // Étape 4
                previous_program: a.previous_program ?? '',
                support_needs:    a.support_needs    ?? [],
                heard_about_us:   a.heard_about_us   ?? '',
                consent_data:     a.consent_data     ?? false,
                consent_image:    a.consent_image    ?? false,
            }),
        };
    },
    computed: {
        stepLabels() {
            return ['Profil personnel', 'Votre projet', 'Documents & liens', 'Finaliser'];
        },
        progressPct() {
            return ((this.step - 1) / 4) * 100;
        },
        isStudent() {
            return this.form.current_status === 'student';
        },
    },
    methods: {
        countWords(text) {
            return text ? text.trim().split(/\s+/).filter(Boolean).length : 0;
        },
        onCvChange(e) {
            this.form.cv_file = e.target.files[0];
            if (e.target.files[0]) this.cvPreview = e.target.files[0].name;
        },
        onUniDocChange(e) {
            this.form.university_doc = e.target.files[0];
            if (e.target.files[0]) this.uniDocPreview = e.target.files[0].name;
        },
        toggleSupportNeed(val) {
            const idx = this.form.support_needs.indexOf(val);
            if (idx >= 0) this.form.support_needs.splice(idx, 1);
            else this.form.support_needs.push(val);
        },
        save(andSubmit = false) {
            this.form.step   = this.step;
            this.form.is_final = andSubmit;
            this.form
                .transform((data) => ({ ...data, _method: 'patch' }))
                .post(route('application.update'), { forceFormData: true });
        },
        goBack() {
            this.form.step   = this.step - 1;
            this.form.is_final = false;
            this.form
                .transform((data) => ({ ...data, _method: 'patch' }))
                .post(route('application.update'), { forceFormData: true });
        },
    },
};
</script>

<template>
    <Head title="Formulaire de candidature — InAfrikaWeTrust" />

    <PublicLayout>
        <!-- HERO bordeaux avec barre d'étapes -->
        <div class="bg-primary-800">
            <div class="mx-auto max-w-4xl px-4 pt-8 sm:px-6 lg:px-8">
                <p class="mb-1 text-[10px] font-medium uppercase tracking-widest text-gold-400">Promotion 2026</p>
                <h1 class="font-serif text-2xl font-normal text-white">Déposez votre candidature</h1>
            </div>

            <!-- Barre d'étapes -->
            <div class="mx-auto max-w-4xl px-4 pt-6 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <div v-for="(label, i) in stepLabels" :key="i" class="flex flex-1 items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="flex h-7 w-7 items-center justify-center rounded-full border text-[11px] font-semibold transition"
                                :class="[
                                    i + 1 < step  ? 'border-gold-400 bg-gold-400 text-primary-900' : '',
                                    i + 1 === step ? 'border-white bg-white text-primary-800' : '',
                                    i + 1 > step  ? 'border-white/30 bg-transparent text-white/50' : '',
                                ]"
                            >
                                <svg v-if="i + 1 < step" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                <span v-else>{{ i + 1 }}</span>
                            </div>
                            <span class="mt-1 hidden text-[10px] sm:block" :class="i + 1 === step ? 'text-white font-medium' : 'text-white/40'">{{ label }}</span>
                        </div>
                        <div v-if="i < stepLabels.length - 1" class="mx-2 h-px flex-1" :class="i + 1 < step ? 'bg-gold-400' : 'bg-white/20'"></div>
                    </div>
                </div>
            </div>

            <!-- Barre de progression -->
            <div class="mt-4 h-1 bg-primary-900/40">
                <div class="h-full bg-gold-500 transition-all duration-500" :style="`width: ${progressPct}%`"></div>
            </div>
        </div>

        <!-- Corps du wizard -->
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex gap-6 items-start">

                <!-- Formulaire -->
                <div class="min-w-0 flex-1">
                    <form enctype="multipart/form-data" @submit.prevent="save()">

                        <!-- ════════ ÉTAPE 1 : Profil personnel ════════ -->
                        <template v-if="step === 1">
                            <div class="space-y-5">
                                <h2 class="font-serif text-xl font-normal text-gray-900">Profil personnel</h2>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.gender" type="radio" value="M" class="text-primary-600" />
                                            <span class="text-sm text-gray-700">Masculin</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.gender" type="radio" value="F" class="text-primary-600" />
                                            <span class="text-sm text-gray-700">Féminin</span>
                                        </label>
                                    </div>
                                    <InputError class="mt-1" :message="form.errors.gender" />
                                </div>

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Pays de résidence <span class="text-red-500">*</span></label>
                                        <input v-model="form.country_of_residence" type="text" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Ex : Côte d'Ivoire" />
                                        <InputError class="mt-1" :message="form.errors.country_of_residence" />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Ville de résidence <span class="text-red-500">*</span></label>
                                        <input v-model="form.city_of_residence" type="text" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Ex : Abidjan" />
                                        <InputError class="mt-1" :message="form.errors.city_of_residence" />
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Adresse postale actuelle <span class="text-red-500">*</span></label>
                                    <input v-model="form.postal_address" type="text" class="w-full rounded-lg border-gray-200 text-sm" />
                                    <InputError class="mt-1" :message="form.errors.postal_address" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Adresse e-mail personnelle <span class="text-red-500">*</span></label>
                                    <input v-model="form.personal_email" type="email" class="w-full rounded-lg border-gray-200 text-sm" />
                                    <p class="mt-1 text-xs text-gray-400">Vérifiez attentivement votre adresse avant de continuer.</p>
                                    <InputError class="mt-1" :message="form.errors.personal_email" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Numéro WhatsApp <span class="text-red-500">*</span></label>
                                    <input v-model="form.whatsapp_phone" type="tel" class="w-full rounded-lg border-gray-200 text-sm" placeholder="+225XXXXXXXXXX" />
                                    <p class="mt-1 text-xs text-gray-400">Format international. Assurez-vous que ce numéro est accessible via WhatsApp.</p>
                                    <InputError class="mt-1" :message="form.errors.whatsapp_phone" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Accès à un matériel adéquat et une connexion internet stable ? <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.has_internet_access" type="radio" :value="true" class="text-primary-600" />
                                            <span class="text-sm">Oui</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.has_internet_access" type="radio" :value="false" class="text-primary-600" />
                                            <span class="text-sm">Non</span>
                                        </label>
                                    </div>
                                    <InputError class="mt-1" :message="form.errors.has_internet_access" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Statut actuel <span class="text-red-500">*</span></label>
                                    <select v-model="form.current_status" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="">Sélectionner une option</option>
                                        <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                                    </select>
                                    <InputError class="mt-1" :message="form.errors.current_status" />
                                </div>

                                <div v-if="isStudent">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Nom de votre établissement universitaire</label>
                                    <input v-model="form.university_name" type="text" class="w-full rounded-lg border-gray-200 text-sm" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Heures par semaine disponibles pour l'entrepreneuriat <span class="text-red-500">*</span></label>
                                    <input v-model="form.weekly_hours" type="number" step="0.5" min="0" class="w-full rounded-lg border-gray-200 text-sm" placeholder="Ex : 10" />
                                    <p class="mt-1 text-xs text-gray-400">En tenant compte de vos autres engagements (études, emploi, etc.).</p>
                                    <InputError class="mt-1" :message="form.errors.weekly_hours" />
                                </div>
                            </div>
                        </template>

                        <!-- ════════ ÉTAPE 2 : Votre projet ════════ -->
                        <template v-if="step === 2">
                            <div class="space-y-5">
                                <h2 class="font-serif text-xl font-normal text-gray-900">Votre projet</h2>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Nom du projet <span class="text-red-500">*</span></label>
                                    <input v-model="form.project_name" type="text" class="w-full rounded-lg border-gray-200 text-sm" />
                                    <InputError class="mt-1" :message="form.errors.project_name" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Quel problème votre projet vise-t-il à résoudre ? <span class="text-red-500">*</span></label>
                                    <textarea v-model="form.project_problem" rows="3" class="w-full rounded-lg border-gray-200 text-sm" @input="wordCounts.problem = countWords(form.project_problem)"></textarea>
                                    <div class="mt-1 flex justify-between">
                                        <InputError :message="form.errors.project_problem" />
                                        <span class="text-xs" :class="wordCounts.problem > 50 ? 'text-red-500' : 'text-gray-400'">{{ wordCounts.problem }} / 50 mots</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Quelle solution proposez-vous ? <span class="text-red-500">*</span></label>
                                    <textarea v-model="form.project_solution" rows="4" class="w-full rounded-lg border-gray-200 text-sm" @input="wordCounts.solution = countWords(form.project_solution)"></textarea>
                                    <div class="mt-1 flex justify-between">
                                        <InputError :message="form.errors.project_solution" />
                                        <span class="text-xs" :class="wordCounts.solution > 100 ? 'text-red-500' : 'text-gray-400'">{{ wordCounts.solution }} / 100 mots</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Secteur d'activité <span class="text-red-500">*</span></label>
                                    <select v-model="form.project_sector" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="">Sélectionner un secteur</option>
                                        <option v-for="s in sectors" :key="s" :value="s">{{ s }}</option>
                                    </select>
                                    <InputError class="mt-1" :message="form.errors.project_sector" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Solutions similaires existantes <span class="text-red-500">*</span></label>
                                    <textarea v-model="form.project_competition" rows="3" class="w-full rounded-lg border-gray-200 text-sm" @input="wordCounts.competition = countWords(form.project_competition)"></textarea>
                                    <div class="mt-1 flex justify-between">
                                        <InputError :message="form.errors.project_competition" />
                                        <span class="text-xs" :class="wordCounts.competition > 50 ? 'text-red-500' : 'text-gray-400'">{{ wordCounts.competition }} / 50 mots</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Résumez votre projet en une phrase <span class="text-red-500">*</span></label>
                                    <textarea v-model="form.project_pitch" rows="2" class="w-full rounded-lg border-gray-200 text-sm" @input="wordCounts.pitch = countWords(form.project_pitch)"></textarea>
                                    <div class="mt-1 flex justify-between">
                                        <InputError :message="form.errors.project_pitch" />
                                        <span class="text-xs" :class="wordCounts.pitch > 50 ? 'text-red-500' : 'text-gray-400'">{{ wordCounts.pitch }} / 50 mots</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Stade de développement <span class="text-red-500">*</span></label>
                                    <select v-model="form.project_stage" class="w-full rounded-lg border-gray-200 text-sm">
                                        <option value="">Sélectionner une option</option>
                                        <option v-for="s in stages" :key="s.value" :value="s.value">{{ s.label }}</option>
                                    </select>
                                    <InputError class="mt-1" :message="form.errors.project_stage" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Projet porté seul ou en équipe ? <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.team_project" type="radio" :value="false" class="text-primary-600" />
                                            <span class="text-sm">Seul(e)</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.team_project" type="radio" :value="true" class="text-primary-600" />
                                            <span class="text-sm">En équipe</span>
                                        </label>
                                    </div>
                                    <InputError class="mt-1" :message="form.errors.team_project" />
                                </div>
                            </div>
                        </template>

                        <!-- ════════ ÉTAPE 3 : Documents ════════ -->
                        <template v-if="step === 3">
                            <div class="space-y-5">
                                <h2 class="font-serif text-xl font-normal text-gray-900">Documents & liens</h2>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">CV <span class="text-red-500">*</span></label>
                                    <div class="rounded-lg border-2 border-dashed border-gray-200 px-4 py-6 text-center">
                                        <p v-if="cvPreview" class="mb-2 text-sm font-medium text-primary-700">{{ cvPreview }}</p>
                                        <p v-else class="mb-2 text-sm text-gray-400">PDF, DOC, DOCX · max 1 Mo</p>
                                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            Déposer un fichier
                                            <input type="file" accept=".pdf,.doc,.docx" class="hidden" @change="onCvChange" />
                                        </label>
                                    </div>
                                    <InputError class="mt-1" :message="form.errors.cv_file" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Attestation universitaire <span class="text-gray-400 font-normal">(si étudiant)</span></label>
                                    <div class="rounded-lg border-2 border-dashed border-gray-200 px-4 py-6 text-center">
                                        <p v-if="uniDocPreview" class="mb-2 text-sm font-medium text-primary-700">{{ uniDocPreview }}</p>
                                        <p v-else class="mb-2 text-sm text-gray-400">Attestation, carte étudiante, diplôme · PDF ou image · max 1 Mo</p>
                                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-full border border-gray-300 bg-white px-4 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            Déposer un fichier
                                            <input type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" @change="onUniDocChange" />
                                        </label>
                                    </div>
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Profil LinkedIn</label>
                                    <input v-model="form.linkedin_url" type="url" class="w-full rounded-lg border-gray-200 text-sm" placeholder="https://linkedin.com/in/votre-profil" />
                                    <p class="mt-1 text-xs text-gray-400">Assurez-vous que votre profil est à jour et accessible au public.</p>
                                    <InputError class="mt-1" :message="form.errors.linkedin_url" />
                                </div>
                            </div>
                        </template>

                        <!-- ════════ ÉTAPE 4 : Finaliser ════════ -->
                        <template v-if="step === 4">
                            <div class="space-y-5">
                                <h2 class="font-serif text-xl font-normal text-gray-900">Finaliser</h2>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Avez-vous déjà bénéficié d'un autre programme d'accompagnement ? <span class="text-red-500">*</span></label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.previous_program" type="radio" :value="true" class="text-primary-600" />
                                            <span class="text-sm">Oui</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input v-model="form.previous_program" type="radio" :value="false" class="text-primary-600" />
                                            <span class="text-sm">Non</span>
                                        </label>
                                    </div>
                                    <InputError class="mt-1" :message="form.errors.previous_program" />
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Principaux besoins en accompagnement <span class="text-red-500">*</span></label>
                                    <div class="space-y-2">
                                        <label v-for="need in supportNeeds" :key="need.value" class="flex items-center gap-2 cursor-pointer">
                                            <input
                                                type="checkbox"
                                                :value="need.value"
                                                :checked="form.support_needs.includes(need.value)"
                                                class="rounded text-primary-600"
                                                @change="toggleSupportNeed(need.value)"
                                            />
                                            <span class="text-sm text-gray-700">{{ need.label }}</span>
                                        </label>
                                    </div>
                                    <InputError class="mt-1" :message="form.errors.support_needs" />
                                </div>

                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Comment avez-vous entendu parler d'InAfrikaWeTrust ? <span class="text-red-500">*</span></label>
                                    <textarea v-model="form.heard_about_us" rows="2" class="w-full rounded-lg border-gray-200 text-sm"></textarea>
                                    <InputError class="mt-1" :message="form.errors.heard_about_us" />
                                </div>

                                <div class="space-y-3 rounded-xl border border-amber-100 bg-amber-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Consentements obligatoires</p>

                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input v-model="form.consent_data" type="checkbox" class="mt-0.5 rounded text-primary-600" />
                                        <span class="text-sm text-gray-700">
                                            J'autorise InAfrikaWeTrust et ses partenaires à collecter, stocker et traiter mes données personnelles dans le cadre de ma candidature, conformément à la réglementation en vigueur. <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <InputError :message="form.errors.consent_data" />

                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input v-model="form.consent_image" type="checkbox" class="mt-0.5 rounded text-primary-600" />
                                        <span class="text-sm text-gray-700">
                                            J'autorise InAfrikaWeTrust et ses partenaires à utiliser mon image, ma voix et mes propositions lors des activités du programme, sur tout support, aux fins de communication. <span class="text-red-500">*</span>
                                        </span>
                                    </label>
                                    <InputError :message="form.errors.consent_image" />
                                </div>
                            </div>
                        </template>

                        <!-- Navigation bas de page -->
                        <div class="mt-8 flex items-center justify-between border-t border-gray-100 pt-6">
                            <button
                                v-if="step > 1"
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-full border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                :disabled="form.processing"
                                @click="goBack"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                                Retour
                            </button>
                            <span v-else></span>

                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400">Étape {{ step }} / 4</span>
                                <button
                                    v-if="step < 4"
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center gap-1.5 rounded-full bg-primary-600 px-6 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60"
                                >
                                    Étape suivante
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                                </button>
                                <button
                                    v-else
                                    type="button"
                                    :disabled="form.processing"
                                    class="inline-flex items-center gap-2 rounded-full bg-gold-500 px-6 py-2 text-sm font-semibold text-primary-900 hover:bg-gold-400 disabled:opacity-60"
                                    @click="save(true)"
                                >
                                    Soumettre ma candidature →
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sidebar de progression -->
                <aside class="hidden w-56 shrink-0 lg:block">
                    <div class="sticky top-24 space-y-4">
                        <div class="rounded-xl border border-gray-100 bg-white p-4">
                            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gray-400">Progression</p>
                            <div class="mb-4 h-1.5 rounded-full bg-gray-100">
                                <div class="h-full rounded-full bg-gold-500 transition-all" :style="`width: ${progressPct}%`"></div>
                            </div>
                            <ul class="space-y-2">
                                <li v-for="(label, i) in stepLabels" :key="i" class="flex items-center gap-2.5">
                                    <span
                                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-semibold"
                                        :class="[
                                            i + 1 < step  ? 'bg-gold-400 text-primary-900' : '',
                                            i + 1 === step ? 'bg-primary-600 text-white' : '',
                                            i + 1 > step  ? 'border border-gray-200 text-gray-300' : '',
                                        ]"
                                    >
                                        <svg v-if="i + 1 < step" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                        <span v-else>{{ i + 1 }}</span>
                                    </span>
                                    <span class="text-xs" :class="i + 1 === step ? 'font-medium text-gray-900' : i + 1 < step ? 'text-gray-500' : 'text-gray-300'">{{ label }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="rounded-xl border border-primary-100 bg-primary-50 p-4">
                            <div class="flex items-start gap-2">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                <div>
                                    <p class="text-xs font-medium text-primary-800">Sauvegarde automatique</p>
                                    <p class="mt-0.5 text-xs text-primary-600">Votre progression est sauvegardée à chaque étape. Vous pouvez reprendre plus tard.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </PublicLayout>
</template>
