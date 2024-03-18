<script setup>
import HelloWorld from './components/HelloWorld.vue'
import FileUpload from "./components/FileUpload.vue"
import DataDisplayer from "./components/DataDisplayer.vue"
import SearchData from "./components/SearchData.vue"
import AnalyzeData from "./components/AnalyzeData.vue"
</script>

<template>
  <HelloWorld msg="CSV Displayer" />
  <FileUpload />
  <!-- <SearchData /> -->
  <DataDisplayer />
  <AnalyzeData/>
</template>

<style scoped>

</style>
