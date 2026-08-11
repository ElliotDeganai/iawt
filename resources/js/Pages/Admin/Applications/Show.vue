<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Icon from '@/Components/Icon.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

const STATUS_CONFIG = {
    draft:     { label: 'Brouillon', class: 'bg-gray-100 text-gray-600' },
    submitted: { label: 'Soumise',   class: 'bg-blue-50 text-blue-700' },
    accepted:  { label: 'Acceptée',  class: 'bg-green-50 text-green-700' },
    rejected:  { label: 'Refusée',   class: 'bg-red-50 text-red-700' },
};

const STAGE_LABELS = {
    wish: 'Une envie', idea: 'Une idée', business_model: 'Business model',
    funding: 'Recherche de financement', legal: 'Accompagnement juridique',
};
const STATUS_LABELS = {
    student: 'Étudiant(e)', employee: 'Salarié(e)',
    entrepreneur: "Chef(fe) d'entreprise", unemployed: 'Sans emploi',
};
const SUPPORT_LABELS = {
    idea: "Définition de l'idée", market: 'Étude de marché',
    business_model: 'Modelage du business model',
    legal: 'Structuration financière et juridique',
    funding: 'Recherche de financement',
};

export default {
    components: { AdminLayout, Icon, Modal, Head, Link },
    props: {
        application: Object,
        journeySteps: Array,
        journeyResponses: Object,
    },
    data() {
        return {
            activeTab: 'profil',
            expandedStep: null,
            showRejectModal: false,
            reworkStep: null,
            reworkForm: useForm({ step: null, reason: '' }),
            rejectForm: useForm({ rejection_reason: '' }),
            commentForm: useForm({ body: '' }),
        };
    },
    computed: {
        a() { return this.application; },
        user() {
            return this.application.user || {
                first_name: 'Utilisateur',
                last_name: 'supprimé',
                email: '—',
                avatar: null,
            };
        },
        userDeleted() { return !this.application.user; },
        statusConf() { return STATUS_CONFIG[this.a.status] ?? STATUS_CONFIG.draft; },
        stageLabel() { return STAGE_LABELS[this.a.project_stage] ?? '—'; },
        currentStatusLabel() { return STATUS_LABELS[this.a.current_status] ?? '—'; },
        genderLabel() { return this.a.gender === 'M' ? 'Masculin' : this.a.gender === 'F' ? 'Féminin' : '—'; },
        teamLabel() { return this.a.team_project ? 'En équipe' : 'Seul(e)'; },
        internetLabel() { return this.a.has_internet_access ? 'Oui' : 'Non'; },
        previousProgramLabel() { return this.a.previous_program ? 'Oui' : 'Non'; },
        supportNeedsLabel() {
            if (!this.a.support_needs?.length) return '—';
            return this.a.support_needs.map(k => SUPPORT_LABELS[k] || k).join(', ');
        },
        submittedDate() {
            if (!this.a.submitted_at) return null;
            return new Date(this.a.submitted_at).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
        },
    },
    methods: {
        accept() {
            router.put(route('admin.applications.accept', this.a.id), {}, { preserveScroll: true });
        },
        submitReject() {
            this.rejectForm.put(route('admin.applications.reject', this.a.id), {
                preserveScroll: true,
                onSuccess: () => { this.showRejectModal = false; this.rejectForm.reset(); },
            });
        },
        unlockStep(step) {
            router.put(route('admin.applications.unlock-step', this.a.id), { step }, { preserveScroll: true });
        },
        validateStep(step) {
            router.put(route('admin.applications.validate-step', this.a.id), { step }, { preserveScroll: true });
        },
        openReworkModal(step) {
            this.reworkStep = step;
            this.reworkForm.step = step;
            this.reworkForm.reason = '';
        },
        submitRework() {
            this.reworkForm.put(route('admin.applications.rework-step', this.a.id), {
                preserveScroll: true,
                onSuccess: () => { this.reworkStep = null; this.reworkForm.reset(); },
            });
        },
        submitComment() {
            this.commentForm.post(route('admin.applications.comment', this.a.id), {
                preserveScroll: true,
                onSuccess: () => this.commentForm.reset(),
            });
        },
        toggleStepExpand(num) {
            this.expandedStep = this.expandedStep === num ? null : num;
        },
        hasStepResponse(num) {
            return !!this.journeyResponses?.[num];
        },
        stepResponseStatus(num) {
            const r = this.journeyResponses?.[num];
            if (!r) return 'todo';
            if (r.completed_at) return 'completed';
            return 'draft';
        },
        getStepResponses(num) {
            return this.journeyResponses?.[num]?.data || {};
        },
        formatFieldLabel(key) {
            const labels = {
                idea_origin: "Origine de l'idée", idea_origin_other: 'Autre origine',
                who: 'Qui', what: 'Quoi', why: 'Pourquoi', how: 'Comment', where: 'Où',
                alignment_energy: 'Alignement énergétique', alignment_skills: 'Compétences', alignment_lifestyle: 'Mode de vie',
                project_name: 'Nom du projet', project_zone: 'Zone géographique', project_description: 'Description',
                problem: 'Problème', problem_frequency: 'Fréquence', problem_consequences: 'Conséquences',
                problem_source: 'Source', people_consulted: 'Personnes consultées', key_quotes: 'Citations',
                target_main: 'Cible principale', target_age: 'Âge/situation', target_constraints: 'Contraintes',
                target_segments: 'Segments', target_priority: 'Priorité',
                market_scope: 'Portée marché', market_trend: 'Tendance',
                competitors: 'Concurrents', alt_solutions: 'Alternatives',
                swot_strengths: 'Forces', swot_weaknesses: 'Faiblesses', swot_opportunities: 'Opportunités', swot_threats: 'Menaces',
                decision: 'Décision',
                partners: 'Partenaires clés', activities: 'Activités clés', value: 'Proposition de valeur',
                relationship: 'Relation client', segments: 'Segments', resources: 'Ressources', channels: 'Canaux',
                costs: 'Structure de coûts', revenue: 'Sources de revenus',
                needs: 'Besoins', sources: 'Ressources financières',
                country: 'Pays', city: 'Ville', current_status: 'Statut', experience: 'Expérience',
                sector: 'Secteur', project_type: 'Type de projet', founders_count: 'Fondateurs',
                chosen_form: 'Forme juridique', reason: 'Justification',
                host_company: "Entreprise d'accueil", activity_tested: 'Activité testée',
                activities_done: 'Activités réalisées', sales_count: 'Ventes', revenue: 'CA',
                expenses: 'Dépenses', result: 'Résultat',
                worked_well: 'Ce qui a fonctionné', was_difficult: 'Difficultés', must_change: 'À changer',
                project_status: 'Bilan', support_need: 'Besoin accompagnement',
                checklist: 'Checklist', parcours: 'Type de parcours',
            };
            return labels[key] || key.replace(/_/g, ' ').replace(/^\w/, c => c.toUpperCase());
        },
        formatDate(d) {
            if (!d) return '—';
            return new Date(d).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
    },
};
</script>

<template>
    <Head :title="`${user.first_name} ${user.last_name} — Candidature`" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <Link :href="route('admin.applications.index')" class="hover:text-primary-600">Candidatures</Link>
                <span>/</span>
                <span class="text-gray-800">{{ user.first_name }} {{ user.last_name }}</span>
            </div>
        </template>

        <!-- Header candidat -->
        <div class="mb-6 overflow-hidden rounded-xl bg-primary-800">
            <!-- Alerte user supprimé -->
            <div v-if="userDeleted" class="bg-red-600 px-6 py-2 text-center text-xs font-medium text-white">
                Cet utilisateur a été supprimé — la candidature est conservée en lecture seule.
            </div>
            <div class="px-6 py-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-4">
                        <img v-if="user.avatar" :src="`/storage/${user.avatar}`" class="h-12 w-12 rounded-full object-cover border-2 border-white/20" alt="" />
                        <span v-else class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-semibold text-white">
                            {{ user.first_name?.[0] }}{{ user.last_name?.[0] }}
                        </span>
                        <div>
                            <h1 class="text-xl font-medium text-white">{{ user.first_name }} {{ user.last_name }}</h1>
                            <p class="text-sm text-primary-200">{{ user.email }}</p>
                            <p v-if="a.country_of_residence" class="mt-0.5 text-xs text-primary-300">
                                {{ a.city_of_residence }}, {{ a.country_of_residence }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold" :class="statusConf.class">
                            {{ statusConf.label }}
                        </span>
                        <!-- Actions -->
                        <template v-if="a.status === 'submitted' && !userDeleted">
                            <button
                                type="button"
                                class="rounded-md bg-green-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-green-700"
                                @click="accept"
                            >Accepter</button>
                            <button
                                type="button"
                                class="rounded-md bg-red-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-red-700"
                                @click="showRejectModal = true"
                            >Rejeter</button>
                        </template>
                    </div>
                </div>

                <!-- Pills résumé -->
                <div v-if="a.project_name" class="mt-4 flex flex-wrap gap-2">
                    <div class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Projet</p>
                        <p class="text-xs font-medium text-white">{{ a.project_name }}</p>
                    </div>
                    <div v-if="a.project_sector" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Secteur</p>
                        <p class="text-xs font-medium text-white">{{ a.project_sector }}</p>
                    </div>
                    <div v-if="a.project_stage" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Stade</p>
                        <p class="text-xs font-medium text-white">{{ stageLabel }}</p>
                    </div>
                    <div v-if="submittedDate" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Soumise le</p>
                        <p class="text-xs font-medium text-white">{{ submittedDate }}</p>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="flex gap-0 border-t border-white/10 px-3 overflow-x-auto sm:px-6">
                <button
                    v-for="tab in [{key:'profil',label:'Profil'},{key:'candidature',label:'Candidature'},{key:'parcours',label:'Parcours'},{key:'commentaires',label:'Commentaires'}]"
                    :key="tab.key"
                    type="button"
                    class="border-b-2 px-4 py-3 text-sm font-medium transition"
                    :class="activeTab === tab.key ? 'border-gold-400 text-white' : 'border-transparent text-white/40 hover:text-white/70'"
                    @click="activeTab = tab.key"
                >{{ tab.label }}</button>
            </div>
        </div>

        <!-- ═══ TAB PROFIL ═══ -->
        <div v-if="activeTab === 'profil'" class="space-y-4">
            <div class="rounded-xl border border-gray-100 bg-white p-5">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Informations personnelles</p>
                <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3">
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Nom complet</p><p class="mt-0.5 text-sm text-gray-800">{{ user.first_name }} {{ user.last_name }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Genre</p><p class="mt-0.5 text-sm text-gray-800">{{ genderLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">E-mail compte</p><p class="mt-0.5 text-sm text-gray-800">{{ user.email }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">E-mail personnel</p><p class="mt-0.5 text-sm text-gray-800">{{ a.personal_email || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">WhatsApp</p><p class="mt-0.5 text-sm text-gray-800">{{ a.whatsapp_phone || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Pays</p><p class="mt-0.5 text-sm text-gray-800">{{ a.country_of_residence || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Ville</p><p class="mt-0.5 text-sm text-gray-800">{{ a.city_of_residence || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Adresse postale</p><p class="mt-0.5 text-sm text-gray-800">{{ a.postal_address || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Internet stable</p><p class="mt-0.5 text-sm text-gray-800">{{ internetLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Statut</p><p class="mt-0.5 text-sm text-gray-800">{{ currentStatusLabel }}</p></div>
                    <div v-if="a.university_name"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Établissement</p><p class="mt-0.5 text-sm text-gray-800">{{ a.university_name }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Heures / semaine</p><p class="mt-0.5 text-sm text-gray-800">{{ a.weekly_hours ? `${a.weekly_hours}h` : '—' }}</p></div>
                </div>

                <div class="mt-5 border-t border-gray-100 pt-4">
                    <p class="mb-2 text-[10px] font-medium uppercase tracking-wider text-gray-400">Documents</p>
                    <div class="flex flex-wrap gap-3">
                        <a v-if="a.cv_file" :href="`/storage/${a.cv_file}`" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-primary-700 hover:bg-gray-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            CV
                        </a>
                        <a v-if="a.university_doc" :href="`/storage/${a.university_doc}`" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-primary-700 hover:bg-gray-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            Attestation
                        </a>
                        <a v-if="a.linkedin_url" :href="a.linkedin_url" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-3 py-2 text-xs text-primary-700 hover:bg-gray-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                            LinkedIn
                        </a>
                        <span v-if="!a.cv_file && !a.university_doc && !a.linkedin_url" class="text-xs text-gray-400">Aucun document.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ TAB CANDIDATURE ═══ -->
        <div v-if="activeTab === 'candidature'" class="space-y-5">

            <!-- Raison du rejet si applicable -->
            <div v-if="a.status === 'rejected' && a.rejection_reason" class="rounded-xl border border-red-100 bg-red-50 p-5">
                <p class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-red-600">Raison du rejet</p>
                <p class="text-sm text-red-800 leading-relaxed">{{ a.rejection_reason }}</p>
            </div>

            <!-- Étape 1 — Profil personnel -->
            <div class="rounded-xl border border-gray-100 bg-white p-5">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 1 — Profil personnel</p>
                <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3">
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Genre</p><p class="mt-0.5 text-sm text-gray-800">{{ genderLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Pays de résidence</p><p class="mt-0.5 text-sm text-gray-800">{{ a.country_of_residence || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Ville</p><p class="mt-0.5 text-sm text-gray-800">{{ a.city_of_residence || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Adresse postale</p><p class="mt-0.5 text-sm text-gray-800">{{ a.postal_address || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">E-mail personnel</p><p class="mt-0.5 text-sm text-gray-800">{{ a.personal_email || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">WhatsApp</p><p class="mt-0.5 text-sm text-gray-800">{{ a.whatsapp_phone || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Accès internet stable</p><p class="mt-0.5 text-sm text-gray-800">{{ internetLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Statut actuel</p><p class="mt-0.5 text-sm text-gray-800">{{ currentStatusLabel }}</p></div>
                    <div v-if="a.university_name"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Établissement</p><p class="mt-0.5 text-sm text-gray-800">{{ a.university_name }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Heures / semaine</p><p class="mt-0.5 text-sm text-gray-800">{{ a.weekly_hours ? `${a.weekly_hours}h` : '—' }}</p></div>
                </div>
            </div>

            <!-- Étape 2 — Votre projet -->
            <div class="rounded-xl border border-gray-100 bg-white p-5 space-y-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 2 — Votre projet</p>
                <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Nom du projet</p><p class="mt-0.5 text-sm font-medium text-gray-900">{{ a.project_name || '—' }}</p></div>
                <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Problème ciblé</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ a.project_problem || '—' }}</p></div>
                <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Solution proposée</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ a.project_solution || '—' }}</p></div>
                <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-3">
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Secteur</p><p class="mt-0.5 text-sm text-gray-800">{{ a.project_sector || '—' }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Stade</p><p class="mt-0.5 text-sm text-gray-800">{{ stageLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Porté</p><p class="mt-0.5 text-sm text-gray-800">{{ teamLabel }}</p></div>
                </div>
                <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Solutions similaires existantes</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ a.project_competition || '—' }}</p></div>
                <div class="rounded-lg bg-gray-50 px-4 py-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-1">En une phrase</p>
                    <p class="text-sm text-gray-700 italic">« {{ a.project_pitch || '—' }} »</p>
                </div>
            </div>

            <!-- Étape 3 — Documents & liens -->
            <div class="rounded-xl border border-gray-100 bg-white p-5">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 3 — Documents & liens</p>
                <div class="flex flex-wrap gap-3">
                    <a v-if="a.cv_file" :href="`/storage/${a.cv_file}`" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        CV
                    </a>
                    <a v-if="a.university_doc" :href="`/storage/${a.university_doc}`" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Attestation universitaire
                    </a>
                    <a v-if="a.linkedin_url" :href="a.linkedin_url" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                        LinkedIn
                    </a>
                    <span v-if="!a.cv_file && !a.university_doc && !a.linkedin_url" class="text-sm text-gray-400">Aucun document uploadé.</span>
                </div>
            </div>

            <!-- Étape 4 — Compléments & consentements -->
            <div class="rounded-xl border border-gray-100 bg-white p-5">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 4 — Compléments & consentements</p>
                <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Programme précédent</p><p class="mt-0.5 text-sm text-gray-800">{{ previousProgramLabel }}</p></div>
                    <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Besoins en accompagnement</p><p class="mt-0.5 text-sm text-gray-800">{{ supportNeedsLabel }}</p></div>
                    <div class="sm:col-span-2"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Comment a-t-il/elle connu InAfrikaWeTrust</p><p class="mt-0.5 text-sm text-gray-800">{{ a.heard_about_us || '—' }}</p></div>
                </div>
                <div class="mt-4 flex items-center gap-4 border-t border-gray-100 pt-4">
                    <div class="flex items-center gap-1.5">
                        <svg :class="a.consent_data ? 'text-green-600' : 'text-gray-300'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs text-gray-600">Consentement données personnelles</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg :class="a.consent_image ? 'text-green-600' : 'text-gray-300'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs text-gray-600">Consentement image et voix</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ TAB PARCOURS ═══ -->
        <div v-if="activeTab === 'parcours'">
            <div v-if="a.status !== 'accepted'" class="mb-4 rounded-xl border border-amber-100 bg-amber-50 px-5 py-3">
                <p class="text-sm text-amber-700">Le parcours est verrouillé — la candidature doit être acceptée pour débloquer les étapes.</p>
            </div>

            <div class="space-y-3">
                <div
                    v-for="(step, i) in journeySteps"
                    :key="step.id"
                    class="rounded-xl border border-gray-100 bg-white overflow-hidden"
                >
                    <!-- En-tête étape -->
                    <div
                        class="flex items-center gap-4 px-5 py-4 cursor-pointer hover:bg-gray-50"
                        @click="toggleStepExpand(i + 1)"
                    >
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                            :class="stepResponseStatus(i + 1) === 'completed' ? 'bg-green-100 text-green-700' : stepResponseStatus(i + 1) === 'draft' ? 'bg-gold-100 text-gold-700' : i + 1 <= a.journey_current_step ? 'bg-primary-100 text-primary-700' : 'border border-gray-200 bg-gray-50 text-gray-400'"
                        >
                            <svg v-if="stepResponseStatus(i + 1) === 'completed'" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span v-else>{{ String(i + 1).padStart(2, '0') }}</span>
                        </span>

                        <div class="flex items-center gap-2.5 flex-1 min-w-0">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full" :class="i + 1 <= a.journey_current_step ? 'bg-primary-50 text-primary-700' : 'bg-gray-50 text-gray-300'">
                                <Icon :name="step.icon" class="h-4 w-4" />
                            </span>
                            <p class="text-sm font-medium" :class="i + 1 <= a.journey_current_step ? 'text-gray-900' : 'text-gray-400'">{{ step.label }}</p>
                        </div>

                        <!-- Badge statut -->
                        <span v-if="journeyResponses?.[i+1]?.validated_at" class="rounded-full bg-green-50 px-2.5 py-0.5 text-[10px] font-medium text-green-700">Validée ✓</span>
                        <span v-else-if="stepResponseStatus(i + 1) === 'completed'" class="rounded-full bg-blue-50 px-2.5 py-0.5 text-[10px] font-medium text-blue-700">Soumise</span>
                        <span v-else-if="stepResponseStatus(i + 1) === 'draft'" class="rounded-full bg-gold-50 px-2.5 py-0.5 text-[10px] font-medium text-gold-700">En cours</span>
                        <span v-else-if="i + 1 <= a.journey_current_step" class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-medium text-gray-500">Débloquée</span>

                        <!-- Bouton valider (si soumise et pas encore validée) -->
                        <button
                            v-if="stepResponseStatus(i + 1) === 'completed' && !journeyResponses?.[i+1]?.validated_at && a.status === 'accepted'"
                            type="button"
                            class="inline-flex items-center gap-1 rounded-md bg-green-600 px-3 py-1 text-xs font-medium text-white hover:bg-green-700"
                            @click.stop="validateStep(i + 1)"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Valider
                        </button>

                        <!-- Bouton retravailler (si soumise et pas encore validée) -->
                        <button
                            v-if="stepResponseStatus(i + 1) === 'completed' && !journeyResponses?.[i+1]?.validated_at && a.status === 'accepted'"
                            type="button"
                            class="inline-flex items-center gap-1 rounded-md bg-amber-500 px-3 py-1 text-xs font-medium text-white hover:bg-amber-600"
                            @click.stop="openReworkModal(i + 1)"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            À retravailler
                        </button>

                        <!-- Bouton débloquer -->
                        <button
                            v-if="a.status === 'accepted' && i + 1 > a.journey_current_step"
                            type="button"
                            class="inline-flex items-center gap-1 rounded-md border border-gray-200 bg-white px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-50"
                            @click.stop="unlockStep(i + 1)"
                        >
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 11V7a5 5 0 0110 0v4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Débloquer
                        </button>

                        <!-- Chevron -->
                        <svg v-if="hasStepResponse(i + 1)" class="h-4 w-4 text-gray-400 transition" :class="expandedStep === (i + 1) ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>

                    <!-- Réponses (dépliable) -->
                    <div v-if="expandedStep === (i + 1) && hasStepResponse(i + 1)" class="border-t border-gray-100 bg-gray-50/50 px-5 py-4">
                        <!-- Motif de retravail -->
                        <div v-if="journeyResponses?.[i+1]?.rework_reason" class="mb-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-amber-600 mb-1">Motif de retravail</p>
                            <p class="text-sm text-amber-800">{{ journeyResponses[i+1].rework_reason }}</p>
                        </div>
                        <div class="space-y-3">
                            <div v-for="(value, key) in getStepResponses(i + 1)" :key="key">
                                <template v-if="value !== null && value !== '' && !(Array.isArray(value) && value.length === 0) && !(typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0)">
                                    <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">{{ formatFieldLabel(key) }}</p>
                                    <!-- Tableau comparatif (concurrents etc.) -->
                                    <template v-if="Array.isArray(value) && value.length && typeof value[0] === 'object'">
                                        <div v-for="(item, idx) in value" :key="idx" class="mt-1 mb-2 rounded-lg border border-gray-200 bg-white p-3">
                                            <div class="grid grid-cols-1 gap-1 sm:grid-cols-2">
                                                <div v-for="(v, k) in item" :key="k">
                                                    <span class="text-[10px] text-gray-400">{{ formatFieldLabel(k) }}</span>
                                                    <p class="text-sm text-gray-700">{{ v || '—' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Objet (besoins, sources, etc.) -->
                                    <template v-else-if="typeof value === 'object' && !Array.isArray(value)">
                                        <div class="mt-1 grid grid-cols-1 gap-1 sm:grid-cols-2">
                                            <div v-for="(v, k) in value" :key="k" class="flex items-center gap-2">
                                                <span class="text-xs text-gray-500">{{ formatFieldLabel(k) }} :</span>
                                                <span class="text-sm text-gray-800">{{ typeof v === 'boolean' ? (v ? '✓' : '—') : (v || '—') }}</span>
                                            </div>
                                        </div>
                                    </template>
                                    <!-- Liste simple -->
                                    <template v-else-if="Array.isArray(value)">
                                        <p class="mt-0.5 text-sm text-gray-700">{{ value.join(', ') }}</p>
                                    </template>
                                    <!-- Valeur simple -->
                                    <template v-else>
                                        <p class="mt-0.5 text-sm text-gray-700">{{ value }}</p>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ TAB COMMENTAIRES ═══ -->
        <div v-if="activeTab === 'commentaires'" class="space-y-4">
            <!-- Formulaire ajout -->
            <div class="rounded-xl border border-gray-100 bg-white p-5">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Ajouter un commentaire</p>
                <form @submit.prevent="submitComment">
                    <textarea
                        v-model="commentForm.body"
                        rows="3"
                        class="w-full rounded-lg border-gray-200 text-sm"
                        placeholder="Votre commentaire sur ce candidat…"
                    ></textarea>
                    <div class="mt-2 flex justify-end">
                        <button
                            type="submit"
                            :disabled="commentForm.processing || !commentForm.body.trim()"
                            class="rounded-md bg-primary-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-primary-700 disabled:opacity-50"
                        >Publier</button>
                    </div>
                </form>
            </div>

            <!-- Liste des commentaires -->
            <div v-if="a.comments?.length" class="space-y-3">
                <div
                    v-for="c in a.comments"
                    :key="c.id"
                    class="rounded-xl border border-gray-100 bg-white px-5 py-4"
                >
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-sm font-medium text-gray-800">{{ c.author?.first_name }} {{ c.author?.last_name }}</p>
                        <p class="text-xs text-gray-400">{{ formatDate(c.created_at) }}</p>
                    </div>
                    <p class="text-sm text-gray-700 leading-relaxed">{{ c.body }}</p>
                </div>
            </div>
            <p v-else class="text-sm text-gray-400">Aucun commentaire pour le moment.</p>
        </div>

        <!-- MODALE RETRAVAILLER -->
        <Modal :show="reworkStep !== null" @close="reworkStep = null">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Demander de retravailler l'étape {{ reworkStep }}</h2>
                <p class="mt-1 text-sm text-gray-600">Le candidat sera invité à modifier et resoumettre cette étape. Expliquez ce qui doit être amélioré.</p>

                <textarea
                    v-model="reworkForm.reason"
                    rows="4"
                    class="mt-4 w-full rounded-lg border-gray-200 text-sm"
                    placeholder="Ce qui doit être revu ou complété…"
                    autofocus
                ></textarea>
                <p v-if="reworkForm.errors.reason" class="mt-1 text-xs text-red-600">{{ reworkForm.errors.reason }}</p>

                <input type="hidden" :value="reworkStep" />

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50" @click="reworkStep = null">Annuler</button>
                    <button
                        type="button"
                        :disabled="reworkForm.processing || !reworkForm.reason.trim()"
                        class="rounded-md bg-amber-500 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-600 disabled:opacity-50"
                        @click="submitRework"
                    >Demander la révision</button>
                </div>
            </div>
        </Modal>

        <!-- MODALE DE REJET -->
        <Modal :show="showRejectModal" @close="showRejectModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Rejeter cette candidature</h2>
                <p class="mt-1 text-sm text-gray-600">Veuillez expliquer la raison du rejet. Ce commentaire sera visible dans l'historique.</p>

                <textarea
                    v-model="rejectForm.rejection_reason"
                    rows="4"
                    class="mt-4 w-full rounded-lg border-gray-200 text-sm"
                    placeholder="Raison du rejet…"
                    autofocus
                ></textarea>
                <p v-if="rejectForm.errors.rejection_reason" class="mt-1 text-xs text-red-600">{{ rejectForm.errors.rejection_reason }}</p>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50"
                        @click="showRejectModal = false"
                    >Annuler</button>
                    <button
                        type="button"
                        :disabled="rejectForm.processing || !rejectForm.rejection_reason.trim()"
                        class="rounded-md bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 disabled:opacity-50"
                        @click="submitReject"
                    >Confirmer le rejet</button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>