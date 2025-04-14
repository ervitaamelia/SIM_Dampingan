<script setup>
import { onMounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
  fasilitator: Object,
  bidangs: Array,
});

const form = useForm({
  name: "",
  email: "",
  password: "",
  nomor_telepon: "",
  alamat: "",
  bidang_dampingan: [""],
});

onMounted(() => {
  if (props.fasilitator) {
    form.name = props.fasilitator.name;
    form.email = props.fasilitator.email;
    form.nomor_telepon = props.fasilitator.nomor_telepon;
    form.alamat = props.fasilitator.alamat;
    form.bidang_dampingan = props.fasilitator.bidang_dampingan || [""];
  }
});

const addBidang = () => {
  form.bidang_dampingan.push("");
};

const removeBidang = (index) => {
  if (form.bidang_dampingan.length > 1) {
    form.bidang_dampingan.splice(index, 1);
  }
};

const handleSubmit = () => {
  if (props.fasilitator) {
    form.put(`/admin/data-fasilitator/${props.fasilitator.id}`);
  } else {
    form.post("/admin/data-fasilitator");
  }
};
</script>

<template>
  <AdminLayout>

    <Head title="Form Fasilitator" />

    <div class="ml-5 w-full max-md:w-full mx-auto flex justify-center">
      <section
        class="flex flex-col items-center px-8 pt-6 pb-8 mt-12 w-full max-w-4xl bg-white rounded-[20px] shadow-lg overflow-y-auto scrollbar-hide max-h-[80vh]">
        <div class="flex flex-col w-full">
          <h2 class="self-center text-2xl font-bold text-black">
            {{
              props.fasilitator
                ? "Form Edit Fasilitator"
                : "Form Tambah Fasilitator"
            }}
          </h2>

          <form @submit.prevent="handleSubmit" class="mt-6 w-full">
            <!-- Nama Lengkap -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="name" class="text-sm font-medium text-gray-600">Nama Lengkap</label>
              <input id="name" type="text" v-model="form.name"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Nama Lengkap" />
            </div>

            <!-- Alamat -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="alamat" class="text-sm font-medium text-gray-600">Alamat</label>
              <textarea id="alamat" v-model="form.alamat" rows="4"
                class="w-full py-3 px-3 mt-1 border border-gray-400 rounded-md outline-none text-base resize-none"
                placeholder="Masukkan Alamat"></textarea>
            </div>

            <!-- No. Telepon -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="nomor_telepon" class="text-sm font-medium text-gray-600">No. Telepon</label>
              <input id="nomor_telepon" type="tel" v-model="form.nomor_telepon"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan No. Telepon" />
            </div>

            <!-- Bidang Dampingan -->
            <div class="flex flex-col gap-2 pb-2">
              <label class="text-sm font-medium text-gray-600">Bidang Dampingan</label>

              <div v-for="(bidang, index) in form.bidang_dampingan" :key="index" class="flex items-center gap-2 mb-2">
                <select v-model="form.bidang_dampingan[index]"
                  class="w-full py-2 px-3 border border-gray-400 rounded-md text-sm">
                  <option value="" disabled>
                    Pilih Bidang
                  </option>
                  <option v-for="b in props.bidangs" :key="b.id" :value="b.id">
                    {{ b.nama }}
                  </option>
                </select>

                <button type="button" @click="addBidang"
                  class="bg-green-500 text-white px-3 py-1 rounded-md text-sm hover:bg-green-600" v-if="
                    index ===
                    form.bidang_dampingan.length - 1
                  ">
                  +
                </button>
                <button type="button" @click="removeBidang(index)"
                  class="bg-red-500 text-white px-3 py-1 rounded-md text-sm hover:bg-red-600"
                  v-if="form.bidang_dampingan.length > 1">
                  -
                </button>
              </div>
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-2 pb-2">
              <label for="email" class="text-sm font-medium text-gray-600">Email</label>
              <input id="email" type="email" v-model="form.email"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Email" />
            </div>

            <!-- Password -->
            <div class="flex flex-col gap-2 pb-2" v-if="!props.fasilitator">
              <label for="password" class="text-sm font-medium text-gray-600">Password</label>
              <input id="password" type="password" v-model="form.password"
                class="w-full py-2 px-3 mt-1 border border-gray-400 rounded-md outline-none text-sm"
                placeholder="Masukkan Password" />
            </div>

            <div class="flex gap-4 mt-4 justify-end">
              <a :href="route('fasilitator.index')" class="px-6 py-2 text-sm font-medium text-white bg-gray-500 rounded-md">
                Batal
              </a>
              <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-sky-600 rounded-md">
                {{ props.fasilitator ? "Simpan Perubahan" : "Tambah" }}
              </button>
            </div>

          </form>
        </div>
      </section>
    </div>
  </AdminLayout>
</template>
