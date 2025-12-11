<template>
    <q-layout view="hHh lpR fFf">
      <q-page-container>
        <q-page class="flex flex-center">
          <q-card class="q-pa-md" style="width: 400px">
            <q-card-section>
              <h4 class="dashboard-title">Bienvenido, {{ user.name }}</h4>
              <q-list separator>
                <q-item>
                  <q-item-section>Correo: {{ user.email }}</q-item-section>
                </q-item>
                <q-item>
                  <q-item-section>ID: {{ user.id }}</q-item-section>
                </q-item>
              </q-list>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn color="negative" label="Cerrar sesión" @click="logout" />
            </q-card-actions>
          </q-card>
        </q-page>
      </q-page-container>
    </q-layout>
  </template>

  <script>
  import { defineComponent } from 'vue';
  import { usePage } from '@inertiajs/vue3';
  import axios from 'axios';

  export default defineComponent({
    setup() {
      const { props } = usePage();
      const user = props.user;

      const logout = async () => {
        await axios.post('/logout');
        window.location.href = '/login';
      };

      return { user, logout };
    }
  });
  </script>

  <style scoped>
  .dashboard-title {
    font-size: 1.8rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 20px;
  }
  </style>
