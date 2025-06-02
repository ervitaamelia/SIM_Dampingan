<script setup>
import FasilitatorLayout from "@/Layouts/FasilitatorLayout.vue";
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const kegiatans = computed(() => page.props.data || []);
const totalGrup = computed(() => page.props.totalGrup || 0);
const totalMasyarakat = computed(() => page.props.totalMasyarakat || 0);
const totalKegiatan = computed(() => page.props.totalKegiatan || 0);
const grups = computed(() => page.props.grups || []);

const getArtikelLink = (id) => `/artikel/${id}`;
</script>

<template>
  <FasilitatorLayout>
    <Head title="Dashboard" />

    <!-- Grid Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
        <span class="text-3xl mr-4">👥</span>
        <div>
          <h3 class="text-gray-500 text-sm">Total Masyarakat yang Didampingi</h3>
          <p class="text-xl font-bold">{{ totalMasyarakat }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
        <span class="text-3xl mr-4">🧑‍🏫</span>
        <div>
          <h3 class="text-gray-500 text-sm">Total Kegiatan yang Dibuat</h3>
          <p class="text-xl font-bold">{{ totalKegiatan }}</p>
        </div>
      </div>
      <div class="bg-white p-4 rounded-lg shadow-md flex items-center">
        <span class="text-3xl mr-4">🏠</span>
        <div>
          <h3 class="text-gray-500 text-sm">Total Grup yang Didampingi</h3>
          <p class="text-xl font-bold">{{ totalGrup }}</p>
        </div>
      </div>
    </div>

    <div class="flex flex-col gap-6 mt-6">
      <!-- Tabel -->
      <div class="w-full bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-bold mb-4">Grup Dampingan</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full border-collapse rounded-lg overflow-hidden shadow-sm">
            <thead>
              <tr class="bg-sky-600 text-white">
                <th class="p-3 text-left">Nama Grup</th>
                <th class="p-3 text-left">Bidang Dampingan</th>
                <th class="p-3 text-left">Provinsi</th>
                <th class="p-3 text-left">Kabupaten</th>
                <th class="p-3 text-left">Kecamatan</th>
                <th class="p-3 text-left">Jumlah Masyarakat</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="grup in grups" :key="grup.id_grup_dampingan" class="border-b">
                <td class="p-3">{{ grup.nama_grup_dampingan }}</td>
                <td class="p-3">{{ grup.nama_bidang }}</td>
                <td class="p-3">{{ grup.nama_provinsi }}</td>
                <td class="p-3">{{ grup.nama_kabupaten }}</td>
                <td class="p-3">{{ grup.nama_kecamatan }}</td>
                <td class="p-3">{{ grup.jumlah_anggota }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="grow mt-6 px-6 py-4 bg-white rounded-lg shadow-md w-full">
      <h2 class="mb-6 text-lg font-semibold text-gray-900">
        Kegiatan Terbaru
      </h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <article v-for="kegiatan in kegiatans.slice(0, 3)" :key="kegiatan.id_kegiatan"
          class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition duration-300 cursor-pointer">
          <a :href="getArtikelLink(kegiatan.id_kegiatan)">
            <img :src="`/storage/${kegiatan.galeris[0]?.foto ?? 'placeholder.jpg'}`" :alt="kegiatan.judul"
              class="object-cover w-full h-[200px] rounded-xl mb-3" />
          </a>
          <time class="text-xs font-semibold text-gray-600 block mb-1">
            {{ new Date(kegiatan.tanggal).toLocaleDateString('id-ID') }}
          </time>
          <h3 class="text-base font-semibold text-gray-800 leading-snug mb-2">
            {{ kegiatan.judul }}
          </h3>
          <a v-if="kegiatan.laporan" :href="`/storage/${kegiatan.laporan}`" target="_blank"
            class="inline-block px-4 py-1.5 text-xs font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition">
            Lihat Laporan
          </a>
        </article>
      </div>
    </div>
  </FasilitatorLayout>
</template>

<style>
:root {
  font-family: 'Poppins', sans-serif;
}
</style>