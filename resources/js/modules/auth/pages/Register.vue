<template>
  <div class="min-h-screen flex items-center justify-center px-4 py-8 bg-slate-50">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-sm border border-slate-200/80 p-8">
      <header class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800 tracking-tight">Daftar sebagai Pasien</h1>
        <p class="text-slate-500 text-sm mt-1">Buat akun untuk mengakses layanan</p>
      </header>

      <form class="space-y-5" @submit.prevent="submit">
        <div class="space-y-1.5">
          <label for="name" class="block text-sm font-medium text-slate-700">Nama lengkap</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            autocomplete="name"
            class="w-full px-4 py-2.5 rounded-xl border bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors"
            :class="errors.name ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200'"
            placeholder="Nama Anda"
          />
          <p v-if="errors.name" class="text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <div class="space-y-1.5">
          <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            autocomplete="email"
            class="w-full px-4 py-2.5 rounded-xl border bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors"
            :class="errors.email ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200'"
            placeholder="nama@email.com"
          />
          <p v-if="errors.email" class="text-sm text-red-600">{{ errors.email }}</p>
        </div>

        <div class="space-y-1.5">
          <label for="password" class="block text-sm font-medium text-slate-700">Kata sandi</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            autocomplete="new-password"
            class="w-full px-4 py-2.5 rounded-xl border bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors"
            :class="errors.password ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200'"
            placeholder="Minimal 8 karakter"
          />
          <p v-if="errors.password" class="text-sm text-red-600">{{ errors.password }}</p>
        </div>

        <div class="space-y-1.5">
          <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi kata sandi</label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            autocomplete="new-password"
            class="w-full px-4 py-2.5 rounded-xl border bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-colors"
            :class="errors.password_confirmation ? 'border-red-300 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200'"
            placeholder="Ulangi kata sandi"
          />
          <p v-if="errors.password_confirmation" class="text-sm text-red-600">{{ errors.password_confirmation }}</p>
        </div>

        <p v-if="errors.submit" class="text-sm text-red-600">{{ errors.submit }}</p>

        <button
          type="submit"
          class="w-full py-2.5 rounded-xl font-medium bg-sky-600 text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 disabled:opacity-50 disabled:pointer-events-none transition-colors"
          :disabled="loading"
        >
          {{ loading ? 'Memproses...' : 'Daftar' }}
        </button>
      </form>

      <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
          <span class="w-full border-t border-slate-200" />
        </div>
        <div class="relative flex justify-center text-sm text-slate-500">
          <span class="bg-white px-3">atau</span>
        </div>
      </div>

      <footer class="mt-6 text-center text-sm text-slate-600">
        Sudah punya akun?
        <router-link to="/login" class="text-sky-600 hover:text-sky-700 font-medium transition-colors">Masuk</router-link>
      </footer>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  submit: ''
})

function clearErrors() {
  errors.name = ''
  errors.email = ''
  errors.password = ''
  errors.password_confirmation = ''
  errors.submit = ''
}

async function submit() {
  clearErrors()

  if (!form.name.trim()) {
    errors.name = 'Nama lengkap wajib diisi'
    return
  }
  if (!form.email.trim()) {
    errors.email = 'Email wajib diisi'
    return
  }
  if (!form.password) {
    errors.password = 'Kata sandi wajib diisi'
    return
  }
  if (form.password.length < 8) {
    errors.password = 'Kata sandi minimal 8 karakter'
    return
  }
  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi kata sandi tidak cocok'
    return
  }

  loading.value = true
  try {
    const { data } = await axios.post('/api/register', {
      name: form.name.trim(),
      email: form.email.trim(),
      password: form.password,
      password_confirmation: form.password_confirmation
    })
    if (data.token) {
      localStorage.setItem('auth_token', data.token)
      axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
    }
    router.push('/')
  } catch (e) {
    const errs = e.response?.data?.errors
    if (errs) {
      if (errs.name) errors.name = Array.isArray(errs.name) ? errs.name[0] : errs.name
      if (errs.email) errors.email = Array.isArray(errs.email) ? errs.email[0] : errs.email
      if (errs.password) errors.password = Array.isArray(errs.password) ? errs.password[0] : errs.password
    }
    errors.submit = e.response?.data?.message || 'Pendaftaran gagal. Coba lagi.'
  } finally {
    loading.value = false
  }
}
</script>
