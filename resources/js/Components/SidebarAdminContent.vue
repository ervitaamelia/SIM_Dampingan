<script>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
  components: {
    Link,
  },
  mounted() {
    this.currentPhone = this.$page.props.nomor_telepon_admin || 'Belum tersedia';
  },
  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
      isMenuOpen: false,
      showPhoneModal: false,
      currentPhone: '',
      newPhone: '',

    };
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen;
    },
    formatRole(role) {
      return role
        .split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
    },
    savePhone() {
      this.$inertia.post('/simpan-nomor-telepon', { nomor_telepon: this.newPhone }, {
        onSuccess: () => {
          this.currentPhone = this.newPhone;
          this.newPhone = '';
          this.showPhoneModal = false;
        }
      });
    },
  },
  setup() {
    const page = usePage();

    const isExactActive = href => computed(() => page.url === href);
    const isPrefixActive = prefix => computed(() => page.url.startsWith(prefix));

    return {
      isExactActive,
      isPrefixActive,
    };
  },
};
</script>

<template>
  <!-- Toggle button on mobile -->
  <button class="lg:hidden block text-gray-600 p-4" @click="isMenuOpen = !isMenuOpen">
    ☰
  </button>

  <!-- Sidebar -->
  <aside :class="isMenuOpen ? 'block' : 'hidden'"
    class="fixed top-0 left-0 h-screen w-72 bg-white shadow-md p-4 overflow-y-auto lg:block z-50">
    <!-- Navigation -->
    <nav class="flex flex-col items-start py-5 pr-7 pl-2 w-full">
      <header class="flex gap-4 items-center text-2xl font-semibold text-black">
        <img src="/images/logo-mpm.png" class="object-contain w-10" alt="Logo MPM" />
        <h1>Dashboard</h1>
      </header>
    </nav>

    <!-- Profile Section -->
    <div class="mt-auto bg-gray-100 p-4 rounded-lg flex items-center gap-3">
      <img src="/images/default-profile.png" alt="Default Profile"
        class="w-12 h-12 rounded-full object-cover border-2 border-gray-300" />
      <div>
        <h2 class="text-md font-semibold text-gray-800">{{ $page.props.auth.user.name }}</h2>
        <p class="text-sm text-gray-500">{{ formatRole($page.props.auth.user.role) }}</p>
        <button v-if="$page.props.auth.user.role === 'superadmin'" @click="showPhoneModal = true"
          class="mt-1 text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
          📞 <span>Kontak Admin</span>
        </button>
      </div>
    </div>

    <!-- Menu Items -->
    <ul class="w-full mt-4 space-y-3">
      <li>
        <Link href="/admin" :class="isExactActive('/admin').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/5176c8cfe1c16182c950f9ebdbb8ff5c75131e74361beaed08846fef6c9b8576"
          class="object-contain shrink-0 w-6 aspect-square" alt="Dashboard icon" />
        <span>Dashboard</span>
        </Link>
      </li>

      <li>
        <Link href="/admin/data-admin" :class="isPrefixActive('/admin/data-admin').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/9c10e1ca5ac1d741dae3033862eb5ab8c9e4ddd2f22b080a5a6272539d83f278"
          class="w-6 shrink-0" alt="Admin icon" />
        <span>Data Admin</span>
        </Link>
      </li>

      <li>
        <Link href="/admin/data-fasilitator" :class="isPrefixActive('/admin/data-fasilitator').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/b162be8a0c28dfc7547cedfb4d9b5bfc761b405e9d2ed8ccd4447b54a75941c8"
          class="w-6 shrink-0" alt="Facilitator icon" />
        <span>Data Fasilitator</span>
        </Link>
      </li>

      <li>
        <Link href="/admin/data-dampingan" :class="isPrefixActive('/admin/data-dampingan').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/c089238d6fd542b8b9009bfa90175af913a34227304625c56c5947f0d0805040"
          class="w-6 shrink-0" alt="Data icon" />
        <span>Data Grup Dampingan</span>
        </Link>
      </li>

      <li>
        <Link href="/admin/data-masyarakat" :class="isPrefixActive('/admin/data-masyarakat').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/0bd9fafaddbf12073acb6d22718aa1fbbaa6da1f2897a8ca58c13e1963513b88"
          class="w-6 shrink-0" alt="User icon" />
        <span>Data Masyarakat</span>
        </Link>
      </li>

      <li>
        <Link href="/admin/kegiatan-dampingan" :class="isPrefixActive('/admin/kegiatan-dampingan').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/c089238d6fd542b8b9009bfa90175af913a34227304625c56c5947f0d0805040"
          class="w-6 shrink-0" alt="Data icon" />
        <span>Kegiatan Dampingan</span>
        </Link>
      </li>

      <li>
        <Link href="/admin/kelola-kegiatan" :class="isPrefixActive('/admin/kelola-kegiatan').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/c089238d6fd542b8b9009bfa90175af913a34227304625c56c5947f0d0805040"
          class="w-6 shrink-0" alt="Kelola icon" />
        <span>Kelola Kegiatan</span>
        </Link>
      </li>
    </ul>

    <!-- Logout -->
    <Link as="button" method="post" href="/logout"
      class="w-full py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 mt-9">
    Keluar
    </Link>
  </aside>

  <!-- Modal Pop-up -->
  <div v-if="showPhoneModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
      <h2 class="text-lg font-semibold mb-4">Nomor Telepon Admin</h2>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-1">Nomor Telepon Saat Ini:</label>
        <input type="text" v-model="currentPhone" disabled class="w-full p-2 border rounded bg-gray-100" />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm mb-1">Nomor Telepon Baru:</label>
        <input type="text" v-model="newPhone" placeholder="Masukkan nomer telepon yang baru"
          class="w-full p-2 border rounded" />
      </div>
      <div class="flex justify-end gap-2">
        <button @click="showPhoneModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
        <button @click="savePhone" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">OK</button>
      </div>
    </div>
  </div>

</template>