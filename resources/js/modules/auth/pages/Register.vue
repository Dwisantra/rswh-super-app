<template>
  <div class="min-h-screen flex items-center justify-center p-5 bg-gradient-to-b from-sky-50 to-white">
    <div class="w-full max-w-xl rounded-3xl border border-sky-100 bg-white p-7 shadow-lg shadow-sky-100/60">
      <h1 class="text-2xl font-semibold text-slate-800">Register Pasien</h1>
      <p class="mt-1 text-sm text-slate-500">Gunakan NIK / No RM untuk koneksi ke SIMRS (Laminas).</p>

      <form class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2" @submit.prevent="submit">
        <div class="md:col-span-2">
          <label class="text-sm font-medium text-slate-700">Nama Lengkap</label>
          <input v-model="form.name" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">NIK</label>
          <input v-model="form.nik" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">No RM (opsional)</label>
          <input v-model="form.norm" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Kode Keluarga (opsional)</label>
          <input v-model="form.family_code" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Email (opsional)</label>
          <input v-model="form.email" type="email" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Kata Sandi</label>
          <input v-model="form.password" type="password" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
          <input v-model="form.password_confirmation" type="password" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>

        <p v-if="error" class="md:col-span-2 text-sm text-red-600">{{ error }}</p>

        <button :disabled="loading" class="md:col-span-2 w-full rounded-xl bg-sky-600 px-4 py-2.5 font-medium text-white hover:bg-sky-700 disabled:opacity-70">
          {{ loading ? 'Mendaftarkan...' : 'Register & Masuk' }}
        </button>
      </form>

      <p class="mt-5 text-center text-sm text-slate-600">
        Sudah punya akun?
        <router-link to="/login" class="font-medium text-sky-600">Login</router-link>
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
  name: '',
  email: '',
  nik: '',
  norm: '',
  family_code: '',
  password: '',
  password_confirmation: ''
})

async function submit() {
  error.value = ''
  loading.value = true

  try {
    const payload = { ...form, email: form.email || null, norm: form.norm || null, family_code: form.family_code || null }
    const { data } = await axios.post('/api/register', payload)
    localStorage.setItem('auth_token', data.token)
    axios.defaults.headers.common.Authorization = `Bearer ${data.token}`
    router.push('/')
  } catch (e) {
    const validation = e.response?.data?.errors
    if (validation) {
      error.value = Object.values(validation).flat().join(' ')
    } else {
      error.value = e.response?.data?.message || 'Registrasi gagal. Silakan coba lagi.'
    }
  } finally {
    loading.value = false
  }
}
</script>
