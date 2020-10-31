<script>
import MainLayout from "../layouts/Main.vue";
import ICountUp from "vue-countup-v2";
import axios from "axios";

export default {
  components: {
    MainLayout,
    ICountUp
  },

  data: function() {
    return {
      numSpamDeleted: 0,
      numBotsBanned: 0,
      numCommentsNeedingReview: 0
    };
  },

  created: function () {
    axios.get("https://api.yt-moderator.belgharbi.com/statistics/", { headers: { 'Accept': 'application/json' } })
         .then(response => {
           this.numSpamDeleted = response.data.spam;
           this.numBotsBanned = response.data.botsBanned;
         });
  }
};
</script>

<template>
  <main-layout :title="'Dashboard'">

    <v-divider />

    <v-row :level="true">
      <v-col :dynamicOffset="'center'">
        <h6 class="u-text-center">
          Spam Deleted
          <span class="text-success">
            <ICountUp :endVal="this.numSpamDeleted" />
          </span>
        </h6>
      </v-col>
      <v-col :dynamicOffset="'center'">
        <h6 class="u-text-center">
          Bots Banned
          <span class="text-success">
            <ICountUp :endVal="this.numBotsBanned" />
          </span>
        </h6>
      </v-col>
      <v-col :dynamicOffset="'center'">
        <h6 class="u-text-center">
          Comments Needing Review
          <span class="text-danger">
            <ICountUp :endVal="this.numCommentsNeedingReview" />
          </span>
        </h6>
      </v-col>
    </v-row>

    <p class="u-text-center">
      YouTube Comment Moderator is just that - a moderation system for YouTube
      comments! Comments on videos are constantly updated, and filtered
      according to the rules you define.
    </p>

  </main-layout>
</template>
