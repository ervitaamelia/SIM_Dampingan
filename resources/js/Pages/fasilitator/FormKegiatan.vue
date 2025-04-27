<script>
import FasilitatorLayout from '@/Layouts/FasilitatorLayout.vue'

export default {
  components: {
    FasilitatorLayout,
  },
  data() {
    return {
      fileInputs: [{ file: null }],
      isMenuOpen: false,
      isDropdownOpen: false,
      showPopup: false,
      form: {
        judulKegiatan: '',
        deskripsi: '',
        tanggal: '',
        waktu: '',
        peserta: '',
      },
    }
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen
    },
    submitForm() {
      // Handle logic penyimpanan form
      console.log('Data dikirim:', this.form, this.daftarPeserta)
      this.showPopup = true
    },
    addFileInput() {
      this.fileInputs.push({ file: null })
    },
    removeFileInput(index) {
      this.fileInputs.splice(index, 1)
    },
    handleFileChange(event, index) {
      this.fileInputs[index].file = event.target.files[0]
    },
    handleSubmit() {
      console.log('Files:', this.fileInputs)
    },
    batal() {
      this.fileInputs = [{ file: null }]
    },
    increasePeserta() {
      this.form.peserta++
    },
    decreasePeserta() {
      if (this.form.peserta > 0) {
        this.form.peserta--
      }
    },
    tambahData() {
      console.log('Tombol ditekan!')
    },
  },
}
</script>

<template>
  <FasilitatorLayout>
    <div class="ml-5 w-[90%] max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-[500px] bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]"
      >
        <div class="flex flex-col w-full">
          <h2 class="self-center text-xl font-bold text-black mb-2">
            Form Tambah Kegiatan
          </h2>

          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <div class="mb-4">
              <label class="text-sm font-medium text-gray-600"
                >Judul Kegiatan</label
              >
              <input
                v-model="form.judulKegiatan"
                type="text"
                placeholder="Masukkan Judul Kegiatan di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                required
              />
            </div>

            <div class="mb-4">
              <label class="text-sm font-medium text-gray-600">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                placeholder="Masukkan Deskripsi di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                required
              ></textarea>
            </div>

            <div class="mb-4 relative">
              <label class="text-sm font-medium text-gray-600"
                >Tanggal Pelaksanaan</label
              >
              <input
                v-model="form.tanggal"
                type="date"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                required
              />
            </div>

            <div class="mb-4 relative">
              <label class="text-sm font-medium text-gray-600">Waktu</label>
              <input
                v-model="form.waktu"
                type="time"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="HH:MM"
                required
              />
            </div>

            <div class="mb-4">
              <label class="text-sm font-medium text-gray-600"
                >Upload File</label
              >

              <div
                v-for="(file, index) in fileInputs"
                :key="index"
                class="flex items-center gap-2 mb-2"
              >
                <input
                  type="file"
                  @change="handleFileChange($event, index)"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                />
                <button
                  @click="addFileInput"
                  class="p-2 bg-blue-500 text-white rounded-md"
                >
                  +
                </button>
              </div>
              <div class="mb-4">
                <label class="text-sm font-medium text-gray-600"
                  >Laporan Kegiatan</label
                >
                <input
                  type="file"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                />
              </div>
            </div>

            <div class="peserta-input">
              <label class="text-sm font-medium text-gray-600"
                >Jumlah Peserta</label
              >
              <div class="peserta-control">
                <button @click="decreasePeserta" class="control-btn">-</button>
                <input
                  type="text"
                  v-model="form.peserta"
                  readonly
                  class="peserta-field"
                />
                <button @click="increasePeserta" class="control-btn">+</button>
              </div>
            </div>

            <div class="flex justify-between mt-4">
              <a
                href="TambahKegiatan"
                type="submit"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Tambah
              </a>
              <a
                href="TambahKegiatan"
                type="button"
                @click="batal"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md"
              >
                Batal
              </a>
            </div>
          </form>
          <!-- Menutup form di sini -->
        </div>
      </section>
    </div>
  </FasilitatorLayout>
</template>

<style>
.peserta-input {
  margin-top: 20px;
}
.peserta-control {
  display: flex;
  align-items: center;
  gap: 10px;
}
.control-btn {
  padding: 5px 10px;
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}
.peserta-field {
  width: 50px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 5px;
  padding: 5px;
}
</style>
