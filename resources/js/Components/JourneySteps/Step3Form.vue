<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: { update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); } },
};
</script>
<template>
    <div class="space-y-5">
        <p class="text-xs text-gray-400 italic">Remplissez chaque case avec vos propres mots — mots-clés ou courtes notes suffisent.</p>

        <!-- Canvas BMC -->
        <div class="grid grid-cols-1 gap-px rounded-xl border border-gray-100 bg-gray-100 overflow-hidden sm:grid-cols-5 sm:grid-rows-3">
            <!-- Row 1-2 : 5 colonnes -->
            <div class="bg-white p-4 sm:row-span-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Partenaires clés</p>
                <p class="text-[10px] text-gray-400 mb-2">Qui vous aide à faire tourner le projet ?</p>
                <textarea :value="d.partners||''" @input="update('partners',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-xs"></textarea>
            </div>
            <div class="bg-white p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Activités clés</p>
                <textarea :value="d.activities||''" @input="update('activities',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-xs" placeholder="Que devez-vous faire ?"></textarea>
            </div>
            <div class="bg-white p-4 sm:row-span-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Proposition de valeur</p>
                <p class="text-[10px] text-gray-400 mb-2">Quel problème résolvez-vous, pour qui ?</p>
                <textarea :value="d.value_prop||''" @input="update('value_prop',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-xs"></textarea>
            </div>
            <div class="bg-white p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Relation client</p>
                <textarea :value="d.client_rel||''" @input="update('client_rel',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-xs" placeholder="Quel lien créez-vous ?"></textarea>
            </div>
            <div class="bg-white p-4 sm:row-span-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Segments clientèle</p>
                <p class="text-[10px] text-gray-400 mb-2">Qui sont vos clients ou bénéficiaires ?</p>
                <textarea :value="d.segments||''" @input="update('segments',$event.target.value)" rows="4" class="w-full rounded-md border-gray-200 text-xs"></textarea>
            </div>
            <div class="bg-white p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Ressources clés</p>
                <textarea :value="d.resources||''" @input="update('resources',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-xs" placeholder="De quoi avez-vous besoin ?"></textarea>
            </div>
            <div class="bg-white p-4">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Canaux</p>
                <textarea :value="d.channels||''" @input="update('channels',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-xs" placeholder="Comment touchez-vous vos clients ?"></textarea>
            </div>
            <!-- Row 3 : 2 colonnes -->
            <div class="bg-white p-4 sm:col-span-2">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Structure de coûts</p>
                <textarea :value="d.costs||''" @input="update('costs',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-xs" placeholder="Que dépensez-vous ?"></textarea>
            </div>
            <div class="bg-white p-4 sm:col-span-3">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gold-600 mb-2">Sources de revenus</p>
                <textarea :value="d.revenue||''" @input="update('revenue',$event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-xs" placeholder="Comment et par qui êtes-vous payé ?"></textarea>
            </div>
        </div>

        <div class="rounded-lg border-l-3 border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
            Commencez par la case centrale (proposition de valeur), puis avancez vers la droite (client), puis vers la gauche (infrastructure).
        </div>
    </div>
</template>
