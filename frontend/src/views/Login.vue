<template>
  <div class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
      <h2 class="text-2xl font-bold mb-4 text-center">Iniciar Sesión</h2>
      <form @submit.prevent="login" class="space-y-4">
        <input v-model="email" type="email" placeholder="Email"
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input v-model="password" type="password" placeholder="Contraseña"
               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
          Entrar
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      email: '',
      password: ''
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post('/auth/login', {
          email: this.email,
          password: this.password
        });
        localStorage.setItem('token', response.data.access_token);
        this.$router.push('/dashboard');
      } catch (error) {
        console.error('Error de autenticación');
      }
    }
  }
};
</script>