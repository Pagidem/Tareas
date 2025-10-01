<template>
  <div class="container">
    <!-- Lista de tarjetas -->
    <div
      v-for="row in rows"
      :key="row.id"
      class="card mb-3 shadow-sm rounded-3"
      style="cursor: pointer;"
      @click="$emit('mostrar-detalle', row)" >
      <div class="card-body d-flex justify-content-between">
        <div>
          <div class="d-flex align-items-center mb-1">
            <!-- Círculo ID -->
            <div class="rounded-circle text-center fw-bold me-2"
                 style="width:35px; height:35px; background-color: #d9c8ff; color: #4a148c; display: flex; align-items: center; justify-content: center;">
              {{ row.id }}
            </div>
            <div>
              <h5 class="mb-0">{{ row.titulo }}</h5>
            </div>
          </div>

          <!-- Botones de prioridad y estado con color dinámico -->
          <div class="d-flex gap-2 mt-2">
            <!-- 🚀 Prioridad: Alta=Rojo, Media/Normal=Naranja, Baja=Verde -->
            <span 
              class="badge rounded-pill" 
              :class="getPrioridadClass(row.prioridad)">
              {{ row.prioridad }}
            </span>
            
            <!-- 🚀 Estado: Pendiente=Rojo (danger), Concluida=Verde (success) -->
            <span 
              class="badge rounded-pill" 
              :class="getEstadoClass(row.estado)">
              {{ row.estado }}
            </span>
          </div>
        </div>

        <div class="text-end">
          <p class="mb-1"><strong>Fecha fin:</strong> {{ formatDate(row.fecha_fin) }}</p>
          <p class="mb-0"><strong>Hora:</strong> {{ row.hora_fin }}</p>
        </div>
      </div>
    </div>
    
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ListaTareas",
  emits: ['mostrar-detalle'], 
  data() {
    return {
      rows: [],
    };
  },
  mounted() {
    this.fetchRegistros();
  },
  methods: {
    async fetchRegistros() {
      try {
        const response = await axios.get("/tareas");
        this.rows = response.data;
      } catch (error) {
        console.error("Error al obtener registros:", error);
      }
    },
    formatDate(fecha) {
      if (!fecha) return "";
      const parts = fecha.split("-");
      if (parts.length === 3) {
          return `${parts[2]}/${parts[1]}/${parts[0]}`;
      }
      return fecha;
    },
    
    // 🚀 NUEVO: Método para asignar clase CSS a Prioridad
    getPrioridadClass(prioridad) {
      switch (prioridad.toLowerCase()) {
        case 'alta':
          return 'bg-danger'; // Rojo
        case 'media':
        case 'normal': // Normal también se pinta de Naranja
          return 'bg-warning text-dark'; // Naranja (Bootstrap warning)
        case 'baja':
          return 'bg-success'; // Verde
        default:
          return 'bg-secondary';
      }
    },

    // 🚀 NUEVO: Método para asignar clase CSS a Estado
    getEstadoClass(estado) {
      switch (estado.toLowerCase()) {
        case 'pendiente':
          return 'bg-danger'; // Rojo (con letras blancas por defecto de Bootstrap)
        case 'concluida':
          return 'bg-success'; // Verde (con letras blancas por defecto de Bootstrap)
        default:
          return 'bg-secondary';
      }
    }
  },
};
</script>

<style scoped>
/* Los estilos scoped aseguran que las tarjetas se vean bien */
.card {
  background-color: #fef9ff;
  transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
}

/* ⚠️ Nota: Las clases bg-danger, bg-success y bg-warning ya tienen texto blanco 
o negro (como warning) por defecto en Bootstrap, por eso no es necesario 
el 'text-white' explícito, excepto si necesitas personalizar el 'warning'. 
Añadí 'text-dark' al warning para mayor legibilidad si el fondo es claro.
*/

/* Opcional: Eliminar los overrides anteriores si no son necesarios */
/*
.badge.bg-danger { background-color: #dc3545 !important; }
.badge.bg-secondary { background-color: #6c757d !important; }
*/
</style>
