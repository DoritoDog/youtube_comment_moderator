<script>
export default {
  props: {
    title: String
  },

  methods: {
    isPageSelected(page) {
      return window.location.pathname === page;
    }
  }
};
</script>

<template>
  <div class="content-no-padding">
    <v-row>
      <!-- Side navigation -->
      <v-col :c="'3'">
        <!-- <SideNavigation :links="[{text: 'Dashboard', url: '/'}, {text: 'Comments', url: '/comments'}, {text: 'Rules', url: '/rules'}]" /> -->

        <v-menu>
          <v-menu-divider :label="'YCM'"></v-menu-divider>
          <v-menu-item :selected="isPageSelected('/')">
            <a href="/">Dashboard</a>
          </v-menu-item>
          <v-menu-item :selected="isPageSelected('/comments')">
            <a href="/comments">Comments Browser</a>
          </v-menu-item>
          <v-menu-item :selected="isPageSelected('/rules')">
            <a href="/rules">Rules</a>
          </v-menu-item>
        </v-menu>
      </v-col>

      <!-- Content -->
      <v-col :c="'9'">
        <div class="content">
          <h5 class="u-text-center">YouTube Comment Moderator 🔒</h5>
          <h2 class="u-text-center">{{ title }}</h2>
          <slot></slot>
        </div>
      </v-col>
    </v-row>
  </div>
</template>
