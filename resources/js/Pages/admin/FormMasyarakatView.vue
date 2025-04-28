<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { Head, useForm } from "@inertiajs/vue3"
import { computed } from 'vue'
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  masyarakat: Object,
  pekerjaans: Object,
  bidangs: Array,
  grups: Array,
})

// Inisialisasi form dengan data masyarakat jika ada
const form = useForm(props.masyarakat ? {
  nama_lengkap: props.masyarakat.nama_lengkap,
  tempat_lahir: props.masyarakat.tempat_lahir,
  tanggal_lahir: props.masyarakat.tanggal_lahir,
  jenis_kelamin: props.masyarakat.jenis_kelamin,
  agama: props.masyarakat.agama,
  nomor_telepon: props.masyarakat.nomor_telepon,
  alamat: props.masyarakat.alamat,
  status: props.masyarakat.status,
  foto: null, // Tetap null untuk file upload
  id_pekerjaan: props.masyarakat.id_pekerjaan,
  id_bidang: props.masyarakat.id_bidang,
  id_grup_dampingan: props.masyarakat.id_grup_dampingan,
  _method: props.masyarakat ? 'PUT' : 'POST' // Penting untuk update
} : {
  nama_lengkap: "",
  tempat_lahir: "",
  tanggal_lahir: "",
  jenis_kelamin: "",
  agama: "",
  nomor_telepon: "",
  alamat: "",
  status: "",
  foto: null,
  id_pekerjaan: "",
  id_bidang: "",
  id_grup_dampingan: "",
})

const pekerjaanOptions = props.pekerjaans.map(b => ({
  value: b.id_pekerjaan,
  label: b.nama_pekerjaan
}))

const bidangOptions = props.bidangs.map(b => ({
  value: b.id_bidang,
  label: b.nama_bidang,
}))

const grupOptions = computed(() => {
  if (!form.id_bidang) return []
  return props.grups
    .filter(grup => grup.id_bidang === form.id_bidang)
    .map(grup => ({
      value: grup.id_grup_dampingan,
      label: grup.nama_grup_dampingan
    }))
})

function handleSubmit() {
  if (props.masyarakat) {
    // Untuk update, gunakan post dengan _method PUT
    form.post(route('masyarakat.update', props.masyarakat.nomor_anggota), {
      preserveScroll: true,
      onSuccess: () => form.reset(),
    })
  } else {
    form.post(route('masyarakat.store'), {
      preserveScroll: true,
      onSuccess: () => form.reset(),
    })
  }
}
</script>

<template>
  <AdminLayout>
    <Head :title="masyarakat ? 'Edit Masyarakat' : 'Tambah Masyarakat'" />
    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ masyarakat ? "Form Edit Masyarakat Dampingan" : "Form Tambah Masyarakat Dampingan" }}
          </h2>
          <form @submit.prevent="handleSubmit" enctype="multipart/form-data" class="mt-6 w-full">
            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
              <input type="text" v-model="form.nama_lengkap"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Lengkap" />
              <div v-if="form.errors.nama_lengkap" class="text-red-500 text-sm">
                {{ form.errors.nama_lengkap }}
              </div>
            </div>

            <!-- Tempat Lahir -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Tempat Lahir</label>
              <input type="text" v-model="form.tempat_lahir"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Tempat Lahir" />
              <div v-if="form.errors.tempat_lahir" class="text-red-500 text-sm">
                {{ form.errors.tempat_lahir }}
              </div>
            </div>

            <!-- Tanggal Lahir -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Tanggal Lahir</label>
              <input type="date" v-model="form.tanggal_lahir"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Tanggal Lahir" />
              <div v-if="form.errors.tanggal_lahir" class="text-red-500 text-sm">
                {{ form.errors.tanggal_lahir }}
              </div>
            </div>

            <!-- Jenis Kelamin -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Jenis Kelamin</label>
              <div class="flex items-center gap-4 mt-1">
                <label class="flex items-center gap-1 text-sm">
                  <input type="radio" value="Perempuan" v-model="form.jenis_kelamin" />
                  Perempuan
                </label>
                <label class="flex items-center gap-1 text-sm">
                  <input type="radio" value="Laki-laki" v-model="form.jenis_kelamin" />
                  Laki-laki
                </label>
              </div>
              <div v-if="form.errors.jenis_kelamin" class="text-red-500 text-sm">
                {{ form.errors.jenis_kelamin }}
              </div>
            </div>

            <!-- Agama -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">
                Agama
              </label>
              <Multiselect v-model="form.agama"
                :options="['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']"
                placeholder="Pilih Agama" class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
              <div v-if="form.errors.agama" class="text-red-500 text-sm">
                {{ form.errors.agama }}
              </div>
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">No. Telepon</label>
              <input type="tel" v-model="form.nomor_telepon"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan No. Telepon" />
              <div v-if="form.errors.nomor_telepon" class="text-red-500 text-sm">
                {{ form.errors.nomor_telepon }}
              </div>
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Alamat</label>
              <textarea v-model="form.alamat"
                class="w-full h-20 py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm resize-none"
                placeholder="Masukkan Alamat"></textarea>
              <div v-if="form.errors.alamat" class="text-red-500 text-sm">
                {{ form.errors.alamat }}
              </div>
            </div>

            <!-- Status -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Status</label>
              <div class="flex items-center gap-4 mt-1">
                <label class="flex items-center gap-1 text-sm">
                  <input type="radio" value="Aktif" v-model="form.status" />
                  Aktif
                </label>
                <label class="flex items-center gap-1 text-sm">
                  <input type="radio" value="Non Aktif" v-model="form.status" />
                  Non Aktif
                </label>
              </div>
              <div v-if="form.errors.status" class="text-red-500 text-sm">
                {{ form.errors.status }}
              </div>
            </div>

            <!-- Pekerjaan Utama -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">
                Pekerjaan Utama
              </label>
              <Multiselect v-model="form.id_pekerjaan" :options="pekerjaanOptions" placeholder="Pilih Pekerjaan"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" :searchable="true" />
              <div v-if="form.errors.id_pekerjaan" class="text-red-500 text-sm">
                {{ form.errors.id_pekerjaan }}
              </div>
            </div>

            <!-- Bidang Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">
                Bidang Dampingan
              </label>
              <Multiselect v-model="form.id_bidang" :options="bidangOptions" placeholder="Pilih Bidang"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
              <div v-if="form.errors.id_bidang" class="text-red-500 text-sm">
                {{ form.errors.id_bidang }}
              </div>
            </div>

            <!-- Grup Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">
                Grup Dampingan
              </label>
              <Multiselect v-model="form.id_grup_dampingan" :options="grupOptions" placeholder="Pilih Grup Dampingan"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" :searchable="true" />
              <div v-if="form.errors.id_grup_dampingan" class="text-red-500 text-sm">
                {{ form.errors.id_grup_dampingan }}
              </div>
            </div>

            <!-- Foto -->
            <div class="mb-4">
              <label class="text-sm font-medium text-gray-600">Foto</label>
              <input type="file" @change="form.foto = $event.target.files[0]"
                class="w-full py-2 px-3 border border-gray-400 rounded-md outline-none text-sm" />
              <div v-if="form.errors.foto" class="text-red-500 text-sm">
                {{ form.errors.foto }}
              </div>
            </div>
            <div v-if="props.masyarakat?.foto" class="mb-4">
              <img :src="`/storage/${props.masyarakat.foto}`" alt="Foto Masyarakat" class="w-32 rounded" />
              <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
            </div>

            <!-- Tombol Submit -->
            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('masyarakat.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" :disabled="form.processing"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md disabled:opacity-50">
                {{ form.processing ? 'Memproses...' : (masyarakat ? 'Simpan Perubahan' : 'Tambah') }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>