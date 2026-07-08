<template>
    <div class="relative" ref="selectRef">
        <!-- Trigger Button -->
        <button
            type="button"
            @click="toggleDropdown"
            :disabled="disabled"
            class="w-full flex items-center justify-between gap-2 bg-background border border-input rounded-xl text-sm text-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring/20 focus:border-ring disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer"
            :class="sizeClass"
        >
            <span v-if="selectedLabel" class="truncate text-left">{{ selectedLabel }}</span>
            <span v-else class="truncate text-left text-muted-foreground">{{ placeholder }}</span>
            
            <svg class="w-4 h-4 shrink-0 text-muted-foreground transition-transform" :class="{ 'rotate-180': isOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Options Panel -->
        <Transition
            enter-active-class="transition-all duration-150 ease-out origin-top"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition-all duration-100 ease-in origin-top"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="absolute z-50 mt-1.5 w-full py-1 bg-popover border border-border rounded-xl shadow-lg max-h-60 overflow-y-auto custom-scrollbar"
            >
                <button
                    v-for="option in options"
                    :key="option.value"
                    type="button"
                    @click="selectOption(option.value)"
                    class="w-full flex items-center gap-2 px-3 py-2 text-left text-sm transition-colors cursor-pointer"
                    :class="option.value === modelValue 
                        ? 'bg-primary/10 text-primary font-semibold' 
                        : 'text-foreground hover:bg-muted'"
                >
                    <!-- Check icon for selected -->
                    <svg v-if="option.value === modelValue" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span v-else class="w-4 shrink-0" />
                    
                    <span class="truncate">{{ option.label }}</span>
                </button>

                <!-- Empty state -->
                <div v-if="options.length === 0" class="px-3 py-4 text-center text-xs text-muted-foreground">
                    {{ t('ui.components.select.no_options') }}
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useI18n } from '@/Composables/useI18n';
const { t } = useI18n();

const props = defineProps({
    modelValue: { type: [String, Number, null], default: null },
    options: { type: Array, default: () => [] }, // [{ label: String, value: String|Number }]
    placeholder: { type: String, default: 'Pilih opsi...' },
    disabled: { type: Boolean, default: false },
    size: {
        type: String,
        default: 'md',
        validator: v => ['sm', 'md'].includes(v),
    },
});

const emit = defineEmits(['update:modelValue']);

const selectRef = ref(null);
const isOpen = ref(false);

const sizeClass = computed(() => props.size === 'sm' ? 'py-1.5 px-3 text-xs' : 'py-2.5 px-3.5');

const selectedLabel = computed(() => {
    const selected = props.options.find(opt => opt.value === props.modelValue);
    return selected ? selected.label : '';
});

const toggleDropdown = () => {
    if (!props.disabled) isOpen.value = !isOpen.value;
};

const selectOption = (value) => {
    emit('update:modelValue', value);
    isOpen.value = false;
};

const handleClickOutside = (e) => {
    if (selectRef.value && !selectRef.value.contains(e.target)) {
        isOpen.value = false;
    }
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>