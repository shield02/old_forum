<template>
    <div>
        <div v-for="(reply, index) in items" v-bind:key="reply.id">
            <reply-component :data="reply" @deleted="remove(index)"></reply-component>
        </div>

        <paginator-component :dataSet="dataSet" @changed="fetch"></paginator-component>

        <new-reply-component @created="add"></new-reply-component>
    </div>
</template>

<script>
    import ReplyComponent from './ReplyComponent.vue';
    import NewReplyComponent from './NewReplyComponent.vue';
    import collection from '../mixins/collection';

    export default {
        components: { ReplyComponent, NewReplyComponent },

        mixins: [collection],

        data() {
            return { dataSet: false };
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            },
        }
    }
</script>