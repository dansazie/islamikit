<template>
    <div class="relative inline-flex" ref="dropdownRef">
        <!-- Trigger -->
        <div @click="toggle">
            <slot name="trigger" :is-open="isOpen" />
        </div>

        <!-- ═══════════════════════════════════════════════════════════
             MENU PANEL: DITELEPORT KE BODY
             Agar tidak terhalang overflow:hidden parent container
             ═══════════════════════════════════════════════════════════ -->
        <Teleport to="body">
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
                    ref="menuPanelRef"
                    class="fixed z-[9999] min-w-[10rem] py-1 bg-popover text-popover-foreground border border-border rounded-xl shadow-xl overflow-hidden"
                    :style="computedPosition"
                >
                    <slot name="default" :close="close" />
                </div>
            </Transition>
        </Teleport>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
        validator: v => ['left', 'right'].includes(v),
    },
});

const dropdownRef = ref(null);
const menuPanelRef = ref(null);
const isOpen = ref(false);
const computedPosition = ref({ top: '0px', left: '0px' });

const toggle = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value) {
        nextTick(() => calculatePosition());
    }
};

const close = () => {
    isOpen.value = false;
};

// ════════════════════════════════════════════════════════════════
// KALKULASI POSISI DINAMIS (Menghitung berdasarkan viewport)
// ════════════════════════════════════════════════════════════════
const calculatePosition = () => {
    if (!dropdownRef.value || !menuPanelRef.value) return;

    const trigger = dropdownRef.value.getBoundingClientRect();
    const menu = menuPanelRef.value.getBoundingClientRect();
    const gap = 8; // Jarak antara trigger dan menu
    const viewportPadding = 12; // Jarak minimal dari tepi layar

    // Posisi awal: di bawah trigger
    let top = trigger.bottom + gap;
    let left;

    // Hitung posisi horizontal berdasarkan align
    if (props.align === 'right') {
        left = trigger.right - menu.width;
    } else {
        left = trigger.left;
    }

    // ═══ BOUNDARY CHECK: Pastikan tidak keluar viewport ═══

    // Cek batas kiri
    if (left < viewportPadding) {
        left = viewportPadding;
    }

    // Cek batas kanan
    if (left + menu.width > window.innerWidth - viewportPadding) {
        left = window.innerWidth - menu.width - viewportPadding;
    }

    // Cek batas bawah - JIKA MELEBIHI, tampilkan di ATAS trigger
    if (top + menu.height > window.innerHeight - viewportPadding) {
        top = trigger.top - menu.height - gap;

        // Jika masih melebihi ke atas, paksa posisi tetap di bawah tapi scrollable
        if (top < viewportPadding) {
            top = viewportPadding;
        }
    }

    // Cek batas atas
    if (top < viewportPadding) {
        top = viewportPadding;
    }

    computedPosition.value = {
        top: `${top}px`,
        left: `${left}px`,
        maxWidth: `${window.innerWidth - (viewportPadding * 2)}px`,
    };
};

// Update posisi saat scroll atau resize
const handlePositionUpdate = () => {
    if (isOpen.value) {
        calculatePosition();
    }
};

// ════════════════════════════════════════════════════════════════
// EVENT LISTENERS
// ════════════════════════════════════════════════════════════════
const handleClickOutside = (event) => {
    // Cek apakah click di luar trigger
    const isOutsideTrigger = dropdownRef.value && !dropdownRef.value.contains(event.target);
    // Cek apakah click di luar menu panel
    const isOutsideMenu = menuPanelRef.value && !menuPanelRef.value.contains(event.target);

    if (isOutsideTrigger && isOutsideMenu) {
        close();
    }
};

const handleEscape = (event) => {
    if (event.key === 'Escape') {
        close();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', handleEscape);
    // Gunakan capture:true agar mendeteksi scroll di semua element
    window.addEventListener('scroll', handlePositionUpdate, true);
    window.addEventListener('resize', handlePositionUpdate);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.removeEventListener('keydown', handleEscape);
    window.removeEventListener('scroll', handlePositionUpdate, true);
    window.removeEventListener('resize', handlePositionUpdate);
});

defineExpose({ close, toggle });
</script>