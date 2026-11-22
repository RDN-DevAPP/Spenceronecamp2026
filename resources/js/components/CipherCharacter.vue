<template>
  <span
    class="inline-flex flex-col items-center justify-center select-none transition-all duration-150"
    :class="[
      containerClass,
      meta.isSpace ? 'opacity-30' : '',
      highlighted ? 'ring-2 ring-amber-400 bg-amber-50 scale-105 shadow-sm' : '',
    ]"
    :title="showLabel ? `${meta.char}` : undefined"
  >
    <img
      v-if="renderMode === 'img'"
      :src="meta.assetPath"
      :alt="meta.isSpace ? 'Spasi' : `Sandi ${meta.type}`"
      class="w-full h-full object-contain pointer-events-none"
      :class="dark ? 'invert' : ''"
      loading="lazy"
    />
    <span
      v-else-if="meta.svg"
      class="w-full h-full flex items-center justify-center [&>svg]:w-full [&>svg]:h-full"
      v-html="meta.svg"
    />
    <span
      v-else
      class="font-mono font-black leading-none flex items-center justify-center w-full h-full"
      :class="angkaTextClass"
    >
      {{ meta.numericCode ?? '' }}
    </span>

    <span
      v-if="showLabel && !meta.isSpace"
      class="text-[9px] font-black uppercase tracking-wider mt-0.5 leading-none"
      :class="dark ? 'text-amber-300' : 'text-emerald-800'"
    >
      {{ meta.char }}
    </span>
  </span>
</template>

<script setup>
import { computed } from 'vue';
import { getCipherMeta } from '../utils/sandiHelper';

const props = defineProps({
  type: {
    type: String,
    required: true,
    default: 'kotak-1',
  },
  char: {
    type: String,
    required: true,
    default: ' ',
  },
  size: {
    type: String,
    default: 'md', // 'xs' | 'sm' | 'md' | 'lg' | 'xl' | 'fill'
  },
  showLabel: {
    type: Boolean,
    default: false,
  },
  highlighted: {
    type: Boolean,
    default: false,
  },
  dark: {
    type: Boolean,
    default: false,
  },
  renderMode: {
    type: String,
    default: 'img', // 'img' uses /assets/sandi/{type}/{char}.svg; 'inline' uses SVG markup
  },
});

const meta = computed(() => getCipherMeta(props.type, props.char));

const containerClass = computed(() => {
  const sizeMap = {
    xs: 'w-6 h-6 p-0.5 rounded',
    sm: 'w-8 h-8 p-1 rounded-md',
    md: 'w-10 h-10 p-1.5 rounded-lg',
    lg: 'w-12 h-12 p-1.5 rounded-xl',
    xl: 'w-14 h-14 p-2 rounded-xl',
    fill: 'w-full h-full p-0.5 rounded',
  };
  return sizeMap[props.size] || sizeMap.md;
});

const angkaTextClass = computed(() => {
  const textMap = {
    xs: 'text-[10px]',
    sm: 'text-xs',
    md: 'text-sm',
    lg: 'text-base',
    xl: 'text-lg',
    fill: 'text-xs',
  };
  return textMap[props.size] || 'text-sm';
});
</script>
