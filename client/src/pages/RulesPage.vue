<script>
import MainLayout from "../layouts/Main.vue";
import RegexList from "../components/RegexList.vue";
import axios from "axios";

export default {
  components: {
    MainLayout,
    RegexList
  },

  data: function () {
    return {
      maxDuplicates: 1
    }
  },

  mounted: function () {
    axios.get(`http://localhost:8765/settings?name=Max. Duplicates`, { headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' } }).then(response => {
      console.log(response);
      this.maxDuplicates = response.data.setting[0].value;
    });
  },

  methods: {
    setSetting(name, value) {
      axios.get(`http://localhost:8765/settings/edit?name=${name}&value=${value}`, { headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' } });
    }
  }
};
</script>

<template>
  <main-layout :title="'Rules'">
    <v-row>
      <v-col :c="'12'">
        <p class="u-text-center">
          Block comments by either filtering out usernames or comments.
        </p>
      </v-col>
      <v-col :c="'6'">
        <regex-list :title="'Usernames to block'" :content="'USERNAME'" />
      </v-col>
      <v-col :c="'6'">
        <regex-list :title="'Comments to block'" :content="'COMMENT'" />
      </v-col>
    </v-row>

    <v-row>
      <v-col :c="'3'" />
      <v-col :c="'6'">
        <h6 class="u-text-center">Settings</h6>

        <!-- Max. Duplicates -->
        <label for="max-duplicates"><b>Max. Duplicates</b></label>
        <input
          type="number"
          name="max-duplicates"
          placeholder="Maximum number of duplicate comments one user can post."
          v-model="maxDuplicates"
        />
        <v-btn color="primary" @click="setSetting('Max. Duplicates', maxDuplicates)" class="u-center">Save</v-btn>
      </v-col>
      <v-col :c="'3'" />
    </v-row>
  </main-layout>
</template>

<style>
input {
  margin: 15px 0;
}
</style>