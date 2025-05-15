<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import FasilitatorLayout from '@/Layouts/FasilitatorLayout.vue'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  kegiatan: Object,
  bidangs: Array,
  grups: Array,
})

const form = useForm({
  judul: props.kegiatan?.judul || '',
  deskripsi: props.kegiatan?.deskripsi || '',
  masalah: props.kegiatan?.masalah || '',
  solusi: props.kegiatan?.solusi || '',
  tanggal: props.kegiatan?.tanggal || '',
  waktu: props.kegiatan?.waktu || '',
  alamat: props.kegiatan?.alamat || '',
  jumlah_peserta: props.kegiatan?.jumlah_peserta || 0,
  laporan: null,
  kode_provinsi: props.kegiatan?.kode_provinsi || '',
  kode_kabupaten: props.kegiatan?.kode_kabupaten || '',
  kode_kecamatan: props.kegiatan?.kode_kecamatan || '',
  id_bidang: props.kegiatan?.id_bidang || '',
  id_grup_dampingan: props.kegiatan?.grups?.map(grup => grup.id_grup_dampingan) || [''],
  foto: [],
})

onMounted(() => {
  fetchProvinsi();
  if (form.kode_provinsi) fetchKabupaten(form.kode_provinsi);
  if (form.kode_kabupaten) fetchKecamatan(form.kode_kabupaten);

  fileInputs.value = props.kegiatan?.galeris?.map(() => ({ file: null })) || [{ file: null }];
})

const provinsiList = ref([])
const kabupatenList = ref([])
const kecamatanList = ref([])

const selectedKabupaten = ref(null)
const selectedKecamatan = ref(null)

function fetchProvinsi() {
  fetch('/api/provinsi')
    .then(res => {
      if (!res.ok) throw new Error('Network response was not ok');
      return res.json();
    })
    .then(data => {
      provinsiList.value = data.map(item => ({
        label: item.nama,
        value: item.kode
      }))
    })
    .catch(error => {
      console.error('Error fetching provinsi:', error)
    })
}

function fetchKabupaten(kodeProvinsi) {
  selectedKabupaten.value = null
  selectedKecamatan.value = null
  kabupatenList.value = []
  kecamatanList.value = []

  if (kodeProvinsi) {
    fetch(`/api/kabupaten/${kodeProvinsi}`)
      .then(res => res.json())
      .then(data => {
        kabupatenList.value = data.map(item => ({
          label: item.nama,
          value: item.kode
        }))
      })
  }
}

function fetchKecamatan(kodeKabupaten) {
  selectedKecamatan.value = null
  kecamatanList.value = []

  if (kodeKabupaten) {
    fetch(`/api/kecamatan/${kodeKabupaten}`)
      .then(res => res.json())
      .then(data => {
        kecamatanList.value = data.map(item => ({
          label: item.nama,
          value: item.kode
        }))
      })
  }
}

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

const isFormValid = computed(() => {
  return form.judul &&
    form.deskripsi &&
    form.tanggal &&
    form.waktu &&
    form.alamat &&
    form.jumlah_peserta &&
    form.kode_provinsi &&
    form.kode_kabupaten &&
    form.kode_kecamatan &&
    form.id_bidang &&
    form.id_grup_dampingan &&
    fileInputs.value.every(input => input.file instanceof File);
})

const handleSubmit = () => {
  form.foto = fileInputs.value
    .map(input => input.file)
    .filter(file => file !== null);

  if (!isFormValid()) {
    alert("Silakan lengkapi semua kolom wajib sebelum mengirim form.");
    return;
  }

  if (props.kegiatan) {
    form.post(`/fasilitator/data-kegiatan/${props.kegiatan.id_kegiatan}`, {
      _method: 'put',
      forceFormData: true
    });
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
            <!-- Judul kegiatan -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Judul Kegiatan</label>
              <input v-model="form.judul" type="text" placeholder="Masukkan Judul Kegiatan di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
            </div>

            <!-- Deskripsi -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Deskripsi</label>
              <textarea v-model="form.deskripsi" placeholder="Masukkan Deskripsi di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Masalah -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Masalah (Opsional)</label>
              <textarea v-model="form.masalah" placeholder="Masukkan Masalah"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Solusi -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Solusi (Opsional)</label>
              <textarea v-model="form.solusi" placeholder="Masukkan Solusi"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Alamat -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Alamat</label>
              <textarea v-model="form.alamat" placeholder="Masukkan Alamat"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Provinsi -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Provinsi</label>
              <Multiselect v-model="form.kode_provinsi" :options="provinsiList" :searchable="true"
                placeholder="Pilih Provinsi" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                @update:modelValue="fetchKabupaten" />
            </div>

            <!-- Kabupaten -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Kabupaten</label>
              <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" :searchable="true"
                placeholder="Pilih Kabupaten" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :disabled="!form.kode_provinsi" @update:modelValue="fetchKecamatan" />
            </div>

            <!-- Kecamatan -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Kecamatan</label>
              <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" :searchable="true"
                placeholder="Pilih Kecamatan" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :disabled="!form.kode_kabupaten" />
            </div>

            <div class="mb-2 flex gap-4">
              <!-- Tanggal -->
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Tanggal</label>
                <input v-model="form.tanggal" type="date"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
              </div>

              <!-- Waktu -->
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Waktu</label>
                <input v-model="form.waktu" type="time"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
              </div>

              <!-- Jumlah peserta -->
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Jumlah Peserta</label>
                <div class="flex items-center">
                  <input type="number" v-model.number="form.jumlah_peserta"
                    class="w-full py-2 px-3 rounded-md border-y border-gray-400 text-center text-sm" min="0" />
                </div>
              </div>
            </div>

            <!-- Bidang dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Bidang Dampingan</label>
              <Multiselect v-model="form.id_bidang" :options="bidangOptions" placeholder="Pilih Bidang"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
            </div>

            <!-- Grup dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Grup Dampingan</label>

              <div v-for="(item, index) in form.id_grup_dampingan" :key="index"
                class="flex gap-2 items-center gap-2 mb-2">
                <Multiselect v-model="form.id_grup_dampingan[index]" :options="grupOptions" placeholder="Pilih Grup"
                  :disabled="!form.id_bidang" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                  :searchable="true" />
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

            <!-- Foto -->
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
            <div v-if="props.kegiatan?.galeris?.length" class="mb-4">
              <label class="text-sm font-medium text-gray-600">Foto Dokumentasi Sebelumnya:</label>
              <div class="flex flex-wrap gap-2 mt-2">
                <img v-for="(foto, idx) in props.kegiatan.galeris" :key="idx"
                  :src="`/storage/${foto.foto}`" alt="Foto Kegiatan"
                  class="w-32 h-32 object-cover border rounded-md" />
              </div>
            </div>

            <!-- Laporan -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Laporan Kegiatan (Opsional)</label>
              <input type="file" @change="e => form.laporan = e.target.files[0]"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" />
            </div>
            <div v-if="props.kegiatan?.laporan" class="mb-4">
              <label class="text-sm font-medium text-gray-600">Laporan Sebelumnya:</label>
              <a :href="`/storage/${props.kegiatan.laporan}`" target="_blank" class="text-blue-500 underline">
                Lihat Laporan
              </a>
            </div>

            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('kegiatan.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" :disabled="form.processing || !isFormValid" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                {{ form.processing ? 'Memproses...' : (props.kegiatan ? "Simpan Perubahan" : "Tambah") }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </FasilitatorLayout>
</template>