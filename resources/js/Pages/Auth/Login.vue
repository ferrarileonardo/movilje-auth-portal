<template>
    <q-layout view="hHh lpR fFf">
      <q-page-container>

        <q-page class="flex flex-center">
          <q-card class="q-pa-md" style="width: 350px">

            <q-card-section>
                <div class="logo-container">
                <img :src="logo" alt="Logo" class="header-logo" />
               </div>
               <h4 class="login-title">LOGIN</h4>

              <q-input v-model="form.email" label="Correo" />
              <q-input v-model="form.password" label="Contraseña" type="password" />
            </q-card-section>
            <q-card-actions align="right" class="btn-container">
              <q-btn color="primary" label="Iniciar sesión" @click="loginUser" />
              <q-btn color="secondary" label="Registrarse" @click="redirectToRegister" />
            </q-card-actions>

          </q-card>
        </q-page>
      </q-page-container>
    </q-layout>
  </template>

  <script>
  import { ref } from 'vue';
  import axios from 'axios';
  import logo from '@/assets/img/logo.png';
  import { router } from '@inertiajs/vue3'; // ✅ Importa router de Inertia

  export default {
    setup() {
      const form = ref({
        email: '',
        password: ''
      });

  const redirectToRegister = () => {
  router.visit('/register'); // 🔹 Usa Inertia para navegar
     };
const logo = '/assets/img/logo.png';


      const loginUser = async () => {
        try {
          const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

          if (!csrfToken) {
            console.error('⚠️ No se encontró el token CSRF');
            return;
          }

          const response = await axios.post('/login', form.value, {
            headers: {
              'Accept': 'application/json', // ✅ Asegura que Laravel devuelve JSON
              'X-CSRF-TOKEN': csrfToken
            }
          });

          if (response.headers['content-type']?.includes('application/json')) {
            if (response.status === 200 && response.data?.message === 'Sesión iniciada' && response.data?.user) {
              console.log('✅ Inicio de sesión exitoso:', response.data.user);
              window.location.href = '/dashboard';
            } else {
              console.error('❌ Error en el inicio de sesión:', response.data);
            }
          } else {
            console.error('⚠️ Laravel devolvió HTML en lugar de JSON.');
          }
        } catch (error) {
          console.error('❌ Error en el inicio de sesión:', error.response?.data || error.message);
        }
      };

      return { form, loginUser,logo,redirectToRegister  };
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
