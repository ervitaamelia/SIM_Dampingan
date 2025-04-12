<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, watch, onMounted } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'

const { admin, provinsis, kabupatens, kecamatans } = defineProps({
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
  kode_provinsi: '',
  kode_kabupaten: '',
  kode_kecamatan: ''
})

const selectedRole = ref(null)
const filteredKabupatens = ref([])
const filteredKecamatans = ref([])

onMounted(() => {
  if (admin) {
    form.name = admin.name
    form.email = admin.email
    form.nomor_telepon = admin.nomor_telepon
    form.alamat = admin.alamat
  }
})

watch(selectedRole, (val) => {
  if (val) {
    form.role = val.value === 'superadmin' ? 'superadmin' : 'admin'
    form.admin_level = val.level
    // Reset kode saat role berubah
    form.kode_provinsi = ''
    form.kode_kabupaten = ''
    form.kode_kecamatan = ''
  }
})

watch(() => form.kode_provinsi, () => {
  filterData()
})

watch(() => form.kode_kabupaten, () => {
  filterData()
})

const filterData = () => {
  filteredKabupatens.value = kabupatens.filter(
    (k) => k.kode_provinsi === form.kode_provinsi
  )
  filteredKecamatans.value = kecamatans.filter(
    (kec) => kec.kode_kabupaten === form.kode_kabupaten
  )
}

const handleSubmit = () => {
  if (admin) {
    form.put(`/admin/data-admin/${admin.id}`)
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
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-5xl bg-white rounded-[20px] shadow-lg overflow-y-auto max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ admin ? 'Form Edit Admin' : 'Form Tambah Admin' }}
          </h2>
          <form @submit.prevent="handleSubmit" class="mt-6 w-full">

            <!-- Role -->
            <div class="flex flex-col gap-2 pb-2" v-if="!admin">
              <label for="role" class="text-sm font-medium text-gray-600">Role</label>
              <select id="role" v-model="selectedRole"
                class="w-full py-3 px-3 mt-1 border border-gray-300 rounded-md shadow-sm text-base">
                <option disabled value="">Pilih Role</option>
                <option v-for="option in roleOptions" :key="option.value" :value="option">
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
            <div class="flex flex-col gap-2 pb-2" v-if="!admin">
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

            <!--filter-->
            <div v-if="selectedRole?.level === 'provinsi'" class="flex flex-col gap-2 pb-2">
              <label for="kode_provinsi" class="text-sm font-medium text-gray-600">Provinsi</label>
              <select v-model="form.kode_provinsi" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Provinsi</option>
                <option v-for="p in provinsis" :key="p.kode" :value="p.kode">{{ p.nama }}</option>
              </select>
            </div>

            <!-- Jika Admin Kabupaten -->
            <div v-if="selectedRole?.level === 'kabupaten'" class="flex flex-col gap-2 pb-2">
              <label for="kode_provinsi" class="text-sm font-medium text-gray-600">Provinsi</label>
              <select v-model="form.kode_provinsi" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Provinsi</option>
                <option v-for="p in provinsis" :key="p.kode" :value="p.kode">{{ p.nama }}</option>
              </select>

              <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten</label>
              <select v-model="form.kode_kabupaten" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Kabupaten</option>
                <option v-for="k in filteredKabupatens" :key="k.kode" :value="k.kode">{{ k.nama }}</option>
              </select>
            </div>

            <!-- Jika Admin Kecamatan -->
            <div v-if="selectedRole?.level === 'kecamatan'" class="flex flex-col gap-2 pb-2">
              <label for="kode_provinsi" class="text-sm font-medium text-gray-600">Provinsi</label>
              <select v-model="form.kode_provinsi" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Provinsi</option>
                <option v-for="p in provinsis" :key="p.kode" :value="p.kode">{{ p.nama }}</option>
              </select>

              <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten</label>
              <select v-model="form.kode_kabupaten" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Kabupaten</option>
                <option v-for="k in filteredKabupatens" :key="k.kode" :value="k.kode">{{ k.nama }}</option>
              </select>

              <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan</label>
              <select v-model="form.kode_kecamatan" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Kecamatan</option>
                <option v-for="kec in filteredKecamatans" :key="kec.kode" :value="kec.kode">{{ kec.nama }}</option>
              </select>
            </div>

            <!-- Tombol -->
            <div class="flex justify-between mt-4">
              <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md">
                {{ admin ? 'Update' : 'Tambah' }}
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
