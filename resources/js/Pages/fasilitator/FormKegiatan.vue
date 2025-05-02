<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import FasilitatorLayout from '@/Layouts/FasilitatorLayout.vue'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  kegiatan: Object,
  kecamatans: Array,
  bidangs: Array,
  grups: Array,
})

const form = useForm({
  judul: '',
  deskripsi: '',
  masalah: '',
  solusi: '',
  tanggal: '',
  waktu: '',
  tempat: '',
  jumlah_peserta: 0,
  laporan: null,
  kode_kecamatan: '',
  id_bidang: '',
  id_grup_dampingan: [""],
  foto: [],
})

onMounted(() => {
  if (props.kegiatan) {
    form.judul = props.kegiatan.judul
    form.deskripsi = props.kegiatan.deskripsi
    form.masalah = props.kegiatan.masalah
    form.solusi = props.kegiatan.solusi
    form.tanggal = props.kegiatan.tanggal
    form.waktu = props.kegiatan.waktu
    form.tempat = props.kegiatan.tempat
    form.laporan = props.kegiatan.laporan
    form.jumlah_peserta = props.kegiatan.jumlah_peserta
    form.kode_kecamatan = props.kegiatan.kode_kecamatan
    form.id_bidang = props.kegiatan.id_bidang
    form.id_grup_dampingan = props.kegiatan.grup_dampingan?.map(g => g.id) || []

    // Kosongkan input file, tapi tetap tampilkan input sesuai jumlah galeri
    fileInputs.value = props.kegiatan.galeris?.map(() => ({ file: null })) || [{ file: null }]
  }
})

const kecamatanOptions = props.kecamatans.map(k => ({
  value: k.kode,
  label: k.nama,
}));

const bidangOptions = props.bidangs.map(b => ({
  value: b.id_bidang,
  label: b.nama_bidang,
}));

const grupOptions = computed(() => {
  if (!form.id_bidang) return []
  return props.grups
    .filter(grup => grup.id_bidang === form.id_bidang)
    .map(grup => ({
      value: grup.id_grup_dampingan,
      label: grup.nama_grup_dampingan
    }))
})

const addGrup = () => {
  form.id_grup_dampingan.push("");
};

const removeGrup = (index) => {
  if (form.id_grup_dampingan.length > 1) {
    form.id_grup_dampingan.splice(index, 1);
  }
};

const fileInputs = ref([{ file: null }])

const addFileInput = () => {
  fileInputs.value.push({ file: null })
}

const handleFileChange = (event, index) => {
  fileInputs.value[index].file = event.target.files[0]
}

const handleSubmit = () => {
  form.foto = fileInputs.value
    .map(input => input.file)
    .filter(file => file !== null);

  if (props.kegiatan) {
    form.put(`/fasilitator/data-kegiatan/${props.kegiatan.id_kegiatan}`);
  } else {
    form.post('/fasilitator/data-kegiatan')
  }
}
</script>

<template>
  <FasilitatorLayout>

    <Head :title="props.kegiatan ? 'Edit Kegiatan' : 'Tambah Kegiatan'" />
    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[90vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-xl font-bold text-black mb-2">
            {{ props.kegiatan ? "Form Edit Kegiatan" : "Form Tambah Kegiatan" }}
          </h2>

          <form @submit.prevent="handleSubmit" enctype="multipart/form-data" class="mt-6 w-full">
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Judul Kegiatan</label>
              <input v-model="form.judul" type="text" placeholder="Masukkan Judul Kegiatan di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Deskripsi</label>
              <textarea v-model="form.deskripsi" placeholder="Masukkan Deskripsi di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Masalah (Opsional)</label>
              <textarea v-model="form.masalah" placeholder="Masukkan Masalah"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Solusi (Opsional)</label>
              <textarea v-model="form.solusi" placeholder="Masukkan Solusi"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Tempat Pelaksanaan</label>
              <input v-model="form.tempat" type="text" placeholder="Masukkan Tempat Pelaksanaan"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Kecamatan</label>
              <Multiselect v-model="form.kode_kecamatan" :options="kecamatanOptions" :searchable="true"
                placeholder="Pilih Kecamatan" class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
            </div>

            <div class="mb-2 flex gap-4">
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Tanggal</label>
                <input v-model="form.tanggal" type="date"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
              </div>

              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Waktu</label>
                <input v-model="form.waktu" type="time"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
              </div>

              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Jumlah Peserta</label>
                <div class="flex items-center">
                  <input type="number" v-model.number="form.jumlah_peserta"
                    class="w-full py-2 px-3 rounded-md border-y border-gray-400 text-center text-sm" min="0" />
                </div>
              </div>
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Bidang Dampingan</label>
              <Multiselect v-model="form.id_bidang" :options="bidangOptions" placeholder="Pilih Bidang"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
            </div>

            <!-- Grup Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Grup Dampingan</label>

              <div v-for="(item, index) in form.id_grup_dampingan" :key="index"
                class="flex gap-2 items-center gap-2 mb-2">
                <Multiselect v-model="form.id_grup_dampingan[index]" :options="grupOptions" placeholder="Pilih Grup"
                  class="w-full border border-gray-300 rounded-md shadow-sm text-sm" :searchable="true" />
                <button type="button" @click="addGrup"
                  class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600"
                  v-if="index === form.id_grup_dampingan.length - 1">
                  +
                </button>
                <button type="button" @click="removeGrup(index)"
                  class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600"
                  v-if="form.id_grup_dampingan.length > 1">
                  -
                </button>
              </div>
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Foto Dokumentasi</label>
              <div v-for="(input, index) in fileInputs" :key="index" class="mb-2">
                <input type="file" @change="(e) => handleFileChange(e, index)"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" />
                <button v-if="fileInputs.length > 1" @click.prevent="fileInputs.splice(index, 1)"
                  class="text-red-500 text-xs mt-1">Hapus</button>
              </div>
              <button @click.prevent="addFileInput" class="text-blue-500 text-xs">+ Tambah Foto Lain</button>
            </div>

            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Laporan Kegiatan (Opsional)</label>
              <input type="file" @change="e => form.laporan = e.target.files[0]"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" />
            </div>

            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('kegiatan.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md">
                {{ props.kegiatan ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </FasilitatorLayout>
</template>