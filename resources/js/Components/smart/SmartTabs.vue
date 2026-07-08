<template>
    <div>
        <!-- Tab Headers -->
        <div class="relative flex items-center gap-0 border-b border-border overflow-x-auto scrollbar-none -mb-px">
            <button
                v-for="tab in items"
                :key="tab.key"
                :disabled="tab.disabled"
                class="relative flex items-center gap-1.5 px-3 sm:px-4 py-2.5 text-xs sm:text-sm font-semibold whitespace-nowrap transition-colors shrink-0 cursor-pointer"
                :class="[
                    tab.key === modelValue
                        ? 'text-primary'
                        : 'text-muted-foreground hover:text-foreground',
                    tab.disabled ? 'opacity-40 pointer-events-none' : ''
                ]"
                @click="select(tab.key)"
            >
                <component v-if="tab.icon" :is="tab.icon" class="w-4 h-4" />
                {{ tab.label }}
                
                <!-- Active Indicator -->
                <span
                    v-if="tab.key === modelValue"
                    class="absolute bottom-0 left-0 right-0 h-0.5 bg-primary rounded-t-full"
                />
            </button>
        </div>

        <!-- Tab Panels -->
        <div class="mt-4 focus:outline-none">
            <slot :name="modelValue" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    modelValue: { type: [String, Number], required: true },
    items: {
        type: Array,
        required: true,
        // Format: { key: String|Number, label: String, icon?: Object, disabled?: Boolean }
    },
});

const emit = defineEmits(['update:modelValue']);

const select = (key) => {
    emit('update:modelValue', key);
};
</script>

<style scoped>
/* Utility sembunyikan scrollbar untuk tab horizontal di mobile */
.scrollbar-none {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-none::-webkit-scrollbar {
    display: none;
}
</style>