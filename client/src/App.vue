<script>
import routes from "./routes";

export default {
  data: function() {
    return {
      currentRoute: window.location.pathname
    };
  },

  computed: {
    View() {
      const page = routes[this.currentRoute] || "404";
      return require(`../src/pages/${page}.vue`).default;
    }
  },

  render(h) {
    return h(this.View);
  },

  created() {
    window.addEventListener(
      "popstate",
      () => (this.currentRoute = window.location.pathname)
    );
  }
};
</script>
