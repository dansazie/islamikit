<template>
    <label
        class="inline-flex items-start gap-3 cursor-pointer select-none"
        :class="disabled ? 'opacity-50 pointer-events-none' : ''"
    >
        <!-- Switch Track -->
        <button
            type="button"
            role="switch"
            :aria-checked="modelValue"
            class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background cursor-pointer mt-0.5"
            :class="modelValue ? 'bg-primary' : 'bg-input'"
            @click="toggle"
        >
            <!-- Thumb -->
            <span
                class="pointer-events-none block h-5 w-5 rounded-full bg-white shadow-lg ring-0 transition-transform"
                :class="modelValue ? 'translate-x-5' : 'translate-x-0'"
            />
        </button>

        <!-- Label & Description -->
        <div v-if="label || $slots.default" class="flex flex-col">
            <span class="text-sm font-medium text-foreground leading-tight">
                <slot>{{ label }}</slot>
            </span>
            <span v-if="description" class="text-xs text-muted-foreground mt-0.5">
                {{ description }}
            </span>
        </div>
    </label>
</template>

<script setup>
const props = defineProps({
    modelValue: { type: Boolean, default: false },
    label: { type: String, default: '' },
    description: { type: String, default: '' },
    disabled: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const toggle = () => {
    emit('update:modelValue', !props.modelValue);
};
</script>