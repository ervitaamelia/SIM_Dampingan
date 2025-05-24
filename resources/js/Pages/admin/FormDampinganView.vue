<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Multiselect from '@vueform/multiselect'
import { ref, watch, onMounted, computed } from 'vue'
import { Head, useForm } from "@inertiajs/vue3"
import '@vueform/multiselect/themes/default.css'

const props = defineProps({
  grup: Object,
  bidangs: Array,
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

onMounted(() => {
  fetchProvinsi();
  fetchKabupaten(props.grup.kode_provinsi);
  fetchKecamatan(props.grup.kode_kabupaten);

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

const getFasilitatorOptions = (index) => {
  if (!form.id_bidang) return []

  const selectedIds = form.id_user.filter((_, i) => i !== index)

  return props.users
    .filter(user =>
      user.bidangs.some(b => b.id_bidang === form.id_bidang) &&
      !selectedIds.includes(String(user.id)) // filter out already selected
    )
    .map(user => ({
      value: String(user.id),
      label: user.name
    }))
}

const bidangOptions = props.bidangs.map(b => ({
  value: b.id_bidang,
  label: b.nama_bidang,
}));

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

const namaGrupError = ref("");
const namaGrupValid = ref(false);

watch(() => form.nama_grup_dampingan, async (newValue) => {
  namaGrupError.value = "";
  namaGrupValid.value = false;

  if (!newValue) return;

  try {
    const query = new URLSearchParams({
      nama: newValue,
      id: props.grup?.id_grup_dampingan ?? ""
    });

    const res = await fetch(`/api/check-nama-grup?${query}`);
    const data = await res.json();

    if (data.exists) {
      namaGrupError.value = "Nama grup sudah digunakan.";
    } else {
      namaGrupValid.value = true;
    }
  } catch (e) {
    console.error("Gagal memeriksa nama grup:", e);
  }
});

const addFasilitator = () => {
  form.id_user.push("");
};

const removeFasilitator = (index) => {
  if (form.id_user.length > 1) {
    form.id_user.splice(index, 1);
  }
};

const isFormValid = computed(() => {
  return (
    form.nama_grup_dampingan &&
    form.id_bidang &&
    form.jenis_dampingan &&
    form.kode_provinsi &&
    form.kode_kabupaten &&
    form.kode_kecamatan &&
    form.id_user.every(user => user !== "")
  )
})

const handleSubmit = () => {
  if (namaGrupError.value) return;

  if (props.grup) {
    form.put(`/admin/data-dampingan/${props.grup.id_grup_dampingan}`);
  } else {
    form.post("/admin/data-dampingan");
  }
};
</script>

<template>
  <AdminLayout>

    <Head :title="props.grup ? 'Edit Grup Dampingan' : 'Tambah Grup Dampingan'" />
    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{ props.grup ? "Form Edit Grup Dampingan" : "Form Tambah Grup Dampingan" }}
          </h2>

          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <!-- Nama Grup Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Nama Grup Dampingan</label>
              <input v-model="form.nama_grup_dampingan" type="text"
                class="w-full py-2 px-3 mt-1 border rounded-md outline-none text-sm" :class="{
                  'border-gray-400': !namaGrupError,
                  'border-red-500': namaGrupError
                }" placeholder="Masukkan Nama Grup Dampingan" />
              <div v-if="namaGrupError" class="text-red-500 text-sm">
                {{ namaGrupError }}
              </div>
              <div v-else-if="namaGrupValid && form.nama_grup_dampingan" class="text-green-500 text-sm">
                Nama grup tersedia.
              </div>
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
              <Multiselect v-model="form.kode_provinsi" :options="provinsiList" placeholder="Pilih Provinsi"
                :searchable="true" class="w-full" @update:modelValue="fetchKabupaten" />

              <!-- Kabupaten -->
              <label for="kode_kabupaten" class="text-sm font-medium text-gray-600">Kabupaten</label>
              <Multiselect v-model="form.kode_kabupaten" :options="kabupatenList" placeholder="Pilih Kabupaten"
                :searchable="true" class="w-full" :disabled="!form.kode_provinsi" @update:modelValue="fetchKecamatan" />

              <!-- Kecamatan -->
              <label for="kode_kecamatan" class="text-sm font-medium text-gray-600">Kecamatan</label>
              <Multiselect v-model="form.kode_kecamatan" :options="kecamatanList" placeholder="Pilih Kecamatan"
                :searchable="true" class="w-full" :disabled="!form.kode_kabupaten" />
            </div>

            <!-- Fasilitator -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Fasilitator</label>

              <div v-for="(item, index) in form.id_user" :key="index" class="flex gap-2 items-center gap-2 mb-2">
                <Multiselect v-model="form.id_user[index]" :options="getFasilitatorOptions(index)"
                  placeholder="Pilih Fasilitator" class="w-full border border-gray-300 rounded-md shadow-sm text-sm"
                  :searchable="true" :disabled="!form.id_bidang" />
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
              <button type="submit" :disabled="!isFormValid"
                class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md disabled:opacity-50 disabled:cursor-not-allowed">
                {{ props.grup ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>