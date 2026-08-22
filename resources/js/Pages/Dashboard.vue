<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Icon from '@/Components/Icon.vue';
import Step1Form from '@/Components/JourneySteps/Step1Form.vue';
import Step2Form from '@/Components/JourneySteps/Step2Form.vue';
import Step3Form from '@/Components/JourneySteps/Step3Form.vue';
import Step4Form from '@/Components/JourneySteps/Step4Form.vue';
import Step5Form from '@/Components/JourneySteps/Step5Form.vue';
import Step6Form from '@/Components/JourneySteps/Step6Form.vue';
import Step7Form from '@/Components/JourneySteps/Step7Form.vue';
import Step8Form from '@/Components/JourneySteps/Step8Form.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const STATUS_CONFIG = {
    draft:     { label: 'Brouillon', color: 'bg-gray-100 text-gray-600' },
    submitted: { label: 'Soumise',   color: 'bg-blue-50 text-blue-700' },
    accepted:  { label: 'Acceptée',  color: 'bg-green-50 text-green-700' },
    rejected:  { label: 'Refusée',   color: 'bg-red-50 text-red-700' },
};
const STAGE_LABELS = { wish: 'Une envie', idea: 'Une idée', business_model: 'Business model', funding: 'Recherche de financement', legal: 'Accompagnement juridique' };
const STATUS_LABELS = { student: 'Étudiant(e)', employee: 'Salarié(e)', entrepreneur: "Chef(fe) d'entreprise", unemployed: 'Sans emploi' };
const SUPPORT_LABELS = { idea: "Définition de l'idée", market: 'Étude de marché', business_model: 'Modelage du business model', legal: 'Structuration financière et juridique', funding: 'Recherche de financement' };

export default {
    components: { PublicLayout, Icon, Head, Link, Step1Form, Step2Form, Step3Form, Step4Form, Step5Form, Step6Form, Step7Form, Step8Form },
    props: {
        application: Object,
        steps: Array,
        journeyResponses: Object,
    },
    data() {
        return {
            activeTab: 'candidature',
            openStep: null,
            stepData: {},
            saving: false,
            stepErrors: {},
        };
    },
    created() {
        for (let i = 1; i <= 8; i++) {
            const existing = this.journeyResponses?.[i];
            this.stepData[i] = existing?.data ? { ...existing.data } : {};
        }
    },
    computed: {
        user() { return this.$page.props.auth.user; },
        hasApplication() { return !!this.application; },
        isSubmitted() { return this.application && this.application.status !== 'draft'; },
        isAccepted() { return this.application?.status === 'accepted'; },
        statusConf() { return STATUS_CONFIG[this.application?.status] ?? STATUS_CONFIG.draft; },
        stageLabel() { return STAGE_LABELS[this.application?.project_stage] ?? '—'; },
        currentStatusLabel() { return STATUS_LABELS[this.application?.current_status] ?? '—'; },
        genderLabel() { return this.application?.gender === 'M' ? 'Masculin' : this.application?.gender === 'F' ? 'Féminin' : '—'; },
        teamLabel() { return this.application?.team_project ? 'En équipe' : 'Seul(e)'; },
        internetLabel() { return this.application?.has_internet_access ? 'Oui' : 'Non'; },
        previousProgramLabel() { return this.application?.previous_program ? 'Oui' : 'Non'; },
        supportNeedsLabel() {
            if (!this.application?.support_needs?.length) return '—';
            return this.application.support_needs.map(k => SUPPORT_LABELS[k] || k).join(', ');
        },
        submittedDate() {
            if (!this.application?.submitted_at) return null;
            return new Date(this.application.submitted_at).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
        },
    },
    methods: {
        toggleStep(n) { this.openStep = this.openStep === n ? null : n; },
        isStepUnlocked(n) { return this.isAccepted && n <= (this.application?.journey_current_step || 0); },
        isStepCompleted(n) { return !!this.journeyResponses?.[n]?.completed_at; },
        isStepValidated(n) { return !!this.journeyResponses?.[n]?.validated_at; },
        hasStepDraft(n) { return !!this.journeyResponses?.[n] && !this.journeyResponses[n].completed_at; },
        updateStepData(n, data) { this.stepData[n] = data; },
        getRequiredFields(n) {
            const R = {
                1: { origins: 'Origine de l\'idée', who: 'Qui', what: 'Quoi', why: 'Pourquoi', how: 'Comment', where: 'Où' },
                2: { project_name: 'Nom du projet', zone_country: 'Pays', description: 'Description', problem: 'Problème identifié', main_client: 'Client principal', decision: 'Décision' },
                3: { value_prop: 'Proposition de valeur', segments: 'Segments de clientèle' },
                5: { country: 'Pays d\'implantation', status: 'Statut actuel', legal_form: 'Forme juridique' },
                6: { host_company: 'Entreprise d\'accueil', tested_activity: 'Activité testée', assessment: 'Bilan' },
                7: { creation_country: 'Pays de création', track: 'Type de parcours' },
                8: { checklist: 'Checklist finale' },
            };
            return R[n] || {};
        },
        validateStep(n) {
            const required = this.getRequiredFields(n);
            const data = this.stepData[n] || {};
            const errors = {};
            for (const [key, label] of Object.entries(required)) {
                const val = data[key];
                if (!val || (Array.isArray(val) && val.length === 0) || (typeof val === 'string' && !val.trim())) {
                    errors[key] = `Le champ « ${label} » est requis.`;
                }
            }
            return errors;
        },
        saveStep(n, completed = false) {
            if (completed) {
                const errors = this.validateStep(n);
                if (Object.keys(errors).length) {
                    this.stepErrors = errors;
                    this.$nextTick(() => {
                        requestAnimationFrame(() => {
                            const el = document.querySelector('.field-error-highlight');
                            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    });
                    return;
                }
            }
            this.stepErrors = {};
            this.saving = true;
            router.post(route('journey-response.save', n), { data: this.stepData[n], completed }, {
                preserveScroll: true,
                onSuccess: () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                onFinish: () => { this.saving = false; },
            });
        },
    },
};
</script>

<template>
    <Head title="Mon espace — InAfrikaWeTrust" />
    <PublicLayout>
        <!-- HERO -->
        <section class="bg-primary-800 px-4 pb-0 pt-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl">
                <p class="mb-1 text-[10px] font-medium uppercase tracking-widest text-gold-400">Mon espace candidat</p>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-center gap-4">
                        <img v-if="user.avatar" :src="`/storage/${user.avatar}`" class="h-12 w-12 rounded-full object-cover border-2 border-white/20 sm:h-14 sm:w-14" alt="" />
                        <span v-else class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-semibold text-white uppercase sm:h-14 sm:w-14 sm:text-xl">{{ user.first_name?.[0] }}{{ user.last_name?.[0] }}</span>
                        <div>
                            <h1 class="font-serif text-xl font-normal text-white sm:text-2xl">{{ user.first_name }} {{ user.last_name }}</h1>
                            <p class="mt-0.5 text-xs text-primary-200 sm:text-sm">{{ user.email }}</p>
                        </div>
                    </div>
                    <span v-if="hasApplication" class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" :class="statusConf.color">{{ statusConf.label }}</span>
                </div>
                <div v-if="hasApplication && application.project_name" class="mt-4 flex flex-wrap gap-2">
                    <div class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5"><p class="text-[9px] uppercase tracking-wider text-white/40">Projet</p><p class="text-xs font-medium text-white">{{ application.project_name }}</p></div>
                    <div v-if="application.project_sector" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5"><p class="text-[9px] uppercase tracking-wider text-white/40">Secteur</p><p class="text-xs font-medium text-white">{{ application.project_sector }}</p></div>
                    <div v-if="application.project_stage" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5"><p class="text-[9px] uppercase tracking-wider text-white/40">Stade</p><p class="text-xs font-medium text-white">{{ stageLabel }}</p></div>
                </div>
                <div class="mt-6 flex gap-0">
                    <button type="button" class="border-b-2 px-5 pb-3 text-sm font-medium transition" :class="activeTab === 'candidature' ? 'border-gold-400 text-white' : 'border-transparent text-white/40 hover:text-white/70'" @click="activeTab = 'candidature'">Ma candidature</button>
                    <button type="button" class="border-b-2 px-5 pb-3 text-sm font-medium transition" :class="activeTab === 'parcours' ? 'border-gold-400 text-white' : 'border-transparent text-white/40 hover:text-white/70'" @click="activeTab = 'parcours'">Mon parcours</button>
                </div>
            </div>
        </section>

        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- MESSAGE FLASH -->
            <transition
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="opacity-0 -translate-y-3"
                enter-to-class="opacity-100 translate-y-0"
            >
                <div v-if="$page.props.flash?.success" class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-3 flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-green-700">{{ $page.props.flash.success }}</p>
                </div>
            </transition>
            <transition
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="opacity-0 -translate-y-3"
                enter-to-class="opacity-100 translate-y-0"
            >
                <div v-if="$page.props.flash?.error" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-3 flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-red-700">{{ $page.props.flash.error }}</p>
                </div>
            </transition>

            <!-- ERREURS DE VALIDATION -->
            <transition
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="opacity-0 -translate-y-3"
                enter-to-class="opacity-100 translate-y-0"
            >
                <div v-if="Object.keys($page.props.errors || {}).length" class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 shrink-0 mt-0.5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.58 14.88A1 1 0 002.58 20h18.84a1 1 0 00.86-1.26L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="mt-2 space-y-1">
                                <li v-for="(msg, field) in $page.props.errors" :key="field" class="flex items-start gap-2 text-sm text-red-700">
                                    <svg class="h-3.5 w-3.5 shrink-0 mt-0.5 text-red-400" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                    {{ msg }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </transition>

            <!-- ═══ PAS DE CANDIDATURE ═══ -->
            <div v-if="!hasApplication" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-primary-50 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        </span>
                        <div><p class="font-medium text-gray-900">Commencez votre parcours</p><p class="text-sm text-gray-500">Déposez votre candidature pour accéder au programme.</p></div>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <Link :href="route('application.index')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">Déposer ma candidature →</Link>
                </div>
            </div>

            <!-- ═══ TAB CANDIDATURE ═══ -->
            <template v-if="hasApplication && activeTab === 'candidature'">
                <div v-if="application.status === 'draft'" class="mb-6 overflow-hidden rounded-2xl border border-amber-100 bg-amber-50 px-6 py-4">
                    <p class="text-sm text-amber-700">Votre candidature est en brouillon.</p>
                    <Link :href="route('application.show')" class="mt-2 inline-flex items-center gap-2 rounded-full bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">Reprendre ma candidature →</Link>
                </div>
                <div v-if="application.status === 'submitted'" class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 px-6 py-4"><p class="text-sm text-blue-700">Votre candidature est en cours d'examen. Soumise le {{ submittedDate }}.</p></div>
                <div v-if="application.status === 'accepted'" class="mb-6 rounded-2xl border border-green-100 bg-green-50 px-6 py-4"><p class="text-sm text-green-700">Félicitations ! Votre candidature a été acceptée.</p></div>
                <div v-if="application.status === 'rejected'" class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-6 py-4"><p class="text-sm text-red-700">Votre candidature n'a pas été retenue.</p></div>

                <div class="mb-5"><p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 1 — Profil personnel</p><div class="rounded-xl border border-gray-100 bg-white p-5"><div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Genre</p><p class="mt-0.5 text-sm text-gray-800">{{ genderLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Pays</p><p class="mt-0.5 text-sm text-gray-800">{{ application.country_of_residence || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Ville</p><p class="mt-0.5 text-sm text-gray-800">{{ application.city_of_residence || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Adresse postale</p><p class="mt-0.5 text-sm text-gray-800">{{ application.postal_address || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">E-mail personnel</p><p class="mt-0.5 text-sm text-gray-800">{{ application.personal_email || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">WhatsApp</p><p class="mt-0.5 text-sm text-gray-800">{{ application.whatsapp_phone || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Internet stable</p><p class="mt-0.5 text-sm text-gray-800">{{ internetLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Statut</p><p class="mt-0.5 text-sm text-gray-800">{{ currentStatusLabel }}</p></div>
                    <div v-if="application.university_name"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Établissement</p><p class="mt-0.5 text-sm text-gray-800">{{ application.university_name }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Heures / semaine</p><p class="mt-0.5 text-sm text-gray-800">{{ application.weekly_hours ? `${application.weekly_hours}h` : '—' }}</p></div>
                </div></div></div>

                <div class="mb-5"><p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 2 — Votre projet</p><div class="rounded-xl border border-gray-100 bg-white p-5 space-y-4">
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Nom du projet</p><p class="mt-0.5 text-sm font-medium text-gray-900">{{ application.project_name || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Problème ciblé</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ application.project_problem || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Solution proposée</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ application.project_solution || '—' }}</p></div>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-3">
                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Secteur</p><p class="mt-0.5 text-sm text-gray-800">{{ application.project_sector || '—' }}</p></div>
                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Stade</p><p class="mt-0.5 text-sm text-gray-800">{{ stageLabel }}</p></div>
                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Porté</p><p class="mt-0.5 text-sm text-gray-800">{{ teamLabel }}</p></div>
                    </div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Concurrence</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ application.project_competition || '—' }}</p></div>
                    <div class="rounded-lg bg-gray-50 px-4 py-3"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-1">En une phrase</p><p class="text-sm text-gray-700 italic">« {{ application.project_pitch || '—' }} »</p></div>
                </div></div>

                <div class="mb-5"><p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 3 — Documents & liens</p><div class="rounded-xl border border-gray-100 bg-white p-5"><div class="flex flex-wrap gap-3">
                    <a v-if="application.cv_file" :href="`/storage/${application.cv_file}`" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>CV</a>
                    <a v-if="application.university_doc" :href="`/storage/${application.university_doc}`" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Attestation</a>
                    <a v-if="application.linkedin_url" :href="application.linkedin_url" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>LinkedIn</a>
                    <p v-if="!application.cv_file && !application.university_doc && !application.linkedin_url" class="text-sm text-gray-400">Aucun document uploadé.</p>
                </div></div></div>

                <div class="mb-5"><p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 4 — Compléments</p><div class="rounded-xl border border-gray-100 bg-white p-5">
                    <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Programme précédent</p><p class="mt-0.5 text-sm text-gray-800">{{ previousProgramLabel }}</p></div>
                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Besoins</p><p class="mt-0.5 text-sm text-gray-800">{{ supportNeedsLabel }}</p></div>
                        <div class="sm:col-span-2"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Comment avez-vous connu IAWT</p><p class="mt-0.5 text-sm text-gray-800">{{ application.heard_about_us || '—' }}</p></div>
                    </div>
                    <div class="mt-4 border-t border-gray-100 pt-4 flex items-center gap-4">
                        <div class="flex items-center gap-1.5"><svg :class="application.consent_data ? 'text-green-600' : 'text-gray-300'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-xs text-gray-600">Données</span></div>
                        <div class="flex items-center gap-1.5"><svg :class="application.consent_image ? 'text-green-600' : 'text-gray-300'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span class="text-xs text-gray-600">Image</span></div>
                    </div>
                </div></div>
            </template>

            <!-- ═══ TAB PARCOURS ═══ -->
            <template v-if="hasApplication && activeTab === 'parcours'">
                <div v-if="!isAccepted" class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 px-6 py-4">
                    <p class="text-sm text-amber-700">
                        <template v-if="application.status === 'draft'">Soumettez votre candidature pour débloquer le parcours.</template>
                        <template v-else-if="application.status === 'submitted'">Le parcours sera accessible dès que votre candidature sera acceptée.</template>
                        <template v-else>Le parcours n'est pas disponible pour le moment.</template>
                    </p>
                </div>

                <div class="space-y-3">
                    <div v-for="(step, i) in steps" :key="step.id" class="overflow-hidden rounded-2xl border bg-white shadow-sm" :class="isStepUnlocked(i+1) ? 'border-gray-100' : 'border-gray-100 opacity-50'">
                        <button type="button" class="flex w-full items-center gap-4 px-5 py-4 text-left transition" :class="isStepUnlocked(i+1) ? 'hover:bg-gray-50 cursor-pointer' : 'cursor-default'" :disabled="!isStepUnlocked(i+1)" @click="isStepUnlocked(i+1) && toggleStep(i+1)">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold" :class="isStepCompleted(i+1) ? 'bg-green-100 text-green-700' : isStepUnlocked(i+1) ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-gray-50 text-gray-400'">
                                <svg v-if="isStepCompleted(i+1)" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span v-else>{{ String(i+1).padStart(2,'0') }}</span>
                            </span>
                            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full" :class="isStepUnlocked(i+1) ? 'bg-primary-50 text-primary-700' : 'bg-gray-50 text-gray-300'"><Icon :name="step.icon" class="h-4 w-4" /></span>
                                <p class="text-sm font-medium" :class="isStepUnlocked(i+1) ? 'text-gray-900' : 'text-gray-400'">{{ step.label }}</p>
                            </div>
                            <span v-if="isStepValidated(i+1)" class="rounded-full bg-green-50 px-2.5 py-0.5 text-[10px] font-medium text-green-700">Validée ✓</span>
                            <span v-else-if="isStepCompleted(i+1)" class="rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-medium text-blue-700">Soumise</span>
                            <span v-else-if="hasStepDraft(i+1) && journeyResponses?.[i+1]?.completed_at === null && journeyResponses?.[i+1]?.updated_at" class="rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-medium text-amber-700">À retravailler</span>
                            <span v-else-if="hasStepDraft(i+1)" class="rounded-full bg-gold-50 px-2.5 py-0.5 text-[10px] font-medium text-gold-700">En cours</span>
                            <span v-else-if="isStepUnlocked(i+1)" class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-medium text-gray-500">À faire</span>
                            <span v-else class="text-gray-300"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg></span>
                            <svg v-if="isStepUnlocked(i+1)" class="h-4 w-4 text-gray-400 transition" :class="openStep===i+1?'rotate-180':''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div v-if="openStep===i+1 && isStepUnlocked(i+1)" class="border-t border-gray-100 px-5 py-5">
                            <!-- Message de retravail de l'admin -->
                            <div v-if="journeyResponses?.[i+1]?.rework_reason" class="mb-5 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="text-sm font-medium text-amber-800">Votre accompagnateur vous demande de revoir cette étape</p>
                                        <p class="mt-1 text-sm text-amber-700 leading-relaxed">{{ journeyResponses[i+1].rework_reason }}</p>
                                    </div>
                                </div>
                            </div>

                            <component :is="'Step'+(i+1)+'Form'" :model-value="stepData[i+1]" :errors="stepErrors" @update:model-value="updateStepData(i+1,$event)" />
                            <div class="mt-6 border-t border-gray-100 pt-5">
                                <div v-if="Object.keys(stepErrors).length" class="mb-4 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5">
                                    <svg class="h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86l-8.58 14.88A1 1 0 002.58 20h18.84a1 1 0 00.86-1.26L13.71 3.86a1 1 0 00-1.72 0z"/></svg>
                                    <p class="text-xs text-red-700">Il y a des erreurs dans le formulaire. Veuillez vérifier les champs en rouge ci-dessus.</p>
                                </div>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <button type="button" :disabled="saving" class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50" @click="saveStep(i+1,false)">Enregistrer le brouillon</button>
                                    <button type="button" :disabled="saving" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-50" @click="saveStep(i+1,true)">Valider cette étape</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </PublicLayout>
</template>
