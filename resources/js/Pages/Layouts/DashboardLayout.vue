<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const sidebarOpen = ref(false);

const title = computed(() => {
  const name = page.component.split('/').pop() || '';
  if (name === 'Dashboard.vue' && page.url.startsWith('/juri')) return 'Dashboard Juri';
  if (name === 'Dashboard.vue' && page.url.startsWith('/peserta')) return 'Dashboard Peserta';
  return 'LT-I Spencerone';
});
</script>

<template>
  <div class="min-h-screen bg-scout-cream">
    <!-- Navbar (mobile: hamburger + title; desktop: sidebar) -->
    <nav class="sticky top-0 z-40 border-b border-scout-tan/30 bg-white shadow-sm">
      <div class="flex h-14 items-center justify-between px-4 sm:px-6">
        <div class="flex items-center gap-3">
          <button
            type="button"
            class="rounded-lg p-2 text-scout-brown hover:bg-scout-cream lg:hidden"
            @click="sidebarOpen = !sidebarOpen"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <span class="font-semibold text-scout-brown">{{ title }}</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-sm text-scout-brown/80">{{ user?.name }}</span>
          <form method="POST" action="/logout" class="inline">
            <input type="hidden" name="_token" :value="page.props.csrf_token || ''" />
            <button
              type="submit"
              class="rounded-lg px-3 py-1.5 text-sm font-medium text-scout-brown hover:bg-scout-tan/20"
            >
              Logout
            </button>
          </form>
        </div>
      </div>
    </nav>

    <!-- Mobile sidebar overlay -->
    <div
      v-show="sidebarOpen"
      class="fixed inset-0 z-30 bg-black/30 lg:hidden"
      @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar (desktop always visible; mobile drawer) -->
    <aside
      :class="[
        'fixed left-0 top-14 z-30 h-[calc(100vh-3.5rem)] w-64 border-r border-scout-tan/30 bg-white transition-transform lg:translate-x-0',
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
      ]"
    >
      <div class="flex h-full flex-col gap-1 p-4">
        <Link
          v-if="user?.role === 'juri' || user?.role === 'admin'"
          href="/juri/dashboard"
          class="rounded-lg px-4 py-2.5 text-sm font-medium text-scout-brown hover:bg-scout-tan/20"
          :class="{ 'bg-scout-brown text-white hover:bg-scout-brown': page.url.startsWith('/juri') }"
        >
          Dashboard Juri
        </Link>
        <Link
          v-if="user?.role === 'regu'"
          href="/peserta/dashboard"
          class="rounded-lg px-4 py-2.5 text-sm font-medium text-scout-brown hover:bg-scout-tan/20"
          :class="{ 'bg-scout-brown text-white hover:bg-scout-brown': page.url.startsWith('/peserta') }"
        >
          Dashboard Peserta
        </Link>
        <div class="mt-auto border-t border-scout-tan/30 pt-4">
          <Link href="/" class="block rounded-lg px-4 py-2 text-sm text-scout-brown/70 hover:bg-scout-tan/20">
            Beranda
          </Link>
        </div>
      </div>
    </aside>

    <!-- Main content -->
    <main class="lg:pl-64">
      <div class="p-4 sm:p-6">
        <slot />
      </div>
    </main>
  </div>
</template>
