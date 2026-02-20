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
          <input v-model="form.nik" type="text" maxlength="16" inputmode="numeric" @input="onlyNumberNIK" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">No RM (opsional)</label>
          <input v-model="form.norm" type="text" maxlength="6" inputmode="numeric" @input="limitNorm" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Kode Keluarga (opsional)</label>
          <input v-model="form.family_code" type="text" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Email</label>
          <input v-model="form.email" type="email" class="mt-1 w-full rounded-xl border border-slate-200 px-4 py-2.5 focus:border-sky-500 focus:outline-none" />
          <button
            v-if="form.email"
            type="button"
            :disabled="otpLoading"
            class="mt-2 w-full rounded-xl border border-sky-300 px-3 py-2 text-sm font-medium text-sky-700 hover:bg-sky-50 disabled:opacity-70"
            @click="sendOtp"
          >
            {{ otpLoading ? 'Mengirim OTP...' : 'Kirim OTP ke Email' }}
          </button>
          <p v-if="otpInfo" class="mt-1 text-xs text-emerald-600">{{ otpInfo }}</p>
        </div>
        <div v-if="form.email" class="space-y-2">
          <label class="text-sm font-medium text-slate-700 text-center block">Kode OTP Email</label>
          
          <div class="flex justify-between gap-2">
            <input
              v-for="(digit, index) in 6"
              :key="index"
              ref="otpInputs"
              v-model="otpArray[index]"
              type="text"
              maxlength="1"
              inputmode="numeric"
              @input="handleOtpInput(index, $event)"
              @keydown="handleOtpBackspace(index, $event)"
              class="w-12 h-14 text-center text-xl font-bold rounded-xl border border-slate-200 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 focus:outline-none transition-all"
            />
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Kata Sandi</label>
          <div class="mt-1 flex rounded-xl border border-slate-200 focus-within:border-sky-500">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              class="w-full rounded-l-xl px-4 py-2.5 focus:outline-none"
            />
            <button type="button" class="px-3 text-xs font-medium text-slate-600" @click="showPassword = !showPassword">
              <font-awesome-icon v-if="showPassword" icon="eye" />
              <font-awesome-icon v-else icon="eye-slash" />
            </button>
          </div>
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
          <div class="mt-1 flex rounded-xl border border-slate-200 focus-within:border-sky-500">
            <input
              v-model="form.password_confirmation"
              :type="showPasswordConfirmation ? 'text' : 'password'"
              class="w-full rounded-l-xl px-4 py-2.5 focus:outline-none"
            />
            <button type="button" class="px-3 text-xs font-medium text-slate-600" @click="showPasswordConfirmation = !showPasswordConfirmation">
              <font-awesome-icon v-if="showPasswordConfirmation" icon="eye" />
              <font-awesome-icon v-else icon="eye-slash" />
            </button>
          </div>
        </div>

        <p v-if="error" class="md:col-span-2 text-sm text-red-600">{{ error }}</p>

        <button :disabled="loading" class="md:col-span-2 w-full rounded-xl bg-sky-600 px-4 py-2.5 font-medium text-white hover:bg-sky-700 disabled:opacity-70">
          {{ loading ? 'Mendaftarkan...' : 'Daftar & Masuk' }}
        </button>
      </form>

      <p class="mt-5 text-center text-sm text-slate-600">
        Sudah punya akun?
        <router-link to="/login" class="font-medium text-sky-600">Masuk</router-link>
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
const otpLoading = ref(false)
const error = ref('')
const otpInfo = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(showPassword)

const otpArray = ref(['', '', '', '', '', ''])
const otpInputs = ref([])

const form = reactive({
  name: '',
  email: '',
  email_otp: '',
  nik: '',
  norm: '',
  family_code: '',
  password: '',
  password_confirmation: ''
})

function onlyNumberNIK(e) {
  form.nik = e.target.value
    .replace(/\D/g, '')
    .slice(0, 16)
}

function limitNorm(e) {
  form.norm = e.target.value
    .replace(/\D/g, '')
    .slice(0, 6)
}

function onlyNumberOtp(e) {
  form.email_otp = e.target.value
    .replace(/\D/g, '')
    .slice(0, 6)
}

async function sendOtp() {  
  otpLoading.value = true
  otpInfo.value = ''
  error.value = ''

  if (!form.name || form.name.trim() === '') {
    error.value = 'Nama Lengkap wajib diisi.'
    otpLoading.value = false
    return
  }

  try {
    const { data } = await axios.post('/api/register/email-otp', {
      email: form.email,
      name: form.name
    })

    otpInfo.value = data.message || 'OTP berhasil dikirim.'
  } catch (e) {
    const validation = e.response?.data?.errors
    if (validation) {
      error.value = Object.values(validation).flat().join(' ')
    } else {      
      error.value = e.response?.data?.message || 'Gagal mengirim OTP email.'
    }
  } finally {
    otpLoading.value = false
  }
}

async function submit() {
  loading.value = true
  error.value = ''

  try {
    const payload = {
      ...form,
      email: form.email || null,
      email_otp: form.email ? form.email_otp : null,
      norm: form.norm || null,
      family_code: form.family_code || null
    }
    const { data } = await axios.post('/api/register', payload)
    localStorage.setItem('auth_token', data.token)
    axios.defaults.headers.common.Authorization = `Bearer ${data.token}`
    router.push('/')
  } catch (e) {
    const validation = e.response?.data?.errors
    if (validation) {
      error.value = Object.values(validation).flat().join(' ')
    } else {
      console.warn(e);
      
      error.value = e.response?.data?.message || 'Registrasi gagal. Silakan coba lagi.'
    }
  } finally {
    loading.value = false
  }
}

function handleOtpInput(index, event) {
  const val = event.target.value
  otpArray.value[index] = val.replace(/\D/g, '').slice(-1)
  form.email_otp = otpArray.value.join('')
  if (val && index < 5) {
    otpInputs.value[index + 1].focus()
  }
}

function handleOtpBackspace(index, event) {
  if (event.key === 'Backspace' && !otpArray.value[index] && index > 0) {
    otpInputs.value[index - 1].focus()
  }
}
</script>
