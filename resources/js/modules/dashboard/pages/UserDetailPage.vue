<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader title="Detail Pengguna" description="Informasi profil pasien lebih lengkap." backTo="/profil" />

    <main class="space-y-3 px-4 pt-4">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm" v-for="item in displayItems" :key="item.label">
        <p class="text-xs text-slate-500">{{ item.label }}</p>
        <p class="text-base font-semibold text-slate-900">{{ item.value || '-' }}</p>
      </article>

      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm space-y-3">
        <p class="text-sm font-semibold text-slate-900">Edit Kontak & BPJS</p>

        <div>
          <label class="text-xs text-slate-500">No. HP</label>
          <input v-model="form.phone" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5" type="text" placeholder="08xxxxxxxxxx" />
        </div>

        <div>
          <label class="text-xs text-slate-500">Email</label>
          <input v-model="form.email" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5" type="email" placeholder="email@contoh.id" />
          <button type="button" class="mt-2 rounded-lg bg-slate-800 px-3 py-2 text-xs font-semibold text-white disabled:opacity-60" :disabled="sendingOtp || !form.email" @click="sendOtp">
            {{ sendingOtp ? 'Mengirim OTP...' : 'Kirim OTP Email' }}
          </button>
        </div>

        <div>
          <label class="text-xs text-slate-500">OTP Email (wajib jika ganti email)</label>
          <input v-model="form.email_otp" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5" type="text" maxlength="6" placeholder="6 digit OTP" />
        </div>

        <div>
          <label class="text-xs text-slate-500">No. Kartu BPJS</label>
          <input v-model="form.bpjs_number" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5" type="text" placeholder="Nomor BPJS" />
          <p class="mt-1 text-[11px] text-slate-500">Diambil otomatis dari data pasien/family jika tersedia.</p>
        </div>

        <button type="button" class="w-full rounded-xl bg-teal-600 px-4 py-3 text-sm font-semibold text-white disabled:opacity-70" :disabled="saving" @click="saveProfile">
          {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>
      </article>
    </main>

    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'
import { useNotify } from '@/utils/useNotify'

const { success, error } = useNotify()
const loading = ref(false)
const sendingOtp = ref(false)
const saving = ref(false)
const profile = ref(null)

const form = reactive({ phone: '', email: '', email_otp: '', bpjs_number: '' })

const displayItems = computed(() => {
  const p = profile.value || {}
  return [
    { label: 'Nama Lengkap', value: p.name },
    { label: 'No. Rekam Medis', value: p.norm },
    { label: 'NIK', value: p.nik },
    { label: 'No. HP', value: p.phone },
    { label: 'Email', value: p.email },
    { label: 'No. Kartu BPJS', value: p.bpjs_number },
    { label: 'Tanggal Lahir', value: p.patient?.birth_date },
    { label: 'Alamat', value: p.patient?.address }
  ]
})

const loadProfile = async () => {
  loading.value = true
  try {
    const { data } = await axios.get('/api/v1/patients/profile')
    profile.value = data
    form.phone = data.phone || ''
    form.email = data.email || ''
    form.bpjs_number = data.bpjs_number || ''
  } catch (e) {
    error('Gagal memuat profil pengguna')
  } finally {
    loading.value = false
  }
}

const sendOtp = async () => {
  sendingOtp.value = true
  try {
    await axios.post('/api/v1/profile/email-otp', {
      email: form.email,
      purpose: 'profile_update'
    })
    success('OTP berhasil dikirim ke email')
  } catch (e) {
    error(e.response?.data?.message || 'Gagal mengirim OTP email')
  } finally {
    sendingOtp.value = false
  }
}

const saveProfile = async () => {
  saving.value = true
  try {
    const payload = {
      phone: form.phone || null,
      email: form.email || null,
      bpjs_number: form.bpjs_number || null
    }

    if (form.email && profile.value?.email !== form.email) {
      payload.email_otp = form.email_otp
    }

    const { data } = await axios.put('/api/v1/patients/profile', payload)
    profile.value = data
    form.email_otp = ''
    success('Profil berhasil diperbarui')
  } catch (e) {
    error(e.response?.data?.message || 'Gagal menyimpan profil')
  } finally {
    saving.value = false
  }
}

onMounted(loadProfile)
</script>