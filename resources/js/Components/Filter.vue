<!-- components/FilterComponent.vue -->
<template>
    <div class="flex gap-4 mb-8">
      <!-- Dropdown Provinsi -->
      <div class="w-1 min-w-[200px]">
        <Multiselect
          v-model="selectedProvinsi"
          :options="provinsiList"
          placeholder="Pilih Provinsi"
          :searchable="true"
          :class="{ 'transparent-dropdown': showPopup }"
          class="w-full"
        />
      </div>
  
      <!-- Dropdown Kabupaten -->
      <div class="w-1 min-w-[200px]">
        <Multiselect
          v-model="selectedKabupaten"
          :options="kabupatenList"
          placeholder="Pilih Kabupaten"
          :searchable="true"
          :class="{ 'transparent-dropdown': showPopup }"
          class="w-full"
        />
      </div>
  
      <!-- Dropdown Kecamatan -->
      <div class="w-1 min-w-[200px]">
        <Multiselect
          v-model="selectedKecamatan"
          :options="kecamatanList"
          placeholder="Pilih Kecamatan"
          :searchable="true"
          :class="{ 'transparent-dropdown': showPopup }"
          class="w-full"
        />
      </div>
  
      <!-- Dropdown Dampingan -->
      <div class="w-1 min-w-[200px]">
        <Multiselect
          v-model="selectedDampingan"
          :options="dampinganList"
          placeholder="Pilih Dampingan"
          :searchable="true"
          :class="{ 'transparent-dropdown': showPopup }"
          class="w-full"
        />
      </div>
    </div>
  </template>
  
  <script>
  import Multiselect from '@vueform/multiselect';
  
  export default {
    components: {
      Multiselect,
    },
    props: {
      showPopup: {
        type: Boolean,
        default: false,
      },
    },
    data() {
      return {
        selectedProvinsi: null,
        selectedKabupaten: null,
        selectedKecamatan: null,
        selectedDampingan: null,
        provinsiList: ['Jawa Tengah', 'Jawa Barat', 'Jawa Timur'],
        kabupatenList: ['Semarang', 'Bandung', 'Surabaya'],
        kecamatanList: ['Tembalang', 'Cibadak', 'Rungkut'],
        dampinganList: ['Dampingan A', 'Dampingan B'],
      };
    },
  };
  </script>
  
  <style scoped>
  /* Batasan tinggi dropdown agar bisa di-scroll */
  .max-h-40 {
    max-height: 10rem;
  }
  .multiselect[disabled] {
    background-color: #e0e0e0 !important;
    cursor: not-allowed;
    opacity: 0.6;
  }
  .transparent-dropdown {
    opacity: 0.5;
    pointer-events: none;
  }
  .transparent-dropdown {
    background-color: rgba(
      255,
      255,
      255,
      0.2
    ) !important; /* Sesuaikan transparansi */
    color: rgb(66, 65, 65) !important; /* Agar teks tetap terbaca */
    border: 1px solid rgba(255, 255, 255, 0.15); /* Sesuaikan border agar tidak menonjol */
  }
  </style>