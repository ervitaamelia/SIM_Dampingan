<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const kegiatans = computed(() => page.props.data || []);

const getArtikelLink = (id) => `/artikel/${id}`;
</script>

<template>
  <AdminLayout>

    <Head title="Kegiatan Dampingan" />

    <div class="flex bg-gray-100 overflow-auto">
      <main
        class="grow px-10 py-6 bg-white rounded-lg shadow-md max-md:p-4 max-md:m-3 h-auto overflow-y-auto scrollbar-hidden">
        <h2 class="mb-6 text-2xl font-semibold text-black">
          Kegiatan Dampingan
        </h2>

        <!-- Tampilan artikel horizontal scroll -->
        <div class="overflow-x-auto w-full pb-4 scrollbar-hidden relative">
          <div class="flex items-center gap-4">
            <!-- Tombol panah kiri -->
            <button class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition">
              &#8249;
            </button>

            <!-- Card kegiatan -->
            <div class="flex flex-col w-max gap-6">
              <section class="flex gap-6">
                <article v-for="kegiatan in kegiatans" :key="kegiatan.id_kegiatan"
                  class="flex flex-col gap-3 w-[350px] flex-shrink-0 bg-white p-4 rounded-xl shadow-md hover:scale-105 transition-transform cursor-pointer ">
                  <a :href="getArtikelLink(kegiatan.id_kegiatan)">
                    <img :src="`/storage/${kegiatan.galeris[0]?.foto ?? 'placeholder.jpg'}`" :alt="kegiatan.judul"
                      class="object-cover w-[350px] h-[230px] rounded-xl" />
                  </a>
                  <time class="text-xs font-bold text-gray-700">
                    {{ new Date(kegiatan.tanggal).toLocaleDateString('id-ID') }}
                  </time>
                  <h3 class="text-base font-semibold text-gray-800 leading-snug">
                    {{ kegiatan.judul }}
                  </h3>
                  <a v-if="kegiatan.laporan" :href="`/storage/${kegiatan.laporan}`" target="_blank"
                    class="px-4 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition text-center">
                    Lihat Laporan
                  </a>
                </article>
              </section>
            </div>

            <!-- Tombol panah kanan -->
            <button class="p-2 bg-gray-200 rounded-full hover:bg-gray-300 transition">
              &#8250;
            </button>
          </div>
        </div>
      </main>
    </div>
  </AdminLayout>
</template>

<style scoped>
.scrollbar-hidden {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.scrollbar-hidden::-webkit-scrollbar {
  display: none;
}
</style>
