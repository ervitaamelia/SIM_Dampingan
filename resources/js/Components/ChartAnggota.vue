<template>
  <Doughnut :data="chartData" :options="chartOptions" />
</template>

<script>
import { Doughnut } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  Colors
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, Colors)

export default {
  name: 'ChartAnggota',
  components: {
    Doughnut
  },
  props: {
    anggotaData: {
      type: Array,
      required: true
    }
  },
  computed: {
    chartData() {
      return {
        labels: this.anggotaData.map(item => item.nama_grup_dampingan),
        datasets: [
          {
            label: 'Jumlah Masyarakat',
            data: this.anggotaData.map(item => item.jumlah_anggota),
            backgroundColor: [
              '#FF6384',
              '#36A2EB',
              '#FFCE56',
              '#4BC0C0',
              '#9966FF',
              '#FF9F40',
              '#00C49F',
              '#FF6B6B',
              '#6B5B95',
              '#F7CAC9',
            ],
            borderWidth: 1,
          }
        ]
      }
    },
    chartOptions() {
  return {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          boxWidth: 16,
          padding: 10,
          usePointStyle: true,
          font: {
            size: 12
          }
        }
      },
      title: {
        display: false
      }
    },
    layout: {
      padding: 10
    }
  }
}
  }
}
</script>