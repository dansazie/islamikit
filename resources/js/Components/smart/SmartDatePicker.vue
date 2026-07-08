<template>
    <div class="space-y-2">
        <label v-if="label" :for="id" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground">
            {{ label }}
        </label>
        <div class="relative">
            <input 
                :id="id"
                :type="type" 
                :value="modelValue" 
                @input="$emit('update:modelValue', $event.target.value)"
                :min="min" 
                :max="max"
                :disabled="disabled"
                :placeholder="placeholder"
                :class="[
                    'w-full bg-transparent border rounded-lg text-sm text-foreground placeholder:text-muted-foreground outline-none transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium',
                    'focus:ring-2 focus:ring-ring focus:border-ring',
                    'disabled:cursor-not-allowed disabled:opacity-50',
                    sizeClass,
                    error ? 'border-destructive' : 'border-input'
                ]"
                style="color-scheme: dark;" 
            />
            <!-- Ikon Kalender -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-muted-foreground">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
        </div>
        <p v-if="error" class="text-xs text-destructive font-medium">{{ error }}</p>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number], default: '' },
    label: { type: String, default: null },
    placeholder: { type: String, default: '' },
    error: { type: String, default: null },
    min: { type: String, default: null },
    max: { type: String, default: null },
    disabled: { type: Boolean, default: false },
    type: { type: String, default: 'date' }, // 'date' atau 'datetime-local'
    size: { type: String, default: 'md', validator: v => ['sm', 'md'].includes(v) },
});

defineEmits(['update:modelValue']);

const id = computed(() => 'date-' + Math.random().toString(36).substr(2, 9));

const sizeClass = computed(() => {
    return props.size === 'sm' ? 'px-3 py-1.5 text-xs' : 'px-3 py-2.5 text-sm';
});
</script>