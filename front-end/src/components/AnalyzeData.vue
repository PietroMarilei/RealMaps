<script>
import axios from 'axios';

export default {
    
    data() {
        let currentDate = new Date();
        let formattedDate = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(currentDate.getDate()).padStart(2, '0')}`;
        return {
            epidemics: [],
            caseThreshold: 3, 
            startDate: '1900-01-01',
            endDate: formattedDate
        };
    },

    methods: {
        detectEpidemics() {
            axios.get(`http://localhost:8000/AnalyzeData`, {
                params: {
                    caseThreshold: this.caseThreshold,
                    startDate: this.startDate,
                    endDate: this.endDate
                }
            })
                .then(response => {
                    this.epidemics = response.data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }
};
</script>

<template>
    <div>
        <input type="number" v-model="caseThreshold" placeholder="Numero di casi per l'epidemia">
        <input type="date" v-model="startDate" placeholder="Data Inizio">
        <input type="date" v-model="endDate" placeholder="Data Fine">
        <button @click="detectEpidemics()">Detect Epidemics</button>

        <div v-if="epidemics.length">
            <h2>Epidemia Rilevata</h2>
            <ul>
                <li v-for="epidemic in epidemics" :key="epidemic.Diagnosis">
                    Luogo: {{ epidemic.Location }} - Diagnosi: {{ epidemic.Diagnosis }}
                </li>
            </ul>
        </div>
        <div v-else>
            <p>Nessuna epidemia rilevata.</p>
        </div>
    </div>
</template>