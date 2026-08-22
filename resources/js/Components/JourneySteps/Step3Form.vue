<script>
export default {
    props: {
        modelValue: { type: Object, default: () => ({}) },
        errors: { type: Object, default: () => ({}) },
    },
    computed: { d() { return this.modelValue; } },
    methods: {
        hasErr(k) { return !!this.errors[k]; },
        errMsg(k) { return this.errors[k] || ''; },
        errClass(k) { return this.hasErr(k) ? 'border-red-400 bg-red-50/30' : 'border-gray-200'; }, update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); } },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 leading-relaxed">
            <p class="font-medium text-gray-700 mb-1">Un business model ne décrit pas ce que vous vendez — il explique comment votre projet crée de la valeur, la délivre et la transforme en ressources durables.</p>
            <p class="italic mt-2">À savoir — Ce document est un outil de travail. Il vous aide à structurer votre modèle économique bloc par bloc, pas à produire un document académique. Répondez avec vos mots, même si certaines réponses restent provisoires à ce stade.</p>
        </div>

        <!-- ══ CANVAS GRILLE — desktop ══ -->
        <div class="hidden lg:block">
            <div class="grid grid-cols-10 gap-px rounded-xl border border-gray-300 bg-gray-300 overflow-hidden" style="grid-template-rows: 1fr 1fr auto;">

                <!-- Row 1-2, Col 1-2 : Partenaires clés (vert) -->
                <div class="col-span-2 row-span-2 flex flex-col p-3" style="background:#E8EEE3">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Partenaires clés</p>
                    <p class="text-[9px] text-gray-500 mb-2">Qui vous aide à faire tourner le projet ?</p>
                    <textarea :value="d.partners||''" @input="update('partners',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[80px]"></textarea>
                </div>

                <!-- Row 1, Col 3-4 : Activités clés (vert) -->
                <div class="col-span-2 flex flex-col p-3" style="background:#E8EEE3">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Activités clés</p>
                    <p class="text-[9px] text-gray-500 mb-2">Que devez-vous faire concrètement ?</p>
                    <textarea :value="d.activities||''" @input="update('activities',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[50px]"></textarea>
                </div>

                <!-- Row 1-2, Col 5-6 : Proposition de valeur (doré) -->
                <div class="col-span-2 row-span-2 flex flex-col p-3 border-l-[3px] border-primary-600" style="background:#FBF0DC">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-primary-800 mb-1">Proposition de valeur</p>
                    <p class="text-[9px] text-primary-600 mb-2">Quel problème résolvez-vous, pour qui ?</p>
                    <textarea :value="d.value_prop||''" @input="update('value_prop',$event.target.value)" class="flex-1 w-full rounded border-primary-200 bg-white/70 text-[11px] leading-snug resize-none min-h-[80px]"></textarea>
                </div>

                <!-- Row 1, Col 7-8 : Relation client (rosé) -->
                <div class="col-span-2 flex flex-col p-3" style="background:#F7E5DE">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Relation client</p>
                    <p class="text-[9px] text-gray-500 mb-2">Quel lien créez-vous avec vos clients ?</p>
                    <textarea :value="d.rel_client||''" @input="update('rel_client',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[50px]"></textarea>
                </div>

                <!-- Row 1-2, Col 9-10 : Segments clientèle (rosé) -->
                <div class="col-span-2 row-span-2 flex flex-col p-3" style="background:#F7E5DE">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Segments de clientèle</p>
                    <p class="text-[9px] text-gray-500 mb-2">Qui sont vos clients ou bénéficiaires ?</p>
                    <textarea :value="d.segments||''" @input="update('segments',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[80px]"></textarea>
                </div>

                <!-- Row 2, Col 3-4 : Ressources clés (vert) -->
                <div class="col-span-2 flex flex-col p-3" style="background:#E8EEE3">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Ressources clés</p>
                    <p class="text-[9px] text-gray-500 mb-2">De quoi avez-vous besoin pour démarrer ?</p>
                    <textarea :value="d.resources||''" @input="update('resources',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[50px]"></textarea>
                </div>

                <!-- Row 2, Col 7-8 : Canaux (rosé) -->
                <div class="col-span-2 flex flex-col p-3" style="background:#F7E5DE">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Canaux</p>
                    <p class="text-[9px] text-gray-500 mb-2">Comment touchez-vous vos clients ?</p>
                    <textarea :value="d.channels||''" @input="update('channels',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[50px]"></textarea>
                </div>

                <!-- Row 3, Col 1-5 : Structure de coûts (beige) -->
                <div class="col-span-5 flex flex-col p-3" style="background:#F1EADA">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Structure de coûts</p>
                    <p class="text-[9px] text-gray-500 mb-2">Que dépensez-vous pour faire fonctionner le projet ?</p>
                    <textarea :value="d.costs||''" @input="update('costs',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[60px]"></textarea>
                </div>

                <!-- Row 3, Col 6-10 : Sources de revenus (beige) -->
                <div class="col-span-5 flex flex-col p-3" style="background:#F1EADA">
                    <p class="text-[9px] font-bold uppercase tracking-widest text-gray-700 mb-1">Sources de revenus</p>
                    <p class="text-[9px] text-gray-500 mb-2">Comment et par qui êtes-vous payé(e) ?</p>
                    <textarea :value="d.revenue||''" @input="update('revenue',$event.target.value)" class="flex-1 w-full rounded border-gray-300 bg-white/70 text-[11px] leading-snug resize-none min-h-[60px]"></textarea>
                </div>
            </div>
        </div>

        <!-- ══ BLOCS EMPILÉS — mobile ══ -->
        <div class="lg:hidden space-y-3">
            <div v-for="block in [
                { k: 'value_prop', title: 'Proposition de valeur', desc: 'Quel problème résolvez-vous, pour qui ?', bg: '#FBF0DC', border: '#7a1f2b', err: true },
                { k: 'segments', title: 'Segments de clientèle', desc: 'Qui sont vos clients ou bénéficiaires ?', bg: '#F7E5DE', border: '#c8912e', err: true },
                { k: 'rel_client', title: 'Relation client', desc: 'Quel lien créez-vous avec vos clients ?', bg: '#F7E5DE', border: '#c8912e' },
                { k: 'channels', title: 'Canaux', desc: 'Comment touchez-vous vos clients ?', bg: '#F7E5DE', border: '#c8912e' },
                { k: 'activities', title: 'Activités clés', desc: 'Que devez-vous faire concrètement ?', bg: '#E8EEE3', border: '#5a7a4a' },
                { k: 'resources', title: 'Ressources clés', desc: 'De quoi avez-vous besoin pour démarrer ?', bg: '#E8EEE3', border: '#5a7a4a' },
                { k: 'partners', title: 'Partenaires clés', desc: 'Qui vous aide à faire tourner le projet ?', bg: '#E8EEE3', border: '#5a7a4a' },
                { k: 'costs', title: 'Structure de coûts', desc: 'Que dépensez-vous pour faire fonctionner le projet ?', bg: '#F1EADA', border: '#8a7a5a' },
                { k: 'revenue', title: 'Sources de revenus', desc: 'Comment et par qui êtes-vous payé(e) ?', bg: '#F1EADA', border: '#8a7a5a' },
            ]" :key="block.k" class="rounded-xl border border-gray-200 p-4" :style="{ background: block.bg }">
                <div class="mb-3 pl-3" :style="{ borderLeft: '3px solid ' + block.border }">
                    <p class="text-xs font-medium text-gray-800">{{ block.title }}</p>
                    <p class="mt-0.5 text-[10px] text-gray-500">{{ block.desc }}</p>
                </div>
                <textarea :value="d[block.k]||''" @input="update(block.k,$event.target.value)" rows="4" class="w-full rounded-md bg-white/70 text-sm resize-none" :class="hasErr(block.k) ? 'border-red-400' : 'border-gray-300'"></textarea>
                <p v-if="hasErr(block.k)" class="mt-1 text-xs text-red-600">{{ errMsg(block.k) }}</p>
            </div>
        </div>

        <div class="rounded-lg border-l-[3px] border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
            Un bon business model n'est pas celui qui semble parfait sur le papier, mais celui dont chaque bloc tient la route face à la réalité du terrain.
        </div>
    </div>
</template>
