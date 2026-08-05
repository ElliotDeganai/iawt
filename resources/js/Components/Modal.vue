<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        maxWidth: {
            type: String,
            default: '2xl',
        },
        closeable: {
            type: Boolean,
            default: true,
        },
    },
    emits: ['close'],
    watch: {
        show: {
            immediate: true,
            handler(show) {
                document.body.style.overflow = show ? 'hidden' : '';
            },
        },
    },
    computed: {
        maxWidthClass() {
            return {
                sm: 'sm:max-w-sm',
                md: 'sm:max-w-md',
                lg: 'sm:max-w-lg',
                xl: 'sm:max-w-xl',
                '2xl': 'sm:max-w-2xl',
            }[this.maxWidth];
        },
    },
    methods: {
        close() {
            if (this.closeable) this.$emit('close');
        },
    },
    created() {
        this.escHandler = (e) => {
            if (e.key === 'Escape' && this.show) this.close();
        };
    },
    mounted() {
        document.addEventListener('keydown', this.escHandler);
    },
    beforeUnmount() {
        document.removeEventListener('keydown', this.escHandler);
        document.body.style.overflow = '';
    },
};
</script>

<template>
    <Teleport to="body">
        <transition leave-active-class="duration-200">
            <div
                v-show="show"
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center"
                scroll-region
            >
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="show" class="fixed inset-0 bg-gray-900/60" @click="close" />
                </transition>

                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div
                        v-show="show"
                        class="relative mb-6 bg-white rounded-lg shadow-xl overflow-hidden transform transition-all sm:w-full sm:mx-auto"
                        :class="maxWidthClass"
                    >
                        <slot />
                    </div>
                </transition>
            </div>
        </transition>
    </Teleport>
</template>
