<script setup>
import { ref, onMounted, computed, watch, onUnmounted } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import FasilitatorLayout from '@/Layouts/FasilitatorLayout.vue'
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  kegiatan: Object,
  bidangs: Array,
  grups: Array,
})

const isDeskripsiValid = computed(() => {
  return form.deskripsi.trim().length >= 500;
});

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

  fileInputs.value = [{ file: null }];
})

onUnmounted(() => {
  // Cleanup object URLs
  Object.values(editedGaleriFiles.value).forEach(file => {
    if (file) {
      URL.revokeObjectURL(URL.createObjectURL(file));
    }
  });
});

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
  value: String(b.id_bidang),
  label: b.nama_bidang,
}));

const getGrupOptions = (index) => {
  if (!form.id_bidang) return []

  const selectedIds = form.id_grup_dampingan
    .filter((_, i) => i !== index)
    .map(g => typeof g === 'object' ? g?.value : g)

  return props.grups
    .filter(grup =>
      String(grup.id_bidang) === String(form.id_bidang) &&
      !selectedIds.includes(String(grup.id_grup_dampingan))
    )
    .map(grup => ({
      value: String(grup.id_grup_dampingan),
      label: grup.nama_grup_dampingan
    }))
}

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
  // Validasi file hanya untuk form baru, bukan edit
  const isFileValid = props.kegiatan ? true : fileInputs.value.some(input => input.file instanceof File);

  // Validasi grup dampingan
  const isGrupValid = form.id_grup_dampingan.length > 0 &&
    form.id_grup_dampingan.every(grup => grup !== null && grup !== "");

  return (
    // Basic field validations
    form.judul?.trim() &&
    isDeskripsiValid.value &&
    form.tanggal &&
    form.waktu &&
    form.alamat?.trim() &&
    form.jumlah_peserta > 0 &&
    form.kode_provinsi &&
    form.kode_kabupaten &&
    form.kode_kecamatan &&
    form.id_bidang &&
    isGrupValid &&
    isFileValid
  );
});

const handleSubmit = () => {
  const formData = new FormData();
  
  // Append all form fields
  Object.keys(form).forEach(key => {
    if (key !== 'foto') {
      if (Array.isArray(form[key])) {
        form[key].forEach((value, index) => {
          formData.append(`${key}[${index}]`, value);
        });
      } else {
        formData.append(key, form[key] || '');
      }
    }
  });

  // Append new photos
  fileInputs.value
    .map(input => input.file)
    .filter(file => file !== null)
    .forEach((file, index) => {
      formData.append(`foto[${index}]`, file);
    });

  // Append edited photos
  Object.entries(editedGaleriFiles.value).forEach(([galeriId, file]) => {
    if (file) {
      formData.append(`edited_galeris[${galeriId}]`, file);
    }
  });

  // Append deleted photo IDs
  deletedGaleriIds.value.forEach(id => {
    formData.append('deleted_galeris[]', id);
  });

  if (props.kegiatan) {
    // Using POST directly without _method field
    router.post(`/fasilitator/data-kegiatan/${props.kegiatan.id_kegiatan}`, formData);
  } else {
    router.post('/fasilitator/data-kegiatan', formData);
  }
}

const deletedGaleriIds = ref([]);
const editedGaleriFiles = ref({});
const showEditInput = ref({});

const handleEditGaleriFile = (event, galeriId) => {
  const file = event.target.files[0];
  if (file) {
    editedGaleriFiles.value[galeriId] = file;
    showEditInput.value[galeriId] = false;
  }
};

const getPreviewUrl = (galeriId, originalFoto) => {
  const editedFile = editedGaleriFiles.value[galeriId];
  if (editedFile) {
    return URL.createObjectURL(editedFile);
  }
  return `/storage/${originalFoto}`;
};
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

            <p class="text-sm text-gray-600 mb-4">
              Kolom yang ditandai dengan <span class="text-red-500 font-semibold">*</span> wajib diisi.
            </p>

            <!-- Judul kegiatan -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Judul Kegiatan <span
                  class="text-red-500">*</span></label>
              <input v-model="form.judul" type="text" placeholder="Masukkan Judul Kegiatan di sini"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
            </div>

            <!-- Deskripsi -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Deskripsi <span class="text-red-500">*</span></label>
              <textarea v-model="form.deskripsi" placeholder="Masukkan Deskripsi (minimal 500 karakter)"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"
                :class="{ 'border-red-500': !isDeskripsiValid && form.deskripsi }"></textarea>
              <p class="text-xs text-blue-500 mt-1">Minimal 500 karakter. Saat ini: {{ form.deskripsi.trim().length }}
                karakter</p>
              <p v-if="form.deskripsi && !isDeskripsiValid" class="text-xs text-red-500 mt-1">Deskripsi terlalu pendek.
              </p>
            </div>

            <!-- Masalah -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Masalah</label>
              <textarea v-model="form.masalah" placeholder="Masukkan Masalah"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Solusi -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Solusi</label>
              <textarea v-model="form.solusi" placeholder="Masukkan Solusi"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Alamat -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Alamat <span class="text-red-500">*</span></label>
              <p class="text-xs text-blue-500 mb-1">
                Masukkan No / Nama jalan, RT, RW, Dusun / Kelurahan setempat secara rinci. <br />
                Contoh: No. 6 Jl. Pracanda 6, RT.05/RW.02, Purwodiningratan, Jebres
              </p>
              <textarea v-model="form.alamat" placeholder="Masukkan Alamat lengkap"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm"></textarea>
            </div>

            <!-- Provinsi -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Provinsi <span class="text-red-500">*</span></label>
              <Multiselect v-model="form.kode_provinsi" :options="provinsiList" :searchable="true"
                placeholder="Pilih Provinsi" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                @update:modelValue="fetchKabupaten" />
            </div>

            <!-- Kabupaten -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Kabupaten <span class="text-red-500">*</span></label>
              <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" :searchable="true"
                placeholder="Pilih Kabupaten" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :disabled="!form.kode_provinsi" @update:modelValue="fetchKecamatan" />
            </div>

            <!-- Kecamatan -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Kecamatan <span class="text-red-500">*</span></label>
              <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" :searchable="true"
                placeholder="Pilih Kecamatan" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                :disabled="!form.kode_kabupaten" />
            </div>

            <div class="mb-2 flex gap-4">
              <!-- Tanggal -->
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Tanggal <span class="text-red-500">*</span></label>
                <input v-model="form.tanggal" type="date"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
              </div>

              <!-- Waktu -->
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Waktu <span class="text-red-500">*</span></label>
                <input v-model="form.waktu" type="time"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" required />
              </div>

              <!-- Jumlah peserta -->
              <div class="w-1/3">
                <label class="text-sm font-medium text-gray-600">Jumlah Peserta <span
                    class="text-red-500">*</span></label>
                <div class="flex items-center">
                  <input type="number" v-model.number="form.jumlah_peserta"
                    class="w-full py-2 px-3 rounded-md border-y border-gray-400 text-center text-sm" min="0" />
                </div>
              </div>
            </div>

            <!-- Bidang dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Bidang Dampingan <span
                  class="text-red-500">*</span></label>
              <Multiselect v-model="form.id_bidang" :options="bidangOptions" placeholder="Pilih Bidang"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
            </div>

            <!-- Grup dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Grup Dampingan <span
                  class="text-red-500">*</span></label>

              <div v-for="(item, index) in form.id_grup_dampingan" :key="index"
                class="flex gap-2 items-center gap-2 mb-2">
                <Multiselect v-model="form.id_grup_dampingan[index]" :options="getGrupOptions(index)"
                  placeholder="Pilih Grup" :disabled="!form.id_bidang"
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

            <!-- Foto -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Foto Dokumentasi <span
                  class="text-red-500">*</span></label>
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
                <div v-for="foto in props.kegiatan.galeris" :key="foto.id_galeri" class="relative group">
                  <!-- Foto -->
                  <div v-if="!deletedGaleriIds.includes(foto.id_galeri)" class="w-32 h-32 relative">
                    <img :src="getPreviewUrl(foto.id_galeri, foto.foto) " alt="Foto Kegiatan"
                      class="w-32 h-32 object-cover border rounded-md" />

                    <!-- Badge untuk foto yang diedit -->
                    <div v-if="editedGaleriFiles[foto.id_galeri]"
                      class="absolute top-0 right-0 bg-yellow-500 text-white text-xs px-2 py-1 rounded-bl-md">
                      Edited
                    </div>

                    <!-- Overlay buttons -->
                    <div
                      class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                      <button type="button" @click="showEditInput[foto.id_galeri] = true"
                        class="bg-yellow-500 text-white p-2 rounded-full hover:bg-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                      </button>
                      <button type="button" @click="deletedGaleriIds.push(foto.id_galeri)"
                        class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>

                    <!-- Edit input -->
                    <div v-if="showEditInput[foto.id_galeri]"
                      class="absolute inset-0 bg-white bg-opacity-90 flex flex-col items-center justify-center p-2">
                      <input type="file" accept="image/*" @change="(e) => handleEditGaleriFile(e, foto.id_galeri)"
                        class="text-xs w-full" />
                      <div class="flex gap-2 mt-2">
                        <button type="button" @click="() => {
                                  showEditInput[foto.id_galeri] = false;
                                  editedGaleriFiles[foto.id_galeri] = null;
                                }" class="text-xs text-red-500">
                          Batal
                        </button>
                        <button type="button" @click="showEditInput[foto.id_galeri] = false"
                          class="text-xs text-green-500">
                          Selesai
                        </button>
                      </div>
                    </div>
                  </div>

                  <!-- Deleted state -->
                  <div v-else class="w-32 h-32 border rounded-md bg-gray-100 flex flex-col items-center justify-center">
                    <span class="text-xs text-red-500">Akan dihapus</span>
                    <button type="button"
                      @click="deletedGaleriIds = deletedGaleriIds.filter(id => id !== foto.id_galeri)"
                      class="mt-2 text-xs text-blue-500">
                      Batalkan
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Laporan -->
            <div class="mb-2">
              <label class="text-sm font-medium text-gray-600">Laporan Kegiatan</label>
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
              <button type="submit" :disabled="!isFormValid"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                {{ props.kegiatan ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </FasilitatorLayout>
</template>