<template>
  <div>
    <a
      v-if="canAccept"
      title="Mark this answer as best answer"
      :class="classes"
      @click.prevent="create"
    >
      <i class="fa fa-check fa-2x"></i>
    </a>

    <a
      v-if="accepted"
      title="The question owner accept this answer as best answer"
      :class="classes"
    >
      <i class="fa fa-check fa-2x"></i>
    </a>
  </div>
</template>
<script>
export default {
  props: ["answer"],

  data() {
    return {
      isBest: this.answer.is_best,
      id: this.answer.id,
    };
  },

  methods: {
    create() {
      axios
        .post(`/answers/${this.id}/accept`)
        .then((response) => {
          console.log("response:" + response);
          this.$toast.success(response.data.message, "Success", {
            timeout: 3000,
            position: "bottomLeft",
          });
          this.isBest = true;
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

  computed: {
    canAccept() {
      return this.authorize("accept", this.answer);
    },

    accepted() {
      return !this.canAccept && this.isBest;
    },

    classes() {
      return ["mt-2", this.isBest ? "vote-accepted" : ""];
    },
  },
};
</script>
