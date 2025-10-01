<template>
  <transition name="fade">
    <div
      v-if="mostrar"
      class="modal-backdrop-custom d-flex justify-content-center align-items-center"
    >
      <div
        class="modal show d-block w-100 h-100"
        tabindex="-1"
        role="dialog"
      >
        <div
          class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down"
          role="document"
        >
          <div class="modal-content h-100">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">Registrar Nueva Tarea</h5>
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
                    :class="{'is-invalid': errors.titulo}"
                    required
                  />
                  <div class="invalid-feedback" v-if="errors.titulo">
                    {{ errors.titulo }}
                  </div>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                  <label for="descripcion" class="form-label">Descripción</label>
                  <textarea
                    class="form-control"
                    id="descripcion"
                    v-model="form.descripcion"
                    :class="{'is-invalid': errors.descripcion}"
                    required
                  ></textarea>
                  <div class="invalid-feedback" v-if="errors.descripcion">
                    {{ errors.descripcion }}
                  </div>
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
                      :min="today"
                      :class="{'is-invalid': errors.fecha_inicio}"
                    />
                    <div class="invalid-feedback" v-if="errors.fecha_inicio">
                      {{ errors.fecha_inicio }}
                    </div>
                  </div>
                  <div class="col-6">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input
                      type="time"
                      class="form-control"
                      id="hora_inicio"
                      v-model="form.hora_inicio"
                      :class="{'is-invalid': errors.hora_inicio}"
                      required
                    />
                    <div class="invalid-feedback" v-if="errors.hora_inicio">
                      {{ errors.hora_inicio }}
                    </div>
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
                      :min="today"
                      :class="{'is-invalid': errors.fecha_fin}"
                      required
                    />
                    <div class="invalid-feedback" v-if="errors.fecha_fin">
                      {{ errors.fecha_fin }}
                    </div>
                  </div>
                  <div class="col-6">
                    <label for="hora_fin" class="form-label">Hora de Fin</label>
                    <input
                      type="time"
                      class="form-control"
                      id="hora_fin"
                      v-model="form.hora_fin"
                      :class="{'is-invalid': errors.hora_fin}"
                      required
                    />
                    <div class="invalid-feedback" v-if="errors.hora_fin">
                      {{ errors.hora_fin }}
                    </div>
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
                      :class="{'is-invalid': errors.prioridad}"
                    >
                      <option value="Normal">Normal</option>
                      <option value="Alta">Alta</option>
                      <option value="Media">Media</option>
                      <option value="Baja">Baja</option>
                    </select>
                    <div class="invalid-feedback" v-if="errors.prioridad">
                      {{ errors.prioridad }}
                    </div>
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
                  Cancelar
                </button>
                <button type="submit" class="btn btn-primary">Registrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios';

export default {
  name: "Formulario",
  props: {
    mostrar: { type: Boolean, default: false },
  },
  data() {
    return {
      form: {
        titulo: "",
        descripcion: "",
        fecha_inicio: this.today,
        hora_inicio: "",
        fecha_fin: "",
        hora_fin: "",
        prioridad: "Normal",
        estado: "Pendiente",
      },
      errors: {
        titulo: "",
        descripcion: "",
        fecha_inicio: "",
        hora_inicio: "",
        fecha_fin: "",
        hora_fin: "",
        prioridad: "",
      },
    };
  },
  computed: {
    today() {
      const d = new Date();
      const month = String(d.getMonth() + 1).padStart(2, "0");
      const day = String(d.getDate()).padStart(2, "0");
      return `${d.getFullYear()}-${month}-${day}`;
    },
  },
  methods: {
    validate() {
      let valid = true;
      this.errors = { 
        titulo: "", descripcion: "", fecha_inicio: "", hora_inicio: "", 
        fecha_fin: "", hora_fin: "", prioridad: "" 
      };

      if (!this.form.titulo.trim()) {
        this.errors.titulo = "El título es obligatorio.";
        valid = false;
      }
      if (!this.form.descripcion.trim()) {
        this.errors.descripcion = "La descripción es obligatoria.";
        valid = false;
      }
      if (!this.form.fecha_inicio) {
        this.errors.fecha_inicio = "La fecha de inicio es obligatoria.";
        valid = false;
      }
      if (!this.form.hora_inicio) {
        this.errors.hora_inicio = "La hora de inicio es obligatoria.";
        valid = false;
      }
      if (!this.form.fecha_fin) {
        this.errors.fecha_fin = "La fecha de vencimiento es obligatoria.";
        valid = false;
      } else if (this.form.fecha_fin < this.today) {
        this.errors.fecha_fin = "La fecha de vencimiento no puede ser pasada.";
        valid = false;
      }
      if (!this.form.hora_fin) {
        this.errors.hora_fin = "La hora de fin es obligatoria.";
        valid = false;
      }
      if (!this.form.prioridad) {
        this.errors.prioridad = "Debe seleccionar una prioridad.";
        valid = false;
      }
      return valid;
    },
    async handleSubmit() {
      try {
        if (this.validate()) {
          const response = await axios.post('/nuevatarea', this.form);
          console.log('Estado del registro:', response.data);

          alert("Tarea registrada con éxito.");

          // Reiniciar formulario
          this.form = {
            titulo: "",
            descripcion: "",
            fecha_inicio: this.today,
            hora_inicio: "",
            fecha_fin: "",
            hora_fin: "",
            prioridad: "Normal",
            estado: "Pendiente",
          };
          this.$emit("cerrar");
        }
      } catch (error) {
        console.error("Error al registrar la tarea:", error);
        alert("Ocurrió un error al registrar la tarea.");
      }
    },
  },
};
</script>

<style scoped>
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
