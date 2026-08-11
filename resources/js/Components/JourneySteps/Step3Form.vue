<script>
export default {
    props: { modelValue: { type: Object, default: () => ({}) } },
    computed: { d() { return this.modelValue; } },
    methods: { update(k, v) { this.$emit('update:modelValue', { ...this.d, [k]: v }); } },
};
</script>
<template>
    <div class="space-y-5">
        <div class="rounded-lg border-l-3 border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
            Un business model ne décrit pas ce que vous vendez — il explique comment votre projet crée de la valeur, la délivre et la transforme en ressources durables. Répondez avec vos mots, même si certaines réponses restent provisoires.
        </div>

        <!-- 1. Proposition de valeur (au centre, en premier) -->
        <div class="rounded-xl border border-primary-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">1</span>
                <p class="text-sm font-medium text-primary-800">Proposition de valeur</p>
            </div>
            <p class="text-xs text-gray-400 mb-3">Commencez ici — c'est le cœur de votre projet.</p>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quel problème résolvez-vous ?</label>
                    <textarea :value="d.value_problem || ''" @input="update('value_problem', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Le problème concret que vous adressez"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Pour qui le résolvez-vous ?</label>
                    <textarea :value="d.value_for_whom || ''" @input="update('value_for_whom', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Les personnes ou organisations concernées"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">En quoi votre solution est-elle différente de ce qui existe ?</label>
                    <textarea :value="d.value_differentiation || ''" @input="update('value_differentiation', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Ce qui vous distingue des alternatives"></textarea>
                </div>
            </div>
        </div>

        <!-- 2. Segments de clientèle -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">2</span>
                <p class="text-sm font-medium text-gray-800">Segments de clientèle</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Qui sont vos clients ou bénéficiaires principaux ?</label>
                    <textarea :value="d.segments_main || ''" @input="update('segments_main', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Décrivez votre client type"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Existe-t-il d'autres types de clients secondaires ?</label>
                    <textarea :value="d.segments_secondary || ''" @input="update('segments_secondary', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Autres segments visés à terme"></textarea>
                </div>
            </div>
        </div>

        <!-- 3. Relation client -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">3</span>
                <p class="text-sm font-medium text-gray-800">Relation client</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quel type de relation créez-vous avec vos clients ?</label>
                    <textarea :value="d.rel_type || ''" @input="update('rel_type', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Service personnalisé, communauté, libre-service, accompagnement…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Comment fidélisez-vous vos clients ?</label>
                    <textarea :value="d.rel_retention || ''" @input="update('rel_retention', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Programme fidélité, qualité, suivi régulier…"></textarea>
                </div>
            </div>
        </div>

        <!-- 4. Canaux -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">4</span>
                <p class="text-sm font-medium text-gray-800">Canaux de distribution</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Comment touchez-vous vos clients ?</label>
                    <textarea :value="d.channels_reach || ''" @input="update('channels_reach', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Bouche à oreille, réseaux sociaux, marché local, site web…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Comment livrez-vous votre produit ou service ?</label>
                    <textarea :value="d.channels_delivery || ''" @input="update('channels_delivery', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="En personne, livraison, en ligne, via un partenaire…"></textarea>
                </div>
            </div>
        </div>

        <!-- 5. Activités clés -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">5</span>
                <p class="text-sm font-medium text-gray-800">Activités clés</p>
            </div>
            <div>
                <label class="block text-xs text-gray-600 mb-1">Que devez-vous faire concrètement au quotidien pour que le projet fonctionne ?</label>
                <textarea :value="d.activities || ''" @input="update('activities', $event.target.value)" rows="3" class="w-full rounded-md border-gray-200 text-sm" placeholder="Production, vente, marketing, gestion, logistique…"></textarea>
            </div>
        </div>

        <!-- 6. Ressources clés -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">6</span>
                <p class="text-sm font-medium text-gray-800">Ressources clés</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">De quoi avez-vous besoin pour démarrer ?</label>
                    <textarea :value="d.resources_start || ''" @input="update('resources_start', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Matériel, local, véhicule, outils numériques…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quelles compétences sont indispensables ?</label>
                    <textarea :value="d.resources_skills || ''" @input="update('resources_skills', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Compétences techniques, commerciales, de gestion…"></textarea>
                </div>
            </div>
        </div>

        <!-- 7. Partenaires clés -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">7</span>
                <p class="text-sm font-medium text-gray-800">Partenaires clés</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Qui vous aide à faire tourner le projet ?</label>
                    <textarea :value="d.partners_who || ''" @input="update('partners_who', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Fournisseurs, institutions, mentors, associations…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Que vous apportent-ils concrètement ?</label>
                    <textarea :value="d.partners_value || ''" @input="update('partners_value', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Matières premières, réseau, formation, financement…"></textarea>
                </div>
            </div>
        </div>

        <!-- 8. Structure de coûts -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">8</span>
                <p class="text-sm font-medium text-gray-800">Structure de coûts</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quelles sont vos principales dépenses ?</label>
                    <textarea :value="d.costs_main || ''" @input="update('costs_main', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Loyer, matières premières, salaires, transport, communication…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quels coûts sont fixes ? Lesquels varient selon l'activité ?</label>
                    <textarea :value="d.costs_fixed_variable || ''" @input="update('costs_fixed_variable', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Fixes : loyer, salaires — Variables : matières premières, transport…"></textarea>
                </div>
            </div>
        </div>

        <!-- 9. Sources de revenus -->
        <div class="rounded-xl border border-gray-100 bg-white p-5">
            <div class="flex items-center gap-2 mb-4">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-primary-600 text-[10px] font-bold text-white">9</span>
                <p class="text-sm font-medium text-gray-800">Sources de revenus</p>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Comment et par qui êtes-vous payé ?</label>
                    <textarea :value="d.revenue_how || ''" @input="update('revenue_how', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Vente directe, abonnement, commission, prestation…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quel est votre prix ou modèle de tarification ?</label>
                    <textarea :value="d.revenue_pricing || ''" @input="update('revenue_pricing', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Prix fixe, à l'unité, forfait, gratuit + premium…"></textarea>
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Quelle est la source de revenus prioritaire au lancement ?</label>
                    <textarea :value="d.revenue_priority || ''" @input="update('revenue_priority', $event.target.value)" rows="2" class="w-full rounded-md border-gray-200 text-sm" placeholder="Le produit/service qui génèrera du revenu en premier"></textarea>
                </div>
            </div>
        </div>

        <div class="rounded-lg border-l-3 border-gold-500 bg-gold-50 px-4 py-3 text-xs text-gray-600 italic">
            Un bon business model n'est pas celui qui semble parfait sur le papier, mais celui dont chaque bloc tient la route face à la réalité du terrain.
        </div>
    </div>
</template>