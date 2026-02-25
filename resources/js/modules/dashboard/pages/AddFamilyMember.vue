<template>
  <div class="mx-auto min-h-screen max-w-md bg-slate-50 pb-10">
    <header class="bg-white px-4 pb-4 pt-6">
      <div class="flex items-center justify-between">
        <RouterLink to="/keluarga" class="back-btn">
          <font-awesome-icon icon="arrow-left" />
        </RouterLink>
        <h1 class="text-2xl font-bold text-slate-900">Tambah Keluarga</h1>
        <span class="w-6" />
      </div>
    </header>

    <main class="space-y-4 px-4 pt-3">
      <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="mb-6 flex rounded-xl bg-slate-100 p-1">
          <!-- 'KAP' bisa ditambahkan jika ingin cari berdasarkan nomor kartu asuransi -->
          <button 
            v-for="mode in ['NORM', 'NIK']"  
            :key="mode"
            @click="searchMode = mode; form.mrn = ''"
            class="flex-1 rounded-lg py-2 text-xs font-bold transition-all"
            :class="searchMode === mode ? 'bg-white text-teal-600 shadow-sm' : 'text-slate-500'"
          >
            {{ mode === 'KAP' ? 'KARTU' : mode }}
          </button>
        </div>

        <label class="mb-1 block text-sm text-slate-600">
          {{ searchMode === 'NORM' ? 'No. Rekam Medis' : searchMode === 'NIK' ? 'NIK (KTP)' : 'Nomor Kartu Asuransi' }}
        </label>
        
        <input 
          v-model.trim="form.mrn" 
          type="text" 
          :maxlength="searchMode === 'NIK' ? 16 : searchMode === 'NORM' ? 6 : 20" 
          @input="onlyNumber"
          class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base bg-white focus:border-teal-500 outline-none" 
          :placeholder="searchMode === 'NORM' ? 'Contoh: 001234' : searchMode === 'NIK' ? '16 Digit NIK' : 'Nomor Peserta'" 
        />

        <div v-if="searchMode === 'NORM'">
          <label class="mb-1 mt-4 block text-sm text-slate-600">Tanggal lahir</label>
          <div class="relative">
            <input 
              :value="formatDateDisplay(form.birthDate)"
              type="text"
              readonly
              placeholder="Pilih Tanggal Lahir"
              @click="$refs.dateInput.showPicker()"
              class="w-full rounded-xl border border-slate-200 px-3 py-3 text-base bg-white cursor-pointer focus:border-teal-500 outline-none" 
            />
            <input 
              ref="dateInput"
              v-model="form.birthDate" 
              type="date" 
              class="absolute inset-0 opacity-0 -z-10" 
            />
            <font-awesome-icon icon="calendar-days" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" />
          </div>
        </div>

        <button 
          class="mt-5 w-full rounded-xl bg-teal-600 px-4 py-3 text-base font-semibold text-white disabled:bg-slate-400" 
          @click="checkPatient"
          :disabled="loading"
        >
          {{ loading ? 'Mengecek...' : 'Cek Data Pasien' }}
        </button>
      </article>

      <article v-if="checked && !matchedPatient" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-center">
        <p class="text-2xl">⚠️</p>
        <p class="mt-2 text-xl font-bold text-slate-900">Data tidak ditemukan</p>
        <!-- <p class="mt-1 text-sm text-slate-600">Jika belum punya No RM, silakan daftar pasien baru terlebih dahulu.</p> -->
        <!-- <RouterLink
          class="mt-4 inline-block rounded-xl bg-teal-600 px-4 py-2 text-sm font-semibold text-white"
          :to="{ path: '/pasien-baru', query: { relation: form.relation, mrn: form.mrn, birthDate: form.birthDate } }"
        >
          Daftar Pasien Baru
        </RouterLink> -->
      </article>

      <article v-if="matchedPatient" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm text-emerald-700">Data ditemukan di SIMRS</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ matchedPatient.name }}</p>
        <p class="text-sm text-slate-600">No RM: {{ matchedPatient.mrn }} · NIK: {{ matchedPatient.nik }}</p>

        <p v-if="isDuplicate" class="mt-3 rounded-lg bg-amber-100 px-3 py-2 text-sm font-medium text-amber-700">
          Data pasien ini sudah tersimpan di daftar keluarga.
        </p>

        <button
          class="mt-4 w-full rounded-xl px-4 py-3 text-base font-semibold text-white"
          :class="isDuplicate ? 'bg-slate-300' : 'bg-emerald-600'"
          :disabled="isDuplicate"
          @click="saveMember"
        >
          {{ isDuplicate ? 'Sudah tersimpan' : 'Simpan ke Keluarga' }}
        </button>
      </article>
    </main>
  </div>
</template>

<script setup>
import { computed, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAlert } from '@/utils/useAlert';

const { error, success, warning, info } = useAlert();
const router = useRouter()
const form = reactive({ relation: '', mrn: '', birthDate: '' })
const searchMode = ref('NORM') // Default pilihan: NORM
const checked = ref(false)
const matchedPatient = ref(null)
const loading = ref(false)

const checkPatient = async () => {
  if (!form.mrn) {
    error(`Masukkan ${searchMode.value}`);
    return;
  }

  // Siapkan params berdasarkan mode yang dipilih
  let searchParams = {};
  if (searchMode.value === 'NIK') {
    searchParams = { NIK: form.mrn };
  } else if (searchMode.value === 'NORM') {
    if (!form.birthDate) {
      error('Tanggal Lahir wajib untuk No. RM');
      return;
    }
    searchParams = { NORM: form.mrn, TANGGAL_LAHIR: form.birthDate };
  } else {
    searchParams = { KAP: form.mrn };
  }

  loading.value = true;
  checked.value = true;
  matchedPatient.value = null;

  try {
    const response = await axios.get('/api/v1/regonline/patient/search', {
      params: searchParams
    });

    const result = response.data;
    
    if (result.success && result.total > 0) {
      const dataPasien = result.data[0];
      matchedPatient.value = {
        name: dataPasien.NAMA || dataPasien.name,
        mrn: dataPasien.NORM || dataPasien.mrn,
        nik: dataPasien.NIK || dataPasien.nik,
        birthDate: dataPasien.TANGGAL_LAHIR || dataPasien.birthDate
      };
      info('Pasien ditemukan!');
    } else {
      error('Data tidak ditemukan');
    }
  } catch (error) {
    const errorMsg = error.response?.data?.message || 'Gagal terhubung ke server';
    error(errorMsg);
  } finally {
    loading.value = false;
  }
}

const onlyNumber = (event) => {
  let val = event.target.value;
  // Jika NORM atau NIK, hanya boleh angka
  if (searchMode.value !== 'KAP') {
    val = val.replace(/[^0-9]/g, '');
  }
  form.mrn = val;
}

const getStoredMembers = () => {
  try {
    return JSON.parse(localStorage.getItem('family_members') || '[]')
  } catch (_) {
    return []
  }
}

const isDuplicate = computed(() => {
  if (!matchedPatient.value) return false
  return getStoredMembers().some(
    (item) => item.mrn === matchedPatient.value.mrn && item.birthDate === matchedPatient.value.birthDate
  )
})

const saveMember = () => {
  if (!matchedPatient.value || isDuplicate.value || !form.relation) return

  const saved = getStoredMembers()
  saved.push({
    ...matchedPatient.value,
    relation: form.relation
  })

  localStorage.setItem('family_members', JSON.stringify(saved))
  router.push('/keluarga')
}

const formatDateDisplay = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(date)
}
</script>
