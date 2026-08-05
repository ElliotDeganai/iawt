<script>
const paths = {
    leaf: 'M5 21c9 0 14-5 14-14 0-1.1-.1-2-.3-2.7C10.7 4.6 5 9.4 5 18v3zm0 0c1-4 3.5-7.5 7-9.5',
    palm: 'M12 21v-9m0 0c-3-3-7-3-9-1 2-1 6-.5 9 1m0 0c1-4 4-6 8-6-1 2-4 5-8 6m0 0c1-2 1-4 0-6-2 1-3 3-3 5',
    rocket: 'M12 2c2.5 2 4 5 4 9 0 2-1 4-2 5l-2 2-2-2c-1-1-2-3-2-5 0-4 1.5-7 4-9zM9 15l-2 2 1 4 3-2M15 15l2 2-1 4-3-2M11 9h2',
    lightbulb: 'M9 18h6M10 21h4M8 12a4 4 0 118 0c0 1.6-.8 2.4-1.5 3.2-.5.5-.5 1.3-.5 1.8H10c0-.5 0-1.3-.5-1.8C8.8 14.4 8 13.6 8 12z',
    search: 'M11 4a7 7 0 100 14 7 7 0 000-14zM21 21l-4.35-4.35',
    chart: 'M4 20V10M10 20V4M16 20v-7M4 20h16',
    currency: 'M12 3v18M8 7.5c0-1.4 1.6-2.5 4-2.5s4 1.1 4 2.5-1.6 2.5-4 2.5-4 1.1-4 2.5 1.6 2.5 4 2.5 4-1.1 4-2.5',
    scale: 'M12 3v18M5 8l3-4 3 4M16 8l3-4 3 4M3 8h5M16 8h5M3 8c0 2.5 2 4 4 4s4-1.5 4-4M16 8c0 2.5 2 4 4 4s4-1.5 4-4M7 21h10',
    target: 'M12 21a9 9 0 100-18 9 9 0 000 18zM12 16a4 4 0 100-8 4 4 0 000 8zM12 13a1 1 0 100-2 1 1 0 000 2z',
    building: 'M4 21V7l8-4 8 4v14M4 21h16M9 21v-4h6v4M9 11h.01M9 15h.01M15 11h.01M15 15h.01',
};

export default {
    props: {
        name: {
            type: String,
            required: true,
        },
    },
    computed: {
        d() {
            return paths[this.name] || '';
        },
    },
};
</script>

<template>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
        <path :d="d" />
    </svg>
</template>
