<template>
    <div 
        class="flex items-start gap-3 p-3 rounded-xl transition-colors group"
        :class="unread ? 'bg-primary/[0.03] hover:bg-primary/[0.06]' : 'hover:bg-muted/50'"
    >
        <!-- Left: Avatar -->
        <div class="relative shrink-0">
            <slot name="avatar">
                <SmartAvatar :src="avatar" :name="title" size="md" />
            </slot>
            <!-- Unread dot -->
            <span v-if="unread" class="absolute -top-0.5 -right-0.5 w-3 h-3 bg-primary rounded-full border-2 border-card" />
        </div>

        <!-- Center: Content -->
        <div class="flex-1 min-w-0">
            <p class="text-sm text-foreground leading-snug">
                <span class="font-bold">{{ title }}</span>
                <span class="text-muted-foreground font-normal"> {{ message }}</span>
            </p>
            
            <!-- Meta (Time & Tags) -->
            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                <span class="text-[11px] text-muted-foreground">{{ time }}</span>
                <slot name="tags" />
            </div>

            <!-- Expandable Content (Optional) -->
            <Transition
                enter-active-class="transition-all duration-200 ease-out overflow-hidden"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-40 opacity-100"
                leave-active-class="transition-all duration-150 ease-in overflow-hidden"
                leave-from-class="max-h-40 opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div v-if="expanded && $slots.detail" class="mt-2 p-2.5 bg-muted/50 rounded-lg border border-border">
                    <slot name="detail" />
                </div>
            </Transition>
        </div>

        <!-- Right: Action Area -->
        <div v-if="$slots.action || expandable" class="flex items-start gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
            <button 
                v-if="expandable"
                @click="expanded = !expanded"
                class="p-1.5 rounded-md text-muted-foreground hover:bg-muted hover:text-foreground transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <slot name="action" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import SmartAvatar from './SmartAvatar.vue';

defineProps({
    title: { type: String, required: true },
    message: { type: String, default: '' },
    time: { type: String, default: '' },
    avatar: { type: String, default: null },
    unread: { type: Boolean, default: false },
    expandable: { type: Boolean, default: false },
});

const expanded = ref(false);
</script>