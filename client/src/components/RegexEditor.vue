<script>
import axios from "axios";

export default {
  props: {
    label: String,
    index: Number,
    id: Number,
    inputText: String
  },

  data: function() {
    return {
      filter: 0,
      filters: [
        { value: 0, text: "contain" },
        { value: 1, text: "end with" },
        { value: 2, text: "start with" }
      ],
      isEditable: false,
      text: this.$props.inputText
    };
  },

  computed: {
    feedback: function() {
      if (this.text === "") {
        return "Please enter some text.";
      } else {
        return `Will block any accounts whose username ${
          this.filters[this.filter].text
        } <b>"${
          this.text
        }"</b><br>(it doesn't matter if it is uppercase or lowercase)."`;
      }
    },

    editButtonText: function() {
      return this.isEditable ? "Done" : "Edit";
    },

    regex: function() {
      switch (this.filters[this.filter]) {
        case 'start with':
          return this.text + ".";
        case 'end with':
          return "." + this.text;
        case 'contain':
        default:
          return "." + this.text + ".";
      }
    }
  },

  methods: {
    remove: function() {
      this.$emit("remove", { index: this.$props.index, id: this.$props.id });
    },
    toggleEditable: function() {
      this.isEditable = !this.isEditable;
    },
    patch: function () {
      if (!this.isEditable) {
        /*axios({
          method: 'get',
          url: `http://localhost:8765/rules/edit/`,//?id=${this.id}&regex=${this.regex}`,
          headers: {
            'Accept': 'application/json'
          },
          data: {
            regex: 'heh',
            id: '1'
          }
        }).then(r => console.log(r.data));*/
         axios.get(`http://localhost:8765/rules/edit?id=${this.id}&regex=${this.regex}`, { headers: { 'Accept': 'application/json' } });
      }
    }
  }
};
</script>

<template>
  <div class="u-box">
    <div v-if="isEditable">
      <label class="font-normal"
        ><b>{{ label }}</b></label
      >
      <div class="input-control level">
        <select class="select" v-model="filter" placeholder="Dropdown">
          <option
            v-for="filter in filters"
            :key="filter.value"
            :value="filter.value"
            >{{ filter.text }}</option
          >
        </select>
        <input v-model="text" type="text" name="regex" placeholder="Text Pattern" />
      </div>
      <p v-html="feedback" class="u-text-center"></p>
    </div>
    <div v-else>
      <span class="u-text-center">
        {{ `${filters[filter].text} "${text}"` }}
      </span>
    </div>

    <!-- Edit and delete buttons -->
    <div class="btn-group u-center">
      <button class="btn-dark" @click="toggleEditable(); patch();">
        {{ editButtonText }}
      </button>
      <button class="btn-dark" @click="remove">Delete</button>
    </div>
  </div>
</template>

<style>
.u-box {
  margin: 30px 0;
}
</style>
