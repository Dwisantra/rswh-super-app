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
        label: 'Kalkulator BMI',
        subtitle: 'Pantau berat badan ideal',
        icon: '⚖️',
        to: '/bmi',
        tone: 'bg-emerald-50 text-emerald-700 border-emerald-100'
      },
      {
        id: 3,
        label: 'Tempat Tidur',
        subtitle: 'Cek ketersediaan rawat inap',
        icon: '🛏️',
        to: '/tempat-tidur',
        tone: 'bg-cyan-50 text-cyan-700 border-cyan-100'
      },
      {
        id: 4,
        label: 'Riwayat Kunjungan',
        subtitle: 'Segera hadir',
        icon: '📄',
        to: '/promo',
        tone: 'bg-violet-50 text-violet-700 border-violet-100'
      }
    ]
  }
]

export const promos = [
  {
    id: 1,
    title: 'Paket Cek Jantung',
    subtitle: 'Diskon 20% untuk pasien usia 45+ tahun.',
    cta: 'Lihat Detail'
  },
  {
    id: 2,
    title: 'Konsultasi Gizi Keluarga',
    subtitle: 'Gratis konsultasi awal untuk 50 pendaftar pertama.',
    cta: 'Daftar Sekarang'
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
