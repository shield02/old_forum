<template>
    <div>
        <div v-for="(reply, index) in items" v-bind:key="index">
            <reply-component :data="reply" @deleted="remove(index)"></reply-component>
        </div>

        <new-reply-component :endpoint="endpoint" @created="add"></new-reply-component>
    </div>
</template>

<script>
    import ReplyComponent from './ReplyComponent.vue';
    import NewReplyComponent from './NewReplyComponent.vue';

    export default {
        props: ['data'],

        components: { ReplyComponent, NewReplyComponent },

        data() {
            return {
                items: this.data,
                endpoint: location.pathname + '/replies'
            }
        },

        methods: {
            add(reply) {
                this.items.push(reply);

                this.$emit('added');
            },

            remove(index) {
                this.items.splice(index, 1);

                this.$emit('removed');

                flash('Your reply has been deleted.');
            }
        }
    }
</script>