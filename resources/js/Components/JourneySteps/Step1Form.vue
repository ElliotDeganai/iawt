<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: {
        d() { return this.modelValue; },
    },
    methods: {
        update(key, val) { this.$emit('update:modelValue', { ...this.d, [key]: val }); },
        toggleOrigin(val) {
            const arr = this.d.origins || [];
            const idx = arr.indexOf(val);
            const updated = idx >= 0 ? arr.filter((_, i) => i !== idx) : [...arr, val];
            this.update('origins', updated);
        },
    },
};
</script>
<template>
    <div class="space-y-5">
        <!-- Origine de l'idée -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Origine de l'idée</p>
            <div class="flex flex-wrap gap-2">
                <button v-for="o in ['Expérience personnelle', 'Observation d\'un besoin', 'Innovation', 'Passion']" :key="o" type="button"
                    class="rounded-full border px-3 py-1.5 text-xs transition"
                    :class="(d.origins || []).includes(o) ? 'border-primary-600 bg-primary-50 text-primary-700 font-medium' : 'border-gray-200 text-gray-500 hover:border-primary-300'"
                    @click="toggleOrigin(o)"
                >{{ o }}</button>
            </div>
            <div class="mt-3">
                <label class="block text-xs text-gray-500 mb-1">Autre</label>
                <input :value="d.origin_other || ''" @input="update('origin_other', $event.target.value)" class="w-full rounded-md border-gray-200 text-sm" placeholder="Précisez…" />
            </div>
        </div>

        <!-- Présentation -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Présentation de l'idée</p>
            <p class="mb-3 text-xs text-gray-400">2-3 lignes maximum par champ.</p>
            <div class="space-y-3">
                <div v-for="q in [{k:'who',l:'Qui',h:'Qui portera ce projet ? Qui sont les bénéficiaires ?'},{k:'what',l:'Quoi',h:'En quoi consiste concrètement l\'idée ?'},{k:'why',l:'Pourquoi',h:'Quel problème résout-elle ?'},{k:'how',l:'Comment',h:'Ressources, moyens et démarche'},{k:'where',l:'Où',h:'Zone géographique, lieu d\'implantation'}]" :key="q.k"
                    class="flex gap-3 items-start">
                    <span class="flex h-7 w-14 shrink-0 items-center justify-center rounded-md bg-primary-50 text-[10px] font-semibold text-primary-700">{{ q.l }}</span>
                    <textarea :value="d[q.k] || ''" @input="update(q.k, $event.target.value)" rows="2" class="flex-1 rounded-md border-gray-200 text-sm" :placeholder="q.h" maxlength="300"></textarea>
                </div>
            </div>
        </div>

        <!-- Adéquation -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-gold-600">Adéquation homme/projet</p>
            <p class="mb-5 text-xs text-gray-400">Évaluez honnêtement où vous vous situez aujourd'hui.</p>
            <div class="space-y-5">
                <div v-for="s in [{k:'alignment_energy',l:'Alignement énergétique',h:'Ce projet a-t-il du sens pour vous ? Correspond-il à vos valeurs et vous donne-t-il l\'énergie de le porter dans la durée ?'},{k:'alignment_skills',l:'Alignement des compétences',h:'Avez-vous ou pouvez-vous acquérir les compétences nécessaires à ce projet ?'},{k:'alignment_lifestyle',l:'Alignement mode de vie',h:'Ce projet est-il compatible avec votre vie actuelle et vos contraintes personnelles ?'}]" :key="s.k"
                    class="rounded-lg border border-gray-100 p-4">
                    <label class="text-sm font-medium text-gray-800">{{ s.l }}</label>
                    <p class="mt-0.5 text-xs text-gray-400 leading-relaxed">{{ s.h }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        <span class="text-[10px] text-gray-400 w-12 shrink-0">Faible</span>
                        <div class="flex gap-1.5">
                            <button v-for="n in 5" :key="n" type="button"
                                class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-medium transition-all"
                                :class="(d[s.k] || 0) >= n
                                    ? 'bg-primary-600 text-white shadow-sm'
                                    : 'border border-gray-200 text-gray-400 hover:border-primary-300 hover:text-primary-600'"
                                @click="update(s.k, n)"
                            >{{ n }}</button>
                        </div>
                        <span class="text-[10px] text-gray-400 w-12 shrink-0 text-right">Fort</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>