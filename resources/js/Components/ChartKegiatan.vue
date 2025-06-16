<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script>
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
  name: 'ChartKegiatan',
  components: { Bar },
  props: {
    dataKegiatan: {
      type: Array,
      required: true,
    }
  },
  computed: {
    chartData() {
      return {
        labels: this.dataKegiatan.map(item => item.nama_grup_dampingan),
        datasets: [{
          label: 'Jumlah Kegiatan',
          data: this.dataKegiatan.map(item => item.jumlah_kegiatan),
          backgroundColor: '#facc15',
        }]
      }
    },
    chartOptions() {
      return {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            beginAtZero: true,
            ticks: { stepSize: 1 }
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: (context) => {
                const index = context.dataIndex
                const detail = this.dataKegiatan[index].detail_kegiatan || ''
                if (!detail) return 'Tidak ada kegiatan'

                const list = detail.split('||').map(item => `• ${item.trim()}`)
                return [`Jumlah: ${context.formattedValue}`, ...list]
              }
            }
          },
          legend: { display: false }
        }
      }
    }
  }
}
</script>

<style scoped>
canvas {
  max-height: 220px;
}
</style>