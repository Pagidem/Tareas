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
        
        <!-- === LÓGICA CONDICIONAL AQUÍ === -->
        <div v-if="tareas.length > 0">
            <!-- Muestra la tabla si hay tareas -->
            <Tabla 
                :tareas="tareas" 
                @mostrar-detalle="handleMostrarDetalle" 
            />
        </div>
        <div v-else class="d-flex justify-content-center align-items-center h-100 p-4">
            <!-- Muestra el mensaje si NO hay tareas -->
            <h3 class="text-center text-muted">
                No se tienen registros. Por favor, utilice el botón "Registrar Tarea" para comenzar.
            </h3>
        </div>
        <!-- ============================== -->

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
import { ref, onMounted } from "vue"; // Importamos onMounted para simular la carga
import Titulo from "./Titulo.vue";
import Subtitulo from "./Subtitulo.vue";
import Formulario from "./SeccionRegistrar/Formulario.vue";
import Tabla from "./SeccionRegistros/Tabla.vue";
import DetalleTarea from "./SeccionRegistros/DetalleTarea.vue";

export default {
  components: { Titulo, Subtitulo, Formulario, Tabla, DetalleTarea },
  setup() {
    const showModal = ref(false); // Modal de Registro
    const showDetalleModal = ref(false); // Modal de Detalle
    const tareaSeleccionada = ref(null); // Tarea seleccionada
    
    // 1. AÑADIMOS EL ESTADO DE LAS TAREAS
    // Debes reemplazar este Array vacío con la lógica que carga tus tareas (Axios/Firestore)
    const tareas = ref([]); 

    // ************* LÓGICA DE CARGA SIMULADA (REEMPLAZAR) *************
    // onMounted(() => {
    //   // Aquí se debería llamar a tu función para obtener tareas de la API
    //   // Ejemplo: fetchTareas().then(data => tareas.value = data);
    //   // Si la API devuelve un arreglo vacío, el mensaje se mostrará.
    //   // Si la API devuelve datos, la tabla se mostrará.
    //   // Para el ejemplo, la dejamos vacía.
    // });
    // *****************************************************************

    const handleMostrarDetalle = (tarea) => {
      tareaSeleccionada.value = tarea;
      showDetalleModal.value = true;
    };

    return { 
        showModal, 
        showDetalleModal, 
        tareaSeleccionada, 
        handleMostrarDetalle,
        tareas // 2. HACEMOS QUE LA LISTA DE TAREAS ESTÉ DISPONIBLE
    };
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
/* Asegura que el contenedor del mensaje ocupe el espacio */
.card-body.tabla-scroll {
    display: flex;
    flex-direction: column;
}
.card-body.tabla-scroll > div {
    flex-grow: 1;
}
</style>
