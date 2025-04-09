<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { ref, watch, onMounted } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  admin: Object,
  provinsis: Array,
  kabupatens: Array,
  kecamatans: Array
})

const roleOptions = [
  { value: 'superadmin', label: 'Admin Pusat', level: '' },
  { value: 'admin', label: 'Admin Provinsi', level: 'provinsi' },
  { value: 'admin', label: 'Admin Kabupaten', level: 'kabupaten' },
  { value: 'admin', label: 'Admin Kecamatan', level: 'kecamatan' }
]

const form = useForm({
  name: '',
  email: '',
  password: '',
  nomor_telepon: '',
  alamat: '',
  role: '',
  admin_level: '',
  kode_provinsi: null,
  kode_kabupaten: null,
  kode_kecamatan: null
})

const selectedRole = ref(null)

onMounted(() => {
  console.log('Isi props.provinsis:', props.provinsis)
  console.log('Apakah array:', Array.isArray(props.provinsis))
  console.log('Contoh item:', props.provinsis[0])

  if (props.admin) {
    form.name = props.admin.name
    form.email = props.admin.email
    form.nomor_telepon = props.admin.nomor_telepon
    form.alamat = props.admin.alamat
    form.role = props.admin.role
    form.admin_level = props.admin.admin_level
    form.kode_provinsi = props.admin.kode_provinsi
    form.kode_kabupaten = props.admin.kode_kabupaten
    form.kode_kecamatan = props.admin.kode_kecamatan

    selectedRole.value = roleOptions.find(
      (r) => r.value === props.admin.role && r.level === props.admin.admin_level
    )
  }
  if (!props.admin) {
    form.kode_provinsi = null
    form.kode_kabupaten = null
    form.kode_kecamatan = null
  }
})

watch(selectedRole, (val) => {
  if (val) {
    form.role = val.value
    form.admin_level = val.level

    // Reset lokasi saat role berubah
    form.kode_provinsi = null
    form.kode_kabupaten = null
    form.kode_kecamatan = null
  }
})

const handleSubmit = () => {
  if (props.admin) {
    form.put(`/admin/data-admin/${props.admin.id}`)
  } else {
    form.post('/admin/data-admin')
  }
}
</script>

<template>
  <AdminLayout>
    <Head title="Form Admin" />

    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-2xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ props.admin ? 'Form Edit Admin' : 'Form Tambah Admin' }}
          </h2>
          <form @submit.prevent="handleSubmit" class="mt-6 w-full">

            <!-- Role -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="role" class="text-sm font-medium text-gray-600">Role</label>
              <select id="role" v-model="selectedRole"
                class="w-full py-3 px-3 mt-1 border border-gray-300 rounded-md shadow-sm text-base">
                <option disabled value="">Pilih Role</option>
                <option v-for="option in roleOptions" :key="option.label" :value="option">
                  {{ option.label }}
                </option>
              </select>
            </div>

            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="name" class="text-sm font-medium text-gray-600">Nama Lengkap</label>
              <input id="name" type="text" v-model="form.name"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Nama Lengkap" />
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="nomor_telepon" class="text-sm font-medium text-gray-600">No. Telepon</label>
              <input id="nomor_telepon" type="tel" v-model="form.nomor_telepon"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan No. Telepon" />
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="email" class="text-sm font-medium text-gray-600">Email</label>
              <input id="email" type="email" v-model="form.email"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Email" />
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-2 pb-2" v-if="!props.admin">
              <label for="password" class="text-sm font-medium text-gray-600">Password</label>
              <input id="password" type="password" v-model="form.password"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base"
                placeholder="Masukkan Password" />
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="alamat" class="text-sm font-medium text-gray-600">Alamat</label>
              <textarea id="alamat" v-model="form.alamat" rows="4"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base resize-none"
                placeholder="Masukkan Alamat"></textarea>
            </div>

            <!-- Provinsi -->
            <div v-if="['provinsi', 'kabupaten', 'kecamatan'].includes(form.admin_level)"
              class="flex flex-col gap-2 pb-2">
              <label for="provinsi" class="text-sm font-medium text-gray-600">Provinsi</label>
              <Multiselect
                v-model="form.kode_provinsi"
                :options="props.provinsis"
                placeholder="Pilih Provinsi..."
                class="w-full border border-gray-300 rounded-md shadow-sm"
                :searchable="true"
                label="nama"
                track-by="kode"
                :preselect="false"
              />
            </div>

            <!-- Kabupaten -->
            <div v-if="['kabupaten', 'kecamatan'].includes(form.admin_level)" class="flex flex-col gap-2 pb-2">
              <label for="kabupaten" class="text-sm font-medium text-gray-600">Kabupaten</label>
              <Multiselect
                v-model="form.kode_kabupaten"
                :options="props.kabupatens"
                placeholder="Pilih Kabupaten..."
                class="w-full border border-gray-300 rounded-md shadow-sm"
                :searchable="true"
                label="nama"
                track-by="kode"
                :preselect="false"
              />
            </div>

            <!-- Kecamatan -->
            <div v-if="form.admin_level === 'kecamatan'" class="flex flex-col gap-2 pb-2">
              <label for="kecamatan" class="text-sm font-medium text-gray-600">Kecamatan</label>
              <Multiselect
                v-model="form.kode_kecamatan"
                :options="props.kecamatans"
                placeholder="Pilih Kecamatan..."
                class="w-full border border-gray-300 rounded-md shadow-sm"
                :searchable="true"
                label="nama"
                track-by="kode"
                :preselect="false"
              />
            </div>

            <!-- Tombol -->
            <div class="flex justify-between mt-4">
              <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md">
                {{ props.admin ? 'Update' : 'Tambah' }}
              </button>
              <a href="/admin/data-admin" class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">
                Batal
              </a>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
