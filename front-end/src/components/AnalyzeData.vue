<script>
import axios from 'axios';

export default {

    data() {
        let currentDate = new Date();
        let formattedDate = `${currentDate.getFullYear()}-${String(currentDate.getMonth() + 1).padStart(2, '0')}-${String(currentDate.getDate()).padStart(2, '0')}`;
        return {
            results: [],
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
                    this.results = response.data;
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
        <div class="container mt-3">
            <div class="row">
                <div class="col">
                    <h2>Epidemics Detector</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <input class="form-control" type="number" v-model="caseThreshold" placeholder="Numero di casi per l'epidemia">
                </div>
                <div class="col-4">
                    <input class="form-control" type="date" v-model="startDate" placeholder="Data Inizio">
                </div>
                <div class="col-4">
                    <input class="form-control" type="date" v-model="endDate" placeholder="Data Fine">
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button class=" btn btn-danger" @click="detectEpidemics()">&#9762; Detect Epidemics</button>
                </div>
            </div>

<div class="row">
    <div class="col">
        <table v-if="results.length" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Location</th>
                    <th scope="col">Disease Name</th>
                    <th scope="col">Case Count</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(result, i) in results" :key="i">
                    <th scope="row">{{ i + 1 }}</th>
                    <td>{{ result.location }}</td>
                    <td>{{ result.disease_name }}</td>
                    <td>{{ result.CaseCount }}</td>
                </tr>
            </tbody>
        </table>

    </div>
</div>
        </div>

    </div>
</template>