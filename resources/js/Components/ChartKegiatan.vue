<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>

<script>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
  name: 'ChartKegiatan',
  components: { Bar },
  props: {
    dataKegiatan: Array
  },
  computed: {
    chartData() {
      return {
        labels: this.dataKegiatan.map(item => item.nama_grup_dampingan),
        datasets: [
          {
            label: 'Jumlah Kegiatan',
            backgroundColor: '#ffc940', 
            data: this.dataKegiatan.map(item => item.total_kegiatan)
          }
        ]
      }
    },
    chartOptions() {
      return {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1,
            }
          }
        }
      }
    }
  }
}
</script>