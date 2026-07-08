<template>
    <div class="app-card-container !gap-0 group">
        <div class="flex items-start justify-between relative z-10">
            <div class="min-w-0 flex-1">
                <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-muted-foreground mb-1.5">
                    {{ label }}
                </p>
                <p class="text-2xl sm:text-3xl font-black tracking-tight text-foreground leading-none">
                    {{ formattedValue }}
                </p>
            </div>
            
            <!-- CSS Only Sparkline -->
            <div class="w-16 h-10 sm:w-20 sm:h-12 flex items-end gap-px opacity-60 group-hover:opacity-100 transition-opacity">
                <div 
                    v-for="(val, i) in sparkline" 
                    :key="i"
                    class="flex-1 rounded-t-sm transition-all duration-300"
                    :class="trendColor"
                    :style="{ height: `${val}%` }"
                />
            </div>
        </div>
        
        <div class="flex items-center gap-2 mt-3 relative z-10">
            <div class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[11px] font-bold" :class="trendBgClass">
                <svg class="w-3 h-3" :class="{ 'rotate-180': trend < 0 }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                </svg>
                {{ Math.abs(trend) }}%
            </div>
            <span class="text-[11px] text-muted-foreground">vs bulan lalu</span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: { type: String, required: true },
    value: { type: [Number, String], required: true },
    trend: { type: Number, default: 0 },
    sparkline: { 
        type: Array, 
        default: () => [40, 60, 45, 80, 55, 90, 70] // Random height percentages
    },
});

const formattedValue = computed(() => {
    return new Intl.NumberFormat('id-ID').format(Number(props.value));
});

const isPositive = computed(() => props.trend >= 0);

const trendColor = computed(() => isPositive.value ? 'bg-primary' : 'bg-destructive');
const trendBgClass = computed(() => isPositive.value ? 'bg-primary/10 text-primary' : 'bg-destructive/10 text-destructive');
</script>