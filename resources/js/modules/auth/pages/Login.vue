<template>
  <div class="min-h-screen flex items-center justify-center p-5 bg-gradient-to-b from-sky-50 to-white">
    <div class="w-full max-w-md rounded-3xl border border-sky-100 bg-white p-7 shadow-lg shadow-sky-100/60">
      <h1 class="text-2xl font-semibold text-slate-800">Aplikasi Pasien RS</h1>
      <p class="mt-1 text-sm text-slate-500">Masuk dengan NIK / No RM / Email</p>

      <form class="mt-6 space-y-4" @submit.prevent="submit">
        <div>
          <label class="text-sm font-medium text-slate-700">Identitas Login</label>
          <input v-model="form.identifier" type="text" placeholder="NIK / No RM / Email" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Kata Sandi</label>
          <input v-model="form.password" type="password" placeholder="••••••••" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <p v-if="error" class="text-sm text-red-600">{{ error }}</p>

        <button :disabled="loading" class="w-full rounded-xl bg-sky-600 px-4 py-2.5 font-medium text-white hover:bg-sky-700 disabled:opacity-70">
          {{ loading ? 'Memproses...' : 'Masuk' }}
        </button>
      </form>

      <p class="mt-5 text-center text-sm text-slate-600">
        Belum punya akun?
        <router-link to="/register" class="font-medium text-sky-600">Register Pasien</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)
const error = ref('')

const form = reactive({
  identifier: '',
  password: ''
})

async function submit() {
  error.value = ''
  loading.value = true

  try {
    const { data } = await axios.post('/api/login', form)
    localStorage.setItem('auth_token', data.token)
    axios.defaults.headers.common.Authorization = `Bearer ${data.token}`
    router.push('/')
  } catch (e) {
    error.value = e.response?.data?.message || 'Login gagal, periksa kembali data Anda.'
  } finally {
    loading.value = false
  }
}
</script>
