<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import CountryGallery from '@/Components/CountryGallery.vue';
import Checkbox from '@/Components/Checkbox.vue';
import FlagPicker from '@/Components/FlagPicker.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const AFRICAN_FLAGS = {
    dz: 'Algérie', ao: 'Angola', bj: 'Bénin', bw: 'Botswana',
    bf: 'Burkina Faso', bi: 'Burundi', cm: 'Cameroun', cv: 'Cap-Vert',
    cf: 'Centrafrique', km: 'Comores', cg: 'Congo', cd: 'Congo (RDC)',
    ci: "Côte d'Ivoire", dj: 'Djibouti', eg: 'Égypte', gq: 'Guinée équatoriale',
    er: 'Érythrée', sz: 'Eswatini', et: 'Éthiopie', ga: 'Gabon',
    gm: 'Gambie', gh: 'Ghana', gn: 'Guinée', gw: 'Guinée-Bissau',
    ke: 'Kenya', ls: 'Lesotho', lr: 'Libéria', ly: 'Libye',
    mg: 'Madagascar', mw: 'Malawi', ml: 'Mali', mr: 'Mauritanie',
    mu: 'Maurice', ma: 'Maroc', mz: 'Mozambique', na: 'Namibie',
    ne: 'Niger', ng: 'Nigeria', rw: 'Rwanda', st: 'Sao Tomé-et-Principe',
    sn: 'Sénégal', sc: 'Seychelles', sl: 'Sierra Leone', so: 'Somalie',
    za: 'Afrique du Sud', ss: 'Soudan du Sud', sd: 'Soudan', tz: 'Tanzanie',
    tg: 'Togo', tn: 'Tunisie', ug: 'Ouganda', zm: 'Zambie', zw: 'Zimbabwe',
};

export default {
    components: { AdminLayout, InputLabel, InputError, TextInput, Checkbox, FlagPicker, CountryGallery, PrimaryButton, SecondaryButton, Head, Link },
    props: {
        country: Object,
        placeCategories: Object,
    },
    data() {
        const existingPlaces = {};
        (this.country.country_places || []).forEach((p) => {
            if (!existingPlaces[p.place_category_id]) existingPlaces[p.place_category_id] = [];
            existingPlaces[p.place_category_id].push({
                name: p.name, description: p.description || '', link: p.link || '',
            });
        });
        return {
            africanFlags: AFRICAN_FLAGS,
            coverPreview: this.country.cover_image ? `/storage/${this.country.cover_image}` : null,
            mapPreview: this.country.map_image ? `/storage/${this.country.map_image}` : null,
            flagPreview: this.country.flag_image ? `/storage/${this.country.flag_image}` : null,
            form: useForm({
                name: this.country.name,
                slug: this.country.slug,
                flag_emoji: this.country.flag_emoji,
                flag_code: this.country.flag_code,
                flag_image: null,
                tags: this.country.tags,
                summary: this.country.summary,
                content: this.country.content,
                cover_image: null,
                map_image: null,
                remove_map_image: false,
                featured_month: this.country.featured_month,
                is_published: this.country.is_published,
                population: this.country.population,
                currency: this.country.currency,
                timezone: this.country.timezone,
                official_language: this.country.official_language,
                sunrise_time: this.country.sunrise_time,
                sunset_time: this.country.sunset_time,
                best_transport: this.country.best_transport,
                highlights: this.country.highlights || [],
                tourism_agencies: this.country.tourism_agencies || [],
                country_places: Object.fromEntries(
                    Object.keys(this.placeCategories ?? {}).map((k) => [k, existingPlaces[k] || []])
                ),
            }),
        };
    },
    methods: {
        onCoverChange(event) {
            const file = event.target.files[0];
            this.form.cover_image = file;
            if (file) this.coverPreview = URL.createObjectURL(file);
        },
        onMapChange(event) {
            const file = event.target.files[0];
            this.form.map_image = file;
            if (file) {
                this.mapPreview = URL.createObjectURL(file);
                this.form.remove_map_image = false;
            }
        },
        removeMap() {
            this.form.remove_map_image = true;
            this.form.map_image = null;
            this.mapPreview = null;
        },
        onFlagChange(event) {
            const file = event.target.files[0];
            this.form.flag_image = file;
            if (file) this.flagPreview = URL.createObjectURL(file);
        },
        addHighlight() {
            if (this.form.highlights.length < 12) {
                this.form.highlights.push({ name: '', description: '' });
            }
        },
        removeHighlight(i) {
            this.form.highlights.splice(i, 1);
        },
        addAgency() {
            this.form.tourism_agencies.push({ name: '', website: '', email: '', phone: '' });
        },
        removeAgency(i) {
            this.form.tourism_agencies.splice(i, 1);
        },
        addPlace(category) {
            this.form.country_places[category].push({ name: '', description: '', link: '' });
        },
        removePlace(category, i) {
            this.form.country_places[category].splice(i, 1);
        },
        submit() {
            this.form
                .transform((data) => ({ ...data, _method: 'put' }))
                .post(route('admin.countries.update', this.country.slug));
        },
    },
};
</script>

<template>
    <Head title="Administration — Modifier le pays à l'honneur" />

    <AdminLayout>
        <template #header>
            <h1 class="text-lg font-semibold text-gray-800">Modifier « {{ country.name }} »</h1>
        </template>

        <form class="max-w-3xl space-y-6" enctype="multipart/form-data" @submit.prevent="submit">
            <!-- Informations générales -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <h2 class="font-medium text-gray-800">Informations générales</h2>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <InputLabel value="Nom du pays" />
                        <TextInput v-model="form.name" class="mt-1" required autofocus />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>
                </div>

                <div>
                    <InputLabel value="Drapeau" />
                    <FlagPicker v-model="form.flag_code" :flags="africanFlags" class="mt-1" />
                    <InputError class="mt-2" :message="form.errors.flag_code" />
                </div>

                <div>
                    <InputLabel value="Ou importer un drapeau personnalisé (si le pays n'est pas dans la liste)" />
                    <div class="mt-2 flex items-center gap-4">
                        <img v-if="flagPreview" :src="flagPreview" class="h-10 w-14 rounded border border-gray-200 object-cover" />
                        <input type="file" accept="image/*,.svg" class="text-sm" @change="onFlagChange" />
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Utilisé en priorité sur le drapeau sélectionné ci-dessus si renseigné.</p>
                    <InputError class="mt-2" :message="form.errors.flag_image" />
                </div>

                <div>
                    <InputLabel value="Identifiant (slug)" />
                    <TextInput v-model="form.slug" class="mt-1" required />
                    <InputError class="mt-2" :message="form.errors.slug" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel value="Mois mis à l'honneur" />
                        <input type="month" :value="form.featured_month.slice(0, 7)" class="mt-1 w-full rounded-md border-gray-300 text-sm"
                            @input="form.featured_month = $event.target.value + '-01'" required />
                        <InputError class="mt-2" :message="form.errors.featured_month" />
                    </div>
                    <div>
                        <InputLabel value="Tags (secteurs)" />
                        <TextInput v-model="form.tags" class="mt-1" />
                    </div>
                </div>

                <div>
                    <InputLabel value="Résumé (page d'accueil)" />
                    <textarea v-model="form.summary" rows="2" maxlength="500" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    <InputError class="mt-2" :message="form.errors.summary" />
                </div>

                <div>
                    <InputLabel value="Contenu introductif (page dédiée)" />
                    <textarea v-model="form.content" rows="6" class="mt-1 w-full rounded-md border-gray-300 text-sm" />
                    <InputError class="mt-2" :message="form.errors.content" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <!-- Carte du pays -->
                        <InputLabel value="Carte du pays" />
                        <div v-if="mapPreview && !form.remove_map_image" class="relative mt-2">
                            <img :src="mapPreview" class="h-28 w-full rounded-md object-contain bg-gray-50" />
                            <button
                                type="button"
                                class="absolute right-2 top-2 rounded-md bg-red-600 px-2 py-0.5 text-xs font-semibold text-white hover:bg-red-700"
                                @click="removeMap"
                            >
                                Supprimer
                            </button>
                        </div>
                        <img v-else-if="form.flag_code" :src="`/images/country-maps/${form.flag_code}.svg`" class="mt-2 h-28 w-full rounded-md object-contain bg-gray-50 p-4 text-primary-700" />
                        <p v-if="form.remove_map_image" class="mt-1 text-xs text-amber-600">La carte personnalisée sera supprimée — la carte auto sera utilisée.</p>
                        <input type="file" accept="image/*" class="mt-2 text-sm" @change="onMapChange" />
                        <p class="mt-1 text-xs text-gray-500">Laissez vide pour utiliser la carte générée automatiquement.</p>
                        <InputError class="mt-2" :message="form.errors.map_image" />
                    </div>
                </div>

                <label class="flex items-center gap-2">
                    <Checkbox v-model:checked="form.is_published" />
                    <span class="text-sm text-gray-700">Publié (visible sur le site)</span>
                </label>
            </div>

            <!-- Bon à savoir -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <h2 class="font-medium text-gray-800">Bon à savoir</h2>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <InputLabel value="Nombre d'habitants" />
                        <TextInput v-model="form.population" class="mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Monnaie" />
                        <TextInput v-model="form.currency" class="mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Fuseau horaire" />
                        <TextInput v-model="form.timezone" class="mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Langue officielle" />
                        <TextInput v-model="form.official_language" class="mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Heure de lever du soleil" />
                        <TextInput v-model="form.sunrise_time" class="mt-1" />
                    </div>
                    <div>
                        <InputLabel value="Heure de coucher du soleil" />
                        <TextInput v-model="form.sunset_time" class="mt-1" />
                    </div>
                    <div class="sm:col-span-2">
                        <InputLabel value="Meilleur moyen de transport" />
                        <TextInput v-model="form.best_transport" class="mt-1" />
                    </div>
                </div>
            </div>

            <!-- Sites incontournables -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-medium text-gray-800">Les 12 sites incontournables</h2>
                    <SecondaryButton type="button" :disabled="form.highlights.length >= 12" @click="addHighlight">Ajouter un site</SecondaryButton>
                </div>

                <div v-for="(h, i) in form.highlights" :key="i" class="flex gap-3 rounded-md border border-gray-100 p-3">
                    <div class="flex-1 space-y-2">
                        <TextInput v-model="h.name" placeholder="Nom du site" />
                        <textarea v-model="h.description" rows="2" placeholder="Description" class="w-full rounded-md border-gray-300 text-sm" />
                    </div>
                    <button type="button" class="text-red-600 text-sm" @click="removeHighlight(i)">Retirer</button>
                </div>
                <p v-if="!form.highlights.length" class="text-sm text-gray-400">Aucun site ajouté.</p>
            </div>

            <!-- Sociétés de tourisme locales -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-medium text-gray-800">Sociétés de tourisme locales</h2>
                    <SecondaryButton type="button" @click="addAgency">Ajouter une société</SecondaryButton>
                </div>

                <div v-for="(a, i) in form.tourism_agencies" :key="i" class="grid grid-cols-1 gap-2 rounded-md border border-gray-100 p-3 sm:grid-cols-5">
                    <TextInput v-model="a.name" placeholder="Nom" class="sm:col-span-2" />
                    <TextInput v-model="a.website" placeholder="Site web" />
                    <TextInput v-model="a.email" placeholder="E-mail" />
                    <div class="flex gap-2">
                        <TextInput v-model="a.phone" placeholder="Téléphone" />
                        <button type="button" class="text-red-600 text-sm" @click="removeAgency(i)">✕</button>
                    </div>
                </div>
                <p v-if="!form.tourism_agencies.length" class="text-sm text-gray-400">Aucune société ajoutée.</p>
            </div>

            <!-- Où ... (loger, shopping, manger, s'enjailler, se dépenser, apprendre) -->
            <div v-for="(cat, key) in placeCategories" :key="key" class="bg-white rounded-lg shadow p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-medium text-gray-800">{{ cat.name }}</h2>
                    <SecondaryButton type="button" @click="addPlace(key)">Ajouter un lieu</SecondaryButton>
                </div>

                <div v-for="(p, i) in form.country_places[key]" :key="i" class="flex gap-3 rounded-md border border-gray-100 p-3">
                    <div class="flex-1 space-y-2">
                        <TextInput v-model="p.name" placeholder="Nom du lieu" />
                        <textarea v-model="p.description" rows="2" placeholder="Description" class="w-full rounded-md border-gray-300 text-sm" />
                        <TextInput v-model="p.link" placeholder="Lien ou adresse (optionnel)" />
                    </div>
                    <button type="button" class="text-red-600 text-sm" @click="removePlace(key, i)">Retirer</button>
                </div>
                <p v-if="!form.country_places[key]?.length" class="text-sm text-gray-400">Aucun lieu ajouté.</p>
            </div>

            <!-- Galerie photos / vidéos -->
            <div class="bg-white rounded-lg shadow p-6 space-y-4">
                <div>
                    <h2 class="font-medium text-gray-800">Galerie photos &amp; vidéos</h2>
                    <p class="mt-1 text-xs text-gray-500">
                        Uploadez les photos et vidéos du pays. Cliquez sur « Choisir comme cover » pour définir la photo de couverture affichée sur le site.
                    </p>
                </div>
                <CountryGallery :country="country" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <PrimaryButton :disabled="form.processing">Enregistrer</PrimaryButton>
                <Link :href="route('admin.countries.index')" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                    Annuler
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
