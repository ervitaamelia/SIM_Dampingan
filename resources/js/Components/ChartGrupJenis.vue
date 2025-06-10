<template>
  <Line :data="chartData" :options="chartOptions" />
</template>

<script>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)

export default {
  name: 'ChartGrupJenis',
  components: { Line },
  props: {
    dataGrup: Array
  },
  computed: {
    chartData() {
      return {
        labels: this.dataGrup.map(item => item.jenis_dampingan),
        datasets: [
          {
            label: 'Jumlah Grup',
            data: this.dataGrup.map(item => item.total),
            borderColor: '#3b82f6', // blue-500
            backgroundColor: '#93c5fd', // blue-300 (area)
            tension: 0.3,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7,
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