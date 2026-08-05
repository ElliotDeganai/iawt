<script>
export default {
    props: {
        modelValue: {
            type: String,
            default: null,
        },
        flags: {
            type: Object,
            required: true, // { code: 'Nom du pays', ... }
        },
    },
    emits: ['update:modelValue'],
    data() {
        return {
            search: '',
        };
    },
    computed: {
        filtered() {
            const q = this.search.trim().toLowerCase();
            return Object.entries(this.flags).filter(([code, name]) =>
                !q || name.toLowerCase().includes(q) || code.includes(q)
            );
        },
    },
};
</script>

<template>
    <div>
        <input
            v-model="search"
            type="text"
            placeholder="Rechercher un pays..."
            class="mb-2 w-full rounded-md border-gray-300 text-sm"
        />
        <div class="grid max-h-56 grid-cols-4 gap-2 overflow-y-auto rounded-md border border-gray-200 p-2 sm:grid-cols-6">
            <button
                v-for="[code, name] in filtered" :key="code"
                type="button"
                class="flex flex-col items-center gap-1 rounded-md border-2 p-2 text-center"
                :class="modelValue === code ? 'border-primary-600 bg-primary-50' : 'border-transparent hover:bg-gray-50'"
                :title="name"
                @click="$emit('update:modelValue', code)"
            >
                <span :class="`fi fi-${code}`" class="h-6 w-8 rounded-sm"></span>
                <span class="truncate text-[11px] text-gray-600 w-full">{{ name }}</span>
            </button>
        </div>
    </div>
</template>