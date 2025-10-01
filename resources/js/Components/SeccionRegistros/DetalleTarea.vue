<template>
  <transition name="fade">
    <div
      v-if="mostrar && form.id"
      class="modal-backdrop-custom d-flex justify-content-center align-items-center"
    >
      <div class="modal show d-block w-100 h-100" tabindex="-1" role="dialog">
        <div
          class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down"
          role="document"
        >
          <div class="modal-content h-100">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">Detalle de la tarea (ID: {{ form.id }})</h5>
              <button
                type="button"
                class="btn-close btn-close-white"
                @click="$emit('cerrar')"
              ></button>
            </div>

            <form @submit.prevent="handleSubmit" novalidate class="d-flex flex-column h-100">
              <div class="modal-body flex-grow-1 overflow-auto">

                <!-- Titulo -->
                <div class="mb-3">
                  <label for="titulo" class="form-label">Tarea</label>
                  <input
                    type="text"
                    class="form-control"
                    id="titulo"
                    v-model="form.titulo"
                    required
                  />
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                  <label for="descripcion" class="form-label">Descripción</label>
                  <textarea
                    class="form-control"
                    id="descripcion"
                    v-model="form.descripcion"
                    required
                  ></textarea>
                </div>

                <!-- Fecha y Hora Inicio -->
                <div class="row mb-3">
                  <div class="col-6">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input
                      type="date"
                      class="form-control"
                      id="fecha_inicio"
                      v-model="form.fecha_inicio"
                    />
                  </div>
                  <div class="col-6">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input
                      type="time"
                      class="form-control"
                      id="hora_inicio"
                      v-model="form.hora_inicio"
                    />
                  </div>
                </div>

                <!-- Fecha y Hora Fin -->
                <div class="row mb-3">
                  <div class="col-6">
                    <label for="fecha_fin" class="form-label">Fecha Fin</label>
                    <input
                      type="date"
                      class="form-control"
                      id="fecha_fin"
                      v-model="form.fecha_fin"
                    />
                  </div>
                  <div class="col-6">
                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                    <input
                      type="time"
                      class="form-control"
                      id="hora_fin"
                      v-model="form.hora_fin"
                    />
                  </div>
                </div>

                <!-- Prioridad y Estado -->
                <div class="row mb-3">
                  <div class="col-6">
                    <label for="prioridad" class="form-label">Prioridad</label>
                    <select
                      id="prioridad"
                      class="form-select"
                      v-model="form.prioridad"
                    >
                      <option value="Normal">Normal</option>
                      <option value="Alta">Alta</option>
                      <option value="Media">Media</option>
                      <option value="Baja">Baja</option>
                    </select>
                  </div>

                  <div class="col-6">
                    <label for="estado" class="form-label">Estado</label>
                    <input
                      type="text"
                      class="form-control"
                      id="estado"
                      v-model="form.estado"
                      readonly
                    />
                  </div>
                </div>

              </div>

              <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" @click="$emit('cerrar')">
                  Cerrar
                </button>
                <button type="button" class="btn btn-primary" @click="editarTarea">
                  Editar
                </button>
                <button type="button" class="btn btn-success" @click="cambiarEstado('Concluida')" v-if="form.estado == 'Pendiente'">
                  Concluir
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from "axios";

export default {
  name: "DetalleTarea",
  // 🚀 Emitimos el evento 'actualizar-lista' para que el padre recargue la lista
  emits: ['cerrar', 'actualizar-lista'], 
  props: {
    mostrar: { type: Boolean, default: false },
    // CORRECCIÓN: Permitir null o un objeto para evitar el error de validación de Vue.
    tarea: { 
        type: [Object, null], 
        default: () => ({ 
            id: null, titulo: '', descripcion: '', fecha_inicio: '', 
            hora_inicio: '', fecha_fin: '', hora_fin: '', prioridad: 'Normal', 
            estado: 'Pendiente' 
        }) 
    },
  },
  data() {
    return {
      // Usamos una copia de los datos de la tarea o un objeto vacío si tarea es null
      form: { 
          id: null, titulo: '', descripcion: '', fecha_inicio: '', 
          hora_inicio: '', fecha_fin: '', hora_fin: '', prioridad: 'Normal', 
          estado: 'Pendiente' 
      }, 
    };
  },
  watch: {
    // Sincroniza el formulario cuando la prop 'tarea' cambie (es decir, cuando se abre el modal)
    tarea: {
      handler(nueva) {
        if (nueva && nueva.id !== this.form.id) { 
            // Esto asegura que solo actualizamos si hay una nueva tarea válida
            this.form = { ...nueva };
        }
      },
      deep: true,
      immediate: true,
    },
  },
  methods: {
    // Función centralizada para manejar la respuesta después de PUT
    handleSuccess() {
        // 1. Cierra el modal
        this.$emit("cerrar");
        // 2. Notifica al componente padre (App.vue) que debe recargar la lista
        this.$emit("actualizar-lista"); 
        console.log("Operación completada con éxito. Lista de tareas pendiente de recarga.");
    },

    async editarTarea() {
      // Se utiliza la ruta definida en tu Laravel: Route::put('/editarTarea/{id}', ...)
      const url = `/editarTarea/${this.form.id}`;
      try {
        // 🚀 DEBUG: Muestra los datos que se enviarán
        console.log(`[PUT - EDITAR] Enviando a ${url} con datos:`, this.form); 
        
        const response = await axios.put(url, this.form);
        
        console.log(`Tarea ${this.form.id} actualizada con éxito.`, response.data);
        this.handleSuccess();
      } catch (error) {
        console.error("Error al actualizar tarea:", error.response || error);
        // Si no puedes usar alert, informa en consola
        console.error("Ocurrió un error al actualizar la tarea.");
      }
    },
    async concluirTarea() {
      // Usando la misma ruta PUT, solo cambiamos el estado
      const url = `/tarea/${this.form.id}`; 
      try {
        this.form.estado = "Concluida";
        // 🚀 DEBUG: Muestra los datos que se enviarán
        console.log(`[PUT - CONCLUIR] Enviando a ${url} con datos:`, this.form);
        
        const response = await axios.put(url, this.form);
        
        console.log(`Tarea ${this.form.id} concluida.`, response.data);
        this.handleSuccess();
      } catch (error) {
        console.error("Error al concluir tarea:", error.response || error);
        // Si no puedes usar alert, informa en consola
        console.error("No se pudo concluir la tarea.");
      }
    },
    async cambiarEstado(estado){
      const url = `/cambiarEstado/${this.form.id}/${estado}`;
      try {
        this.form.estado = estado;
        const response = await axios.put(url, this.form);
        console.log(`Tarea ${this.form.id} actualizada a estado ${estado}.`, response.data);
        this.handleSuccess();
      } catch (error) {
        console.error("Error al cambiar estado de la tarea:", error.response || error);
        console.error("No se pudo cambiar el estado de la tarea.");
      }
    },
  },
};
</script>

<style scoped>
/* Estilos del modal (mantener) */
.modal-backdrop-custom {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,.5);
  z-index: 1050;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
