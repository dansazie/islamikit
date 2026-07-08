<template>
    <div class="relative" :class="wrapperClass">
        <!-- Search Icon -->
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg
                class="w-4 h-4 text-muted-foreground"
                :class="{ 'animate-pulse': loading }"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </div>

        <!-- Input -->
        <input
            ref="inputRef"
            :type="type"
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            class="w-full bg-background border border-input rounded-xl text-sm text-foreground placeholder:text-muted-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring/20 focus:border-ring disabled:opacity-50 disabled:cursor-not-allowed"
            :class="[
                sizeClass,
                clearable && modelValue ? 'pr-16' : 'pr-3',
            ]"
            @input="onInput"
            @keydown.escape="onClear"
            @keydown="$emit('keydown', $event)"
            @focus="$emit('focus', $event)"
            @blur="$emit('blur', $event)"
        />

        <!-- Clear Button -->
        <button
            v-if="clearable && modelValue"
            type="button"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground transition-colors cursor-pointer"
            @click="onClear"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Keyboard Shortcut Hint (Desktop only) -->
        <div
            v-if="shortcut && !modelValue"
            class="absolute inset-y-0 right-0 hidden sm:flex items-center pr-3 pointer-events-none"
        >
            <kbd class="inline-flex items-center h-5 px-1.5 text-[10px] font-mono font-bold text-muted-foreground bg-muted border border-border rounded-md">
                {{ shortcut }}
            </kbd>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: 'Cari...' },
    delay: { type: Number, default: 300 },
    disabled: { type: Boolean, default: false },
    loading: { type: Boolean, default: false },
    clearable: { type: Boolean, default: true },
    shortcut: { type: String, default: '' },
    size: {
        type: String,
        default: 'md',
        validator: v => ['sm', 'md', 'lg'].includes(v),
    },
    type: { type: String, default: 'search' },
    wrapperClass: { type: String, default: '' },
});

const emit = defineEmits(['update:modelValue', 'search', 'clear', 'focus', 'blur', 'keydown']);

const inputRef = ref(null);
let debounceTimer = null;

const sizeClass = computed(() => {
    const map = {
        sm: 'py-1.5 pl-9 text-xs',
        md: 'py-2.5 pl-10',
        lg: 'py-3 pl-11 text-base',
    };
    return map[props.size];
});

const onInput = (e) => {
    const val = e.target.value;
    emit('update:modelValue', val);

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        emit('search', val);
    }, props.delay);
};

const onClear = () => {
    emit('update:modelValue', '');
    emit('clear');
    emit('search', '');
    inputRef.value?.focus();
};

// Global keyboard shortcut listener
const onKeyDown = (e) => {
    if (!props.shortcut) return;
    const keys = props.shortcut.split('+').map(k => k.trim().toLowerCase());
    const ctrl = keys.includes('ctrl') || keys.includes('cmd');
    const shift = keys.includes('shift');
    const key = keys.filter(k => !['ctrl', 'cmd', 'shift'].includes(k))[0];

    if (
        (ctrl && (e.ctrlKey || e.metaKey)) &&
        (shift ? e.shiftKey : true) &&
        e.key.toLowerCase() === key
    ) {
        e.preventDefault();
        inputRef.value?.focus();
    }
};

onMounted(() => {
    if (props.shortcut) window.addEventListener('keydown', onKeyDown);
});

onUnmounted(() => {
    clearTimeout(debounceTimer);
    if (props.shortcut) window.removeEventListener('keydown', onKeyDown);
});

defineExpose({ focus: () => inputRef.value?.focus() });
</script>