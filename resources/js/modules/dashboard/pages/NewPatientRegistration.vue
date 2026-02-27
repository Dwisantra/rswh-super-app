<template>
  <div class="mx-auto min-h-screen max-w-md bg-cyan-50 pb-10">
    <PageHeader 
      title="Pendaftaran Pasien Baru"
      backTo="/keluarga"
    />

    <main class="px-4 pt-3">
      <div class="rounded-xl bg-cyan-100 p-3">
        <div class="flex items-center justify-between text-slate-800">
          <div v-for="n in [1, 2, 3]" :key="n" class="flex items-center gap-2">
            <span class="grid h-8 w-8 place-items-center rounded-full text-sm" :class="step >= n ? 'bg-sky-500 text-white' : 'bg-white text-slate-500'">{{ n }}</span>
            <span class="text-base">{{ stepLabels[n - 1] }}</span>
          </div>
        </div>
      </div>

      <section v-if="step === 1" class="mt-4 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
        <InputField label="Nama Lengkap" v-model="form.name" placeholder="Inputkan nama sesuai identitas" />
        <InputField label="Tempat Lahir" v-model="form.birthPlace" placeholder="Inputkan sesuai identitas" />
        <InputField label="Tgl. Lahir" type="date" v-model="form.birthDate" />
        <SelectField label="Jenis Kelamin" v-model="form.gender" :options="['Laki-laki', 'Perempuan']" />
        <InputField label="Nomor Selular" v-model="form.phone" placeholder="Inputkan nomor selular aktif" />
        <InputField label="Alamat Domisili" v-model="form.address" placeholder="Inputkan alamat domisili saat ini" multiline />
      </section>

      <section v-else-if="step === 2" class="mt-4 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
        <InputField label="Nomor KTP" v-model="form.nik" placeholder="Inputkan nomor sesuai identitas" />
        <SelectField label="Agama" v-model="form.religion" :options="['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Lainnya']" />
        <SelectField label="Pendidikan" v-model="form.education" :options="['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'Lainnya']" />
        <SelectField label="Pekerjaan" v-model="form.job" :options="['Pelajar', 'Pegawai Swasta', 'PNS', 'Wiraswasta', 'Lainnya']" />
        <SelectField label="Status Perkawinan" v-model="form.maritalStatus" :options="['Belum Kawin', 'Kawin', 'Cerai']" />
      </section>

      <section v-else class="mt-4 space-y-3 rounded-2xl bg-white p-4 shadow-sm">
        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
          <p class="text-sm text-slate-600">Ringkasan pendaftaran pasien baru akan digunakan untuk membuat data RM sementara di aplikasi.</p>
          <p class="mt-2 text-sm font-semibold">Nama: {{ form.name || '-' }}</p>
          <p class="text-sm">Tgl Lahir: {{ form.birthDate || '-' }}</p>
          <p class="text-sm">NIK: {{ form.nik || '-' }}</p>
        </div>

        <label class="flex items-start gap-3 rounded-xl border border-slate-200 p-3">
          <input v-model="form.consent" type="checkbox" class="mt-1 h-5 w-5" />
          <span class="text-sm text-slate-700">Saya menyatakan data yang diisi benar dan bersedia data dicantumkan sebagai anggota keluarga.</span>
        </label>
      </section>

      <div class="mt-5">
        <button
          v-if="step < 3"
          class="w-full rounded-full bg-teal-500 px-4 py-3 text-xl font-medium text-white"
          @click="nextStep"
        >
          Selanjutnya
        </button>

        <button
          v-else
          class="w-full rounded-full px-4 py-3 text-xl font-medium text-white"
          :class="canSubmit ? 'bg-teal-600' : 'bg-slate-300'"
          :disabled="!canSubmit"
          @click="submitNewPatient"
        >
          Daftar Pasien Baru
        </button>
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, defineComponent, h, reactive, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import PageHeader from '../components/Header.vue'

const router = useRouter()
const route = useRoute()
const step = ref(1)
const stepLabels = ['Personal', 'Referensi', 'Consent']

const form = reactive({
  relation: route.query.relation || '',
  mrn: route.query.mrn || '',
  birthDate: route.query.birthDate || '',
  name: '',
  birthPlace: '',
  gender: '',
  phone: '',
  address: '',
  nik: '',
  religion: '',
  education: '',
  job: '',
  maritalStatus: '',
  consent: false
})

const nextStep = () => {
  if (step.value < 3) step.value += 1
}

const canSubmit = computed(() => form.name && form.birthDate && form.gender && form.phone && form.nik && form.consent)

const getList = (key) => {
  try {
    return JSON.parse(localStorage.getItem(key) || '[]')
  } catch (_) {
    return []
  }
}

const submitNewPatient = () => {
  if (!canSubmit.value) return

  const familyMembers = getList('family_members')

  const generatedMrn = form.mrn || `9${Date.now().toString().slice(-8)}`

  const patient = {
    mrn: generatedMrn,
    birthDate: form.birthDate,
    name: form.name.toUpperCase(),
    nik: form.nik
  }

  if (!extraSimrs.some((item) => item.mrn === patient.mrn && item.birthDate === patient.birthDate)) {
    extraSimrs.push(patient)
  }

  const relation = form.relation || 'Keluarga'
  if (!familyMembers.some((item) => item.mrn === patient.mrn && item.birthDate === patient.birthDate)) {
    familyMembers.push({ ...patient, relation })
  }

  localStorage.setItem('family_members', JSON.stringify(familyMembers))
  router.push('/keluarga')
}

const InputField = defineComponent({
  props: ['label', 'modelValue', 'placeholder', 'type', 'multiline'],
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    return () =>
      h('div', { class: 'space-y-1' }, [
        h('label', { class: 'block text-sm text-slate-700' }, props.label),
        props.multiline
          ? h('textarea', {
              class: 'w-full rounded-xl bg-cyan-50 px-3 py-3 text-base outline-none',
              placeholder: props.placeholder,
              value: props.modelValue,
              onInput: (e) => emit('update:modelValue', e.target.value)
            })
          : h('input', {
              class: 'w-full rounded-xl bg-cyan-50 px-3 py-3 text-base outline-none',
              type: props.type || 'text',
              placeholder: props.placeholder,
              value: props.modelValue,
              onInput: (e) => emit('update:modelValue', e.target.value)
            })
      ])
  }
})

const SelectField = defineComponent({
  props: ['label', 'modelValue', 'options'],
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    return () =>
      h('div', { class: 'space-y-1' }, [
        h('label', { class: 'block text-sm text-slate-700' }, props.label),
        h(
          'select',
          {
            class: 'w-full rounded-xl bg-cyan-50 px-3 py-3 text-base outline-none',
            value: props.modelValue,
            onChange: (e) => emit('update:modelValue', e.target.value)
          },
          [h('option', { value: '' }, `Pilih ${String(props.label || '').toLowerCase()}`), ...(props.options || []).map((opt) => h('option', { value: opt }, opt))]
        )
      ])
  }
})
</script>
