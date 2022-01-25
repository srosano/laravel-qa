<template >
  <div class="d-flex flex-column vote-controls">
    <a
      @click.prevent="voteUp"
      :title="title('up')"
      class="vote-up"
      :class="classes"
    >
      <i class="fa fa-caret-up fa-3x"></i>
    </a>

    <span class="votes-count">{{ count }}</span>
    <a
      @click.prevent="voteDown"
      :title="title('down')"
      class="vote-down"
      :class="classes"
    >
      <i class="fa fa-caret-down fa-3x"></i>
    </a>

    <favorite v-if="name === 'question'" :question="model"></favorite>

    <accept v-else :answer="model"></accept>
  </div>
</template>
<script>
import favorite from "./Favorite.vue";
import accept from "./Accept.vue";
export default {
  components: {
    favorite,
    accept,
  },
  props: ["name", "model"],
  mounted() {
    console.log("RUNNNING VOTE VUE !!!!");
  },
  computed: {
    classes() {
      return this.signedIn ? "" : "off";
    },

    endpoint() {
      return `/${this.name}s/${this.id}/vote`;
    },
  },

  data() {
    return {
      count: this.model.votes_count,
      id: this.model.id,
    };
  },

  methods: {
    title(voteType) {
      let titles = {
        up: `This ${this.name} is useful`,
        down: `This ${this.name} is useful`,
      };
      return titles[voteType];
    },

    voteUp() {
      this._vote(1);
    },

    voteDown() {
      this._vote(-1);
    },

    _vote(vote) {
      if (!this.signedIn) {
        this.$toast.warning(
          `Please login to vote the ${this.name}`,
          "Warning",
          {
            timeout: 3000,
            position: "bottomLeft",
          }
        );
        return;
      }

      axios
        .post(this.endpoint, {
          vote,
        })
        .then((response) => {
          console.log(response);
          this.editing = false;
          this.bodyHtml = response.data.body_html;
          // this never gets
          this.$toast.success(response.data.message, "Success", {
            timeout: 3000,
            position: "bottomLeft",
          });
          this.count = response.data.votesCount;
        })
        .catch((err) => {
          console.log("Something went wrong");
          console.log(err.response);
          // This comes up empty
          this.$toast.error(err.response.data.message, "Error", {
            timeout: 3000,
          });
        });
    },
  },
};
</script>