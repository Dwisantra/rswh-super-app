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
          <div class="mt-1 flex rounded-xl border border-slate-200 focus-within:border-sky-500">
            <input v-model="form.password" :type="showPassword ? 'text' : 'password'" placeholder="••••••••" class="w-full rounded-l-xl px-4 py-2.5 focus:outline-none" />
            <button type="button" class="px-3 text-xs font-medium text-slate-600" @click="showPassword = !showPassword">
              <font-awesome-icon v-if="showPassword" icon="eye" />
              <font-awesome-icon v-else icon="eye-slash" />
            </button>
          </div>
        </div>
        <p v-if="error" class="text-sm text-red-600">{{ error }}</p>

        <button :disabled="loading" class="w-full rounded-xl bg-sky-600 px-4 py-2.5 font-medium text-white hover:bg-sky-700 disabled:opacity-70">
          {{ loading ? 'Memproses...' : 'Masuk' }}
        </button>
      </form>

      <div class="my-5 flex items-center gap-3 text-xs text-slate-400">
        <span class="h-px flex-1 bg-slate-200"></span>
        <span>atau</span>
        <span class="h-px flex-1 bg-slate-200"></span>
      </div>

      <div
        ref="googleButtonRef"
        class="flex justify-center"
      ></div>
      <p v-if="!googleClientId" class="mt-2 text-xs text-amber-600">
        Google SSO belum aktif. Set <code>VITE_GOOGLE_CLIENT_ID</code> di frontend.
      </p>

      <p class="mt-5 text-center text-sm text-slate-600">
        Belum punya akun?
        <router-link to="/register" class="font-medium text-sky-600">Daftar</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(false)
const googleButtonRef = ref(null)
const error = ref('')
const showPassword = ref(false)
const googleClientId = import.meta.env.VITE_GOOGLE_CLIENT_ID || ''

const form = reactive({
  identifier: '',
  password: ''
})

const applyLogin = (token) => {
  localStorage.setItem('token', token)
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

async function submit() {
  error.value = ''
  loading.value = true

  try {
    const { data } = await axios.post('/api/login', form)
    applyLogin(data.token)
    window.location.href = '/'
  } catch (e) {
    error.value = e.response?.data?.message || 'Login gagal, periksa kembali data Anda.'
  } finally {
    loading.value = false
  }
}

const loadGoogleScript = () => new Promise((resolve, reject) => {
  if (window.google?.accounts?.id) {
    resolve()
    return
  }

  const existing = document.querySelector('script[data-google-gsi="1"]')

  if (existing) {
    existing.addEventListener('load', resolve, { once: true })
    existing.addEventListener('error', reject, { once: true })
    return
  }

  const script = document.createElement('script')
  script.src = 'https://accounts.google.com/gsi/client'
  script.async = true
  script.defer = true
  script.dataset.googleGsi = '1'
  script.onload = resolve
  script.onerror = reject
  document.head.appendChild(script)
})

const handleGoogleCredential = async ({ credential }) => {
  if (!credential) {
    error.value = 'Credential Google tidak ditemukan.'
    return
  }

  try {
    const { data } = await axios.post('/api/auth/google', {
      credential: credential
    })

    applyLogin(data.token)
    
    if (data.isProfileGoogle) {
        window.location.href = '/'
    } else {
        window.location.href = '/'
    }
  } catch (e) {
    error.value = e.response?.data?.message || 'Login Google gagal. Coba lagi.'
  }
}

onMounted(async () => {
  if (!googleClientId) {
    return
  }

  try {
    await loadGoogleScript()

    if (!window.google?.accounts?.id || !googleButtonRef.value) {
      return
    }

    window.google.accounts.id.initialize({
      client_id: googleClientId,
      callback: handleGoogleCredential,
      auto_select: false
    })

    window.google.accounts.id.renderButton(googleButtonRef.value, {
      theme: 'outline',
      size: 'large',
      text: 'signin_with',
      shape: 'rectangular',
      width: 320
    })

    google.accounts.id.prompt((notification) => {
      console.log(notification);
  });
  } catch (_) {
    // SDK gagal dimuat, fallback ke login biasa
  }
})
</script>
