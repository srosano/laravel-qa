<template>
<div>
    <span class="text-muted"> {{ postDate }} </span>
    <div class="media mt-2">
        <a :href="user.url" class="pr-2">
            <img :src="user.avatar" alt="" />
        </a>
        <div class="media-body mt-1">
            <a :href="user.url">{{ user.name }}</a>
        </div>
    </div>
</div>
</template>
<script>
//import { defineComponent } from '@vue/composition-api'

export default {
    props: ['model','label'],

    computed: {
        postDate: function () {
            return this.label + " " + this.model.created_date;
        }
    },
    data: function () {
        return{
            user: this.model.user
        }
    },


}
</script>
