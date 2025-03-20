<script setup>
import { ref } from 'vue'

const isDropdownOpen = ref(false)
const showPopup = ref(false)
const isMenuOpen = ref(false)

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const getArtikelLink = id => {
  return `/Artikel${id}`
}

const tambahData = () => {
  console.log('Tombol ditekan!')
}

const keyIcon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M16.2802 13.61C15.1502 14.74 13.5302 15.09 12.1002 14.64L9.5102 17.22C9.3302 17.41 8.9602 17.53 8.6902 17.49L7.4902 17.33C7.0902 17.28 6.7302 16.9 6.6702 16.51L6.5102 15.31C6.4702 15.05 6.6002 14.68 6.7802 14.49L9.3602 11.91C8.9202 10.48 9.2602 8.86001 10.3902 7.73001C12.0102 6.11001 14.6502 6.11001 16.2802 7.73001C17.9002 9.34001 17.9002 11.98 16.2802 13.61Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M10.4501 16.28L9.6001 15.42" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M13.3945 10.7H13.4035" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`

const productIcon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15 22.75H9C3.57 22.75 1.25 20.43 1.25 15V9C1.25 3.57 3.57 1.25 9 1.25H15C20.43 1.25 22.75 3.57 22.75 9V15C22.75 20.43 20.43 22.75 15 22.75ZM9 2.75C4.39 2.75 2.75 4.39 2.75 9V15C2.75 19.61 4.39 21.25 9 21.25H15C19.61 21.25 21.25 19.61 21.25 15V9C21.25 4.39 19.61 2.75 15 2.75H9Z" fill="#9197B3"/>
<path d="M12.0006 13.0797C11.8706 13.0797 11.7406 13.0497 11.6206 12.9797L6.32061 9.9197C5.96061 9.7097 5.84059 9.2497 6.05059 8.8997C6.26059 8.5397 6.72061 8.4197 7.07061 8.6297L11.9906 11.4797L16.8806 8.6497C17.2406 8.4397 17.7006 8.5697 17.9006 8.9197C18.1006 9.2697 17.9806 9.7397 17.6306 9.9397L12.3706 12.9797C12.2606 13.0397 12.1306 13.0797 12.0006 13.0797Z" fill="#9197B3"/>
<path d="M12 18.5201C11.59 18.5201 11.25 18.1801 11.25 17.7701V12.3301C11.25 11.9201 11.59 11.5801 12 11.5801C12.41 11.5801 12.75 11.9201 12.75 12.3301V17.7701C12.75 18.1801 12.41 18.5201 12 18.5201Z" fill="#9197B3"/>
<path d="M12.0002 18.7498C11.4202 18.7498 10.8503 18.6198 10.3903 18.3698L7.19025 16.5898C6.23025 16.0598 5.49023 14.7898 5.49023 13.6898V10.2998C5.49023 9.20981 6.24025 7.9398 7.19025 7.3998L10.3903 5.6198C11.3103 5.1098 12.6902 5.1098 13.6102 5.6198L16.8102 7.3998C17.7702 7.9298 18.5103 9.19981 18.5103 10.2998V13.6898C18.5103 14.7798 17.7602 16.0498 16.8102 16.5898L13.6102 18.3698C13.1502 18.6298 12.5802 18.7498 12.0002 18.7498Z" fill="#9197B3"/>
</svg>`

const dropdownIcon = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M6 9L12 15L18 9" stroke="#757575" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>`

const newsItems = [
  {
    id: 1,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/16271b27a3e1e288d76578e3e2a469550c5ba032',
    date: '05/02/25',
    title:
      'Peluncuran BLASMU: Beras Sehat Inovasi Jamaah Tani Muhammadiyah (JATAM) Lebong Bengkulu',
  },
  {
    id: 2,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/25b61d5f09e4ad466a1dd3cdd7e2a49e9bdb1f89',
    date: '14/12/24',
    title:
      'JATAM Difabel Bejen Dampingan MPM Muhammadiyah Bagikan Kisah Sukses Peternakan Ayam Petelur Inklusif',
  },
  {
    id: 3,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/9c3b9782b96384670eaa8bf3d45b86c1ca8b9b23',
    date: '25/01/25',
    title:
      'MPM Muhammadiyah Kuatkan Kaderisasi untuk Pemberdayaan Masyarakat di Kalimantan Timur',
  },
  {
    id: 1,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/16271b27a3e1e288d76578e3e2a469550c5ba032',
    date: '05/02/25',
    title:
      'Peluncuran BLASMU: Beras Sehat Inovasi Jamaah Tani Muhammadiyah (JATAM) Lebong Bengkulu',
  },
  {
    id: 2,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/25b61d5f09e4ad466a1dd3cdd7e2a49e9bdb1f89',
    date: '14/12/24',
    title:
      'JATAM Difabel Bejen Dampingan MPM Muhammadiyah Bagikan Kisah Sukses Peternakan Ayam Petelur Inklusif',
  },
  {
    id: 3,
    image:
      'https://cdn.builder.io/api/v1/image/assets/TEMP/9c3b9782b96384670eaa8bf3d45b86c1ca8b9b23',
    date: '25/01/25',
    title:
      'MPM Muhammadiyah Kuatkan Kaderisasi untuk Pemberdayaan Masyarakat di Kalimantan Timur',
  },
]

const tableHeaders = [
  'No Anggota',
  'Nama Lengkap',
  'Tempat Lahir',
  'Tanggal Lahir',
  'Jenis Kelamin',
  'Agama',
  'Pekerjaan',
  'No Telepon',
  'Alamat',
]

const tableData = [
  {
    'No Anggota': '1',
    'Nama Lengkap': 'Ervita',
    'Tempat Lahir': 'Klaten',
    'Tanggal Lahir': '02 Juli 1999',
    'Jenis Kelamin': 'Perempuan',
    Agama: 'Islam',
    Pekerjaan: 'Wiraswasta',
    'No Telepon': '(225) 555-0118',
    Alamat: 'Bantul',
  },
  {
    'No Anggota': '2',
    'Nama Lengkap': 'Fevy',
    'Tempat Lahir': 'Kuningan',
    'Tanggal Lahir': '04 Juni 1999',
    'Jenis Kelamin': 'Perempuan',
    Agama: 'Islam',
    Pekerjaan: 'Wiraswasta',
    'No Telepon': '(225) 555-0118',
    Alamat: 'Bantul',
  },
  {
    'No Anggota': '3',
    'Nama Lengkap': 'Fica',
    'Tempat Lahir': 'Ngawi',
    'Tanggal Lahir': '12 Juni 2000',
    'Jenis Kelamin': 'Perempuan',
    Agama: 'Islam',
    Pekerjaan: 'Wiraswasta',
    'No Telepon': '(225) 555-0118',
    Alamat: 'Bantul',
  },
]
</script>

<template>
  <div class="flex flex-1">
    <!-- Sidebar -->
    <button
      class="lg:hidden block text-gray-600"
      @click="isMenuOpen = !isMenuOpen"
    >
      ☰
    </button>
    <aside
      :class="isMenuOpen ? 'block' : 'hidden'"
      class="w-72 bg-white shadow-md p-4 lg:block"
    >
      <nav class="flex flex-col items-start py-5 pr-7 pl-2 w-full">
        <header
          class="flex gap-4 items-center text-2xl font-semibold text-black"
        >
          <img
            src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/bb85d5e3d957075707b815e47b52006b5b02cf69f26fa4fd8f985d630e49f5bc?placeholderIfAbsent=true"
            class="object-contain w-10"
            alt="Dashboard logo"
          />
          <h1>Dashboard</h1>
        </header>
      </nav>
      <!-- Profile Section -->
      <div class="mt-auto bg-gray-300 p-4 rounded-lg flex items-center gap-3">
        <img
          src="https://randomuser.me/api/portraits/men/32.jpg"
          alt="Admin Profile"
          class="w-12 h-12 rounded-full object-cover border-2 border-gray-300"
        />
        <div>
          <h2 class="text-md font-semibold text-gray-800">John Doe</h2>
          <p class="text-sm text-gray-500">Fasilitator</p>
        </div>
      </div>

      <ul class="w-full mt-10 space-y-3">
        <li>
          <a
            href="/fasilitator/"
            class="flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700"
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              class="shrink-0"
            >
              <path
                d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M16.2799 13.61C15.1499 14.74 13.5299 15.09 12.0999 14.64L9.50995 17.22C9.32995 17.41 8.95995 17.53 8.68995 17.49L7.48995 17.33C7.08995 17.28 6.72995 16.9 6.66995 16.51L6.50995 15.31C6.46995 15.05 6.59995 14.68 6.77995 14.49L9.35995 11.91C8.91995 10.48 9.25995 8.86001 10.3899 7.73001C12.0099 6.11001 14.6499 6.11001 16.2799 7.73001C17.8999 9.34001 17.8999 11.98 16.2799 13.61Z"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-miterlimit="10"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
            <span>Dashboard</span>
          </a>
        </li>

        <li>
          <a
            href="/fasilitator/data-kegiatan"
            class="flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200"
          >
            <img
              src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/b162be8a0c28dfc7547cedfb4d9b5bfc761b405e9d2ed8ccd4447b54a75941c8?placeholderIfAbsent=true"
              class="w-6 shrink-0"
              alt="Facilitator icon"
            />
            <span>Data Kegiatan</span>
          </a>
        </li>
      </ul>

      <button
        class="w-full py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 mt-80"
      >
        Keluar
      </button>
    </aside>
  </div>
</template>
