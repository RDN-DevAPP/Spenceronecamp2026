<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  username: '',
  password: '',
  remember: false,
});

const submit = () => form.post('/login');
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-scout-cream px-4">
    <div class="w-full max-w-sm rounded-2xl border border-scout-tan/40 bg-white p-8 shadow-lg">
      <div class="text-center">
        <h1 class="text-2xl font-bold text-scout-brown">Login Sistem</h1>
        <p class="mt-1 text-sm text-scout-brown/70">LT-I Spencerone Camp 2026</p>
      </div>
      <form @submit.prevent="submit" class="mt-8 space-y-5">
        <div v-if="form.errors.username" class="rounded-lg bg-red-50 p-3 text-sm text-red-700">
          {{ form.errors.username }}
        </div>
        <div>
          <label for="username" class="block text-sm font-medium text-scout-brown">Username</label>
          <input
            id="username"
            v-model="form.username"
            type="text"
            autocomplete="username"
            required
            class="mt-1 block w-full rounded-lg border border-scout-tan/60 bg-white px-4 py-2.5 text-scout-brown placeholder-scout-brown/50 focus:border-scout-brown focus:ring-2 focus:ring-scout-brown/20"
            placeholder="Masukkan username"
          />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-scout-brown">Password</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            autocomplete="current-password"
            required
            class="mt-1 block w-full rounded-lg border border-scout-tan/60 bg-white px-4 py-2.5 text-scout-brown placeholder-scout-brown/50 focus:border-scout-brown focus:ring-2 focus:ring-scout-brown/20"
            placeholder="Masukkan password"
          />
        </div>
        <div class="flex items-center">
          <input
            id="remember"
            v-model="form.remember"
            type="checkbox"
            class="h-4 w-4 rounded border-scout-tan text-scout-brown focus:ring-scout-brown"
          />
          <label for="remember" class="ml-2 text-sm text-scout-brown/80">Ingat saya</label>
        </div>
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full rounded-lg bg-scout-brown py-3 font-semibold text-white transition hover:bg-scout-brown/90 disabled:opacity-70"
        >
          <span v-if="form.processing">Memproses...</span>
          <span v-else>Masuk</span>
        </button>
      </form>
      <p class="mt-6 text-center text-sm text-scout-brown/60">
        <a href="/" class="underline hover:text-scout-brown">Kembali ke Beranda</a>
      </p>
    </div>
  </div>
</template>
