
<script>
export default {
    data() {
        return {
            file: null, 
            uploadStatus: '' 
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0]; 
        },
        submitFile() {
            if (!this.file) {
                this.uploadStatus = 'Seleziona un file prima di caricarlo.';
                return;
            }

            let formData = new FormData();
            formData.append('csv_file', this.file); // 'csv_file' deve corrispondere al nome utilizzato nello script PHP

            this.uploadStatus = 'Caricamento in corso...';

            fetch('http://localhost:8000/UploadData', { 
                method: 'POST',
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    this.uploadStatus = data.message; // Mostra il messaggio di risposta dall'API
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.uploadStatus = 'Si è verificato un errore durante il caricamento.';
                });
        }
    }
};
</script>

<template>
    <div>
        <h2>Carica File CSV</h2>
        <form @submit.prevent="submitFile">
            <input type="file" @change="handleFileUpload" />
            <button type="submit">Carica</button>
        </form>
        <div v-if="uploadStatus">
            {{ uploadStatus }}
        </div>
    </div>
</template>
