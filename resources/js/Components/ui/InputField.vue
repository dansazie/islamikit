<template>
    <div>
        <label v-if="label" class="block text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">
            {{ label }}
        </label>

        <input
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="block w-full rounded-xl text-sm py-2.5 px-3.5 border bg-background text-foreground transition-colors duration-200 placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring/20 disabled:opacity-50 disabled:cursor-not-allowed"
            :class="[
                error
                    ? 'border-destructive focus:border-destructive focus:ring-destructive/20'
                    : 'border-input focus:border-ring'
            ]"
            v-bind="$attrs"
        />

        <p v-if="error" class="text-xs text-destructive font-medium mt-1.5">
            {{ error }}
        </p>
    </div>
</template>

<script setup>
defineOptions({ inheritAttrs: false });

defineProps({
    modelValue: [String, Number],
    label: String,
    type: { type: String, default: 'text' },
    error: String,
});

defineEmits(['update:modelValue']);
</script>