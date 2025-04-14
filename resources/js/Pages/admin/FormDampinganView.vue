<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { ref, watch, onMounted, computed } from 'vue'
import { Head, useForm } from "@inertiajs/vue3"
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  grup: Object,
  bidangs: Array,
  provinsis: Array,
  kabupatens: Array,
  kecamatans: Array,
  users: Array
})

const form = useForm({
  nama_grup_dampingan: "",
  id_bidang: "",
  jenis_dampingan: "",
  kode_provinsi: "",
  kode_kabupaten: "",
  kode_kecamatan: "",
  id_user: [""],
})

const filteredKabupatens = ref([])
const filteredKecamatans = ref([])

onMounted(() => {
  if (props.grup) {
    form.nama_grup_dampingan = props.grup.nama_grup_dampingan;
    form.id_bidang = props.grup.id_bidang;
    form.jenis_dampingan = props.grup.jenis_dampingan;
    form.kode_provinsi = props.grup.kode_provinsi;
    form.kode_kabupaten = props.grup.kode_kabupaten;
    form.kode_kecamatan = props.grup.kode_kecamatan;
    form.id_user = props.grup.users.map(user => String(user.id)) || [""];
  }
})

const fasilitatorOptions = computed(() => {
  if (!form.id_bidang) return []

  return props.users
    .filter(user =>
      user.bidangs.some(b => b.id_bidang === form.id_bidang)
    )
    .map(user => ({
      value: String(user.id),
      label: user.name
    }))
})


const bidangOptions = props.bidangs.map(b => ({
  value: b.id_bidang,
  label: b.nama_bidang,
}));

watch(() => form.kode_provinsi, () => {
  filterData()
})

watch(() => form.kode_kabupaten, () => {
  filterData()
})

const filterData = () => {
  filteredKabupatens.value = props.kabupatens.filter(
    (k) => k.kode_provinsi === form.kode_provinsi
  )
  filteredKecamatans.value = props.kecamatans.filter(
    (kec) => kec.kode_kabupaten === form.kode_kabupaten
  )
}

const addFasilitator = () => {
  form.id_user.push("");
};

const removeFasilitator = (index) => {
  if (form.id_user.length > 1) {
    form.id_user.splice(index, 1);
  }
};

const handleSubmit = () => {
  if (props.grup) {
    form.put(`/admin/data-dampingan/${props.grup.id_grup_dampingan}`);
  } else {
    form.post("/admin/data-dampingan");
  }
};
</script>

<template>
  <AdminLayout>

    <Head title="Form Dampingan" />
    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ props.grup ? "Form Edit Dampingan" : "Form Tambah Dampingan" }}
          </h2>

          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <!-- Nama Grup Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Nama Dampingan</label>
              <input v-model="form.nama_grup_dampingan" type="text"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Dampingan" />
            </div>

            <!-- Jenis Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Jenis Dampingan</label>
              <Multiselect v-model="form.jenis_dampingan" :options="['Pusat', 'Provinsi', 'Kabupaten', 'Kecamatan']"
                placeholder="Pilih Jenis Dampingan"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
            </div>

            <!-- Bidang Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Bidang Dampingan</label>
              <Multiselect v-model="form.id_bidang" :options="bidangOptions" placeholder="Pilih Bidang"
                class="w-full border border-gray-300 rounded-md shadow-sm text-sm" />
            </div>

            <div class="flex flex-col gap-2 pb-2">
              <!-- Provinsi -->
              <label for="kode_provinsi" class="text-sm font-medium text-gray-600">Provinsi</label>
              <select v-model="form.kode_provinsi" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Provinsi</option>
                <option v-for="p in provinsis" :key="p.kode" :value="p.kode">{{ p.nama }}</option>
              </select>

              <!-- Kabupaten -->
              <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten</label>
              <select v-model="form.kode_kabupaten" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Kabupaten</option>
                <option v-for="k in filteredKabupatens" :key="k.kode" :value="k.kode">{{ k.nama }}</option>
              </select>

              <!-- Kecamatan -->
              <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan</label>
              <select v-model="form.kode_kecamatan" class="w-full py-3 px-3 border rounded-md">
                <option value="" disabled>Pilih Kecamatan</option>
                <option v-for="kec in filteredKecamatans" :key="kec.kode" :value="kec.kode">{{ kec.nama }}</option>
              </select>
            </div>

            <!-- Fasilitator -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Fasilitator</label>

              <div v-for="(item, index) in form.id_user" :key="index" class="flex gap-2 items-center gap-2 mb-2">
                <Multiselect v-model="form.id_user[index]" :options="fasilitatorOptions" placeholder="Pilih Fasilitator"
                  class="w-full border border-gray-300 rounded-md shadow-sm text-sm" :searchable="true" />
                <button type="button" @click="addFasilitator"
                  class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600"
                  v-if="index === form.id_user.length - 1">
                  +
                </button>
                <button type="button" @click="removeFasilitator(index)"
                  class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600"
                  v-if="form.id_user.length > 1">
                  -
                </button>
              </div>
            </div>

            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('dampingan.index')"
                class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">Batal</a>
              <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md">
                {{ props.grup ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>