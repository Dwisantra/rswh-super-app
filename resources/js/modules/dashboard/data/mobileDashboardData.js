export const patientSnapshot = {
  name: 'Okvi Dwi Santra',
  mrn: '000123456',
  nextVisit: 'Rabu, 26 Nov 2025 · 17:00',
  clinic: 'Bedah Digestif'
}

export const menuSections = [
  {
    id: 'utama',
    title: 'Layanan Utama',
    description: 'Akses cepat fitur yang paling sering dipakai.',
    columns: 2,
    items: [
      {
        id: 1,
        label: 'Tiket Antrian',
        subtitle: 'Lihat nomor & detail kunjungan',
        icon: '🎟️',
        to: '/ticket',
        tone: 'bg-sky-50 text-sky-700 border-sky-100'
      },
      {
        id: 2,
        label: 'Jadwal Dokter',
        subtitle: 'Per hari & ruangan/poli',
        icon: '🩺',
        to: '/jadwal-dokter',
        tone: 'bg-teal-50 text-teal-700 border-teal-100'
      },
      {
        id: 3,
        label: 'Kalkulator BMI',
        subtitle: 'Pantau berat badan ideal',
        icon: '⚖️',
        to: '/bmi',
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

export const dayFilters = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']

export const doctorSchedules = [
  { id: 1, doctor: 'dr. Rina Mardiana, Sp.PD', day: 'Senin', time: '08:00 - 11:00', poli: 'Penyakit Dalam', room: 'Poli A1' },
  { id: 2, doctor: 'dr. Hendra Saputra, Sp.B', day: 'Senin', time: '13:00 - 16:00', poli: 'Bedah Umum', room: 'Poli B2' },
  { id: 3, doctor: 'dr. Budi Santoso, Sp.JP', day: 'Selasa', time: '09:00 - 12:00', poli: 'Jantung', room: 'Poli C1' },
  { id: 4, doctor: 'dr. Anita Pratiwi, Sp.M', day: 'Rabu', time: '10:00 - 13:00', poli: 'Mata', room: 'Poli D3' },
  { id: 5, doctor: 'dr. Kamal Agung Wijayana, Sp.B(K)KBD', day: 'Rabu', time: '17:00 - 18:00', poli: 'Bedah Digestif', room: 'Poli B3' },
  { id: 6, doctor: 'dr. Dina Rahmawati, Sp.OG', day: 'Kamis', time: '08:00 - 12:00', poli: 'Kandungan', room: 'Poli E1' },
  { id: 7, doctor: 'dr. Rahmat Hidayat, Sp.A', day: 'Jumat', time: '13:00 - 16:00', poli: 'Anak', room: 'Poli F1' },
  { id: 8, doctor: 'dr. Lisa Kurnia, Sp.THT', day: 'Sabtu', time: '09:00 - 11:00', poli: 'THT', room: 'Poli G2' }
]
