<script>
import AdminLayout from '@/layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'

export default {
  components: {
    Multiselect,
    AdminLayout,
  },
  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      showPopupHapus: false,
      formData: {
        fullName: '',
        birthPlace: '',
        birthDate: '',
        gender: '',
        religion: '', // Pastikan sesuai dengan Multiselect v-model
        job: '',
        dampingan: '',
        phone: '',
        address: '',
      },
      religions: ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'],
      jobs: [
        'Pelajar',
        'Mahasiswa',
        'Pegawai Negeri',
        'Pegawai Swasta',
        'Wiraswasta',
      ],
      dampingan: ['Tani', 'Nelayan'],
    }
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen
    },
    handleSubmit() {
      console.log('Data Terkirim:', this.formData)
      alert('Form berhasil dikirim!')
    },
    tambahData() {
      console.log('Tombol ditekan!')
    },
  },
}
</script>

<template>
  <AdminLayout>
    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]"
      >
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            Form Tambah Masyarakat Dampingan
          </h2>
          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="fullName" class="text-sm font-medium text-gray-600"
                >Nama Lengkap</label
              >
              <input
                id="fullName"
                type="text"
                v-model="formData.fullName"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Lengkap"
              />
            </div>

            <!-- Tempat Lahir -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="birthPlace" class="text-sm font-medium text-gray-600"
                >Tempat Lahir</label
              >
              <input
                id="birthPlace"
                type="text"
                v-model="formData.birthPlace"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Tempat Lahir"
              />
            </div>

            <!-- Tanggal Lahir -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="birthDate" class="text-sm font-medium text-gray-600"
                >Tanggal Lahir</label
              >
              <input
                id="birthDate"
                type="text"
                v-model="formData.birthDate"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Tanggal Lahir"
              />
            </div>

            <!-- Jenis Kelamin -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600"
                >Jenis Kelamin</label
              >
              <div class="flex items-center gap-4 mt-1">
                <label class="flex items-center gap-1 text-sm">
                  <input
                    type="radio"
                    value="Perempuan"
                    v-model="formData.gender"
                  />
                  Perempuan
                </label>
                <label class="flex items-center gap-1 text-sm">
                  <input
                    type="radio"
                    value="Laki-laki"
                    v-model="formData.gender"
                  />
                  Laki-laki
                </label>
              </div>
            </div>

            <!-- Agama -->

            <div class="flex flex-col gap-2 pb-2">
              <label for="religion" class="text-sm font-medium text-gray-600">
                Agama
              </label>

              <Multiselect
                v-model="formData.religion"
                :options="religions"
                placeholder="Pilih Agama"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :searchable="true"
              />
            </div>

            <!-- Pekerjaan -->

            <div class="flex flex-col gap-2 pb-2">
              <label for="religion" class="text-sm font-medium text-gray-600">
                Pekerjaan
              </label>

              <Multiselect
                v-model="formData.job"
                :options="jobs"
                placeholder="Pilih Pekerjaan"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :searchable="true"
              />
            </div>

            <!-- Nama Dampingan -->

            <div class="flex flex-col gap-2 pb-2">
              <label for="dampingan" class="text-sm font-medium text-gray-600">
                Nama Dampingan
              </label>

              <Multiselect
                v-model="formData.dampingan"
                :options="dampingan"
                placeholder="Pilih Dampingan"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :searchable="true"
              />
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="phone" class="text-sm font-medium text-gray-600"
                >No. Telepon</label
              >
              <input
                id="phone"
                type="text"
                v-model="formData.phone"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan No. Telepon"
              />
            </div>

            <!-- Alamat -->

            <div class="flex flex-col gap-2 pb-2">
              <label for="address" class="text-sm font-medium text-gray-600"
                >Alamat</label
              >
              <textarea
                id="address"
                v-model="formData.address"
                class="w-full h-20 py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm resize-none"
                placeholder="Masukkan Alamat"
              ></textarea>
            </div>

            <div class="mb-4">
              <label class="text-sm font-medium text-gray-600">Pas Foto</label>
              <input
                type="file"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
              />
            </div>

            <!-- Tombol Submit -->
            <div class="mt-8"></div>
            <div class="flex justify-between mt-4">
              <a
                href="DataMasyarakat"
                type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Tambah
              </a>
              <a
                href="DataMasyarakat"
                type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Batal
              </a>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>

<style>
:root {
  --ms-option-selected-bg: #e0e0e0; /* Warna abu-abu muda */
  --ms-option-selected-text: #000000; /* Warna teks hitam */
  --ms-font-size: 14px; /* Ukuran font untuk dropdown */
}

.multiselect {
  font-size: 14px !important; /* Ukuran teks dalam input */
}

.multiselect-dropdown {
  font-size: 14px !important; /* Ukuran teks di dalam dropdown */
}

.multiselect-option {
  font-size: 14px !important; /* Ukuran teks di setiap opsi */
}

.multiselect .multiselect-option.is-selected {
  background-color: #e0e0e0 !important;
  color: #000000 !important;
}
</style>
