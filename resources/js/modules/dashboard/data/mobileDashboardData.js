export const serviceMenus = [
  {
    id: 1,
    label: 'Tiket Antrian',
    description: 'Lihat nomor antrian dan detail kunjungan',
    icon: '🎟️',
    to: '/ticket',
    color: 'bg-rose-100 text-rose-700'
  },
  {
    id: 2,
    label: 'Kalkulator BMI',
    description: 'Hitung indeks massa tubuh secara cepat',
    icon: '⚖️',
    to: '/bmi',
    color: 'bg-emerald-100 text-emerald-700'
  },
  {
    id: 3,
    label: 'Tempat Tidur',
    description: 'Pantau ketersediaan kamar rawat inap',
    icon: '🛏️',
    to: '/tempat-tidur',
    color: 'bg-sky-100 text-sky-700'
  }
]

export const promos = [
  {
    id: 1,
    title: 'Promo Cek Kesehatan',
    subtitle: 'Paket medical check-up hemat untuk keluarga',
    cta: 'Lihat promo'
  },
  {
    id: 2,
    title: 'Diskon Konsultasi Spesialis',
    subtitle: 'Potongan biaya konsultasi hingga 20%',
    cta: 'Ambil voucher'
  }
]

export const ticketData = {
  queueNumber: 'A2-005',
  clinicNumber: '5',
  patientName: 'OKVI DWI SANTRA',
  clinicName: 'BEDAH DIGESTIF',
  bookingCode: '2511260617',
  clinicHour: '17:00-18:00',
  serviceDate: '26 November 2025',
  estimatedService: '17:00-18:00',
  doctorName: 'dr. Kamal Agung Wijayana, Sp.B(K)KBD'
}

export const bedAvailability = [
  { id: 1, room: 'Mawar 1', class: 'VIP', available: 2, total: 8 },
  { id: 2, room: 'Melati 2', class: 'Kelas 1', available: 4, total: 12 },
  { id: 3, room: 'Anggrek 3', class: 'Kelas 2', available: 1, total: 10 },
  { id: 4, room: 'Kenanga 1', class: 'ICU', available: 0, total: 6 }
]
