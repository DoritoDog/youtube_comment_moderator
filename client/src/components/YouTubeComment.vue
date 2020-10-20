<script>
export default {
  props: {
    avatarSrc: String,
    username: String,
    commentText: String,
    age: String,
    showButtons: {
      type: Boolean,
      default: false
    }
  }
};
</script>

<template>
  <v-tile>
    <div v-if="avatarSrc !== ''">
      <v-avatar :src="avatarSrc"></v-avatar>
    </div>
    <div v-else>
      <v-avatar></v-avatar>
    </div>

    <v-tile-content :title="username" :subtitle="commentText" :info="age">
    </v-tile-content>
    <div v-if="this.showButtons">
      <v-btn size="small" :style="[{ 'width': '100%' }]">Approve</v-btn>
      <v-btn size="small" :style="[{ 'width': '100%' }]" color="dark">Spam</v-btn>
    </div>
  </v-tile>
</template>
