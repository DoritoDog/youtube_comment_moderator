import Vue from "vue";
import App from "./App.vue";
import VueCirrus from "vue-cirrus";
import "vue-cirrus/dist/vue-cirrus.css";

Vue.use(VueCirrus);

Vue.config.productionTip = false;

new Vue({
  render: h => h(App)
}).$mount("#app");
