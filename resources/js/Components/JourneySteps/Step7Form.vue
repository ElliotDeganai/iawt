<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: {
        update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); },
        toggleChecklist(val) {
            const arr = this.d.checklist || [];
            const idx = arr.indexOf(val);
            this.update('checklist', idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val]);
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Pré-requis avant dépôt</p>
            <div class="space-y-1">
                <label v-for="item in ['Projet validé après le Business Lab','Forme juridique choisie','Nom de l\'entreprise choisi','Associés et répartition des parts définis','Siège social identifié','Capital et apports déterminés','Dirigeant(s) identifié(s)','Activités définies','Autorisations identifiées','Documents personnels réunis']" :key="item"
                    class="flex items-center gap-2.5 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition">
                    <input type="checkbox" :checked="(d.checklist||[]).includes(item)" @change="toggleChecklist(item)" class="rounded text-primary-600" />
                    <span class="text-sm text-gray-700">{{ item }}</span>
                </label>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Pays de création</p>
            <div class="flex flex-wrap gap-2">
                <button v-for="c in [{code:'🇨🇫',name:'RCA'},{code:'🇨🇲',name:'Cameroun'},{code:'🇨🇮',name:'Côte d\'Ivoire'},{code:'🇸🇳',name:'Sénégal'}]" :key="c.name" type="button"
                    class="flex items-center gap-2 rounded-lg border px-4 py-2.5 text-sm transition"
                    :class="d.creation_country===c.name?'border-primary-600 bg-primary-50 text-primary-700 font-medium':'border-gray-200 text-gray-600 hover:border-primary-300'"
                    @click="update('creation_country',c.name)">
                    <span class="text-lg">{{ c.code }}</span>{{ c.name }}
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Type de parcours</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <button type="button"
                    class="rounded-xl border-2 p-5 text-center transition"
                    :class="d.track==='individual'?'border-primary-600 bg-primary-50':'border-gray-200 hover:border-primary-300'"
                    @click="update('track','individual')">
                    <p class="text-2xl mb-2">👤</p>
                    <p class="text-sm font-medium" :class="d.track==='individual'?'text-primary-700':'text-gray-700'">Entrepreneur individuel</p>
                </button>
                <button type="button"
                    class="rounded-xl border-2 p-5 text-center transition"
                    :class="d.track==='company'?'border-primary-600 bg-primary-50':'border-gray-200 hover:border-primary-300'"
                    @click="update('track','company')">
                    <p class="text-2xl mb-2">🏢</p>
                    <p class="text-sm font-medium" :class="d.track==='company'?'text-primary-700':'text-gray-700'">Société</p>
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Notes personnelles</p>
            <textarea :value="d.notes||''" @input="update('notes',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-sm" placeholder="Vos observations sur les démarches effectuées…"></textarea>
        </div>
    </div>
</template>
