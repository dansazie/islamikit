<template>
    <div class="relative pl-6 sm:pl-8">
        <!-- Vertical Line -->
        <div class="absolute left-[9px] sm:left-[11px] top-2 bottom-2 w-px bg-border" />

        <div class="space-y-4 sm:space-y-6">
            <div v-for="(item, index) in items" :key="index" class="relative">
                <!-- Dot Indicator -->
                <div 
                    class="absolute -left-6 sm:-left-8 top-1 w-[18px] sm:w-[22px] h-[18px] sm:h-[22px] rounded-full border-2 border-card flex items-center justify-center z-10"
                    :class="item.dotClass || 'bg-primary'"
                >
                    <!-- Optional Icon inside dot -->
                    <slot :name="`icon-${index}`">
                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-card" />
                    </slot>
                </div>

                <!-- Content -->
                <div class="pb-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                        <h4 class="text-sm font-semibold text-foreground">
                            {{ item.title }}
                        </h4>
                        <span class="text-[11px] font-medium text-muted-foreground whitespace-nowrap">
                            {{ item.time }}
                        </span>
                    </div>
                    <p v-if="item.description" class="text-xs text-muted-foreground leading-relaxed">
                        {{ item.description }}
                    </p>
                    
                    <!-- Slot for extra content like images or sub-components -->
                    <div v-if="$slots[`content-${index}`]" class="mt-2">
                        <slot :name="`content-${index}`" :item="item" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    items: {
        type: Array,
        required: true,
        // Format: { title: String, time: String, description?: String, dotClass?: String (e.g., 'bg-green-500') }
    },
});
</script>