<template>
  <div class="row mt-4">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="card-title">
            <h3>Your Answer</h3>
          </div>
          <hr />
          <form @submit.prevent="create">
            <div class="form-group">
              <textarea
                v-model="body"
                required
                class="form-control"
                rows="7"
                name="body"
              ></textarea>
            </div>
            <div class="form-group">
              <button
                :disabled="isInvalid"
                type="submit"
                class="btn btn-lg btn-outline-primary"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["questionId"],
  methods: {
    create() {
      axios
        .post(`/questions/${this.questionId}/answers`, {
          body: this.body,
        })
        .catch((err) => {
          console.log("Something went wrong");
          console.log(err.response);
          // This comes up empty
          this.$toast.error(err.response.data.message, "Error", {
            timeout: 3000,
          });
        })
        .then(({ data }) => {
          this.$toast.success(data.message, "Success");
          this.body = "";
          this.$emit("created", data.answer);
        });
    },
  },
  data() {
    return {
      body: "",
    };
  },
  computed: {
    isInvalid() {
      return !this.signedIn || this.body.length < 10;
    },
  },
};
</script>
