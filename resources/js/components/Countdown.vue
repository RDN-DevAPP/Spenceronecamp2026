<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

// 24 April 2026, 13:30 WITA (UTC+8)
const target = new Date('2026-04-24T13:30:00+08:00');

const now = ref(new Date());
let interval = null;

const diff = computed(() => {
  const d = target - now.value;
  if (d <= 0) return { days: 0, hours: 0, minutes: 0, seconds: 0, done: true };
  return {
    days: Math.floor(d / (1000 * 60 * 60 * 24)),
    hours: Math.floor((d % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
    minutes: Math.floor((d % (1000 * 60 * 60)) / (1000 * 60)),
    seconds: Math.floor((d % (1000 * 60)) / 1000),
    done: false,
  };
});

onMounted(() => {
  interval = setInterval(() => { now.value = new Date(); }, 1000);
});
onUnmounted(() => clearInterval(interval));
</script>

<template>
  <div class="countdown flex flex-wrap justify-center gap-3 sm:gap-4">
    <template v-if="!diff.done">
      <div class="flex flex-col items-center rounded-xl bg-scout-brown px-4 py-3 min-w-[4rem] sm:min-w-[5rem] text-scout-cream shadow-lg">
        <span class="text-2xl sm:text-3xl font-bold tabular-nums">{{ String(diff.days).padStart(2, '0') }}</span>
        <span class="text-xs font-medium uppercase tracking-wider">Hari</span>
      </div>
      <div class="flex flex-col items-center rounded-xl bg-scout-brown px-4 py-3 min-w-[4rem] sm:min-w-[5rem] text-scout-cream shadow-lg">
        <span class="text-2xl sm:text-3xl font-bold tabular-nums">{{ String(diff.hours).padStart(2, '0') }}</span>
        <span class="text-xs font-medium uppercase tracking-wider">Jam</span>
      </div>
      <div class="flex flex-col items-center rounded-xl bg-scout-brown px-4 py-3 min-w-[4rem] sm:min-w-[5rem] text-scout-cream shadow-lg">
        <span class="text-2xl sm:text-3xl font-bold tabular-nums">{{ String(diff.minutes).padStart(2, '0') }}</span>
        <span class="text-xs font-medium uppercase tracking-wider">Menit</span>
      </div>
      <div class="flex flex-col items-center rounded-xl bg-scout-brown px-4 py-3 min-w-[4rem] sm:min-w-[5rem] text-scout-cream shadow-lg">
        <span class="text-2xl sm:text-3xl font-bold tabular-nums">{{ String(diff.seconds).padStart(2, '0') }}</span>
        <span class="text-xs font-medium uppercase tracking-wider">Detik</span>
      </div>
    </template>
    <div v-else class="rounded-xl bg-scout-gold px-6 py-4 text-scout-cream font-semibold">
      Upacara Pembukaan Dimulai!
    </div>
  </div>
  <p class="mt-2 text-sm text-scout-brown/80">Menuju Upacara Pembukaan LT-I • 24 April 2026, 13:30 WITA</p>
</template>
