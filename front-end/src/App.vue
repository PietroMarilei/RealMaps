<script setup>
import HelloWorld from './components/HelloWorld.vue'
import FileUpload from "./components/FileUpload.vue"
import DataDisplayer from "./components/DataDisplayer.vue"
import SearchData from "./components/SearchData.vue"
import AnalyzeData from "./components/AnalyzeData.vue"
</script>

<template>
  <HelloWorld msg="RealMaps Test" />
  <AnalyzeData/>
   <FileUpload />
  <SearchData />
  <DataDisplayer />
</template>

<style scoped>

</style>
