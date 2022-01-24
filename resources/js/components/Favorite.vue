<template>
  <a
    title="Click to mark as favorite question (Click again to undo)"
    :class="classes"
    @click.prevent="toggle"
  >
    <i class="fa fa-star fa-2x"></i>
    <span class="favorites-count">{{ count }}</span>
  </a>
</template>
<script>
export default {
  props: ["question"],
  mounted() {
    //console.log(`/questions/${this.id}/favorites`);
  },
  data() {
    return {
      isFavorited: this.question.is_favorited,
      count: this.question.favorites_count,
      id: this.question.id,
    };
  },
  computed: {
    classes() {
      return [
        "favorite",
        "mt-2",
        !this.signedIn ? "off" : this.isFavorited ? "favorited" : "",
      ];
    },
    endpoint() {
      return `/questions/${this.id}/favorites`;
    },
  },
  methods: {
    toggle() {
      if (!this.signedIn) {
        this.$toast.warning(
          "Please login to favorite this questions",
          "Warning",
          {
            timeout: 3000,
            position: "bottomLeft",
          }
        );
        return;
      }
      this.isFavorited ? this.destroy() : this.create();
    },
    destroy() {
      axios.delete(this.endpoint).then((response) => {
        this.count--;
        this.isFavorited = false;
      });
    },
    create() {
      console.log(this.endpoint);
      //require("axios-debug-log/enable");
      axios
        .post(this.endpoint)
        .then((response) => {
          console.log("response:" + response);
          this.count++;
          this.isFavorited = true;
        })
        .catch((err) => {
          console.log("Something went wrong");
          if (err.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(err.response.data);
            console.log(err.response.status);
            console.log(err.response.headers);
          } else if (err.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(err.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error", err.message);
          }
          console.log(err.config);
        });
    },
  },
};
</script>