<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { 
  Chart, 
  registerables, 
  ChartConfiguration, 
  ChartType,
} from 'chart.js';

Chart.register(...registerables);

const props = defineProps<{
  type: ChartType;
  data: any;
  options?: any;
}>();

const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const createChart = () => {
    if (!chartCanvas.value) return;

    if (chartInstance) {
        chartInstance.destroy();
    }

    const config: ChartConfiguration = {
        type: props.type,
        data: props.data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#1a1a1a',
                    titleColor: '#fff',
                    bodyColor: '#9ca3af',
                    borderColor: '#262626',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: true,
                }
            },
            ...props.options
        }
    };

    chartInstance = new Chart(chartCanvas.value, config);
};

onMounted(() => {
    createChart();
});

onUnmounted(() => {
    if (chartInstance) {
        chartInstance.destroy();
    }
});

watch(() => props.data, () => {
    createChart();
}, { deep: true });
</script>

<template>
  <div class="relative w-full h-full min-h-[150px]">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>
