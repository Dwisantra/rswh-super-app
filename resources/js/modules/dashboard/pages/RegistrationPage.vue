<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-24">
    <PageHeader title="Pendaftaran" description="Registrasi pasien." backTo="/" />

    <main class="space-y-3 px-4 pt-4">
      <StepProgress :current-step="currentStep" :steps="steps" />

      <section v-if="currentStep === 0" class="space-y-3">
        <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
          <label class="mb-1 block text-sm text-slate-600">Pilih Pasien</label>
          <select v-model="form.selectedNorm" class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base">
            <option value="">Pilih Pasien</option>
            <option v-for="member in familyMembers" :key="member.id" :value="member.norm">
              {{ member.name }} ({{ member.relation }})
            </option>
          </select>
          <p v-if="errors.selectedNorm" class="mt-2 text-xs text-rose-600">{{ errors.selectedNorm }}</p>
        </article>

        <StepRoom v-model="form.roomId" :clinic="clinic" :error="errors.roomId" />
      </section>

      <section v-if="currentStep === 1">
        <StepDate v-model="form.visitDate" :min="minDate" :max="maxDate" :error="errors.visitDate"/>
        <div v-if="loadingDoctors" class="mt-2 text-center text-xs text-slate-500 animate-pulse">
          Mengecek ketersediaan jadwal...
        </div>
      </section>

      <StepDoctor v-if="currentStep === 2" v-model="form.doctorId" :doctors="doctors" :error="errors.doctorId" />

      <section v-if="currentStep === 3" class="space-y-3">
        <StepPayment v-model="form.paymentMethodId" :methods="paymentMethods" :error="errors.paymentMethodId" />
        
        <article v-if="isBpjs" class="rounded-2xl border border-blue-100 bg-blue-50 p-4 shadow-sm">
          <label class="mb-1 block text-xs font-semibold text-blue-700">Nomor Rujukan / Surat Kontrol</label>
          <input 
            v-model="form.noRujukan" 
            type="text" 
            placeholder="Contoh: 1234B0010123..." 
            class="w-full rounded-xl border border-blue-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <p class="mt-1 text-[10px] text-blue-500">*Wajib diisi untuk validasi BPJS</p>
        </article>
      </section>

      <div class="grid grid-cols-2 gap-2 pt-4">
        <button 
          class="rounded-xl bg-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 disabled:opacity-50" 
          :disabled="currentStep === 0" 
          @click="previousStep"
        >
          Kembali
        </button>
        
        <button 
          v-if="!isNextHidden"
          class="rounded-xl px-4 py-3 text-sm font-semibold text-white transition-colors" 
          :class="currentStep === steps.length - 1 ? 'bg-teal-600' : 'bg-blue-600'" 
          @click="nextStep"
        >
          {{ currentStep === steps.length - 1 ? 'Konfirmasi' : 'Lanjut' }}
        </button>
      </div>
    </main>
    <MobileBottomNav />
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import axios from 'axios'
import MobileBottomNav from '../components/MobileBottomNav.vue'
import PageHeader from '../components/Header.vue'
import StepProgress from './registration/StepProgress.vue'
import StepRoom from './registration/StepClinic.vue'
import StepDate from './registration/StepDate.vue'
import StepDoctor from './registration/StepDoctor.vue'
import StepPayment from './registration/StepPayment.vue'

const steps = ['Poli', 'Tanggal', 'Dokter', 'Bayar']
const currentStep = ref(0)
const familyMembers = ref([])
const clinic = ref([])
const doctors = ref([])
const paymentMethods = ref([])
const loadingDoctors = ref(false)
const minDate = new Date().toISOString().split('T')[0]

const form = reactive({
  selectedNorm: '',
  roomId: '',
  visitDate: '',
  doctorId: '',
  paymentMethodId: '',
  noRujukan: ''
})

const errors = reactive({
  selectedNorm: '',
  roomId: '',
  visitDate: '',
  doctorId: '',
  paymentMethodId: ''
})

const maxDate = computed(() => {
  const date = new Date()
  date.setDate(date.getDate() + 30)
  return date.toISOString().split('T')[0]
})

const isBpjs = computed(() => form.paymentMethodId === '2')
const selectedRoom = computed(() => clinic.value.find((room) => room.ID === form.roomId))

const isNextHidden = computed(() => {
  if (currentStep.value === 1) {
    return loadingDoctors.value || doctors.value.length === 0 || !form.visitDate
  } else if (currentStep.value === 2) {
    return !form.doctorId
  } else if (currentStep.value === 3) {
    return !form.paymentMethodId || (isBpjs.value && !form.noRujukan)
  }
  return false
})

const dayCodeFromDate = (dateString) => {
  const date = new Date(dateString)
  const day = date.getDay()
  return day === 0 ? 7 : day
}

// Fetching Data
const fetchDoctors = async () => {
  if (!selectedRoom.value || !form.visitDate) return

  loadingDoctors.value = true
  errors.visitDate = ''
  doctors.value = []

  const poliCode = selectedRoom.value?.REFERENSI?.PENJAMIN?.RUANGAN_PENJAMIN
  const dayCode = dayCodeFromDate(form.visitDate)

  try {
    const { data } = await axios.get('/api/v1/regonline/doctor-schedules-by-clinic', {
      params: { poli: poliCode, day_code: dayCode }
    })
    const datax = data.data
    doctors.value = Array.isArray(datax) ? datax : []
    
    if (doctors.value.length === 0) {
      errors.visitDate = 'Tidak ada jadwal dokter untuk tanggal ini.'
    }
  } catch (err) {
    errors.visitDate = 'Gagal memuat jadwal.'
  } finally {
    loadingDoctors.value = false
  }
}

const fetchFamily = async () => {
  try {
    const res = await axios.get('/api/v1/patients/family')
    familyMembers.value = res.data
    
    const defaultPatient = res.data.find((p) => p.is_default)
    
    if (defaultPatient) {
      form.selectedNorm = defaultPatient.norm
    }
  } catch (err) {
    console.error("Gagal memuat pasien", err)
  }
}

watch(() => form.visitDate, (newVal) => {
  if (newVal) fetchDoctors()
})

const validateCurrentStep = () => {
  Object.keys(errors).forEach((key) => { errors[key] = '' })

  if (currentStep.value === 0) {
    if (!form.selectedNorm) errors.selectedNorm = 'Pasien harus dipilih.'
    if (!form.roomId) errors.roomId = 'Poli harus dipilih.'
    return !errors.selectedNorm && !errors.roomId
  }
  return true
}

const nextStep = async () => {
  if (!validateCurrentStep()) return
  
  if (currentStep.value === 2) {
    const ruanganPenjamin = selectedRoom.value?.REFERENSI?.PENJAMIN?.RUANGAN_PENJAMIN
    const { data } = await axios.get('/api/v1/regonline/payment-methods', {
      params: { ruangan_penjamin: ruanganPenjamin }
    })
    paymentMethods.value = Array.isArray(data.data) ? data.data : []
  }

  if (currentStep.value < steps.length - 1) {
    currentStep.value += 1
  } else {
    submitFinal()
  }
}

const previousStep = () => {
  if (currentStep.value > 0) currentStep.value -= 1
}

const submitFinal = () => {
  alert('Pendaftaran Berhasil Dikirim!')
}

onMounted(async () => {
  // Fetch awal untuk pasien dan klinik  
  fetchFamily()
  const [resFamily, resClinic] = await Promise.all([
    axios.get('/api/v1/patients/family'),
    axios.get('/api/v1/regonline/clinic')
  ])
  familyMembers.value = resFamily.data
  const allClinics = resClinic.data.data

  const excludedClinics = [
    '101010123', 
    '101010119', 
    '101010125',
    '101010124',
    '101010122',
  ]
  
  clinic.value = allClinics.filter(item => {
    return !excludedClinics.includes(item.ID)
  })
})
</script>