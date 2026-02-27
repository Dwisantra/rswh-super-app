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
            @click="searchMode = mode; form.norm = ''"
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
          v-model.trim="form.norm" 
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
          {{ loading ? 'Sedang Mencari...' : 'Cek Data Pasien' }}
        </button>
      </article>

      <article v-if="checked" class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-center">
        <p class="text-2xl">⚠️</p>
        <p class="mt-2 text-xl font-bold text-slate-900">Data tidak ditemukan</p>
      </article>

      <article v-if="matchedPatient" class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
        <p class="text-sm text-emerald-700 font-medium">Data ditemukan</p>
        <p class="mt-1 text-lg font-bold text-slate-900">{{ matchedPatient.name }}</p>

        <div class="mt-4">
          <label class="mb-1 block text-sm font-semibold text-slate-700">Hubungan Keluarga</label>
          <select 
            v-model="form.relation" 
            class="w-full rounded-xl border border-emerald-200 px-3 py-3 text-base bg-white focus:border-teal-500 outline-none"
          >
            <option value="" disabled>Pilih Hubungan</option>
            <option 
              v-for="opt in shdkOptions" 
              :key="opt.ID" 
              :value="opt.DESKRIPSI"
            >
              {{ opt.DESKRIPSI }}
            </option>
          </select>
        </div>

        <button
          class="mt-4 w-full rounded-xl px-4 py-3 text-base font-semibold text-white"
          :class="(isDuplicate || !form.relation) ? 'bg-slate-300' : 'bg-emerald-600'"
          :disabled="isDuplicate || !form.relation || loading"
          @click="saveMember"
        >
          {{ loading ? 'Menyimpan...' : (isDuplicate ? 'Sudah tersimpan' : 'Simpan ke Keluarga') }}
        </button>
      </article>
    </main>
  </div>
</template>

<script setup>
import { onMounted, computed, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { useAlert } from '@/utils/useAlert';

const { error, success, warning, info } = useAlert();
const router = useRouter()
const form = reactive({ relation: '', norm: '', birthDate: '' })
const searchMode = ref('NORM') // Default pilihan: NORM
const checked = ref(false)
const matchedPatient = ref(null)
const loading = ref(false)
const familyMembers = ref([])

const shdkOptions = ref([]);
const fetchShdk = async () => {
  try {
    const res = await axios.get('/api/v1/patients/shdk');
    shdkOptions.value = res.data.data || [];
  } catch (err) {
    console.error("Gagal memuat data SHDK", err);
  }
};

onMounted(async () => { // <--- Tambahkan async di sini
  fetchShdk();
  try {
    const res = await axios.get('/api/v1/patients/family');
    familyMembers.value = res.data; 
    
    if (res.data.length > 0) {
      info('Anda sudah memiliki data keluarga tersimpan.');
    }
  } catch (err) {
    console.error("Gagal cek data keluarga", err);
  }
});

const checkPatient = async () => {
  if (!form.norm) {
    error(`Masukkan ${searchMode.value}`);
    return;
  }

  // Siapkan params berdasarkan mode yang dipilih
  let searchParams = {};
  if (searchMode.value === 'NIK') {
    searchParams = { NIK: form.norm };
  } else if (searchMode.value === 'NORM') {
    if (!form.birthDate) {
      error('Tanggal Lahir wajib untuk No. RM');
      return;
    }
    searchParams = { NORM: form.norm, TANGGAL_LAHIR: form.birthDate };
  } else {
    searchParams = { KAP: form.norm };
  }

  loading.value = true;
  matchedPatient.value = null;

  try {
    const response = await axios.get('/api/v1/patients/search', { params: searchParams });
    const result = response.data;

    console.log(result);
    
    
    if (result.success && result.total > 0) {
      matchedPatient.value = extractPatientData(result.data[0]); 
      
      form.relation = '';
      checked.value = false;
      info('Pasien ditemukan!');
    } else {
      checked.value = true;
      error('Data tidak ditemukan');
    }
  } catch (err) {
    error(err.response?.data?.message || 'Gagal terhubung ke server');
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
  form.norm = val;
}

const isDuplicate = computed(() => {
  if (!matchedPatient.value) return false
  return familyMembers.value.some(
    (item) => item.mrn === matchedPatient.value.norm
  )
})

const extractPatientData = (dataPasien) => {
  const identitas = dataPasien.KARTUIDENTITAS?.find(k => k.JENIS === '1');
  
  return {
    name: dataPasien.NAMA,
    panggilan: dataPasien.PANGGILAN,
    gelar_depan: dataPasien.GELAR_DEPAN,
    gelar_belakang: dataPasien.GELAR_BELAKANG,
    norm: dataPasien.NORM,
    nik: identitas ? identitas.NOMOR : (dataPasien.NIK || ''),
    birth_date: dataPasien.TANGGAL_LAHIR?.split(' ')[0],
    birth_place: dataPasien.REFERENSI?.TEMPATLAHIR?.DESKRIPSI || dataPasien.TEMPAT_LAHIR,
    gender: dataPasien.REFERENSI?.JENISKELAMIN?.DESKRIPSI || dataPasien.JENIS_KELAMIN,
    religion: dataPasien.REFERENSI?.AGAMA?.DESKRIPSI,
    address: dataPasien.ALAMAT,
    occupation: dataPasien.REFERENSI?.PEKERJAAN?.DESKRIPSI,
    marital_status: dataPasien.REFERENSI?.STATUS_PERKAWINAN?.DESKRIPSI
  };
};

const saveMember = async () => {
  if (!matchedPatient.value || !form.relation) {
    warning('Pilih hubungan keluarga terlebih dahulu');
    return;
  }
  
  loading.value = true;
  try {
    await axios.post('/api/v1/patients/family', {
      ...matchedPatient.value, 
      relation: form.relation
    });
    
    success('Data pasien berhasil disimpan ke akun Anda');
    router.push('/keluarga');
  } catch (err) {
    error(err.response?.data?.message || 'Gagal menyimpan data');
  } finally {
    loading.value = false;
  }
};

const formatDateDisplay = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(date)
}

const hasKepalaKeluarga = computed(() => {
  return false; 
});
</script>
