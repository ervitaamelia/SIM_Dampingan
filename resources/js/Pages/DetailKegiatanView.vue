<script>
import DetailKegiatanLayout from '@/Layouts/DetailKegiatanLayout.vue'

export default {
  components: {
    DetailKegiatanLayout,
  },
  name: 'MainContent',
  data() {
    return {
      isDropdownOpen: false,
      showPopup: false,
    }
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen
    },
  },
}
</script>

<template>
  <DetailKegiatanLayout>
    <main
      class="flex-1 p-6 max-md:p-4 max-sm:p-2.5 h-screen overflow-y-auto scrollbar-hide"
    >
      <button
        @click="$router.go(-1)"
        class="mb-4 px-4 py-2 bg-sky-600 text-white rounded-md hover:bg-sky-700 transition"
      >
        ← Kembali
      </button>
      <article
        class="p-8 bg-white rounded-[30px] shadow-[0_10px_60px_rgba(226,236,249,0.5)] max-sm:p-4"
      >
        <header class="mb-5 text-center">
          <h2 class="mb-2.5 text-base font-bold">
            Muhammadiyah Panen Raya Padi Sehat: Ikhtiar Mewujudkan Kedaulatan
            Pangan
          </h2>
          <div
            class="flex gap-2.5 justify-center items-center text-sm text-slate-400 max-sm:flex-wrap"
          >
            <time datetime="2025-02-07">Februari 7, 2025</time>
            <span>|</span>
            <time>10.00</time>
          </div>
        </header>
        <img
          src="https://cdn.builder.io/api/v1/image/assets/TEMP/4bd689ea69e6c38f65a3cd4dc52f56ee3c474aed"
          alt="Panen Padi"
          class="block mx-auto mt-0 mb-8 rounded-2xl h-[266px] w-[431px] max-md:w-full max-md:h-auto"
        />
        <p class="text-base leading-normal text-black">
          Lebong, Bengkulu – Majelis Pemberdayaan Masyarakat (MPM) Pimpinan
          Pusat Muhammadiyah melakukan Panen Padi Sehat pada Rabu (5/2) di
          Kampung Jawa, Lebong, Bengkulu. Acara ini sekaligus memperteguh
          komitmen Muhammadiyah terus menggelorakan ikhtiar besar menghadirkan
          kedaulatan pangan. Dengan pendampingan intensif dalam kesatuan
          ekosistem pemberdayaan, Alhamdulillah panen raya ini memberikan hasil
          yang menggembirakan. Demikian disampaikan Ketua MPM PP Muhammadiyah M.
          Nurul Yamin saat memberikan sambutan acara tersebut. Pada sisi lain,
          panen raya ini menunjukkan keberhasilan Jamaah Tani Muhammadiyah
          (JATAM) Lebong Bengkulu dalam mengimplementasikan metode pertanian
          sehat berbasis Jajar Legowo 2:1 dan penggunaan pupuk organik yang
          dikembangkan oleh Jatam mampu meningkatkan hasil panen secara
          signifikan.
        </p>
      </article>
    </main>
  </DetailKegiatanLayout>
</template>

<style>
/* Untuk Chrome, Safari, dan Edge */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
/* Untuk Firefox */
.hide-scrollbar {
  scrollbar-width: none;
}
</style>
