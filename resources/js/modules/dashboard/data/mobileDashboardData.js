export const patientSnapshot = {
  name: 'Okvi Dwi Santra',
  mrn: '000123456',
  nextVisit: 'Rabu, 26 Nov 2025 · 17:00',
  clinic: 'Bedah Digestif'
}

export const menuSections = [
  {
    id: 'utama',
    title: 'Menu Pelayanan',
    description: 'Gunakan fitur yang dibutuhkan tanpa input berulang.',
    columns: 2,
    items: [
      {
        id: 1,
        label: 'Keluarga',
        subtitle: 'Kelola pasien/anggota keluarga',
        icon: '👨‍👩‍👧‍👦',
        to: '/keluarga',
        tone: 'bg-sky-50 text-sky-700 border-sky-100'
      },
      {
        id: 2,
        label: 'Pendaftaran',
        subtitle: 'Daftar berobat tanpa spam data',
        icon: '📝',
        to: '/pendaftaran',
        tone: 'bg-teal-50 text-teal-700 border-teal-100'
      },
      {
        id: 3,
        label: 'Jadwal Dokter',
        subtitle: 'Per tanggal & klinik',
        icon: '🩺',
        to: '/jadwal-dokter',
        tone: 'bg-emerald-50 text-emerald-700 border-emerald-100'
      },
      {
        id: 4,
        label: 'Tempat Tidur',
        subtitle: 'Cek ketersediaan rawat inap',
        icon: '🛏️',
        to: '/tempat-tidur',
        tone: 'bg-cyan-50 text-cyan-700 border-cyan-100'
      }
    ]
  }
]

export const relationOptions = ['Ayah', 'Ibu', 'Suami', 'Istri', 'Anak', 'Saudara', 'Lainnya']

export const simrsPatients = [
  { mrn: '000123456', birthDate: '1990-05-12', name: 'OKVI DWI SANTRA', nik: '3175011205900001' },
  { mrn: '000778899', birthDate: '1987-09-03', name: 'RINA WULANDARI', nik: '3175010309870002' },
  { mrn: '000445566', birthDate: '2012-01-20', name: 'RAFA ALFARIZI', nik: '3175012001120003' }
]

export const promos = [
  {
    id: 1,
    title: 'Paket Cek Jantung',
    subtitle: 'Diskon 20% untuk pasien usia 45+ tahun.',
    cta: 'Lihat Detail',
    to: '/promo'
  },
  {
    id: 2,
    title: 'Konsultasi Gizi Keluarga',
    subtitle: 'Gratis konsultasi awal untuk 50 pendaftar pertama.',
    cta: 'Daftar Sekarang',
    to: '/promo'
  }
]

export const ticketData = {
  queueNumber: 'A2-005',
  clinicNumber: '5',
  patientName: 'OKVI DWI SANTRA',
  clinicName: 'BEDAH DIGESTIF',
  bookingCode: '2511260617',
  clinicHour: '17:00 - 18:00',
  serviceDate: '26 November 2025',
  estimatedService: '17:00 - 18:00',
  doctorName: 'dr. Kamal Agung Wijayana, Sp.B(K)KBD'
}

export const bedAvailability = [
  { id: 1, room: 'Mawar 1', class: 'VIP', available: 2, total: 8 },
  { id: 2, room: 'Melati 2', class: 'Kelas 1', available: 4, total: 12 },
  { id: 3, room: 'Anggrek 3', class: 'Kelas 2', available: 1, total: 10 },
  { id: 4, room: 'Kenanga 1', class: 'ICU', available: 0, total: 6 }
]

export const doctorSchedules = [
  { id: 1, doctor: 'dr. Rina Mardiana, Sp.PD', date: '2025-11-24', time: '08:00 - 11:00', clinic: 'Penyakit Dalam' },
  { id: 2, doctor: 'dr. Hendra Saputra, Sp.B', date: '2025-11-24', time: '13:00 - 16:00', clinic: 'Bedah Umum' },
  { id: 3, doctor: 'dr. Budi Santoso, Sp.JP', date: '2025-11-25', time: '09:00 - 12:00', clinic: 'Jantung' },
  { id: 4, doctor: 'dr. Anita Pratiwi, Sp.M', date: '2025-11-26', time: '10:00 - 13:00', clinic: 'Mata' },
  { id: 5, doctor: 'dr. Kamal Agung Wijayana, Sp.B(K)KBD', date: '2025-11-26', time: '17:00 - 18:00', clinic: 'Bedah Digestif' },
  { id: 6, doctor: 'dr. Dina Rahmawati, Sp.OG', date: '2025-11-27', time: '08:00 - 12:00', clinic: 'Kandungan' },
  { id: 7, doctor: 'dr. Rahmat Hidayat, Sp.A', date: '2025-11-28', time: '13:00 - 16:00', clinic: 'Anak' },
  { id: 8, doctor: 'dr. Lisa Kurnia, Sp.THT', date: '2025-11-29', time: '09:00 - 11:00', clinic: 'THT' }
]
