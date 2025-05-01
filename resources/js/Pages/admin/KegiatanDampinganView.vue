<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const isMenuOpen = ref(false)
const isDropdownOpen = ref(false)
const showPopup = ref(false)

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value
}

const getArtikelLink = id => {
  return `/Artikel${id}`
}

const newsItems = ref([
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
])

const tambahData = () => {
  console.log('Tombol ditekan!')
}
</script>

<template>
  <AdminLayout>
    <Head title="Kegiatan Dampingan" />
    <div class="flex bg-gray-100 overflow-auto">
      <main
        class="grow px-10 py-6 bg-white rounded-lg shadow-md max-md:p-4 max-md:m-3 h-auto overflow-y-auto scrollbar-hidden"
      >
        <h2 class="mb-6 text-2xl font-semibold text-black">
          Kegiatan Dampingan
        </h2>

        <!-- Ubah dari flex menjadi grid -->
        <section
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 w-full pb-4"
        >
          <article
            v-for="news in newsItems"
            :key="news.id"
            class="flex flex-col gap-2"
          >
            <router-link :to="getArtikelLink(news.id)">
              <img
                :src="news.image"
                :alt="news.title"
                class="object-cover w-full rounded-2xl h-[221px] cursor-pointer transition-transform hover:scale-105"
              />
            </router-link>
            <time class="text-sm font-bold text-zinc-700">{{ news.date }}</time>
            <h3 class="text-sm font-bold leading-snug text-zinc-800">
              {{ news.title }}
            </h3>
          </article>
        </section>
      </main>
    </div>
  </AdminLayout>
</template>
