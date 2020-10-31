<script>
import MainLayout from "../layouts/Main.vue";
import YouTubeComment from "../components/YouTubeComment.vue";
import axios from "axios"
import moment from "moment"

export default {
  components: {
    MainLayout,
    YouTubeComment
  },

  data: function() {
    return {
      comments: [],
      filter: 'spam'
    }
  },

  created: function() {
    this.updateComments('spam');
  },

  methods: {
    updateComments: function(status) {
      this.filter = status;
      axios.get("https://api.yt-moderator.belgharbi.com/comments/status/" + status, { headers: { 'Accept': 'application/json' } })
           .then(response => {
             this.comments = response.data.comments;
             this.comments.forEach(comment => {
               comment.age = moment(comment.created_at).subtract().fromNow();
             });
           });
    }
  }
};
</script>

<template>
  <main-layout :title="'Comments'">
    <v-tabs :position="'center'" :size="'normal'" :mode="'fill'">
      <v-tab :selected="this.filter === 'new'" @click="updateComments('new')">New</v-tab>
      <v-tab :selected="this.filter === 'in-review'" @click="updateComments('in-review')">In Review</v-tab>
      <v-tab :selected="this.filter === 'ok'" @click="updateComments('ok')">OK</v-tab>
      <v-tab :selected="this.filter === 'spam'" @click="updateComments('spam')">Spam</v-tab>
    </v-tabs>

    <div>
      <YouTubeComment
        v-for="comment in comments" :key="comment.id"
        :avatar-src="comment.author.avatar_url"
        :username="comment.author.username"
        :comment-text="comment.text"
        :age="comment.age"
      />

      <v-pagination class="u-center u-hide">
        <v-pagination-item disabled>Prev</v-pagination-item>
        <v-pagination-item selected>1</v-pagination-item>
        <v-pagination-item>2</v-pagination-item>
        <v-pagination-item>3</v-pagination-item>
        <v-pagination-item>Next</v-pagination-item>
      </v-pagination>
    </div>
  </main-layout>
</template>
