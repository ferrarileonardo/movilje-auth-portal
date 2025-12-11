<template>
    <q-layout view="hHh lpR fFf">
      <q-page-container> <!-- ✅ Agregar QPageContainer -->
        <q-page class="flex flex-center">
          <q-card class="q-pa-md" style="width: 350px">
            <q-card-section>
                <div class="logo-container">
                <img :src="logo" alt="Logo" class="header-logo" />
               </div>
               <h4 class="login-title">Registrarse</h4>
              <q-input v-model="form.name" label="Nombre" />
              <q-input v-model="form.email" label="Correo" />
              <q-input v-model="form.password" label="Contraseña" type="password" />
              <q-input v-model="form.password_confirmation" label="Confirmar Contraseña" type="password" />
            </q-card-section>

            <q-card-actions align="right"  class="btn-container">
              <q-btn color="primary" label="Registrarse" @click="registerUser" />
            </q-card-actions>
          </q-card>
        </q-page>
      </q-page-container> <!-- ✅ Cerramos QPageContainer -->
    </q-layout>
  </template>

  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  import logo from '@/assets/img/logo.png';

  export default {
    setup() {
      const form = ref({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      });
      const logo = '/assets/img/logo.png';

      const registerUser = async () => {
        try {
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

          if (!csrfToken) {
            console.error('⚠️ No se encontró el token CSRF');
            return;
          }

          const response = await axios.post('/register', form.value, {
            headers: {
              'Accept': 'application/json', // ✅ Asegura que Laravel devuelve JSON
              'X-CSRF-TOKEN': csrfToken
            }
          });

          if (response.headers['content-type']?.includes('application/json')) {
            if (response.status === 201 && response.data?.message === 'Registro exitoso' && response.data?.user) {
              console.log('🎉 Registro exitoso:', response.data.user);
              window.location.href = '/login';
            } else {
              console.error('❌ Error en el registro:', response.data);
            }
          } else {
            console.error('⚠️ Laravel devolvió HTML en lugar de JSON.');
          }
        } catch (error) {
          console.error('❌ Error en el registro:', error.response?.data || error.message);
        }
      };

      return { form, registerUser,logo };
    },
  };
  </script>

<style scoped>
.logo-container {
display: flex;
justify-content: center;
margin-bottom: 20px; /* Espacio entre el logo y el título */
}
/* Texto "LOGIN" */
.login-title {
font-size: 1.8rem; /* Ajuste de tamaño */
font-weight: bold;
text-align: center;
padding-top: 15px; /* Espacio extra */
}


/* Tamaño del logo */
.header-logo {
width: 140px; /* Ligera reducción */
height: 90px;
padding: 5px;
}
/* 🔹 Botón centrado */
.btn-container {
display: flex;
justify-content: center;
}
</style>
