<script>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Icon from '@/Components/Icon.vue';
import { Head, Link } from '@inertiajs/vue3';

const STATUS_CONFIG = {
    draft:     { label: 'Brouillon', color: 'bg-gray-100 text-gray-600' },
    submitted: { label: 'Soumise',   color: 'bg-blue-50 text-blue-700' },
    accepted:  { label: 'Acceptée',  color: 'bg-green-50 text-green-700' },
    rejected:  { label: 'Refusée',   color: 'bg-red-50 text-red-700' },
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
    components: { PublicLayout, Icon, Head, Link },
    props: {
        application: Object,
        steps: Array,
    },
    data() {
        return { activeTab: 'candidature' };
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
                        <span v-else class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-semibold text-white uppercase sm:h-14 sm:w-14 sm:text-xl">
                            {{ user.first_name?.[0] }}{{ user.last_name?.[0] }}
                        </span>
                        <div>
                            <h1 class="font-serif text-xl font-normal text-white sm:text-2xl">{{ user.first_name }} {{ user.last_name }}</h1>
                            <p class="mt-0.5 text-xs text-primary-200 sm:text-sm">{{ user.email }}</p>
                        </div>
                    </div>
                    <span
                        v-if="hasApplication"
                        class="mt-1 inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold"
                        :class="statusConf.color"
                    >{{ statusConf.label }}</span>
                </div>

                <!-- Pills résumé projet -->
                <div v-if="hasApplication && application.project_name" class="mt-4 flex flex-wrap gap-2">
                    <div class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Projet</p>
                        <p class="text-xs font-medium text-white">{{ application.project_name }}</p>
                    </div>
                    <div v-if="application.project_sector" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Secteur</p>
                        <p class="text-xs font-medium text-white">{{ application.project_sector }}</p>
                    </div>
                    <div v-if="application.project_stage" class="rounded-lg border border-white/15 bg-white/10 px-3 py-1.5">
                        <p class="text-[9px] uppercase tracking-wider text-white/40">Stade</p>
                        <p class="text-xs font-medium text-white">{{ stageLabel }}</p>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="mt-6 flex gap-0">
                    <button
                        type="button"
                        class="border-b-2 px-5 pb-3 text-sm font-medium transition"
                        :class="activeTab === 'candidature' ? 'border-gold-400 text-white' : 'border-transparent text-white/40 hover:text-white/70'"
                        @click="activeTab = 'candidature'"
                    >Ma candidature</button>
                    <button
                        type="button"
                        class="border-b-2 px-5 pb-3 text-sm font-medium transition"
                        :class="activeTab === 'parcours' ? 'border-gold-400 text-white' : 'border-transparent text-white/40 hover:text-white/70'"
                        @click="activeTab = 'parcours'"
                    >Mon parcours</button>
                </div>
            </div>
        </section>

        <!-- CONTENU -->
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

            <!-- ═══ PAS DE CANDIDATURE ═══ -->
            <div v-if="!hasApplication" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-primary-50 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-100 text-primary-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        </span>
                        <div>
                            <p class="font-medium text-gray-900">Commencez votre parcours</p>
                            <p class="text-sm text-gray-500">Déposez votre candidature pour accéder au programme.</p>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <Link :href="route('application.index')" class="inline-flex items-center gap-2 rounded-full bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-primary-700">
                        Déposer ma candidature →
                    </Link>
                </div>
            </div>

            <!-- ═══ TAB CANDIDATURE ═══ -->
            <template v-if="hasApplication && activeTab === 'candidature'">

                <!-- Brouillon -->
                <div v-if="application.status === 'draft'" class="mb-6 overflow-hidden rounded-2xl border border-amber-100 bg-amber-50 px-6 py-4">
                    <p class="text-sm text-amber-700">Votre candidature est en brouillon — certains champs peuvent être vides.</p>
                    <Link :href="route('application.show')" class="mt-2 inline-flex items-center gap-2 rounded-full bg-primary-600 px-5 py-2 text-sm font-semibold text-white hover:bg-primary-700">
                        Reprendre ma candidature →
                    </Link>
                </div>

                <!-- Messages statut -->
                <div v-if="application.status === 'submitted'" class="mb-6 rounded-2xl border border-blue-100 bg-blue-50 px-6 py-4">
                    <p class="text-sm text-blue-700">Votre candidature est en cours d'examen. Soumise le {{ submittedDate }}.</p>
                </div>
                <div v-if="application.status === 'accepted'" class="mb-6 rounded-2xl border border-green-100 bg-green-50 px-6 py-4">
                    <p class="text-sm text-green-700">Félicitations ! Votre candidature a été acceptée. Accédez à votre parcours via l'onglet ci-dessus.</p>
                </div>
                <div v-if="application.status === 'rejected'" class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-6 py-4">
                    <p class="text-sm text-red-700">Votre candidature n'a pas été retenue pour cette promotion.</p>
                </div>

                <!-- Étape 1 — Profil personnel -->
                <div class="mb-5">
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 1 — Profil personnel</p>
                    <div class="rounded-xl border border-gray-100 bg-white p-5">
                        <div class="grid grid-cols-2 gap-x-6 gap-y-4 sm:grid-cols-3">
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Genre</p><p class="mt-0.5 text-sm text-gray-800">{{ genderLabel }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Pays de résidence</p><p class="mt-0.5 text-sm text-gray-800">{{ application.country_of_residence || '—' }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Ville</p><p class="mt-0.5 text-sm text-gray-800">{{ application.city_of_residence || '—' }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Adresse postale</p><p class="mt-0.5 text-sm text-gray-800">{{ application.postal_address || '—' }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">E-mail personnel</p><p class="mt-0.5 text-sm text-gray-800">{{ application.personal_email || '—' }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">WhatsApp</p><p class="mt-0.5 text-sm text-gray-800">{{ application.whatsapp_phone || '—' }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Accès internet stable</p><p class="mt-0.5 text-sm text-gray-800">{{ internetLabel }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Statut actuel</p><p class="mt-0.5 text-sm text-gray-800">{{ currentStatusLabel }}</p></div>
                            <div v-if="application.university_name"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Établissement</p><p class="mt-0.5 text-sm text-gray-800">{{ application.university_name }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Heures / semaine</p><p class="mt-0.5 text-sm text-gray-800">{{ application.weekly_hours ? `${application.weekly_hours}h` : '—' }}</p></div>
                        </div>
                    </div>
                </div>

                <!-- Étape 2 — Votre projet -->
                <div class="mb-5">
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 2 — Votre projet</p>
                    <div class="rounded-xl border border-gray-100 bg-white p-5 space-y-4">
                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Nom du projet</p><p class="mt-0.5 text-sm font-medium text-gray-900">{{ application.project_name || '—' }}</p></div>

                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Problème ciblé</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ application.project_problem || '—' }}</p></div>

                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Solution proposée</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ application.project_solution || '—' }}</p></div>

                        <div class="grid grid-cols-2 gap-x-6 gap-y-4 sm:grid-cols-3">
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Secteur</p><p class="mt-0.5 text-sm text-gray-800">{{ application.project_sector || '—' }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Stade</p><p class="mt-0.5 text-sm text-gray-800">{{ stageLabel }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Porté</p><p class="mt-0.5 text-sm text-gray-800">{{ teamLabel }}</p></div>
                        </div>

                        <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Solutions similaires existantes</p><p class="mt-1 text-sm text-gray-700 leading-relaxed">{{ application.project_competition || '—' }}</p></div>

                        <div class="rounded-lg bg-gray-50 px-4 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-wider text-gray-400 mb-1">En une phrase</p>
                            <p class="text-sm text-gray-700 italic">« {{ application.project_pitch || '—' }} »</p>
                        </div>
                    </div>
                </div>

                <!-- Étape 3 — Documents -->
                <div class="mb-5">
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 3 — Documents & liens</p>
                    <div class="rounded-xl border border-gray-100 bg-white p-5">
                        <div class="flex flex-wrap gap-3">
                            <a
                                v-if="application.cv_file"
                                :href="`/storage/${application.cv_file}`"
                                target="_blank"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                CV
                            </a>
                            <a
                                v-if="application.university_doc"
                                :href="`/storage/${application.university_doc}`"
                                target="_blank"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                Attestation universitaire
                            </a>
                            <a
                                v-if="application.linkedin_url"
                                :href="application.linkedin_url"
                                target="_blank"
                                rel="noopener"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm text-primary-700 hover:bg-gray-100"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3"/></svg>
                                LinkedIn
                            </a>
                            <p v-if="!application.cv_file && !application.university_doc && !application.linkedin_url" class="text-sm text-gray-400">
                                Aucun document uploadé.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Étape 4 — Compléments -->
                <div class="mb-5">
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Étape 4 — Compléments</p>
                    <div class="rounded-xl border border-gray-100 bg-white p-5">
                        <div class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Programme précédent</p><p class="mt-0.5 text-sm text-gray-800">{{ previousProgramLabel }}</p></div>
                            <div><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Besoins en accompagnement</p><p class="mt-0.5 text-sm text-gray-800">{{ supportNeedsLabel }}</p></div>
                            <div class="sm:col-span-2"><p class="text-[10px] font-medium uppercase tracking-wider text-gray-400">Comment avez-vous connu InAfrikaWeTrust</p><p class="mt-0.5 text-sm text-gray-800">{{ application.heard_about_us || '—' }}</p></div>
                        </div>
                        <div class="mt-4 border-t border-gray-100 pt-4">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <svg v-if="application.consent_data" class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <svg v-else class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                    <span class="text-xs text-gray-600">Consentement données</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg v-if="application.consent_image" class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <svg v-else class="h-4 w-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                    <span class="text-xs text-gray-600">Consentement image</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- ═══ TAB PARCOURS ═══ -->
            <template v-if="hasApplication && activeTab === 'parcours'">

                <!-- Message si pas encore accepté -->
                <div v-if="!isAccepted" class="mb-6 rounded-2xl border border-amber-100 bg-amber-50 px-6 py-4">
                    <p class="text-sm text-amber-700">
                        <template v-if="application.status === 'draft'">Soumettez votre candidature pour débloquer le parcours.</template>
                        <template v-else-if="application.status === 'submitted'">Le parcours sera accessible dès que votre candidature sera acceptée.</template>
                        <template v-else>Le parcours n'est pas disponible pour le moment.</template>
                    </p>
                </div>

                <!-- Les 7 étapes -->
                <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="border-b border-gray-100 px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">Parcours entrepreneur — {{ steps.length }} étapes</p>
                    </div>

                    <div class="divide-y divide-gray-50">
                        <div
                            v-for="(step, i) in steps"
                            :key="step.id"
                            class="flex items-center gap-4 px-6 py-4"
                            :class="!isAccepted ? 'opacity-40' : ''"
                        >
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold"
                                :class="isAccepted ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-gray-50 text-gray-400'"
                            >{{ String(i + 1).padStart(2, '0') }}</span>

                            <div class="flex items-center gap-2.5 flex-1 min-w-0">
                                <span
                                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full"
                                    :class="isAccepted ? 'bg-primary-50 text-primary-700' : 'bg-gray-50 text-gray-300'"
                                >
                                    <Icon :name="step.icon" class="h-4 w-4" />
                                </span>
                                <p class="text-sm font-medium" :class="isAccepted ? 'text-gray-900' : 'text-gray-400'">{{ step.label }}</p>
                            </div>

                            <span v-if="!isAccepted" class="text-gray-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            </span>
                            <span v-else class="rounded-full bg-gray-100 px-2.5 py-0.5 text-[10px] font-medium text-gray-500">À faire</span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </PublicLayout>
</template>