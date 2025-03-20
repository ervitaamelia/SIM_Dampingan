<script setup>
import { ref } from 'vue'

const newPassword = ref('')
const confirmPassword = ref('')

const handleSubmit = () => {
  if (!newPassword.value || !confirmPassword.value) {
    alert('Please fill in all fields')
    return
  }
  if (newPassword.value !== confirmPassword.value) {
    alert('Passwords do not match')
    return
  }
  console.log('Password change submitted')
}
</script>

<template>
  <main
    class="flex flex-col md:flex-row h-screen w-screen bg-white overflow-hidden"
  >
    <!-- Bagian Form -->
    <section
      class="w-full md:w-1/2 h-full flex items-center justify-center px-6 md:px-10"
    >
      <form
        @submit.prevent="handleSubmit"
        class="flex flex-col w-full max-w-md text-sm tracking-wide"
      >
        <header class="text-center mb-10">
          <h1
            class="text-3xl md:text-4xl font-medium tracking-wider text-black uppercase"
          >
            Lupa Password
          </h1>
        </header>

        <div class="flex flex-col mb-4">
          <label class="mb-2 font-medium text-neutral-900">Password Baru</label>
          <input
            type="password"
            v-model="newPassword"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
            placeholder="**********"
          />
        </div>

        <div class="flex flex-col mb-4">
          <label class="mb-2 font-medium text-neutral-900"
            >Konfirmasi Password</label
          >
          <input
            type="password"
            v-model="confirmPassword"
            class="px-4 py-3 rounded-xl border border-black border-opacity-30 shadow-sm text-zinc-600"
            placeholder="**********"
          />
        </div>

        <a
          href="login"
          class="w-full py-3 font-medium text-white bg-sky-600 rounded-xl shadow hover:bg-blue-700 transition-colors flex justify-center items-center"
        >
          Ubah Password
        </a>
      </form>
    </section>

    <section
      class="w-1/2 h-full overflow-hidden flex items-center justify-center"
    >
      <img
        src="https://cdn.builder.io/api/v1/image/assets/99e98c75e74449b086557677558acabb/565513d8e8b2eff5bf3fd018f49e7907f07b7b0998074991ec9da8a42f4ad693?placeholderIfAbsent=true"
        alt="Forgot Password Illustration"
        class="w-full h-full object-cover object-[50%_30%]"
      />
    </section>
  </main>
</template>