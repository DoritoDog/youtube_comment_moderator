<script>
import RegexEditor from "../components/RegexEditor.vue";
import axios from "axios";

export default {
  components: {
    RegexEditor
  },

  props: {
    title: String,
    content: String
  },

  data: function() {
    return {
      regexes: []
    };
  },

  created: function () {
    axios.get("http://localhost:8765/rules/", { headers: { 'Accept': 'application/json' } })
         .then(response => {
           const filters = response.data.filters;
           filters.forEach(filter => {
             if (filter.content === this.content) {
               this.regexes.push({ index: this.regexes.length, text: filter.regex, id: filter.id });
             }
           });
         });
  },

  methods: {
    add: function() {
      axios.get(`http://localhost:8765/rules/add?content=${this.content}`, { headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' } })
         .then(response => {
           const filter = response.data.filter;
           this.regexes.push({ index: this.regexes.length, text: filter.regex, id: filter.id });
         });
    },
    remove: function(data) {
      axios.get(`http://localhost:8765/rules/delete?id=${data.id}`, { headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' } })
           .then(response => {
             console.log(response);
             this.regexes.splice(data.index, 1);
           });
    }
  }
};
</script>

<template>
  <div>
    <!-- Title -->
    <h6 class="u-text-center">{{ title }}</h6>

    <!-- List  -->
    <div v-for="regex in regexes" :key="regex.index">
      <regex-editor v-on:remove="remove" :index="regex.index" :id="regex.id" :inputText="regex.text" />
    </div>

    <!-- Buttons -->
    <div class="button-group u-center">
      <button class="btn-primary" @click="add">Add</button>
    </div>
  </div>
</template>
