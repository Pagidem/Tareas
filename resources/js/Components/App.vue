<template>
  <div class="card h-100">
    <Titulo />

    <div class="card-body d-flex flex-column align-items-start">
      <Subtitulo @abrir-modal="showModal = true" />
      <Formulario :mostrar="showModal" @cerrar="showModal = false" />
    </div>

    <div class="card flex-grow-1 d-flex flex-column">
      <div class="card-header bg-secondary text-white text-center">
        <h2><strong>Tareas Registradas</strong></h2>
      </div>

      <div class="card-body tabla-scroll flex-grow-1">
        <Tabla @mostrar-detalle="handleMostrarDetalle" />
      </div>
    </div>
    
    <DetalleTarea 
        :mostrar="showDetalleModal" 
        :tarea="tareaSeleccionada" 
        @cerrar="showDetalleModal = false; tareaSeleccionada = null" 
    />
  </div>
</template>

<script>
import { ref } from "vue";
import Titulo from "./Titulo.vue";
import Subtitulo from "./Subtitulo.vue";
import Formulario from "./SeccionRegistrar/Formulario.vue";
import Tabla from "./SeccionRegistros/Tabla.vue";
import DetalleTarea from "./SeccionRegistros/DetalleTarea.vue"; // Asegúrate de importar DetalleTarea

export default {
  components: { Titulo, Subtitulo, Formulario, Tabla, DetalleTarea },
  setup() {
    const showModal = ref(false); // Modal de Registro
    const showDetalleModal = ref(false); // 🚀 Modal de Detalle
    const tareaSeleccionada = ref(null); // 🚀 Tarea seleccionada
    
    const handleMostrarDetalle = (tarea) => {
      tareaSeleccionada.value = tarea;
      showDetalleModal.value = true;
    };

    return { showModal, showDetalleModal, tareaSeleccionada, handleMostrarDetalle };
  },
};
</script>

<style scoped>
/* Estilos globales (mantener) */
html, body, #app {
  height: 100%;
  margin: 0;
  padding: 0;
}
.card {
  display: flex;
  flex-direction: column;
  height: 100%;
}
.tabla-scroll {
  overflow-y: auto;
  max-height: 400px; /* Ajusta este valor según el espacio que quieras */
}
</style>