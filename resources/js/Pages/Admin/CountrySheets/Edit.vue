<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { AFRICAN_COUNTRIES } from '@/Data/africanCountries';
import FlagPicker from '@/Components/FlagPicker.vue';

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Checkbox, FlagPicker, PrimaryButton, SecondaryButton, Head, Link },
    props: { sheet: Object },
    data() {
        const s = this.sheet || {};
        return {
            africanCountries: AFRICAN_COUNTRIES,
            africanFlags: { dz: 'Algérie', ao: 'Angola', bj: 'Bénin', bw: 'Botswana', bf: 'Burkina Faso', bi: 'Burundi', cm: 'Cameroun', cv: 'Cap-Vert', cf: 'Centrafrique', km: 'Comores', cg: 'Congo', cd: 'Congo (RDC)', ci: "Côte d'Ivoire", dj: 'Djibouti', eg: 'Égypte', gq: 'Guinée équatoriale', er: 'Érythrée', sz: 'Eswatini', et: 'Éthiopie', ga: 'Gabon', gm: 'Gambie', gh: 'Ghana', gn: 'Guinée', gw: 'Guinée-Bissau', ke: 'Kenya', ls: 'Lesotho', lr: 'Libéria', ly: 'Libye', mg: 'Madagascar', mw: 'Malawi', ml: 'Mali', mr: 'Mauritanie', mu: 'Maurice', ma: 'Maroc', mz: 'Mozambique', na: 'Namibie', ne: 'Niger', ng: 'Nigeria', rw: 'Rwanda', st: 'Sao Tomé-et-Principe', sn: 'Sénégal', sc: 'Seychelles', sl: 'Sierra Leone', so: 'Somalie', za: 'Afrique du Sud', ss: 'Soudan du Sud', sd: 'Soudan', tz: 'Tanzanie', td: 'Tchad', tg: 'Togo', tn: 'Tunisie', ug: 'Ouganda', zm: 'Zambie', zw: 'Zimbabwe' },
            isEdit: !!this.sheet,
            form: useForm({
                country_name:     s.country_name ?? '',
                flag_code:        s.flag_code ?? null,
                title:            s.title ?? '',
                intro:            s.intro ?? '',
                warning:          s.warning ?? 'Les informations de cette fiche peuvent évoluer : elle est un point de départ, pas une source immuable.',
                actors:           s.actors ?? [],
                organism_name:    s.organism_name ?? '',
                organism_full:    s.organism_full ?? '',
                organism_desc:    s.organism_desc ?? '',
                organism_address: s.organism_address ?? '',
                organism_hours:   s.organism_hours ?? '',
                organism_website: s.organism_website ?? '',
                documents:        s.documents ?? [],
                individual_steps: s.individual_steps ?? [],
                company_steps:    s.company_steps ?? [],
                fees:             s.fees ?? '',
                sector_auth:      s.sector_auth ?? '',
                is_published:     s.is_published ?? false,
            }),
        };
    },
    computed: {
        nameToCode() {
            const map = {};
            for (const [code, name] of Object.entries(this.africanFlags)) {
                map[name] = code;
            }
            return map;
        },
        codeToName() {
            return this.africanFlags;
        },
    },
    watch: {
        'form.country_name'(name) {
            if (name && this.nameToCode[name]) {
                this.form.flag_code = this.nameToCode[name];
            }
        },
        'form.flag_code'(code) {
            if (code && this.codeToName[code] && !this.form.country_name) {
                this.form.country_name = this.codeToName[code];
            }
        },
    },
    methods: {
        addActor() { this.form.actors.push({ name: '', role: '' }); },
        removeActor(i) { this.form.actors.splice(i, 1); },
        addDoc() { this.form.documents.push(''); },
        removeDoc(i) { this.form.documents.splice(i, 1); },
        addIndStep() { this.form.individual_steps.push(''); },
        removeIndStep(i) { this.form.individual_steps.splice(i, 1); },
        addCoStep() { this.form.company_steps.push(''); },
        removeCoStep(i) { this.form.company_steps.splice(i, 1); },
        submit() {
            if (this.isEdit) {
                this.form.transform(d => ({ ...d, _method: 'put' })).post(route('admin.country-sheets.update', this.sheet.id));
            } else {
                this.form.post(route('admin.country-sheets.store'));
            }
        },
    },
};
</script>
<template>
    <Head :title="isEdit ? 'Modifier — ' + form.country_name : 'Nouvelle fiche pays'" />
    <AdminLayout>
        <template #header><h1 class="text-lg font-semibold text-gray-800">{{ isEdit ? 'Modifier « ' + sheet.country_name + ' »' : 'Nouvelle fiche pays' }}</h1></template>
        <form class="max-w-3xl space-y-6" @submit.prevent="submit">

            <!-- Identité -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <h2 class="font-medium text-gray-800">Identité</h2>
                <div>
                    <InputLabel value="Nom du pays" />
                    <select v-model="form.country_name" class="mt-1 w-full rounded-md border-gray-300 text-sm" required>
                        <option value="">Sélectionnez un pays</option>
                        <option v-for="c in africanCountries" :key="c.name" :value="c.name">{{ c.name }}</option>
                    </select>
                    <InputError class="mt-1" :message="form.errors.country_name" />
                </div>
                <div>
                    <InputLabel value="Drapeau du pays" />
                    <div v-if="form.flag_code" class="mb-2 inline-flex items-center gap-2 rounded-full border-2 border-green-500 bg-green-50 px-4 py-2">
                        <svg class="h-4 w-4 text-green-600" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span :class="`fi fi-${form.flag_code}`" class="h-5 w-7 rounded-sm"></span>
                        <span class="text-sm font-medium text-green-800">{{ codeToName[form.flag_code] || form.flag_code }}</span>
                        <button type="button" class="ml-1 text-green-400 hover:text-red-500" @click="form.flag_code = null"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <FlagPicker v-model="form.flag_code" :flags="africanFlags" class="mt-1" />
                    <InputError class="mt-1" :message="form.errors.flag_code" />
                </div>
                <div><InputLabel value="Titre de la fiche" /><TextInput v-model="form.title" class="mt-1 w-full" required /><InputError class="mt-1" :message="form.errors.title" /></div>
                <div><InputLabel value="Introduction" /><textarea v-model="form.intro" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
                <div><InputLabel value="Avertissement" /><textarea v-model="form.warning" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
                
            </div>

            <!-- Carte des acteurs -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <div class="flex items-center justify-between"><h2 class="font-medium text-gray-800">Carte des acteurs</h2><SecondaryButton type="button" @click="addActor">Ajouter</SecondaryButton></div>
                <div v-for="(a, i) in form.actors" :key="i" class="flex gap-2 rounded-md border border-gray-100 p-2">
                    <TextInput v-model="a.name" placeholder="Organisme" class="w-1/3" />
                    <TextInput v-model="a.role" placeholder="Rôle" class="flex-1" />
                    <button type="button" class="text-red-600 text-xs px-2" @click="removeActor(i)"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <p v-if="!form.actors.length" class="text-sm text-gray-400">Aucun acteur.</p>
            </div>

            <!-- Organisme compétent -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <h2 class="font-medium text-gray-800">Organisme compétent</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div><InputLabel value="Nom court" /><TextInput v-model="form.organism_name" class="mt-1 w-full" placeholder="GUFE" /></div>
                    <div><InputLabel value="Nom complet" /><TextInput v-model="form.organism_full" class="mt-1 w-full" /></div>
                </div>
                <div><InputLabel value="Description" /><textarea v-model="form.organism_desc" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div><InputLabel value="Adresse" /><TextInput v-model="form.organism_address" class="mt-1 w-full" /></div>
                    <div><InputLabel value="Horaires" /><TextInput v-model="form.organism_hours" class="mt-1 w-full" /></div>
                    <div><InputLabel value="Site web" /><TextInput v-model="form.organism_website" class="mt-1 w-full" /></div>
                </div>
            </div>

            <!-- Documents -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <div class="flex items-center justify-between"><h2 class="font-medium text-gray-800">Documents à préparer</h2><SecondaryButton type="button" @click="addDoc">Ajouter</SecondaryButton></div>
                <div v-for="(d, i) in form.documents" :key="i" class="flex gap-2">
                    <TextInput :model-value="d" @update:model-value="form.documents[i] = $event" class="flex-1" placeholder="Document requis" />
                    <button type="button" class="text-red-600 text-xs px-2" @click="removeDoc(i)"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <p v-if="!form.documents.length" class="text-sm text-gray-400">Aucun document.</p>
            </div>

            <!-- Parcours individuel -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <div class="flex items-center justify-between"><h2 class="font-medium text-gray-800">Parcours A — Individuel</h2><SecondaryButton type="button" @click="addIndStep">Ajouter</SecondaryButton></div>
                <div v-for="(s, i) in form.individual_steps" :key="i" class="flex gap-2 items-center">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary-100 text-[9px] font-bold text-primary-700">{{ i + 1 }}</span>
                    <TextInput :model-value="s" @update:model-value="form.individual_steps[i] = $event" class="flex-1" />
                    <button type="button" class="text-red-600 text-xs px-2" @click="removeIndStep(i)"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>

            <!-- Parcours société -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <div class="flex items-center justify-between"><h2 class="font-medium text-gray-800">Parcours B — Société</h2><SecondaryButton type="button" @click="addCoStep">Ajouter</SecondaryButton></div>
                <div v-for="(s, i) in form.company_steps" :key="i" class="flex gap-2 items-center">
                    <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary-100 text-[9px] font-bold text-primary-700">{{ i + 1 }}</span>
                    <TextInput :model-value="s" @update:model-value="form.company_steps[i] = $event" class="flex-1" />
                    <button type="button" class="text-red-600 text-xs px-2" @click="removeCoStep(i)"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
            </div>

            <!-- Frais & Autorisations -->
            <div class="rounded-lg bg-white p-6 shadow space-y-4">
                <h2 class="font-medium text-gray-800">Frais & Autorisations</h2>
                <div><InputLabel value="Frais de création" /><textarea v-model="form.fees" rows="2" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
                <div><InputLabel value="Autorisations sectorielles" /><textarea v-model="form.sector_auth" rows="3" class="mt-1 w-full rounded-md border-gray-300 text-sm" /></div>
            </div>

            <!-- Statut de publication -->
            <div class="flex items-center gap-3 rounded-xl border-2 px-4 py-3" :class="form.is_published ? 'border-green-400 bg-green-50' : 'border-amber-300 bg-amber-50'">
                <div class="flex h-8 w-8 items-center justify-center rounded-full" :class="form.is_published ? 'bg-green-500' : 'bg-amber-400'">
                    <svg v-if="form.is_published" class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <svg v-else class="h-4 w-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold" :class="form.is_published ? 'text-green-800' : 'text-amber-800'">{{ form.is_published ? 'Publiée' : 'Brouillon' }}</p>
                    <p class="text-[10px]" :class="form.is_published ? 'text-green-600' : 'text-amber-600'">{{ form.is_published ? 'Visible dans le parcours des candidats' : 'Non visible — en cours de rédaction' }}</p>
                </div>
                <button type="button" class="rounded-full px-3 py-1.5 text-xs font-semibold transition" :class="form.is_published ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200'" @click="form.is_published = !form.is_published">
                    {{ form.is_published ? 'Repasser en brouillon' : 'Publier' }}
                </button>
            </div>

            <div class="flex items-center gap-3">
                <PrimaryButton :disabled="form.processing">{{ isEdit ? 'Enregistrer' : 'Créer' }}</PrimaryButton>
                <Link :href="route('admin.country-sheets.index')" class="text-sm text-gray-600 hover:underline">Annuler</Link>
            </div>
        </form>
    </AdminLayout>
</template>