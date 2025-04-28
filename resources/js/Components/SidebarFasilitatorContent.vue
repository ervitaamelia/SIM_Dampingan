<script>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
  components: {
    Link,
  },
  data() {
    return {
      isMenuOpen: false,
      isDropdownOpen: false,
    }
  },
  methods: {
    toggleDropdown() {
      this.isDropdownOpen = !this.isDropdownOpen
    },
  },
  setup() {
    const page = usePage();

    // Aktif hanya kalau URL exact match
    const isExactActive = (href) => computed(() => page.url === href);

    // Aktif kalau URL diawali dengan prefix tertentu
    const isPrefixActive = (prefix) => computed(() => page.url.startsWith(prefix));

    return {
      isExactActive,
      isPrefixActive,
    };
  },
}
</script>

<template>
  <button class="lg:hidden block text-gray-600" @click="isMenuOpen = !isMenuOpen">☰</button>

  <aside :class="isMenuOpen ? 'block' : 'hidden'" class="w-72 bg-white shadow-md p-4 lg:block">
    <nav class="flex flex-col items-start py-5 pr-7 pl-2 w-full">
      <header class="flex gap-4 items-center text-2xl font-semibold text-black">
        <img
          src="/images/logo-mpm.png"
          class="object-contain w-10" alt="Logo MPM" />
        <h1>Dashboard</h1>
      </header>
    </nav>

    <!-- Profile Section -->
    <div class="mt-auto bg-gray-300 p-4 rounded-lg flex items-center gap-3">
      <img src="/images/default-profile.png" alt="Default Profile"
        class="w-12 h-12 rounded-full object-cover border-2 border-gray-300" />
      <div>
        <h2 class="text-md font-semibold text-gray-800">{{ $page.props.auth.user.name }}</h2>
        <p class="text-sm text-gray-500">{{ $page.props.auth.user.role }}</p>
      </div>
    </div>

    <ul class="w-full mt-10 space-y-3">

      <!-- Dashboard -->
      <li>
        <Link href="/fasilitator" :class="isExactActive('/fasilitator').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="shrink-0">
          <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z" stroke="currentColor"
            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          <path
            d="M16.2799 13.61C15.1499 14.74 13.5299 15.09 12.0999 14.64L9.50995 17.22C9.32995 17.41 8.95995 17.53 8.68995 17.49L7.48995 17.33C7.08995 17.28 6.72995 16.9 6.66995 16.51L6.50995 15.31C6.46995 15.05 6.59995 14.68 6.77995 14.49L9.35995 11.91C8.91995 10.48 9.25995 8.86001 10.3899 7.73001C12.0099 6.11001 14.6499 6.11001 16.2799 7.73001C17.8999 9.34001 17.8999 11.98 16.2799 13.61Z"
            stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg>
        <span>Dashboard</span>
        </Link>
      </li>

      <!-- Data Fasilitator -->
      <li>
        <Link href="/fasilitator/data-kegiatan" :class="isPrefixActive('/admin/data-fasilitator').value
          ? 'flex items-center gap-3 px-4 py-3 w-full text-white bg-sky-600 rounded-lg hover:bg-sky-700'
          : 'flex items-center gap-3 px-4 py-3 w-full rounded-lg hover:bg-blue-200'">
        <img
          src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/b162be8a0c28dfc7547cedfb4d9b5bfc761b405e9d2ed8ccd4447b54a75941c8?placeholderIfAbsent=true"
          class="w-6 shrink-0" alt="Facilitator icon" />
        <span>Data Kegiatan</span>
        </Link>
      </li>
      </ul>

    <!-- Logout -->
    <Link as="button" method="post" href="/logout"
      class="w-full py-2 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 mt-20">
    Keluar
    </Link>
  </aside>
</template>