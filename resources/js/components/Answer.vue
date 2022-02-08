<template>
  <div class="media post">
    <vote :model="answer" name="answer"></vote>
    <div class="media-body">
      <form v-if="editing" @submit.prevent="update">
        <div class="form-group">
          <textarea
            v-model="body"
            class="form-control"
            cols="30"
            rows="10"
            required
          ></textarea>
        </div>
        <button class="btn btn-primary" :disabled="isInvalid">update</button>
        <button class="btn btn-outline-secondary" @click="cancel" type="button">
          Cancel
        </button>
      </form>
      <div v-else>
        <div v-html="bodyHtml"></div>
        <div class="row">
          <div class="col-4">
            <div class="ml-auto">
              <a
                v-if="authorize('modify', answer)"
                @click.prevent="edit"
                class="btn btn-sm btn-outline-info"
                >Edit</a
              >
              <button
                v-if="authorize('modify', answer)"
                @click="destroy"
                class="btn btn-sm btn-outline-danger"
              >
                Delete
              </button>
            </div>
          </div>
          <div class="col-4"></div>
          <div class="col-4">
            <user-info :model="answer" label="Answered"></user-info>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Vote from "./Vote.vue";
import UserInfo from "./UserInfo.vue";
export default {
  props: ["answer"],
  components: { Vote, UserInfo },
  data() {
    return {
      editing: false,
      body: this.answer.body,
      bodyHtml: this.answer.body_html,
      id: this.answer.id,
      questionId: this.answer.question_id,
      beforeEditCache: null,
    };
    var test = `questions/${this.questionId}/answers/${this.id}`;
    //console.log(test);
  },

  methods: {
    edit() {
      this.beforeEditCache = this.body;
      this.editing = true;
    },
    cancel() {
      this.body = this.beforeEditCache;
      this.editing = false;
    },
    update() {
      // Template literals Template literals can contain placeholders and
      //  evaluated (executed) by the shell before the main command
      //console.log(this.questionId);
      axios
        .patch(this.endpoint, {
          body: this.body,
        })
        .then((response) => {
          console.log(response);
          this.editing = false;
          this.bodyHtml = response.data.body_html;
          // this never gets
          this.$toast.success(response.data.message, "Success", {
            timeout: 3000,
          });
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
    destroy() {
      this.$toast.question("Are you sure about that?", "Confirm", {
        timeout: 20000,
        close: false,
        overlay: true,
        displayMode: "once",
        id: "question",
        zindex: 999,
        title: "Hey",
        message: "Are you sure about that?",
        position: "center",
        buttons: [
          [
            "<button><b>YES</b></button>",
            (instance, toast) => {
              axios.delete(this.endpoint).then((response) => {
                this.$emit("deleted");
              });
              instance.hide({ transitionOut: "fadeOut" }, toast, "button");
            },
            true,
          ],
          [
            "<button>NO</button>",
            function (instance, toast) {
              instance.hide({ transitionOut: "fadeOut" }, toast, "button");
            },
          ],
        ],
      });
    },
  },
  computed: {
    isInvalid() {
      return this.body.length < 10;
    },
    endpoint() {
      return `/questions/${this.questionId}/answers/${this.id}`;
    },
  },
};
</script>
