<script>
    import RepliesComponent from '../components/RepliesComponent.vue';
    import SubscribeButtonComponent from '../components/SubscribeButtonComponent.vue';

    export default {
        props: ['initialRepliesCount'],

        components: { RepliesComponent, SubscribeButtonComponent },

        data() {
            return {
                repliesCount: this.initialRepliesCount
            }
        }
    }
</script>