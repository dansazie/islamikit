<template>
    <div class="relative w-full">
        <textarea
            ref="textareaRef"
            :value="modelValue"
            :placeholder="placeholder"
            :rows="minRows"
            :disabled="disabled"
            :maxlength="maxLength"
            class="w-full bg-background border border-input rounded-xl text-sm text-foreground placeholder:text-muted-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-ring/20 focus:border-ring disabled:opacity-50 disabled:cursor-not-allowed resize-none overflow-hidden py-2.5 px-3.5"
            :class="error ? 'border-destructive focus:ring-destructive/20' : ''"
            @input="onInput"
        />
        
        <!-- Character Counter -->
        <div 
            v-if="maxLength" 
            class="absolute bottom-2 right-3 text-[10px] font-mono font-bold"
            :class="charCount >= maxLength ? 'text-destructive' : 'text-muted-foreground'"
        >
            {{ charCount }}/{{ maxLength }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, nextTick } from 'vue';

const props = defineProps({
    modelValue: { type: String, default: '' },
    placeholder: { type: String, default: '' },
    minRows: { type: Number, default: 3 },
    maxRows: { type: Number, default: 10 },
    maxLength: { type: Number, default: null },
    error: { type: [String, Boolean], default: false },
    disabled: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue']);

const textareaRef = ref(null);
const charCount = computed(() => props.modelValue?.length || 0);

const autoResize = () => {
    const el = textareaRef.value;
    if (!el) return;
    
    el.style.height = 'auto';
    const style = window.getComputedStyle(el);
    const paddingTop = parseFloat(style.paddingTop);
    const paddingBottom = parseFloat(style.paddingBottom);
    const lineHeight = parseFloat(style.lineHeight) || 20;
    
    const maxHeight = lineHeight * props.maxRows + paddingTop + paddingBottom;
    const newHeight = Math.min(el.scrollHeight, maxHeight);
    
    el.style.height = `${newHeight}px`;
    el.style.overflowY = el.scrollHeight > maxHeight ? 'auto' : 'hidden';
};

const onInput = (e) => {
    emit('update:modelValue', e.target.value);
    nextTick(autoResize);
};

onMounted(autoResize);
watch(() => props.modelValue, () => nextTick(autoResize));
</script>