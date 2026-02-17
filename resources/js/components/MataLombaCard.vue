<script setup>
defineProps({
  title: { type: String, required: true },
  slug: { type: String, default: '' },
});
</script>

<template>
  <div
    class="mata-lomba-card rounded-xl border-2 border-scout-tan/40 bg-white p-5 shadow-sm transition-all duration-200 hover:border-scout-brown/50 hover:shadow-md"
  >
    <div class="flex items-start gap-3">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-scout-brown text-scout-gold font-bold">
        {{ title.charAt(0) }}
      </div>
      <div>
        <h3 class="font-semibold text-scout-brown">{{ title }}</h3>
        <p class="mt-1 text-sm text-scout-brown/70">Mata lomba LT-I Spencerone Camp 2026</p>
      </div>
    </div>
  </div>
</template>
