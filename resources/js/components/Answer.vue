<script>
export default {
  props: ["answer"],

  data() {
    return {
      editing: false,
      body: this.answer.body,
      bodyHtml: this.answer.body_html,
      id: this.answer.id,
      questionId: this.answer.question_id,
      beforeEditCache: null,
    };
  },
  created() {
    var test = `questions/${this.questionId}/answers/${this.id}`;
    console.log(test);
  },

  methods: {
    edit: function () {
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
      console.log(this.questionId);
      axios
        .patch(this.endpoint, {
          body: this.body,
        })
        .then((response) => {
          console.log(response);
          this.editing = false;
          this.bodyHtml = response.data.body_html;
          // this never gets
          alert(response.data.message);
        })
        .catch((err) => {
          console.log("Something went wrong");
          console.log(err.response);
          // This comes up empty
          alert(err.response.data.message);
        });
    },
    destroy() {
      if (confirm("Are you sure?")) {
        axios.delete(this.enpoint).then((response) => {
          $(this.$el).fadeOut(500, () => {
            alert(response.data.message);
          });
        });
      }
    },
  },
  computed: {
    isInvalid() {
      return this.body.length < 10;
    },
    enpoint() {
      return `/questions/${this.questionId}/answers/${this.id}`;
    },
  },
};
</script>
